<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
Use \Carbon\Carbon;
use App\Models\Asaas;


class Payments extends Model
{
    use HasFactory;

    /* ====== USUÁRIOS ====== */
    public static function customersCreate( $data ) { // OK
        // Criar usuário
        $endpoint = "/customers";
        $data["externalReference"] = "app_". Auth::user()->id;
        $post = Asaas::post( $endpoint, $data );
        return $post;
    }
    public static function customersGet( $id ) { // OK
        // Pega usuário
        $endpoint = "/customers/".$id;
        $get = Asaas::get( $endpoint );
        return $get;
    }
    public static function customersGetAll( $query ) { // OK
        // Lista usuários
        $endpoint = "/customers?".$query;
        $get = Asaas::get( $endpoint );
        return $get;
    }
    public static function customersUpdate() {
        // Atualiza usuário ******* AJUSTAR
        $endpoint = "/".$id;
    }

    /* ====== ASSINATURAS ====== */
    public static function subscriptionsCreate( $data ) { // OK
        // Cria assinatura
        $endpoint = "/subscriptions";
        $data = array(
            "customer"  => $data['id'],
            "billingType" => "UNDEFINED",
            "value" => 49.90,
            "nextDueDate"   => "",
            "cycle" => "MONTHLY",
            "description" => "APP NS - Frete Personalizado (v1)"

        );
        $post = Asaas::post( $endpoint, $data );
        return $post;

    }
    
    public static function subscriptionsGetAll($query) { // OK
        // Lista assinaturas
        $endpoint = "/subscriptions?".$query;
        $get = Asaas::get( $endpoint );
        return $get;
    }
    public static function subscriptionsUpdate() {
        // Atualiza assinatura
        $endpoint = "/subscriptions/{id}";
    }

    /* ====== COBRANÇAS ====== */
    public static function subscriptionsGetPayments( $id ) { // OK
        // Pega assinatura
        $endpoint = "/subscriptions/{$id}/payments";
        $get = Asaas::get( $endpoint );
        return $get;
    }

    public static function createSubscriptionFlow( $data ){
       
        // Pega ou cria usuário
        $customersGetAll = Payments::customersGetAll( "cpfCnpj=".$data['cpfCnpj'] );
        $customer = null;
        if($customersGetAll['totalCount']){
            $customer = $customersGetAll['data'][0];
        } else {
            $customer = Payments::customersCreate( $data );
        }

        // Pega ou cria assinatura
        $subscriptionsGetAll = Payments::subscriptionsGetAll( "customer=".$customer['id'] );
        $subscription = null;
        if($subscriptionsGetAll['totalCount']){
            $subscription = $subscriptionsGetAll['data'][0];
        } else {
            $subscription = Payments::subscriptionsCreate( $customer );
        }

        // Pega última cobrança
        $subscriptionsGetPayments = Payments::subscriptionsGetPayments( $subscription['id'] );
        $payments = null;
        if($subscriptionsGetPayments['totalCount']){
            $payments = $subscriptionsGetPayments['data'][0];
        }
        

        DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->update([
                'payments_cus_id'=>$subscription['customer'],
                'payments_sub_id'=>$subscription['id'],
                'payments_next_date'=>$subscription['nextDueDate'],
                'payments_status'=>$payments['status'],
                'payments_data'=>json_encode($payments),
                'updated_at'    => Carbon::now()
            ]);

        return;

    }

    public static function paymentsRefresh( ) {
        
        $subs = DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->get('payments_sub_id')
            ->first();
            
        // Pega última cobrança
        $subscriptionsGetPayments = Payments::subscriptionsGetPayments( $subs->payments_sub_id );
        $payments = null;

        if(!$subscriptionsGetPayments)  
            return;

        if($subscriptionsGetPayments['totalCount']){
            $payments = $subscriptionsGetPayments['data'][0];
        }

        if(!$payments) {
            DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->update([
                'payments_next_date'=>null,
                'payments_status'=>null,
                'payments_data'=>null,
                'payments_cus_id'=>null,
                'payments_sub_id'=>null,
                'updated_at'    => Carbon::now()
            ]);
            return redirect('payments');
        }

        DB::table('store')
            ->where('user_id',Auth::user()->id)
            ->update([
                'payments_next_date'=>$payments['dueDate'],
                'payments_status'=>$payments['status'],
                'payments_data'=>json_encode($payments),
                'updated_at'    => Carbon::now()
            ]);

        return redirect('payments');
    }
}
