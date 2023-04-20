<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NsShipping extends Model
{
    use HasFactory;
    public static function NsShippingCarrierGet( $store_id ){

        if (!Auth::check())
            return;

        $data = DB::table('store')
            ->where('nuvemshop_store_id', $store_id)
            ->first();

        // dd( Auth::user()->id );

        $url = env('NS_URL') . '/' . $data->nuvemshop_store_id . '/shipping_carriers';

        $NsApiGet = Http::withHeaders([
            'Authentication' => 'bearer '.$data->nuvemshop_store_token,
            'User-Agent' => 'Frete Fixo (sampisolution.com.br)'
        ])->get($url);

        $NsApiGetJson = $NsApiGet->json();
        
        $find = false;
        if( gettype($NsApiGetJson) == 'object' && count($NsApiGetJson)>0 || gettype($NsApiGetJson) == 'array' && count($NsApiGetJson)>0 ) {
            $find = array_filter($NsApiGetJson, function ($v, $k) {
                
                if( $v['name'] == env("NS_SHIPPINGCARRIER") ) {
                    return $v;
                }
            },ARRAY_FILTER_USE_BOTH );
        }

        if (!$find)
            return false;

        return reset($find);
    }
    public static function NsShippingCarrierCreate( $store_id ){

        if (!Auth::check())
            return;

        $data = DB::table('store')
            ->where('nuvemshop_store_id', $store_id)
            ->first();

        $url = env('NS_URL') . '/' . $data->nuvemshop_store_id . '/shipping_carriers';

        $post_data = array(
            'name' => env("NS_SHIPPINGCARRIER"),
            'callback_url' => env("NS_CBCKURL"),
            'types' => 'ship'
        );

        $post = Http::withHeaders([
            'Authentication' => 'bearer '.$data->nuvemshop_store_token,
            'User-Agent' => 'Frete Fixo (sampisolution.com.br)'
        ])->post( $url,$post_data);
        
        return $post;
    }
    public static function NsShippingCarrierOptionsCreate( $store_id, $carrier_id ){

        if (!Auth::check())
            return;

        $data = DB::table('store')
            ->where('nuvemshop_store_id', $store_id)
            ->first();


        $url = env('NS_URL') . '/' . $data->nuvemshop_store_id . '/shipping_carriers/'.$carrier_id.'/options/';

        for ($i = 1; $i <= 5; $i++) {
            $post = Http::withHeaders([
                'Authentication' => 'bearer '.$data->nuvemshop_store_token,
                'User-Agent' => 'Frete Personalizado (sampisolution.com.br)'
            ])->post( $url, array(
                'code'  => 'fretepersonalizado'.$i,
                'name'  => 'Frete Personalizado - '.$i
            ) );
        }

        return $post;
    }

    public static function NsShippingCarrierInit( $store_id ) {
        $get = NsShipping::NsShippingCarrierGet( $store_id );

        if (!$get){
            $create = NsShipping::NsShippingCarrierCreate( $store_id );
            if (!$create)
                return;
            
            $createOptions = NsShipping::NsShippingCarrierOptionsCreate( $store_id, $create['id'] );
            if (!$createOptions)
                return;
            $get = $create->json();
        }

        $data = [
            'user_id'   => Auth::user()->id,
            'nuvemshop_store_id' => $store_id,
            'payments_data'  => null
        ];

        $updateStore = DB::table('store')
            ->upsert($data, ['nuvemshop_store_id'],['payments_data']);

        // dd($get);

        return $get;
    }
}
