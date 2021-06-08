<?php

namespace App\Http\Middleware;

use Closure;
use Auth0\SDK\Exception\InvalidTokenException;
use Auth0\SDK\Helpers\JWKFetcher;
use Auth0\SDK\Helpers\Tokens\AsymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\TokenVerifier;


class Auth0Middleware {
    
    private function generate() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => env('AUTH0_DOMAIN').'oauth/token',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_POSTFIELDS      =>'{
                "client_id":"wAMB3wZbECK3e6xgdl1fjRRY5eBbiHdU",
                "client_secret":"66MkLOQ8w5sMsSBtSZtuVCl74eU0XScqPpZmKez-cU_ss0DkZ6UrGcgiv16iB03H",
                "audience":"https://api.league.co.id",
                "grant_type":"client_credentials"
            }',
            CURLOPT_HTTPHEADER      => array(
                'Content-Type: application/json',
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function handle($request, Closure $next) {
        //$token = $request->bearerToken();    
        //get token
            $generate   = $this->generate();
            $token      = $generate->access_token;

        //validate token
            if(!$token) {
                return response()->json('No token provided', 401);
            }
            $this->validateToken($token);
    
        return $next($request);
    }

    public function validateToken($token) {
        try {
            $jwksUri = env('AUTH0_DOMAIN') . '.well-known/jwks.json';
            $jwksFetcher = new JWKFetcher(null, [ 'base_uri' => $jwksUri ]);
            $signatureVerifier = new AsymmetricVerifier($jwksFetcher);
            $tokenVerifier = new TokenVerifier(env('AUTH0_DOMAIN'), env('AUTH0_AUD'), $signatureVerifier);
            $decoded = $tokenVerifier->verify($token);
        }
        catch(InvalidTokenException $e) {
            throw $e;
        };
    }
}