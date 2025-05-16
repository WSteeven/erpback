<?php

namespace Src\Shared;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConeccionContifico
{

    private $headers;


    public function __construct()
    {
        $this->headers   = [
            'Authorization' => env('API_KEY_CONTIFICO', 'FrguR1kDpFHaXHLQwplZ2CwTX3p8p9XHVTnukL98V5U')
        ];
    }
    
    public function consultar($endpoint, $params)
    {
        // Log::channel('testing')->info('Log', ['headers', $this->headers]);
        try {
            $url = 'https://api.contifico.com/sistema/api/v1/' . $endpoint;

            $response = Http::withHeaders($this->headers)->get($url, $params);

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
