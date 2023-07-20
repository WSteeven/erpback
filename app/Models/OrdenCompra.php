<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;


    //Forma de pago
    const CONTADO = 'CONTADO';
    const CREDITO = 'CREDITO';

    //Tiempo de pago
    CONST SEMANAL = '7 DIAS';
    CONST QUINCENAL = '15 DIAS';
    CONST MES = '30 DIAS';



}
