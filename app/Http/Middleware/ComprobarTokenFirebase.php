<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use function Psy\debug;

class ComprobarTokenFirebase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug("Entrando al handle del ComprobarTokenFirebase",
            array(
                "request: " => compact("request")
            )
        );

        if($request->header("firebasetoken") != null && auth()->user()){
            Log::debug("Viene token de firebase y hay usuario logueado",
                array(
                    "request: " => compact("request")
                )
            );

            if(!isset(auth()->user()->firebasetoken) ||
                auth()->user()->firebasetoken != $request->header("firebasetoken"))
            {
                Log::debug("Guardo el token de firebase porque el user no tiene o no coincide",
                    array(
                        "request: " => $request->all()
                    )
                );

                User::almacenarFirebaseToken($request->header("firebasetoken"), auth()->user()->id);
            }
        }

        Log::debug("Saliendo del handle del ComprobarTokenFirebase",
            array(
                "request: " => $request->all()
            )
        );

        return $next($request);
    }
}
