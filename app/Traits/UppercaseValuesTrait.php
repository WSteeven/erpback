<?php

namespace App\Traits;

trait UppercaseValuesTrait
{
    //guardar en la base de datos los atributos en mayusculas
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        if (is_string($value))
            $this->attributes[$key] = trim(strtoupper($value));
    }

    
}
