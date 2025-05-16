<?php

if(!function_exists('getActiveGuard')){
    /**
     * @return string|null
     */
    function getActiveGuard(){
        $guards = ['web', 'sanctum', 'user_external']; // Agregar en este array cualquier otro guard que se cree

        foreach ($guards as $guard){
            if(Auth::guard($guard)->check()){
                return $guard;
            }
        }
        return null;
    }
}
