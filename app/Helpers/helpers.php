<?php
namespace App\Helpers;

use App\Http\Controllers\web\ResultadoController;
use App\Models\Resultado;
use App\Models\Sorteo;
use App\Models\User;
use App\Notifications\NuevasFechasDeSorteoDisponibles;
use App\Notifications\NuevosResultadosDisponibles;
use Carbon\Carbon;
use DOMDocument;
use Exception;
use Faker\Extension\Helper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

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
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Especial" => $resultado->premio]);
                                }
                            }
                            break;
                        case "primero":
                            if(self::comprobarPrimero($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Primer premio" => $resultado->premio]);
                                }
                            }
                            break;
                        case "segundo":
                            if(self::comprobarSegundo($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Segundo premio" => $resultado->premio]);
                                }
                            }
                            break;
                        case "4cifras":
                            if(self::comprobar4cifras($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Acierto de 4 cifras" => $resultado->premio]);
                                }
                            }
                            break;
                        case "3cifras":
                            if(self::comprobar3cifras($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Acierto de 3 cifras" => $resultado->premio]);
                                }
                            }
                            break;
                        case "2cifras":
                            if(self::comprobar2cifras($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Acierto de 2 cifras" => $resultado->premio]);
                                }
                            }
                            break;
                        case "aproximacionesprimero":
                            if(self::comprobarAproximacionesPrimero($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Aproximación al primer premio" => $resultado->premio]);
                                }
                            }
                            break;
                        case "aproximacionessegundo":
                            if(self::comprobarAproximacionesSegundo($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Aproximación al segundo premio" => $resultado->premio]);
                                }
                            }
                            break;
                        case "centenaprimero":
                            if(self::comprobarCentenaPrimero($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Centena del primer premio" => $resultado->premio]);
                                }
                            }
                            break;
                        case "centenasegundo":
                            if(self::comprobarCentenaSegundo($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Centena del segundo premio" => $resultado->premio]);
                                }
                            }
                            break;
                        case "3terminacionesprimero":
                            if(self::comprobar3terminacionesPrimero($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Terminación de 3 cifras" => $resultado->premio]);
                                }
                            }
                            break;
                        case "2terminacionesprimero":
                            if(self::comprobar2terminacionesPrimero($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Terminación de 2 cifras" => $resultado->premio]);
                                }
                            }
                            break;
                        case "1terminacionprimero":
                            if(self::comprobar1terminacionPrimero($resultado->numero, $numeroApostado)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Terminación de 1 cifra" => $resultado->premio]);
                                }
                            }
                            break;
                        case "reintegros":
                            if(self::comprobarReintegros($resultado->numero, $reintegro)){
                                if($resultado->premio > $premioTotal){
                                    $premioTotal = $resultado->premio;
                                    $premiosObtenidos = collect((object)["Reintegro" => $resultado->premio]);
                                }
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

    private static function interpretarCadenaResultados($resultados)
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

    /**
     * Método para esnifar los sorteos de varias fuentes e insertar en BD los que no existan
     *
     * @return void
     *  0: OK
     * -1: Excepción
     * -2: Error al leer las url desde el env
     * -3: Error al contrastar las fechas a insertar con las fechas que hay en bd
     * -4: Error al sacar los elementos diferentes entre dos arrays de sorteos
     * -5: Error al crear los sorteos dado en array a insertar
     */
    public static function esnifarYGuardarNuevosSorteos()
    {
        $response = [];

        Log::debug("Entrando al esnifarYGuardarNuevosSorteos de helpers");

        try{
            //Leemos las URLS desde las que esnifar
            $resultURLS = self::dameURLSParaEsnifarSorteos();

            if($resultURLS["code"] == 0){
                //Si están bien las urls paso a iterar sobre ellas y operar
                $urls = $resultURLS["data"];
                $arrayDeDatosProvisionales = [];

                foreach($urls as $url){
                    //Me traigo un array con el nombre, fecha y num de sorteo de cada URL
                    $result = self::dameFechaNombreYNumEsnifandoURL($url);

                    if($result["code"] == 0){
                        $datos = $result["data"];

                        array_push($arrayDeDatosProvisionales, $datos);
                    }
                }

                //Una vez tengo todos los datos en el array de provisionales, saco una intersección para evitar errores
                //TODO
                $sorteosDefinitivos = $arrayDeDatosProvisionales[0]; //TODO:

                //Ahora con el array de sorteos potenciales a insertar, consulto en BD los que ya tengo
                $result = Sorteo::dameFechasSorteoInArrayDeFechas(array_column($sorteosDefinitivos, "fecha"));

                if($result["code"] == 0){
                    $sorteosExistentes = $result["data"];

                    //Una vez tenga los dos arrays, hay que sacar un array resultante de la diferencia entre uno y otro, es decir,
                    //las fechas del array de sorteosDefinitivos que no estén en los sorteosExistentes
                    $result = self::dameArraySorteosNoExistentesEnBD($sorteosDefinitivos, $sorteosExistentes);

                    if($result["code"] == 0){
                        $sorteosAInsertar = $result["data"];

                        //TODO: La idea es que un futuro esto se haga automático, pero por el momento, simplemente se envía un mail co la info
                        /*$result = Sorteo::crearSorteosDadoUnArrayDeSorteos($sorteosAInsertar);

                        if($result["code"] == 0){
                            $response["code"] = 0;
                        }else{
                            $response["code"] = -5;

                            Log::error("Error al insertar el array de sorteos en la BD",
                                array(
                                    "response: " => $response
                                )
                            );
                        } */

                        // FIN TODO:

                        if(count($sorteosAInsertar)>0){
                            $user = User::find(1);
                            $user->notify(new NuevasFechasDeSorteoDisponibles($sorteosAInsertar));
                        }

                        $response["code"] = 0;
                    }
                    else{
                        //Fallo al sacar los elementos que difieren entre arrays
                        $response["code"] = -4;

                        Log::error("Esto no debería fallar si los arrays están bien formados.",
                            array(
                                "response: " => $response
                            )
                        );
                    }
                }else{
                    //Sin el listado de fechas existentes no puedo comparar por tanto no puede seguir la ejecución
                    $response["code"] = -3;

                    Log::error("Esto no debería fallar. Símplemente es la ejecución de un select",
                        array(
                            "response: " => $response
                        )
                    );
                }

            }else{
                $response["code"] = -2;

                Log::error("Error al leer las urls de configuración",
                    array(
                        "response: " => $response
                    )
                );
            }
        }catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del esnifarYGuardarNuevosSorteos de helpers",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que lee y devuelve un array con las URLS guardadas en el archivo de configuración
     *
     * @return array Array con las urls
     *  0: OK
     * -1: Excepción
     * -2: La clave no está puesta en la confiuración
     */
    private static function dameURLSParaEsnifarSorteos()
    {
        $response = [];

        Log::debug("Entrando al dameURLSParaEsnifarSorteos de helpers");

        try{
            //Leemos las URLS desde las que esnifar
            $urls = env("URLS_ESNIFAR_SORTEOS");

            if($urls){
                //Separamos cada url
                $urlsArr = explode(";", $urls);

                $response["code"] = 0;
                $response["data"] = $urlsArr;
            }else{
                $response["code"] = -2;

                Log::error("Las URLS para esnifar no están puestas. Guardalas con la clave: URLS_ESNIFAR_SORTEOS separadas por ; si hay más de una",
                    array(
                        "response: " => $response
                    )
                );
            }
        }catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del dameURLSParaEsnifarSorteos de helpers",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que devuelve las URL de las que esnifar los resultados
     *
     * @return [string]
     *  0: OK
     * -1: Excepción
     * -2: No está puesta la propiedad en el env
     */
    private static function dameURLSParaEsnifarResultados()
    {
        $response = [];

        Log::debug("Entrando al dameURLSParaEsnifarResultados de helpers");

        try{
            //Leemos las URLS desde las que esnifar
            $urls = env("URLS_ESNIFAR_RESULTADOS");

            if($urls){
                //Separamos cada url
                $urlsArr = explode(";", $urls);

                $response["code"] = 0;
                $response["data"] = $urlsArr;
            }else{
                $response["code"] = -2;

                Log::error("Las URLS para esnifar no están puestas. Guardalas con la clave: URLS_ESNIFAR_RESULTADOS separadas por ; si hay más de una",
                    array(
                        "response: " => $response
                    )
                );
            }
        }catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del dameURLSParaEsnifarSorteos de helpers",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * //Método que se encarga de consultar la url pasada por param y extraer la fecha, nombre y número de sorteo de la fuente
     *
     * @param string $url La url a consultar
     *
     * @return [{nombre, fecha, numero_sorteo}]
     *  0: OK
     * -1: Excepción
     */
    private static function dameFechaNombreYNumEsnifandoURL(string $url)
    {
        $response = [];

        Log::debug("Entrando al dameFechaNombreYNumEsnifandoURL de helpers");

        try{
            $sorteosObtenidos = [];

            //Hacemos la petición y creamos el objeto DOM para movernos
            $contenido = Http::get($url)->body();
            $contenidoHTML = new DOMDocument;
            libxml_use_internal_errors(true);
            $contenidoHTML->loadHTML($contenido);

            //Buscamos el desplegable de sorteos y leemos los options que tiene
            $optionsSelectSorteos = $contenidoHTML->getElementById("loteria_desplegable")
                ->getElementsByTagName("option");

            //Iteramos por los options (cada sorteo)
            for($i=0;$i<$optionsSelectSorteos->count()-1;$i++){
                //extraigo la cadena del nombre
                $cadenaSorteo = $optionsSelectSorteos->item($i)->textContent;

                //Separo la cadena con el nombre y fecha por espacios para así coger lo que quiera
                $cadenaSorteosSplit = explode(" ", $cadenaSorteo);

                //Extraigo la fecha
                $fechaux = Carbon::createFromFormat("d/m/Y", $cadenaSorteosSplit[count($cadenaSorteosSplit)-1]);
                $fechaSorteo = $fechaux->format("Y-m-d");

                //Quito el último elemento del array que es la fecha y quedarme así con el nombre del sorteo
                array_pop($cadenaSorteosSplit);
                $nombreSorteo = implode(" ", $cadenaSorteosSplit);

                //Extraigo el value del option que contiene el número de sorteo
                $valorOption = $optionsSelectSorteos->item($i)->attributes->getNamedItem("value")->nodeValue;

                //Extraigo el número de sorteo de la cadena; 2023102
                //Quito el año de delante y me quedo con el nº de sorteo
                $numSorteo = substr($valorOption, 4);

                //Guardo los datos en el array
                array_push($sorteosObtenidos, (object)["nombre" => $nombreSorteo, "fecha" => $fechaSorteo, "numero_sorteo" => $numSorteo]);

                //Si llegamos hasta aquí es porque ha salido bien la operación. Metemos losd atos en la respuesta y el codigo OK
                $response["data"] = $sorteosObtenidos;
                $response["code"] = 0;
            }
        }catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del dameFechaNombreYNumEsnifandoURL de helpers",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que devuelve un array de arrays con los resultados de la url pasada como parámetros
     *
     * @param string $url La url de la que esnifar
     *
     * @return [{fecha => [nombre, numero, premio]}]
     *  0: OK
     * -1: Excepción
     * -2:
     */
    private static function esnifaResultadosDisponiblesEnURL(string $url)
    {
        $response = [];
        $resultadosDisponibles = [];

        Log::debug("Entrando al esnifaResultadosDisponiblesEnURL de helpers",
            array(
                "request: " => $url
            )
        );

        try{
            $sorteosObtenidos = [];

            //Hacemos la petición y creamos el objeto DOM para movernos
            $contenido = Http::get($url)->body();
            $contenidoHTML = new DOMDocument;
            libxml_use_internal_errors(true);
            $contenidoHTML->loadHTML($contenido);

            //Buscamos el desplegable de sorteos y leemos los options que tiene
            $optionsSelectResultados = $contenidoHTML->getElementById("loteria_fecha_sel")
                ->getElementsByTagName("option");

            //Iteramos por los options (cada sorteo)
            for($i=0;$i<count($optionsSelectResultados);$i++){
                $premiosSorteoAux = [];

                //extraigo la cadena del nombre
                $fechaSorteo = $optionsSelectResultados->item($i)->textContent;
                $optionValue = $optionsSelectResultados->item($i)->attributes->getNamedItem("value")->textContent;

                //Ahora extraigo el dominio de la url que estoy consultando para montar una nueva petición
                $segmentosUrl = parse_url($url);
                $urlServicioResultados = "https://" . $segmentosUrl["host"] . "/mod_ultimos_resultados.php?idiomaQuery=es&fechaQuery=".$optionValue."&juegoQuery=loteria";

                //Con la fecha de sorteo y el value del option, peticiono los datos de la tabla de resultados
                $contenidoHTML = new DOMDocument;
                libxml_use_internal_errors(true);
                $contenidoHTML->loadHTML(Http::get($urlServicioResultados)->body());

                $tablaResultados = $contenidoHTML->getElementById("banner_r_loteria_premios");
                $arrayFilasResultados = $tablaResultados->getElementsByTagName("tr");

                //Ahora recorro la tabla para quedarme con cada resultado
                //Itero desde el 1 para saltarme la cabecera de la tabla
                for($j=1;$j<count($arrayFilasResultados);$j++){
                    $nombreAux = $arrayFilasResultados->item($j)->childNodes->item(1)->nodeValue;
                    $numeroAux = $arrayFilasResultados->item($j)->childNodes->item(3)->nodeValue;
                    $premioAux = $arrayFilasResultados->item($j)->childNodes->item(5)->nodeValue;

                    //trato la cadena de premio para quitar todos los caracteres no numericos
                    $premioAux = str_replace(".", "", $premioAux);
                    $premioAux = str_replace("€", "", $premioAux);
                    $premioAux = str_replace(" ", "", $premioAux);

                    //Trato la cadena de numeros recibidos
                    $numeroAux = str_replace("*", "", $numeroAux);
                    $numeroAux = str_replace(",", "&", $numeroAux);
                    $numeroAux = str_replace("-", "&", $numeroAux);
                    $numeroAux = str_replace(" ", "", $numeroAux);

                    //Meto los datos en el array de resultados
                    array_push($premiosSorteoAux, array("nombre" => $nombreAux, "numero" => $numeroAux, "premio" => $premioAux));
                }

                //Cuando tengo en el array todos los posibles premios del sorteo, lo guardo en el array general de todos los sorteos
                array_push($resultadosDisponibles, (object)["fecha" => $fechaSorteo, "premios" => $premiosSorteoAux]);
            }

            $response["data"] = $resultadosDisponibles;
            $response["code"] = 0;
        }catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del esnifaResultadosDisponiblesEnURL de helpers",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que devuelve un array de sorteos que no estén en la base de datos
     *
     * @param mixed $sorteosAInsertar Los sorteos potenciales a ser insertados en bd
     * @param string $sorteosExistentes Los sorteos que ya están en BD
     *
     * @return [{nombre, fecha, numero_sorteo}]
     *  0: OK
     * -1: Excepción
     */
    private static function dameArraySorteosNoExistentesEnBD($sorteosAInsertar, $sorteosExistentes)
    {
        $response = [];
        $sorteosEncontrados = [];

        Log::debug("Entrando al dameArraySorteosNoExistentesEnBD de helpers",
            array(
                "request: " => compact("sorteosAInsertar", "sorteosExistentes")
            )
        );

        try{
            //Busco por cada sorteo que tengo en bd, si está en el array a insertar
            foreach($sorteosExistentes as $sorteoExistente){
                $encontrado = array_filter($sorteosAInsertar, function($obj) use ($sorteoExistente){
                    if($obj->fecha == $sorteoExistente->fecha){
                        return true;
                    }else{
                        return false;
                    }
                });

                //Meto los sorteos encontrados en bd en un array para darle la vuelta a continuación
                $sorteosEncontrados = array_merge($encontrado, $sorteosEncontrados);
            }

            //Saco los elementos del sorteosdefinitivos que no están en el array de sorteosencontrados
            foreach($sorteosAInsertar as $key => $sorteoAInsertar){
                $encontrado = false;
                foreach($sorteosEncontrados as $sorteoEncontrado){
                    //Si se ha encontrado lo quito del array de sorteos a insertar
                    if($sorteoEncontrado->fecha == $sorteoAInsertar->fecha){
                        $encontrado = true;
                    }
                }

                if($encontrado){
                    unset($sorteosAInsertar[$key]);
                }
            }

            $response["data"] = $sorteosAInsertar;
            $response["code"] = 0;
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("sorteosAInsertar", "sorteosExistentes"),
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del dameArraySorteosNoExistentesEnBD de helpers",
            array(
                "request: " => compact("sorteosAInsertar", "sorteosExistentes"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Devuelve una lista de resultados a insertar que no estén en la lista de resultados existentes
     *
     * @param $resultadosAInsertar
     * @param $resultadosExistentes
     *
     * @return array Array con los resultados a insertar definitivo
     *  0: OK
     * -1: excepción
     */
    private static function dameArrayResultadosNoExistentesEnBD($resultadosAInsertar, $resultadosExistentes)
    {
        $response = [];
        $resultadosEncontrados = [];

        Log::debug("Entrando al dameArrayResultadosNoExistentesEnBD de helpers",
            array(
                "request: " => compact("resultadosAInsertar", "resultadosExistentes")
            )
        );
        try{
            //Busco por cada sorteo que tengo en bd, si está en el array a insertar
            foreach($resultadosExistentes as $resultadoExistente){
                $encontrado = array_filter($resultadosAInsertar, function($obj) use ($resultadoExistente){
                    $aux = explode(" ", $obj->fecha);
                    $aux = Carbon::createFromFormat("d/m/y", $aux[count($aux)-1])->format("Y-m-d");

                    if($aux == $resultadoExistente["fecha"]){
                        return true;
                    }
                });

                //Meto los sorteos encontrados en bd en un array para darle la vuelta a continuación
                $resultadosEncontrados = array_merge($resultadosEncontrados, $encontrado);
            }

            $response["data"] = $resultadosEncontrados;
            $response["code"] = 0;
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("resultadosAInsertar", "resultadosExistentes"),
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del dameArrayResultadosNoExistentesEnBD de helpers",
            array(
                "request: " => compact("resultadosAInsertar", "resultadosExistentes"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que esnifa el contenido de los resultados de las URLS y los almacena en BD
     *
     * @return void
     *  0: OK
     * -1: Excepción
     * -2: Las URLS no están configuradas en el env
     */
    public static function esnifarYGuardarResultadosDisponibles()
    {
        $response = [];

        Log::debug("Entrando al esnifarResultadosDisponibles de helpers");

        try{
            //Leemos las URLS desde las que esnifar resultados
            $resultURLS = self::dameURLSParaEsnifarResultados();

            if($resultURLS["code"] == 0){
                //Si están bien las urls paso a iterar sobre ellas y operar
                $urls = $resultURLS["data"];
                $arrayResultadosDisponibles = [];

                foreach($urls as $url){
                    //Me traigo un array de arrays con todos los premios de cada sorteo
                    $result = self::esnifaResultadosDisponiblesEnURL($url);

                    if($result["code"] == 0){
                        $datos = $result["data"];

                        array_push($arrayResultadosDisponibles, $datos);
                    }
                }

                //TODO: Saco el cruzamiento de datos de las distintas fuentes para mitigar errores
                $arrayResultadosDisponiblesFinal = $arrayResultadosDisponibles[0];

                //Saco un array de fechas obtenidas del array de resultados disponibles para consultar los existentes
                $arrayFechasResultadosDisponibles = array_column($arrayResultadosDisponiblesFinal, "fecha");

                foreach($arrayFechasResultadosDisponibles as $index=>$fechaux){
                    $aux = explode(" ", $fechaux);
                    $arrayFechasResultadosDisponibles[$index] = Carbon::createFromFormat("d/m/y", $aux[count($aux)-1])->format("Y-m-d");
                }

                //Me traigo las fechas existentes en BD que estén en el array de resultados obtenidos
                $arrayFechasResultadosExistentesEnBD = Sorteo::cruzaFechasSorteosExistentesSinResultadosEnBDConArrayFechas($arrayFechasResultadosDisponibles);

                if($arrayFechasResultadosExistentesEnBD["code"] == 0){
                    $arrayFechasResultadosExistentesEnBD = $arrayFechasResultadosExistentesEnBD["data"];
                    //Una vez tengo los resultados de cada url y los resultados existentes en bd, descarto los que ya tenga guardados en bd
                    $arrayResultadosDisponiblesDefinitivo = self::dameArrayResultadosNoExistentesEnBD($arrayResultadosDisponiblesFinal, $arrayFechasResultadosExistentesEnBD);

                    if($arrayResultadosDisponiblesDefinitivo["code"] == 0){
                        $arrayResultadosDisponiblesDefinitivo = $arrayResultadosDisponiblesDefinitivo["data"];

                        /*
                        //Ahora con el array definitivo, inserto los resultados en BD
                        foreach($arrayResultadosDisponiblesDefinitivo as $resultadoAux){
                            $resultInsercion = Sorteo::insertarNuevoResultadoDadoArrayDeResultados($resultadoAux);

                            if($resultInsercion["code"] == 0){
                                $response["code"] = 0;
                            }else{
                                $response["code"] = -5;

                                Log::error("Esto no debería fallar. Método para insertar un nuevo resultado",
                                    array(
                                        "response: " => $response
                                    )
                                );
                            }
                        } */

                        if(count($arrayResultadosDisponiblesDefinitivo) > 0){
                            //Enviar por mail la cadena de resultados, se meterán a mano por el momento
                            $user = User::find(1);
                            $user->notify(new NuevosResultadosDisponibles($arrayResultadosDisponiblesDefinitivo));
                        }

                        $response["code"] = 0;
                    }else{
                        //Fallo al sacar los elementos que difieren entre arrays
                        $response["code"] = -4;

                        Log::error("Esto no debería fallar si los arrays están bien formados.",
                            array(
                                "response: " => $response
                            )
                        );
                    }
                }else{
                    $response["code"] = -3;

                    Log::error("Error al leer las fechas existentes de resutlados en bd",
                        array(
                            "response: " => $response
                        )
                    );
                }
            }else{
                $response["code"] = -2;

                Log::error("Error al leer las urls de configuración",
                    array(
                        "response: " => $response
                    )
                );
            }
        }catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "response: " => $response
                )
            );
        }

        Log::debug("Saliendo del esnifarResultadosDisponibles de helpers",
            array(
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método que convierte la cadena que viene de cervantes a una cadena interna de sistema
     *
     * @param string $nombre nombre del premio
     *
     * @return string El nombre del premio traducido
     */
    public static function convertirNombrePremioANombreDeSistema(string $nombre)
    {
        switch($nombre)
        {
            case "Especial (1º+Ser.+Fracc.)":
                return "especial";
                case "1er. Premio":
                return "primero";
            case "2º Premio":
                return "segundo";
            case "4 últimas cifras":
                return "4cifras";
            case "3 últimas cifras":
                return "3cifras";
            case "2 últimas cifras":
                return "2cifras";
            case "Ant. y Post. al 1er P":
                return "aproximacionesprimero";
            case "Ant. y Post. al 2º P":
                return "aproximacionessegundo";
            case "A la centena del 1er P":
                return "centenaprimero";
            case "A la centena del 2º P":
                return "centenasegundo";
            case "Terminac. 3 cifras 1er P":
                return "3terminacionesprimero";
            case "Terminac. 2 cifras 1er P":
                return "2terminacionesprimero";
            case "Terminación 1er P":
                return "1terminacionprimero";
            case "Reintegros":
                return "reintegros";
            default:
                return "";
        }
    }
}
