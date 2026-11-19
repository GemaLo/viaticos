<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true ;
    }

   public function rules()
    {
        return [
            'cargo' => 'required',
            'sexo' => 'required',
            'nivel' => 'required',
            'rfc' => 'required',
            'curp' => 'required',
            'clabe' => 'required',
            'cuenta' => 'required',
            'banco' => 'required',
            'adscripcion' => 'required',
        ];
    }

    public function messages()
    {
        return[
            'cargo' => 'El :attribute es obligatorio',
            'sexo' => 'El :attribute es obligatorio',
            'nivel' => 'El :attribute es obligatorio',
            'rfc' => 'El :attribute es obligatorio',
            'curp' => 'El :attribute es obligatorio',
            'clabe' => 'La :attribute es obligatorio',
            'cuenta' => 'La :attribute es obligatorio',
            'banco' => 'El :attribute es obligatorio',
            'adscripcion' => 'La :attribute es obligatorio'
            ];
    }

    public function attributes()
    {
        return [
            'cargo' => 'Cargo',
            'sexo' => 'Sexo',
            'nivel' => 'Nivel',
            'rfc' => 'RFC',
            'curp' => 'CURP',
            'clabe' => 'CLABE',
            'cuenta' => 'Cuenta',
            'banco' => 'Banco'
        ];
    }
}
