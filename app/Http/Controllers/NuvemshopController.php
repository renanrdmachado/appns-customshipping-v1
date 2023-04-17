<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
Use \Carbon\Carbon;
use App\Models\Nuvemshop;
use App\Models\NsShipping;
use App\Models\Payments;

class NuvemshopController extends Controller
{
    //
    public function install() {
        if( !isset($_GET["code"]) ) {
            return response()->view('pages.error', ['message' => "A query ?code não foi encontrada!"], 500);
        }

        $token = Nuvemshop::tokenGet($_GET["code"]);
        if( !isset( $token['access_token'] ) ) {
            return response()->view('pages.error', ['message' => "Erro ao obter token!<br/> Erro: ".json_encode($token)], 500);
        }

        $data = array (
            "user_id" => Auth::user()->id,
            "nuvemshop_store_id" => $token['user_id'],
            "nuvemshop_store_token" => $token['access_token'],
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        );

        $upsert = DB::table('store')
            ->upsert($data,["user_id"],["nuvemshop_store_id","nuvemshop_store_token"]);

        $shippingcarrier = NsShipping::NsShippingCarrierInit($token['user_id']);

        Payments::paymentsRefresh();

        return redirect('dashboard');
    }
}
