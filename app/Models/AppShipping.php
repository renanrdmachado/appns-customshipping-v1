<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
Use \Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\App;


class AppShipping extends Model
{
    use HasFactory;

    public static function AppShippingSave( $inputs ){

        $store = DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->first();

        $data = array(
            'store_id'  => $store->nuvemshop_store_id,
            'shipping_data' => json_encode($inputs)
        );
        // dd( $data );

        $upsert = DB::table('zipcodes')
            ->upsert($data, ['store_id'],['shipping_data']);
        return $upsert;
    }
    public static function AppShippingOptionsUpsert( $data ){

        $store = DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->first();

        $upsert = DB::table('zipcodes')
            ->upsert( ['shipping_data'=>$data,'store_id'=>$store->nuvemshop_store_id],['store_id'],['shipping_data'] );
        return $upsert;
    }
    public static function AppShippingOptionsGetAll(){

    }
    public static function AppShippingOptionsGet( $store_id = false, $zipcode = false ){

        if(!$store_id && Auth::check()) {
            $store = DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->first();
            if(!$store)
                return;
            $store_id = $store->nuvemshop_store_id;
        }

        if(!$store_id)
            return false;

        $range = DB::table('zipcodes')
            ->where('store_id', $store_id)
            ->first('shipping_data');

        if (!$range)
            return false;

        $res = json_decode( $range->shipping_data );

        if ($zipcode) {
            $res = array_filter($res, function ($k) use ($zipcode) {
                if( !isset($k->from) || !isset($k->to) )
                    return;
                if ($k->from <= $zipcode && $k->to >= $zipcode) {
                    return $k;
                }
            });
        }

        return $res;
    }

    public static function AppShippingOptionResponse( $find ) {
        // dd($find);
        // AsaasSubscriptions::AsaasSubscriptionsRefresh( 0, $find["store_id"] );
        // dd($find);
        
        if( !isset($find['store_id']) || !isset($find["destination"]) )
            return;

        $status = DB::table('store')
            ->where("nuvemshop_store_id", $find['store_id'])
            ->get('payments_status')
            ->first();

        if( App::isFreeTrial($find['store_id']) ) {
            // continue;
        }else if ( !isset($status->payments_status) || $status->payments_status != "RECEIVED" && $status->payments_status != "RECEIVED_IN_CASH" ) {
            return ["error"=> true,"message"=>$status];

        }

        $inputs = array(
            'store_id' => $find["store_id"],
            'cep'   => $find["destination"]["postal_code"]
        );

        $res = AppShipping::AppShippingOptionsGet($inputs['store_id'],$inputs['cep']);

        if (!$res)
            return false;
        $rates = [];

        $items = array_filter($res, function( $item ){
            return trim($item->active) == "on";
        });
        
        if( !$items )
            return;

        $items = array_values($items);
        foreach($items as $key=>$fd) {            
            $min = $fd->min_days;
            $max = $fd->max_days;

            $min_delivery_date = Carbon::now()->addDays($min)->toIso8601String();
            $max_delivery_date = Carbon::now()->addDays($max)->toIso8601String();

            $fd->price = str_replace(",", ".", $fd->price);

            //var_dump($data);

            $newkey = $key + 1;
            $rates["rates"][] = array(
                "name"=> $fd->name,
                "code"=> 'fretepersonalizado'.$newkey,
                "price"=> floatval(number_format($fd->price,2,".","") ),
                // "price"=> $fd->price,
                "price_merchant"=> 0,
                "currency"=> "BRL",
                "type"=> "ship",
                "min_delivery_date"=> $min_delivery_date,
                "max_delivery_date"=> $max_delivery_date,
                "reference"=> Str::slug($fd->name,'-')
            );
            $newkey=false; 
        }
    
        return $rates;

        // return $res;
    }

    public static function AppShippingImportCSV($file) {
        // dd($file->path());
        $cols = ["name","from","to","min_days","max_days","price","active"];
        $csv = file($file->path());
        // dd($csv);
        $output = [];

        foreach ($csv as $line_index => $line) {
            if ($line_index > 0) { // I assume the the first line contains the column names.
                $newLine = [];
                $values = explode(';', $line);
                foreach ($values as $col_index => $value) {
                    if( isset($cols[$col_index]) ) {
                        $newLine[$cols[$col_index]] = $value;
                    }
                }
                $output[] = $newLine;
            }
        }

        $json_output = $output;  
        // dd($output);

        AppShipping::AppShippingSave($output);
        return true;
    }
}
