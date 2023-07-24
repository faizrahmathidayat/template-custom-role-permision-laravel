<?php

namespace App\Http\Requests\Settings\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $add_rule_password = '';
        if($this->password != '') {
            $add_rule_password = 'min:8';
        }
        return array(
            'name' => 'required',
            'role_id' => 'required',
            'password' => $add_rule_password
        );
    }

    public function messages()
    {
        return array(
            'required' => ':attribute tidak boleh kosong.',
            'min' => ':attribute minimal :min huruf.'
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'Nama',
            'role_id' => 'Peran',
            'password' => 'Password'
        );
    }
}
