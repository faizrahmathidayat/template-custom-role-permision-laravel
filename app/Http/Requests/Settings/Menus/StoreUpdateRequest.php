<?php

namespace App\Http\Requests\Settings\Menus;

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
        $add_rule_parent_menu_id = !$this->is_parent ? 'required' : '';
        $add_rule_unique_on_update = isset($this->id) ? ','.$this->id : '';

        $rule_unique_url = $this->url != '#' ? '|unique:menus,url'.$add_rule_unique_on_update : '';

        $rules = array(
            'is_parent' => 'required|in:1,0',
            'parent_menu_id' => $add_rule_parent_menu_id,
            'title' => 'required|unique:menus,title'.$add_rule_unique_on_update,
            'url' => 'required'.$rule_unique_url,
            'position' => 'required|numeric',
            'role_id'    => 'required|array',
            'role_id.*'  => 'required|string',
        );
        return $rules;
    }

    public function messages()
    {
        $message =  array(
            'required' => ':attribute tidak boleh kosong.',
            'required.*' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute sudah digunakan.',
            'numeric' => ':attribute harus berupa angka.',
            'in' => ':atttribute hanya di perbolehkan :in',
            'array' => ':attribute harus berupa array.'
        );

        return $message;
    }

    public function attributes()
    {
        $attribute = array(
            'is_parent' => 'Jenis Menu',
            'parent_menu_id' => 'Parent Menu',
            'title' => 'Judul',
            'url' => 'Url',
            'position' => 'Posisi Menu',
            'role_id.*' => 'Peran'
        );

        return $attribute;
    }
}
