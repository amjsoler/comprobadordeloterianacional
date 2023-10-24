<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    public static function crearNuevoUsuario(string $name, string $email, string $password)
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
}
