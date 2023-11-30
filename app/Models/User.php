<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //////////////////////
    ///// RELACIONES /////
    //////////////////////

    /**
     * Devuelve los décimos de un usuario
     *
     * @return HasMany
     */
    public function decimos() : HasMany
    {
        return $this->hasMany(Decimo::class, "usuario", "id");
    }

    /**
     * Un usuario tiene un token de verificación de cuenta
     *
     * @return HasOne
     */
    public function accountVerifyToken() : HasOne
    {
        return $this->hasOne(AccountVerifyToken::class, "usuario", "id");
    }

    /**
     * Un usuario tiene un token de recuperación de cuenta
     *
     * @return HasOne
     */
    public function recuperarCuentaToken() : HasOne
    {
        return $this->hasOne(RecuperarCuentaToken::class, "usuario", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

    /**
     * Función que devuelve un usuario dado el correo o nada si no existe
     *
     * @param string $correo El correo a buscar
     *
     * @return User
     *  0: OK
     * -1: Excepción
     * -2: No se ha encontrado el usuario
     */
    public static function dameUsuarioDadoCorreo(string $correo)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al dameUsuarioDadoCorreo de User",
                array(
                    "request: " => compact("correo")
                )
            );

            //Acción
            $usuario = User::where("email", $correo)->first();

            if($usuario){
                $response["code"] = 0;
                $response["data"] = $usuario;
            }
            else{
                $response["code"] = -2;
            }

            //Log de salida
            Log::debug("Saliendo del dameUsuarioDadoCorreo de User",
                array(
                    "request: " => compact("correo"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("correo"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }

    /**
     * Método usado para crear nuevos usuarios
     *
     * @param string $name El nombre del usuario
     * @param string $email El correo del usuario
     * @param string $password La contraseña del usuario
     *
     * @return User El usuario recién creado
     *  0: OK
     * -1: Excepción
     * -2: Error al guardar el usuario
     */
    public static function crearNuevoUsuario(
        string $name,
        string $email,
        string $password)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al crearNuevoUsuario de User",
                array(
                    "request: " => compact("name", "email", "password")
                )
            );

            //Acción
            $nuevoUsuario = new User();
            $nuevoUsuario->name = $name;
            $nuevoUsuario->email = $email;
            $nuevoUsuario->password = $password;

            if($nuevoUsuario->save()){
                $response["code"] = 0;
                $response["data"] = $nuevoUsuario;
            }
            else{
                $response["code"] = -2;

                Log::error("Esto no debería fallar, los campos ya vienen validados. No debería haber fallo de guardado",
                    array(
                        "request: " => compact("name", "email", "password")
                    )
                );
            }

            //Log de salida
            Log::debug("Saliendo del crearNuevoUsuario de User",
                array(
                    "request: " => compact("name", "email", "password"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("name", "email", "password"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }

    /**
     * Método para marcar una cuenta de usuario como verificada
     *
     * @param int $userID El id del usuario a verificar
     *
     * @return null
     *  0: OK
     * -1: Excepción
     * -2: El id de usuario no existe
     * -3: Error al guardar el usuario
     *
     */
    public static function marcarCuentaVerificada(int $userID)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al marcarCuentaVerificada de User",
                array(
                    "request: " => compact("userID")
                )
            );

            //Acción
            $result = User::find($userID);

            if($result){
                $result->email_verified_at = now();

                if($result->save()){
                    $response["code"] = 0;
                }
                else{
                    $response["code"] = -3;
                }
            }else{
                $response["code"] = -2;
            }


            //Log de salida
            Log::debug("Saliendo del marcarCuentaVerificada de User",
                array(
                    "request: " => compact("userID"),
                    "response: " => $response
                )
            );
        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("userID"),
                    "response: " => $response
                )
            );
        }

        return $response;
    }

    /**
     * Método para modificar la contraseña de un usuario
     *
     * @param int $usuarioID El identificador de usuario
     * @param string $password La nueva contraseña
     *
     * @return void
     *  0: OK
     * -1: Excepción
     * -2: No se ha encontrado el id de usuario
     * -3: No se ha podido guardar los datos
     */
    public static function guardarNuevoPass(
        int $usuarioID,
        string $password)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al guardarNuevoPass de User",
                array(
                    "request: " => compact("usuarioID", "password")
                )
            );

            //Acción
            $result = User::find($usuarioID);

            if($result){
                $result->password = $password;

                if($result->save()){
                    $response["code"] = 0;
                }
                else{
                    $response["code"] = -3;
                }
            }else{
                $response["code"] = -2;
            }


        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("usuarioID", "password"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del guardarNuevoPass de User",
            array(
                "request: " => compact("usuarioID", "password"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método para almacenar el token de firebase al usuario pasado como parámetro
     *
     * @param string $token Token a almacenar
     * @param int $userId Usuario al que asociar el token
     *
     * @return void
     *  0: OK
     * -1: Excepción
     * -2: Usuario no encontrado
     * -3: Error al guardar los datos
     */
    public static function almacenarFirebaseToken(string $token, int $userId)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al almacenarFirebaseToken de User",
                array(
                    "request: " => compact("token", "userId")
                )
            );

            //Acción
            $result = User::find($userId);

            if($result){
                $result->firebasetoken = $token;

                if($result->save()){
                    $response["code"] = 0;
                }
                else{
                    $response["code"] = -3;
                }
            }else{
                $response["code"] = -2;
            }


        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("token", "userId"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del almacenarFirebaseToken de User",
            array(
                "request: " => compact("token", "userId"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método para guardar los ajustes de la cuenta de usuaruio
     *
     * @param int $userId El id del usuario sobre el que guardar los ajustes
     * @param boolean $alertaPorCorreo Si se envían alertas por correo o no
     * @param boolean $alertaPorNotificacion Si se envían alertas por notificación o no
     *
     * @return void
     *  0: OK
     * -1: Excepción
     * -2: No se ha encontrado al usuario
     * -3: Error al guardar los datos
     */
    public static function guardarAjustesCuentaUsuario(
        int $userId,
        bool $alertasPorCorreo,
        bool $alertasPorNotificacion)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al guardarAjustesCuentaUsuario de User",
                array(
                    "request: " => compact("userId", "alertasPorCorreo", "alertasPorNotificacion")
                )
            );

            //Acción
            $result = User::find($userId);

            if($result){
                $result->alertasporcorreo = $alertasPorCorreo;
                $result->alertaspornotificacion = $alertasPorNotificacion;

                if($result->save()){
                    $response["code"] = 0;
                }
                else{
                    $response["code"] = -3;
                }
            }else{
                $response["code"] = -2;
            }


        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("userId", "alertasPorCorreo", "alertasPorNotificacion"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del guardarAjustesCuentaUsuario de User",
            array(
                "request: " => compact("userId", "alertasPorCorreo", "alertasPorNotificacion"),
                "response: " => $response
            )
        );

        return $response;
    }

    /**
     * Método para leer los ajustes de la cuenta de usuario
     *
     * @param int $userId La cuenta del usuario
     *
     * @return User(campos limitados)
     *  0: OK
     * -1: Excepción
     * -2: No se ha encontrado el usuario
     */
    public static function leerAjustesCuentaUsuario(int $userId)
    {
        $response = [
            "code" => "",
            "data" => ""
        ];

        try{
            //Log de entrada
            Log::debug("Entrando al leerAjustesCuentaUsuario de User",
                array(
                    "request: " => compact("userId")
                )
            );

            //Acción
            $result = User::find($userId)->get(["alertasporcorreo", "alertaspornotificacion"])->first();

            if($result){
                $response["code"] = 0;
                $response["data"] = $result;
            }else{
                $response["code"] = -2;
            }


        }
        catch(Exception $e){
            $response["code"] = -1;

            Log::error($e->getMessage(),
                array(
                    "request: " => compact("userId"),
                    "response: " => $response
                )
            );
        }

        //Log de salida
        Log::debug("Saliendo del leerAjustesCuentaUsuario de User",
            array(
                "request: " => compact("userId"),
                "response: " => $response
            )
        );

        return $response;
    }
}
