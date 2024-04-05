<?php

namespace App\Traits;

trait UppercaseValuesTrait
{
    //guardar en la base de datos los atributos en mayusculas
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        if ($key !== 'password') {
            if (is_string($value))
                $this->attributes[$key] = trim(strtoupper($value));
        }

        if ($key === 'email') {
            if (is_string($value))
                $this->attributes[$key] = trim(strtolower($value));
        }

        if ($key === 'correo') {
            if (is_string($value))
                $this->attributes[$key] = trim(strtolower($value));
        }

        if($key === 'firma_url') {
            if (is_string($value))
            $this->attributes[$key] = $value;
        }

        if($key === 'foto_url') {
            if (is_string($value))
            $this->attributes[$key] = $value;
        }
        if($key === 'comprobante') {
            if (is_string($value))
            $this->attributes[$key] = $value;
        }
        if($key === 'comprobante2') {
            if (is_string($value))
            $this->attributes[$key] = $value;
        }

        if($key === 'fotografia' && is_string($value))$this->attributes[$key] = trim($value);
        if($key === 'evidencia1' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'evidencia2' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'logo_url' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'imagen_informe' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'logo_claro' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'logo_oscuro' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'logo_marca_agua' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'descripcion' && is_string($value)) $this->attributes[$key] = trim($value);
        if($key === 'saldoable_type' && is_string($value)) $this->attributes[$key] = $value;
    }
}
