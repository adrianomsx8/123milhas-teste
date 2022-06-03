<?php

namespace App\Http\Controllers;

use App\facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ApiController extends Controller
{
    public function agruparVoos()
    {
       // $response = Http::get('http://prova.123milhas.net/api/flights');
        $response  = Api::get('flights')->json();

        $outbound = [];
        $inbound = [];
        $totalPrice = [];

        $grupo1 = $this->getDadosPrimeiroGrupo($response, $outbound, $inbound, $totalPrice);


       var_dump( $grupo1);
    }

    public function getDadosPrimeiroGrupo($response, $outbound, $inbound, $totalPrice){
        foreach ($response as $arr){

            //voos de ida
            if($arr['origin'] == 'CNF' && $arr['fare'] == '1AF'){
                $outbound[] = $arr;
                $totalPrice[] +=  $arr['price'];
            }

            //voos de volta
            if($arr['origin'] == 'BSB' && $arr['fare'] == '1AF'){
                $inbound[] = $arr;
                $totalPrice[] +=  $arr['price'];
            }

            $grupo1['uniqueId'] = uniqid();
            $grupo1['totalPrice'] = array_sum($totalPrice);
            $grupo1['outbound'] = $outbound;
            $grupo1['inbound'] = $inbound;
        }

        return $grupo1;
    }
}
