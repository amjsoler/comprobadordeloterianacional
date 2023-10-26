<?php
namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;

class Helpers
{
    /**
     * Método para ver si un décimo está premiado dado el set de resultados
     *
     * @param [Obj{tipo,numero,premio}] $resultados Los resultados sobre los que se comprueba
     * @param string $numeroApostado El número apostado
     * @param string $reintegro El reintegro apostado
     * @param string $serie La serie apostada
     * @param string $fraccion La fracción apostada
     *
     * @return {premioTotal, premiosObtenidos=>[{premio, cantidad}]}
     *  0: OK
     * -1: excepción
     */
    public static function comprobarDecimo($resultados, $numeroApostado, $reintegro, $serie, $fraccion)
    {
        $response = [];


        Log::debug("Entrando al helper comprobarDecimo",
            array(
                "request: " => compact("resultados","numeroApostado", "reintegro", "serie", "fraccion"),
            )
        );

        try{
            $premioTotal = 0;
            $premiosObtenidos = collect();

            $resultados = self::interpretarCadenaResultados($resultados);

            $premioEncontrado = false;

            //Iteramos sobre el array de resultados
            foreach($resultados as $resultado){
                //Dentro de cada elemento comprobamos el tipo de premio
                if(!$premioEncontrado){
                    switch($resultado->nombre){
                        case "especial":
                            if(self::comprobarEspecial($resultado->numero, $numeroApostado, $serie, $fraccion)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Especial" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "primero":
                            if(self::comprobarPrimero($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Primer premio" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "segundo":
                            if(self::comprobarSegundo($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Segundo premio" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "4cifras":
                            if(self::comprobar4cifras($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Acierto de 4 cifras" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "3cifras":
                            if(self::comprobar3cifras($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Acierto de 3 cifras" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "2cifras":
                            if(self::comprobar2cifras($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Acierto de 2 cifras" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "aproximacionesprimero":
                            if(self::comprobarAproximacionesPrimero($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Aproximación al primer premio" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "aproximacionessegundo":
                            if(self::comprobarAproximacionesSegundo($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Aproximación al segundo premio" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "centenaprimero":
                            if(self::comprobarCentenaPrimero($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Centena del primer premio" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "centenasegundo":
                            if(self::comprobarCentenaSegundo($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Centena del segundo premio" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "3terminacionesprimero":
                            if(self::comprobar3terminacionesPrimero($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Terminación de 3 cifras" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "2terminacionesprimero":
                            if(self::comprobar2terminacionesPrimero($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Terminación de 2 cifras" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "1terminacionprimero":
                            if(self::comprobar1terminacionPrimero($resultado->numero, $numeroApostado)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Terminación de 1 cifra" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                        case "reintegros":
                            if(self::comprobarReintegros($resultado->numero, $reintegro)){
                                $premioTotal += $resultado->premio;
                                $premiosObtenidos->push((object)["Reintegro" => $resultado->premio]);
                                $premioEncontrado = true;
                            }
                            break;
                    }
                }
            }

            $response["data"] = compact("premioTotal", "premiosObtenidos");
            $response["code"] = 0;
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("resultados","numeroApostado", "reintegro", "serie", "fraccion"),
                    "response: " => $response
                )
            );
        }

        Log::debug("Entrando al helper comprobarDecimo",
            array(
                "request: " => compact("resultados","numeroApostado", "reintegro", "serie", "fraccion"),
                "response: " => $response
            )
        );

        return $response;
    }

    private static function comprobarEspecial($numero, $numeroApostado, $serie, $fraccion)
    {
        //Hacemos split del número premiado puesto que vendrá con formato XXXXX&X&X
        $desglose = explode("&", $numero);

        if($desglose[0] == $numeroApostado &&
            $desglose[1] == $serie &&
            $desglose[2] == $fraccion){
            return true;
        }
        else{
            return false;
        }
    }

    private static function comprobarPrimero($numero, $numeroApostado)
    {
        return ($numero == $numeroApostado) ? true : false;
    }

    private static function comprobarSegundo($numero, $numeroApostado)
    {
        return ($numero == $numeroApostado) ? true : false;
    }

    private static function comprobar4cifras($numero, $numeroApostado)
    {
        //Desglosamos los números de 4 cifras premiados
        $desglose = explode("&", $numero);

        //reducimos el número apostado a 4 cifras
        $numeroApostado4cifras = substr($numeroApostado, -4);
        foreach($desglose as $premiado){

            if($premiado == $numeroApostado4cifras){
                return true;
            }
        }

        return false;
    }

    private static function comprobar3cifras($numero, $numeroApostado)
    {
        //Desglosamos los números de 3 cifras premiados
        $desglose = explode("&", $numero);

        //reducimos el número apostado a 3 cifras
        $numeroApostado3cifras = substr($numeroApostado, -3);
        foreach($desglose as $premiado){

            if($premiado == $numeroApostado3cifras){
                return true;
            }
        }

        return false;
    }

    private static function comprobar2cifras($numero, $numeroApostado)
    {
        //Desglosamos los números de 2 cifras premiados
        $desglose = explode("&", $numero);

        //reducimos el número apostado a 2 cifras
        $numeroApostado2cifras = substr($numeroApostado, -2);
        foreach($desglose as $premiado){

            if($premiado == $numeroApostado2cifras){
                return true;
            }
        }

        return false;
    }

    private static function comprobarAproximacionesPrimero($numero, $numeroApostado)
    {
        //Desglosamos los números por aproximación
        $desglose = explode("&", $numero);

        foreach($desglose as $premiado) {
            if ($premiado == $numeroApostado) {
                return true;
            }
        }

        return false;
    }

    private static function comprobarAproximacionesSegundo($numero, $numeroApostado)
    {
        //Desglosamos los números por aproximación
        $desglose = explode("&", $numero);

        foreach($desglose as $premiado) {
            if ($premiado == $numeroApostado) {
                return true;
            }
        }

        return false;
    }

    private static function comprobarCentenaPrimero($numero, $numeroApostado)
    {
        $extremos = explode("&", $numero);

        if($numeroApostado >= $extremos[0] && $numeroApostado <= $extremos[1]){
            return true;
        }
        else{
            return false;
        }
    }

    private static function comprobarCentenaSegundo($numero, $numeroApostado)
    {
        $extremos = explode("&", $numero);

        if($numeroApostado >= $extremos[0] && $numeroApostado <= $extremos[1]){
            return true;
        }
        else{
            return false;
        }
    }

    private static function comprobar3terminacionesPrimero($numero, $numeroApostado)
    {
        //reducimos el número apostado a 3 cifras
        $numeroApostado3cifras = substr($numeroApostado, -3);

        if($numero == $numeroApostado3cifras){
            return true;
        }
        else{
            return false;
        }
    }

    private static function comprobar2terminacionesPrimero($numero, $numeroApostado)
    {
        //reducimos el número apostado a 2 cifras
        $numeroApostado2cifras = substr($numeroApostado, -2);

        if($numero == $numeroApostado2cifras){
            return true;
        }
        else{
            return false;
        }
    }

    private static function comprobar1terminacionPrimero($numero, $numeroApostado)
    {
        //reducimos el número apostado a 3 cifras
        $numeroApostado1cifras = substr($numeroApostado, -1);

        if($numero == $numeroApostado1cifras){
            return true;
        }
        else{
            return false;
        }
    }

    private static function comprobarReintegros($numero, $reintegro)
    {
        //Desglosamos los reintegros premiados
        $desglose = explode("&", $numero);

        foreach($desglose as $reintegroPremiado){
            if($reintegroPremiado == $reintegro) {
                return true;
            }
        }

        return false;
    }

    public static function interpretarCadenaResultados($resultados)
    {
        $coleccionResultados = collect();

        $resultadosDesglosados = explode("\r\n", $resultados);

        foreach($resultadosDesglosados as $resultadoRaw){
            $resultadoRawDesglosado = explode(";", $resultadoRaw);

            $nombre = $resultadoRawDesglosado[0];
            $numero = $resultadoRawDesglosado[1];
            $premio = $resultadoRawDesglosado[2];

            $coleccionAux = (object)["nombre" => $nombre, "numero" => $numero, "premio" => $premio];
            $coleccionResultados->push($coleccionAux);
        }

        return $coleccionResultados;
    }
}
