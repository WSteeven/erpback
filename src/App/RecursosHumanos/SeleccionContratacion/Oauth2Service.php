<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Oauth2Service
{
    private $driver;
    private $id_empleado;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }
    public function coneccion(){
        $clientId = config('services.google.client_id');
        // $clientSecret = config('services.google.client_secret');
         $redirectUri = config('services.google.redirect');

         $url = 'https://accounts.google.com/o/oauth2/auth';
         $queryParams = [
             'client_id' => $clientId,
             'redirect_uri' => $redirectUri,
             'scope' => 'openid profile email',
             'response_type' => 'code',
             'state' => Session::token()
         ];
         $queryString = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
         $redirectUrl = $url . '?' . $queryString;
    }



}
