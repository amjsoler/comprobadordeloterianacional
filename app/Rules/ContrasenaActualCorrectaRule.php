<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContrasenaActualCorrectaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        Log::debug("Entrando al validate de la regla ContrasenaActualCorrectaRule");

        $userRequest = auth()->user();

        //Si no se ha encontrado el usuario...
        if(!Hash::check($value, $userRequest->password)){
            $fail("La contraseña actual no es correcta");
        }

        Log::debug("Saliendo del validate de la regla ContrasenaActualCorrectaRule");
    }
}
