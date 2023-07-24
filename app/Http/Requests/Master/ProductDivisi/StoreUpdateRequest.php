<?php

namespace App\Http\Requests\Master\ProductDivisi;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
        $add_rule_unique_on_update = isset($this->id) ? ','.$this->id : '';
        return array(
            'name' => 'required|unique:master_product_divisions,name'.$add_rule_unique_on_update
        );
    }

    public function messages()
    {
        return array(
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute sudah digunakan.'
        );
    }

    public function attributes()
    {
        return array(
            'name' => 'Nama'
        );
    }
}
