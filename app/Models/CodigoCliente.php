<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoCliente extends Model
{
    use HasFactory;

    protected $table = "codigo_cliente";
    protected $fillable = ['propietario_id','producto_id','codigo'];

    public function x(){
        {
            "message": "SQLSTATE[HY000]: General error: 1366 Incorrect integer value: '{\"id\":1,\"nombre\":\"JP\",\"created_at\":\"2022-08-17T20:11:30.000000Z\",\"updated_at\":\"2022-08-17T20:11:30.000000Z\"}' for column 'propietario_id' at row 1 (SQL: insert into `codigo_cliente` (`propietario_id`, `producto_id`, `codigo`, `updated_at`, `created_at`) values ({\"id\":1,\"nombre\":\"JP\",\"created_at\":\"2022-08-17T20:11:30.000000Z\",\"updated_at\":\"2022-08-17T20:11:30.000000Z\"}, 51, 000051, 2022-08-17 17:48:55, 2022-08-17 17:48:55))",
            "exception": "Illuminate\\Database\\QueryException",
            "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php",
            "line": 759,
            "trace": [
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php",
                    "line": 719,
                    "function": "runQueryCallback",
                    "class": "Illuminate\\Database\\Connection",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php",
                    "line": 545,
                    "function": "run",
                    "class": "Illuminate\\Database\\Connection",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php",
                    "line": 497,
                    "function": "statement",
                    "class": "Illuminate\\Database\\Connection",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Processors\\Processor.php",
                    "line": 32,
                    "function": "insert",
                    "class": "Illuminate\\Database\\Connection",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php",
                    "line": 3246,
                    "function": "processInsertGetId",
                    "class": "Illuminate\\Database\\Query\\Processors\\Processor",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php",
                    "line": 1835,
                    "function": "insertGetId",
                    "class": "Illuminate\\Database\\Query\\Builder",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php",
                    "line": 1216,
                    "function": "__call",
                    "class": "Illuminate\\Database\\Eloquent\\Builder",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php",
                    "line": 1181,
                    "function": "insertAndSetId",
                    "class": "Illuminate\\Database\\Eloquent\\Model",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php",
                    "line": 1022,
                    "function": "performInsert",
                    "class": "Illuminate\\Database\\Eloquent\\Model",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php",
                    "line": 975,
                    "function": "save",
                    "class": "Illuminate\\Database\\Eloquent\\Model",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php",
                    "line": 302,
                    "function": "Illuminate\\Database\\Eloquent\\{closure}",
                    "class": "Illuminate\\Database\\Eloquent\\Builder",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php",
                    "line": 976,
                    "function": "tap"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23,
                    "function": "create",
                    "class": "Illuminate\\Database\\Eloquent\\Builder",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php",
                    "line": 2191,
                    "function": "forwardCallTo",
                    "class": "Illuminate\\Database\\Eloquent\\Model",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php",
                    "line": 2203,
                    "function": "__call",
                    "class": "Illuminate\\Database\\Eloquent\\Model",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\app\\Http\\Controllers\\ProductoController.php",
                    "line": 43,
                    "function": "__callStatic",
                    "class": "Illuminate\\Database\\Eloquent\\Model",
                    "type": "::"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php",
                    "line": 54,
                    "function": "store",
                    "class": "App\\Http\\Controllers\\ProductoController",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php",
                    "line": 45,
                    "function": "callAction",
                    "class": "Illuminate\\Routing\\Controller",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php",
                    "line": 261,
                    "function": "dispatch",
                    "class": "Illuminate\\Routing\\ControllerDispatcher",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php",
                    "line": 204,
                    "function": "runController",
                    "class": "Illuminate\\Routing\\Route",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 725,
                    "function": "run",
                    "class": "Illuminate\\Routing\\Route",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 141,
                    "function": "Illuminate\\Routing\\{closure}",
                    "class": "Illuminate\\Routing\\Router",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\SubstituteBindings.php",
                    "line": 50,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
                    "line": 126,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
                    "line": 102,
                    "function": "handleRequest",
                    "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php",
                    "line": 54,
                    "function": "handleRequestUsingNamedLimiter",
                    "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\Middleware\\Authenticate.php",
                    "line": 44,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Auth\\Middleware\\Authenticate",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\sanctum\\src\\Http\\Middleware\\EnsureFrontendRequestsAreStateful.php",
                    "line": 33,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 141,
                    "function": "Laravel\\Sanctum\\Http\\Middleware\\{closure}",
                    "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 116,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\sanctum\\src\\Http\\Middleware\\EnsureFrontendRequestsAreStateful.php",
                    "line": 34,
                    "function": "then",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 116,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 726,
                    "function": "then",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 703,
                    "function": "runRouteWithinStack",
                    "class": "Illuminate\\Routing\\Router",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 667,
                    "function": "runRoute",
                    "class": "Illuminate\\Routing\\Router",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 656,
                    "function": "dispatchToRoute",
                    "class": "Illuminate\\Routing\\Router",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
                    "line": 167,
                    "function": "dispatch",
                    "class": "Illuminate\\Routing\\Router",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 141,
                    "function": "Illuminate\\Foundation\\Http\\{closure}",
                    "class": "Illuminate\\Foundation\\Http\\Kernel",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 21,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull.php",
                    "line": 31,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 21,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TrimStrings.php",
                    "line": 40,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php",
                    "line": 27,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance.php",
                    "line": 86,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Middleware\\HandleCors.php",
                    "line": 62,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Http\\Middleware\\HandleCors",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Middleware\\TrustProxies.php",
                    "line": 39,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 180,
                    "function": "handle",
                    "class": "Illuminate\\Http\\Middleware\\TrustProxies",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 116,
                    "function": "Illuminate\\Pipeline\\{closure}",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
                    "line": 142,
                    "function": "then",
                    "class": "Illuminate\\Pipeline\\Pipeline",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
                    "line": 111,
                    "function": "sendRequestThroughRouter",
                    "class": "Illuminate\\Foundation\\Http\\Kernel",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\public\\index.php",
                    "line": 52,
                    "function": "handle",
                    "class": "Illuminate\\Foundation\\Http\\Kernel",
                    "type": "->"
                },
                {
                    "file": "C:\\xampp\\htdocs\\backend_jpconstrucred\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\resources\\server.php",
                    "line": 16,
                    "function": "require_once"
                }
            ]
        }
    }

}


