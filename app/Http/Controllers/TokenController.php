<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Token;

class TokenController extends Controller {

    public function all() {
        return response()->json(Post::all());
    }

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

    public function handle() {
        
        //delete
            Token::truncate();

        //create
            $token  = $this->generate();
            $task   = $this->create($token->access_token);

        //return
            return $task;
    }

    private function create($token) {
        $create = Token::create([
            'token' => $token,
        ]);
        return response()->json($create, 201);
    }

    public function read() {
        $find = Token::firstWhere('id_token', 1);
        return response()->json($find->token);
    }
}
