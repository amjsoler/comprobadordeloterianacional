<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AjustesCuentaFormRequest extends FormRequest
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
        Log::debug("Entrando a validación del AjustesCuentaFormRequest",
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
        Log::debug("Saliendo del validador de AjustesCuentaFormRequest. Status: KO",
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
        Log::debug("Saliendo del validador de AjustesCuentaFormRequest. Status: OK",
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
            "alertasporcorreo" => "required|boolean",
            "alertaspornotificacion" => "required|boolean"
        ];
    }

    public function messages()
    {
        return [
            "alertasporcorreo.required" => "Debes especificar el campo alertas por correo"  ,
            "alertasporcorreo.boolean" => "El campo alertas por correo tiene un formato no valido",
            "alertaspornotificacion.required" => "El campo alertas por correo tiene un formato no valido",
            "alertaspornotificacion.boolean" => "El campo alertas por correo tiene un formato no valido",
        ];
    }
}
