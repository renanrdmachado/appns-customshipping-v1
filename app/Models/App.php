<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
Use \Carbon\Carbon;

class App extends Model
{
    use HasFactory;
    public static function isFreeTrial( $store_id = null ){
        $query = false;
        if( $store_id == null && Auth::check() ) {
            $query = array(
                'find' => 'user_id',
                'replace' => Auth::user()->id
            );
        } else if( $store_id ) {
            $query = array(
                'find' => 'nuvemshop_store_id',
                'replace' => $store_id
            );
        }

        if( !$query )
            return;

        $get = DB::table('store')
            ->where($query['find'], $query['replace'])
            ->get(['payments_status','freetrial'])
            ->first();

        if( !$get ) 
            return;

        $diff = Carbon::now()->diffInHours($get->freetrial, false);

        return ($diff > 0 ) ? $diff : false;
    }
    public static function storeActive(){

        if( !Auth::check() )
            return;

        $get = DB::table('store')
            ->where("user_id", Auth::user()->id)
            ->get(['payments_status','freetrial'])
            ->first();

        if( !$get ) 
            return;

        $status = $get->payments_status;
        
        $statusSuccess = ['RECEIVED','RECEIVED_IN_CASH'];

        $pay = in_array($status,$statusSuccess);

        $diff = App::isFreeTrial();
        if( $pay ) {
            return true;
        } elseif( $diff > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public static function nuvemshopActive(){

        if( !Auth::check() )
            return;

        $get = DB::table('store')
            ->where("user_id", Auth::user()->id)
            ->get('nuvemshop_store_token')
            ->first();

        if( !$get ) 
            return;

        $status = $get->nuvemshop_store_token;

        return $status;
    }

    public static function hasCeps(){

        if( !Auth::check() )
            return;
        
        $store = DB::table('store')
            ->where("user_id", Auth::user()->id)
            ->get('nuvemshop_store_id')
            ->first();
        if(!$store)
            return;
        $get = DB::table('zipcodes')
            ->where("store_id", $store->nuvemshop_store_id)
            ->count();

        return $get;
    }
}
