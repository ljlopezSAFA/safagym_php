<?php

namespace App\Utils;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UtilidadesToken
{


    public function extraerTokenData(Request $request, JWTTokenManagerInterface  $jwtManager){
        // Obtener el token JWT de la cabecera
        $token = $request->headers->get('authorization');
        $formatToken = str_replace('Bearer ', '', $token);
        $finalToken = $jwtManager->parse($formatToken);
        return $finalToken;
    }

}