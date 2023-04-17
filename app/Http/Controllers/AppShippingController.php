<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// Use \Carbon\Carbon;

use App\Models\AppShipping;

use Illuminate\Http\Request;

class AppShippingController extends Controller
{
    //
    public static function index( ) {

        // dd( NsShipping::NsShippingCarrierInit() );

        $store = DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->first();
        if(!$store) {
            $store_id = 0;
        } else {
            $store_id = $store->nuvemshop_store_id;
        }
        $shipping_data = AppShipping::AppShippingOptionsGet();
        return view('shippings.edit',['shippings'=> $shipping_data,'store'=>$store_id]);
    }

    public static function find( Request $data ) {
        $req = $data->all();
        // dd( $req );
        if (!$req)
            return false;
        // dd( $req );
        return AppShipping::AppShippingOptionResponse($req);
    }
    public static function save(Request $data) {
        $req = $data->all();

        if (!$req)
            return false;

        $inputs = [];
        foreach( $req as $k=>$v ) {
            foreach( $v as $k2=>$v2 ) {
                $inputs[$k2][$k] = $v2;
            }
        }

        if (!$inputs)
            return false;

        
        $upsert = AppShipping::AppShippingSave($inputs);

        return $upsert;
    }

    public static function importCSV(Request $request) {
        $import = AppShipping::AppShippingImportCSV( $request->file('my-csv') );
    }
}
