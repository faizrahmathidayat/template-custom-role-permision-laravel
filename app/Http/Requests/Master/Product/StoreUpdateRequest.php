<?php

namespace App\Http\Requests\Master\Product;

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
            'code' => 'required|unique:master_products,code'.$add_rule_unique_on_update,
            'name' => 'required|unique:master_products,name'.$add_rule_unique_on_update,
            'product_type_id' => 'required',
            'product_brand_id' => 'required',
            'product_divisi_id' => 'required',
            'product_category_id' => 'required',
            'product_subcategory_id' => 'required',
            'price' => 'nullable|numeric',
            'is_point' => 'required',
            'commision_type' => 'required|in:NOMINAL,PERCENTAGE',
            'stock' => 'nullable|numeric',
            'unit_commision' => 'nullable|numeric',
            'stock_unit_name' => 'required',
            'small_unit' => 'required',
            'unit_conversion' => 'nullable|numeric',
            'puchase_lead_time' => 'nullable|numeric',
            'minimal_order' => 'nullable|numeric',
            'product_age_standard' => 'nullable|numeric',
            'level_stock' => 'nullable|in:MINIMUM,MEDIUM,MAXIMUM',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'is_active' => 'required',
        );
    }

    public function messages()
    {
        return array(
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah digunakan',
            'max' => 'Ukuran :attribute tidak boleh lebih besar dari 4MB',
            'numeric' => ':attribute harus berupa angka'
        );
    }

    public function attributes()
    {
        return array(
            'code' => 'Kode Produk',
            'name' => 'Nama Produk',
            'product_type_id' => 'Jenis',
            'product_brand_id' => 'Brand',
            'product_divisi_id' => 'Dvisi',
            'product_category_id' => 'Kategori',
            'product_subcategory_id' => 'Sub Kategori',
            'price' => 'Harga',
            'is_point' => 'Hitung Komisi',
            'commision_type' => 'Jenis Komisi',
            'stock' => 'Stock',
            'unit_commision' => 'Komisi Satuan',
            'stock_unit_name' => 'Satuan Stock',
            'small_unit' => 'Satuan Kecil',
            'unit_conversion' => 'Konversi Satuan',
            'puchase_lead_time' => 'Lead Time Pembelian',
            'minimal_order' => 'Minimal Order',
            'product_age_standard' => 'Standar Usia Produk',
            'level_stock' => 'Stock Level',
            'image' => 'Gambar',
            'is_active' => 'Status',
        );
    }
}
