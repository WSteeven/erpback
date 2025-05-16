<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function getFile($file_path)
    {
        $file_path = ltrim($file_path, '/');

        Log::channel('testing')->info('Log', ['getFile: arg', $file_path]);
        Log::channel('testing')->info('Log', ['getFile: url', url($file_path)]);

//        return response()->file(redirect($file_path));
//        return redirect($file_path);
        $fastAPIUrl = env('FAST_API_URL_FOR_DOWNLOAD') . $file_path;
//        return redirect($fastAPIUrl);
        return response()->file($fastAPIUrl);
    }

    public function downloadFile($file_path){
        $file_path = ltrim($file_path, '/');

    }
}
