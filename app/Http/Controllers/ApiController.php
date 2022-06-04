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

        $grupo1 = $this->getDadosPrimeiroGrupo($response , '1AF');
        $grupo2 = $this->getDadosPrimeiroGrupo($response , '4DA');

        $result = [];
        $result['flights'] = $response;
        $result['groups'] = [$grupo1,  $grupo2];
        $result['totalGroups'] = count($result['groups'] );
        $result['cheapestPrice'] = ($grupo1['totalPrice'] < $grupo2['totalPrice'] ? $grupo1['totalPrice'] : $grupo2['totalPrice']);
        $result['cheapestGroup'] = ($grupo1['totalPrice'] < $grupo2['totalPrice'] ? $grupo1['uniqueId'] : $grupo2['uniqueId']);

        $result['totalFlights'] = count($result['flights'] );

        return response()->json( $result);
    }

    public function getDadosPrimeiroGrupo($response, $fare){

        $outbound = [];
        $inbound = [];
        $totalPrice = [];
        foreach ($response as $arr){

            //voos de ida
            if($arr['origin'] == 'CNF' && $arr['fare'] == $fare){
                $outbound[] = $arr;
                $totalPrice[] +=  $arr['price'];
            }

            //voos de volta
            if($arr['origin'] == 'BSB' && $arr['fare'] == $fare){
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
