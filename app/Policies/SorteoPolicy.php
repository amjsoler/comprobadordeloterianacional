<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class SorteoPolicy
{
    public function delete(User $user)
    {
        if(env("ADMIN_AUTORIZADO")){
            if($user->email == env("ADMIN_AUTORIZADO")){
                return Response::allow();
            }else{
                return Response::deny();
            }
        }else{
            Log::error("La clave ADMIN_AUTORIZADO no está puesta en el archivo de configuración");
        }
    }
}
