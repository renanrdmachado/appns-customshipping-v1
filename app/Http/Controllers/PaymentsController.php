<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use App\Models\Payments;
use App\Models\App;

class PaymentsController extends Controller
{
    //

    public function edit() {
        $data = array(
            "store" => DB::table('store')->where('user_id',Auth::user()->id)->first()
        );

        return view('payments.edit', $data );
    }

    public function save( Request $req ) {
        $req = $req->except('_token');
        $save = Payments::createSubscriptionFlow( $req );
        return $save;
    }

    public function refresh( Request $req ) {
        $req = $req->except('_token');
        if( !$req['refresh'] )
            return;
        $refresh = Payments::paymentsRefresh();
        return $refresh;
    }

    public function webhook( Request $data ) {
        if( !$data['payment'] )
            return json_encode([ "erro" => "Array 'payment' não encontrada!" ]);

        if( !str_starts_with($data['payment']['externalReference'],"app_") )
            return json_encode([ "erro" => "Array 'externalReference' não começa com 'app_'!" ]);

        $user_id = str_replace("app_","",$data['payment']['externalReference']);

        DB::table('store')
            ->where('user_id',$user_id)
            ->update([
                'payments_cus_id'=>$data['payment']['customer'],
                'payments_sub_id'=>$data['payment']['subscription'],
                'payments_next_date'=>$data['payment']['dueDate'],
                'payments_status'=>$data['payment']['status'],
                'payments_data'=>json_encode($data['payment']),
                'updated_at'    => Carbon::now()
            ]);

        return json_encode([ "success" => "Usuário {$user_id} atualizado com sucesso!" ]);
    }

}
