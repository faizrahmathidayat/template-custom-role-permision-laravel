<?php

namespace App\Traits;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

use GuzzleHttp\Client;
trait ApiRequest
{
    public function performRequest($method, $requestUri, $formParams = [], $headers = [])
    {
        $client = new Client([
                'headers' => $headers
            ]);

        $response = $client->request($method, $requestUri, ['form_params' => $formParams]);
        $response = json_decode($response->getBody()->getContents());
        return $response;
    }
}
