<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use Illuminate\Support\Facades\Session;

class Oauth2Service
{
    private $driver;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $url;
    protected $version = 'v3.3';
    protected $queryParams;
    protected $scope;



    public function __construct($driver)
    {
        $this->driver = $driver;
        $this->conexion();
    }
    public function conexion()
    {
        switch ($this->driver) {
            case 'google':
                $this->clientId = config('services.google.client_id');
                $this->redirectUri = config('services.google.redirect');
                $this->url = 'https://accounts.google.com/o/oauth2/auth';
                $this->scope = 'openid profile email';
                break;
            case 'linkedin':
                $this->clientId = config('services.linkedin.client_id');
                $this->clientSecret = config('services.linkedin.client_secret');
                $this->redirectUri = config('services.linkedin.redirect');
                $this->url = 'https://www.linkedin.com/oauth/v2/authorization';
                $this->scope = 'r_liteprofile r_emailaddress';

                break;
            case 'facebook':
                $this->clientId = config('services.facebook.client_id');
                $this->clientSecret = config('services.facebook.client_secret');
                $this->redirectUri = config('services.facebook.redirect');
                $this->url = 'https://www.facebook.com/' . $this->version . '/dialog/oauth';
                break;
        }
    }
    private function obtenerParametros()
    {
        $this->queryParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'scope' => $this->scope,
            'response_type' => 'code',
            'state' => Session::token()
        ];
    }
    public function obtenerUrl()
    {
        $this->obtenerParametros();
        return $this->url . '?' . http_build_query($this->queryParams, '', '&', PHP_QUERY_RFC3986);
    }
}
