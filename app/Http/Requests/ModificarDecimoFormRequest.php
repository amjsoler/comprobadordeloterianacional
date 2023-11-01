<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ModificarDecimoFormRequest extends FormRequest
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
        Log::debug("Entrando a validación del ModificarDecimoFormRequest",
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
        Log::debug("Saliendo del validador de ModificarDecimoFormRequest. Status: KO",
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
        Log::debug("Saliendo del validador de ModificarDecimoFormRequest. Status: OK",
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
            "numero" => "required|string|max:5",
            "reintegro" => "required|numeric",
            "serie" => "numeric",
            "fraccion" => "numeric",
            "cantidad" => "numeric",
            "sorteo" => "required|exists:sorteos,id"
        ];
    }

    public function messages()
    {
        return [
            "numero.required" => "El número no puede estar vacío",
            "numero.string" => "El número no tiene el formato correcto",
            "numero.max" => "El número debe tener 5 cifras",
            "reintegro.required" => "Debes especificar un reintegro",
            "reintegro.numeric" => "El reintegro no tiene un formato válido",
            "serie.numeric" => "La serie no tiene un formato válido",
            "fraccion.numeric" => "La fracción no tiene un formato válido",
            "cantidad.required" => "Debes especificar la cantidad de décimos que llevas de este número",
            "cantidad.numeric" => "La cantidad no tiene un formato válido",
            "sorteo.required" => "Debes especificar el sorteo al que pertenece este número",
            "sorteo.exists" => "El sorteo especificado no es válido",
        ];
    }
}
