<?php

namespace App\Http\Requests\Settings\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array(
            'name' => 'required',
            'role_id' => 'required',
            'user' => 'required|unique:users,username',
            'pass' => 'required|min:8'
        );
    }

    public function messages()
    {
        return array(
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute sudah digunakan.',
            'min' => ':attribute minimal :min huruf.'
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'Nama',
            'user' => 'Username',
            'pass' => 'Password'
        );
    }
}
