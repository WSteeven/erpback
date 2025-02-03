<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CheckAndFetchImageOrFile
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        if (str_starts_with($path, 'storage/')) {
            $filePath = $path;
//            Log::channel('testing')->info('Log', ['Ruta  completa antes de local', $filePath]);

            // Verificar si la solicitud corresponde a un archivo
            // INFO: No entra aquí cuando la imagen si existe en local, ya que accede al recurso por la carpeta public que está enlazada al storage/app/public
            if (Storage::exists($filePath)) {
//                Log::channel('testing')->info('Log', ['CheckAndFetchImageOrFile -> entro en if de local', $filePath]);
                return $next($request); // Archivo existe localmente, continuar
            }

//            $fastAPIUrl = env('FAST_API_URL').$filePath;
//            return redirect($fastAPIUrl);
            // Se le quita el storage para que no de problemas, porque en FASTAPI se accede sin storage/
            $filePath = str_replace('storage/', '', ltrim($path, '/'));
//            Log::channel('testing')->info('Log', ['filePath despues de no encontrarse en local', $filePath]);

            $options = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ]);

            $fastAPIUrl = env('FAST_API_URL') . $filePath;
            $response = @file_get_contents($fastAPIUrl, false, $options);
            if ($response === false) {
                throw new Exception('Archivo no encontrado', 404);
            }
            // Decodifica la respuesta del servidor remoto
            $remoteData = json_decode($response, true);
            // Log::channel('testing')->info('Log', ['remoteData', $remoteData]);
            if (isset($remoteData['url'])) {
                // return redirect($remoteData['url']);
                return redirect()->away($remoteData['url']);

            }
            throw new Exception('Error al procesar la solicitud', 500);
        }
        return $next($request);
    }
}
