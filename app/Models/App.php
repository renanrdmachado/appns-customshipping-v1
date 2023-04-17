<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;
    public static function storeActive(){

        if( !Auth::check() )
            return;

        $get = DB::table('store')
            ->where("user_id", Auth::user()->id)
            ->get('payments_status')
            ->first();

        if( !$get ) 
            return;

        $status = $get->payments_status;
        
        $statusSuccess = ['RECEIVED','RECEIVED_IN_CASH'];

        return in_array($status,$statusSuccess);
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
