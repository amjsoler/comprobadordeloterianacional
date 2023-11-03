<?php

namespace App\Policies;

use App\Models\Decimo;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class DecimoPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Decimo $decimo): Response
    {
        $response = Response::deny();

        try {
            Log::debug(
                "Entrando al update del DecimoPolicy",
                array(
                    "user: " => $user,
                    "decimo: " => $decimo
                )
            );

            //Si el usuario es admin o propietario entonces sí puede editar
            if($decimo->usuario == $user->id){
                $response = Response::allow();

                Log::debug(
                    "Saliendo del update del DecimoPolicy: OK",
                    array(
                        "user: " => $user,
                        "decimo: " => $decimo,
                        "response: " => $response
                    )
                );
            }else{
                $response = Response::deny("Este décimo no es tuyo, no lo puedes modificar");

                Log::debug(
                    "Saliendo del update del DecimoPolicy: KO",
                    array(
                        "user: " => $user,
                        "decimo: " => $decimo,
                        "response: " => $response
                    )
                );
            }
        }catch(Exception $e){
            Log::error(
                $e->getMessage(),
                array(
                    "user: " => $user,
                    "decimo: " => $decimo,
                    "response: " => $response
                )
            );

            $response = Response::deny("Error al comprobar el permiso de edición del décimo");
        }

        return $response;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Decimo $decimo): Response
    {
        $response = Response::deny();

        try {
            Log::debug(
                "Entrando al delete del DecimoPolicy",
                array(
                    "user: " => $user,
                    "decimo: " => $decimo
                )
            );

            //Si el usuario es admin o propietario entonces sí puede editar
            if($decimo->usuario == $user->id){
                $response = Response::allow();

                Log::debug(
                    "Saliendo del delete del DecimoPolicy",
                    array(
                        "user: " => $user,
                        "decimo: " => $decimo,
                        "response: " => $response
                    )
                );
            }else{
                $response = Response::deny("Este décimo no es tuyo, no lo puedes eliminar");

                Log::debug(
                    "Saliendo del delete del DecimoPolicy",
                    array(
                        "user: " => $user,
                        "decimo: " => $decimo,
                        "response: " => $response
                    )
                );
            }
        }catch(Exception $e){
            Log::error(
                $e->getMessage(),
                array(
                    "user: " => $user,
                    "decimo: " => $decimo,
                    "response: " => $response
                )
            );

            $response = Response::deny("Error al comprobar el permiso de eliminación del décimo");
        }

        return $response;
    }
}
