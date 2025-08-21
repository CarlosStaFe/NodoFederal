<?php

namespace App\Helpers;

class CuitHelper
{
    /**
     * Calcula el CUIT a partir del DNI y el tipo (M, F, S)
     * @param string $dni
     * @param string $tipo 'M' (masculino), 'F' (femenino), 'S' (sociedad)
     * @return string
     */
    public static function calcularCuit($dni, $tipo)
    {
        $dni = str_pad($dni, 8, '0', STR_PAD_LEFT);
        switch (strtoupper($tipo)) {
            case 'M':
                $prefijo = '20';
                break;
            case 'F':
                $prefijo = '27';
                break;
            case 'S':
                $prefijo = '30';
                break;
            default:
                $prefijo = '20';
        }
        $base = $prefijo . $dni;
        $multiplicadores = [5,4,3,2,7,6,5,4,3,2];
        $suma = 0;
        for ($i = 0; $i < 10; $i++) {
            $suma += $base[$i] * $multiplicadores[$i];
        }
        $resto = $suma % 11;
        $digito = 11 - $resto;
        if ($digito == 11) $digito = 0;
        if ($digito == 10) {
            if ($prefijo == '20') $prefijo = '23';
            if ($prefijo == '27') $prefijo = '23';
            $base = $prefijo . $dni;
            $suma = 0;
            for ($i = 0; $i < 10; $i++) {
                $suma += $base[$i] * $multiplicadores[$i];
            }
            $resto = $suma % 11;
            $digito = 11 - $resto;
            if ($digito == 11) $digito = 0;
        }
        return $prefijo . $dni . $digito;
    }
}
