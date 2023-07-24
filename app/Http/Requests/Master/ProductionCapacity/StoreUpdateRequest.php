<?php

namespace App\Http\Requests\Master\ProductionCapacity;

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
            'subcategory_id' => 'required|unique:production_capacities,subcategory_id'.$add_rule_unique_on_update,
            'qty' => 'required|min:1|numeric',
            'total_sdm' => 'required|min:1|numeric',
        );
    }

    public function messages()
    {
        return array(
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute sudah digunakan.',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih besar dari 0',
        );
    }

    public function attributes()
    {
        return array(
            'subcategory_id' => 'Kategori',
            'qty' => 'Qty',
            'total_sdm' => 'Jumlah SDM',
        );
    }
}
