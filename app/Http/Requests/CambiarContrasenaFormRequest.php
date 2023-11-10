<?php

namespace App\Http\Requests;

use App\Rules\ContrasenaActualCorrectaRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CambiarContrasenaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Log de entrada para el validador de LoginRequest
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        Log::debug("Entrando a validación del CambiarContrasenaFormRequest",
            array(
                "request:" => $this->request->all()
            )
        );
    }

    /**
     * Función que se llama cuando la validación falla
     *
     * @param Validator $validator
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        Log::debug("Saliendo del validador de CambiarContrasenaFormRequest. Status: KO",
            array(
                "request:" => $this->request->all()
            )
        );

        parent::failedValidation($validator);
    }

    /**
     * Función que se llama cuando la validación pasa
     * @return void
     */
    protected function passedValidation()
    {
        Log::debug("Saliendo del validador de CambiarContrasenaFormRequest. Status: OK",
            array(
                "request:" => $this->request->all()
            )
        );

        parent::passedValidation();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "contrasenaActual" => ["required", new ContrasenaActualCorrectaRule()],
            "nuevaContrasena" => "required|confirmed"
        ];
    }

    public function messages()
    {
        return [
            "contrasenaActual.required" => "La contraseña actual no puede estar vacía",
            "contrasenaActual.ContrasenaActualCorrectaRule" => "La contraseña actual no es correcta",
            "nuevaContrasena.required" => "Debes especificar la contraseña nueva",
            "nuevaContrasena.confirmed" => "Las contraseñas no coinciden",
        ];
    }
}
