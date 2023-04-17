<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Asaas extends Model
{
    use HasFactory;
    public static function get( $endpoint ){
        return $response = Http::withHeaders([
            'access_token' => env("ASAAS_KEY")
        ])->get(env('ASAAS_URL') . $endpoint)
        ->json();
    }
    public static function post( $endpoint, $data ){
        return $response = Http::withHeaders([
            'access_token' => env("ASAAS_KEY")
        ])->post(env('ASAAS_URL') . $endpoint, $data )
        ->json();
    }
}
