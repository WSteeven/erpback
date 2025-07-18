<?php

namespace Src\App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PowerBiService
{
    protected Client $client;
    protected string $tenantId;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->client = new Client();
        $this->tenantId = env('POWERBI_TENANT_ID');
        $this->clientId = env('POWERBI_CLIENT_ID');
        $this->clientSecret = env('POWERBI_CLIENT_SECRET');
    }

    /**
     * @throws GuzzleException
     */
    public function getAccessToken(){
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $response = $this->client->post($url, [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['access_token'];
    }

    /**
     * @throws GuzzleException
     */
    public function getReportEmbedUrl($reportId, $groupId)
    {
        $accessToken = $this->getAccessToken();

        $url = "https://api.powerbi.com/v1.0/myorg/groups/{$groupId}/reports/{$reportId}";

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['embedUrl'];
    }
}
