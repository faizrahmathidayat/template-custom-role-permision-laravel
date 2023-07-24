<?php

namespace App\Http\Controllers\Master;

use App\Helpers\GlobalHelpers;
use App\Http\Controllers\Controller;
use App\Models\Master\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Master\Product\StoreUpdateRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return view('master.product.list');
    }

    public function getData(Request $request)
    {
        $total_data = Product::with('product_category:id,name','product_subcategory:id,name','product_brand:id,name','stock_unit:id,name','product_divisi:id,name')->count();

        $limit = $request->length;
        $start = $request->start;
        
        if(is_null($request->search['value'])) {
            $query = Product::orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);
        } else {
            $search = $request->search['value'];
            $query = Product::with('product_category:id,name','product_subcategory:id,name','product_brand:id,name','stock_unit:id,name','product_divisi:id,name')->where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);

            $total_data = Product::with('product_category:id,name','product_subcategory:id,name','product_brand:id,name','stock_unit:id,name','product_divisi:id,name')->count();
        }

        $data = array();
        if(count($query->get()) > 0) {
            $no = $start + 1;
            foreach($query->get() as $value) {
                $arr_data = array(
                    'no' => $no++,
                    'divisi' => $value['product_divisi']['name'],
                    'category' => $value['product_category']['name'],
                    'subcategory' => $value['product_subcategory']['name'],
                    'brand' => $value['product_brand']['name'],
                    'code' => $value['code'],
                    'name' => $value['name'],
                    'unit' => $value['stock_unit']['name'],
                    'price' => $value['price'],
                    'status' => $value['is_active'] ? '<span class="badge bg-success text-light">Aktif</span>' : '<span class="badge bg-danger text-light">Tidak Aktif</span>',
                    'action' => '<a href="'.route('master.product.detail', ['id' => $value->id]) .'"
                    class="btn btn-sm btn-info"><i class="fa fa-list"></i>&nbsp;Detail</a>'
                );
                array_push($data, $arr_data);
            }
        }

        $json_data = array(
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($total_data),
            "recordsFiltered" => intval($total_data),
            "data"            => $data
        );
        
        return response()->json($json_data);
    }

    public function listSubcategory($category_id)
    {
        try {
            if(!is_null($category_id)) {
                $subcategory = GlobalHelpers::getProductSubCategory(null,$category_id);
            } else {
                $subcategory = [];
            }            

            return $this->successResponse('Success', $subcategory);
        } catch(\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function create()
    {
        $type = GlobalHelpers::getProductType();
        $brand = GlobalHelpers::getProductBrand();
        $divisi = GlobalHelpers::getProductDivisi();
        $category = GlobalHelpers::getProductCategory();
        $stock_unit = GlobalHelpers::getStockUnit();
        $master = array(
            'type' => $type,
            'brand' => $brand,
            'divisi' => $divisi,
            'category' => $category,
            'stock_unit' => $stock_unit
        );
        
        return view('master.product.create', compact('master'));
    }

    public function store(StoreUpdateRequest $request)
    {
        try {
            $code = $request->code;
            $name = $request->name;
            $product_type_id = $request->product_type_id;
            $product_brand_id = $request->product_brand_id;
            $product_divisi_id = $request->product_divisi_id;
            $product_category_id = $request->product_category_id;
            $product_subcategory_id = $request->product_subcategory_id;
            $price = $request->price;
            $is_point = $request->is_point;
            $commision_type = $request->commision_type;
            $stock = $request->stock;
            $unit_commision = $request->unit_commision;
            $stock_unit_name = $request->stock_unit_name;
            $small_unit = $request->small_unit;
            $unit_conversion = $request->unit_conversion;
            $purchase_lead_time = $request->purchase_lead_time;
            $minimal_order = $request->minimal_order;
            $product_age_standard = $request->product_age_standard;
            $level_stock = $request->level_stock;
            $is_active = $request->is_active;
            $upload_product = null;
            if($request->has('image')) {
                $product = $request->file('image');
                $filename = 'product_'.Str::random(16);
                $upload_product = GlobalHelpers::UploadFile($product, 'product/'.md5($code), $filename);
            }
            
            DB::beginTransaction();
            $input = array(
                'code' => Str::upper($code),
                'name' => Str::upper($name),
                'product_type_id' => $product_type_id,
                'product_brand_id' => $product_brand_id,
                'product_divisi_id' => $product_divisi_id,
                'product_category_id' => $product_category_id,
                'product_subcategory_id' => $product_subcategory_id,
                'price' => $price,
                'is_point' => $is_point,
                'commision_type' => $commision_type,
                'stock' => $stock,
                'unit_commision' => $unit_commision,
                'stock_unit_name' => $stock_unit_name,
                'small_unit' => $small_unit,
                'unit_conversion' => $unit_conversion,
                'purchase_lead_time' => $purchase_lead_time,
                'minimal_order' => $minimal_order,
                'product_age_standard' => $product_age_standard,
                'level_stock' => $level_stock,
                'level_stock' => $level_stock,
                'image' => $upload_product,
                'is_active' => $is_active,
                'created_by' => Auth::user()->id
            );
            Product::create($input);

            DB::commit();
            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        $type = GlobalHelpers::getProductType();
        $brand = GlobalHelpers::getProductBrand();
        $divisi = GlobalHelpers::getProductDivisi();
        $category = GlobalHelpers::getProductCategory();
        $subcategory = GlobalHelpers::getProductSubCategory();
        $stock_unit = GlobalHelpers::getStockUnit();
        $master = array(
            'type' => $type,
            'brand' => $brand,
            'divisi' => $divisi,
            'category' => $category,
            'subcategory' => $subcategory,
            'stock_unit' => $stock_unit
        );
        $data = Product::where('id', $id)->first();

        if(empty($data)) {
            return $this->errorPage(404,'Data tidak ditemukan');
        }

        return view('master.product.detail', compact('data','master'));
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            if(!$product) {
                return $this->errorResponse('Produk tidak ditemukan.',404);
            }
            $name = $request->name;
            $product_type_id = $request->product_type_id;
            $product_brand_id = $request->product_brand_id;
            $product_divisi_id = $request->product_divisi_id;
            $product_category_id = $request->product_category_id;
            $product_subcategory_id = $request->product_subcategory_id;
            $price = $request->price;
            $is_point = $request->is_point;
            $commision_type = $request->commision_type;
            $stock = $request->stock;
            $unit_commision = $request->unit_commision;
            $stock_unit_name = $request->stock_unit_name;
            $small_unit = $request->small_unit;
            $unit_conversion = $request->unit_conversion;
            $purchase_lead_time = $request->purchase_lead_time;
            $minimal_order = $request->minimal_order;
            $product_age_standard = $request->product_age_standard;
            $level_stock = $request->level_stock;
            $is_active = $request->is_active;

            $input = array(
                'name' => Str::upper($name),
                'product_type_id' => $product_type_id,
                'product_brand_id' => $product_brand_id,
                'product_divisi_id' => $product_divisi_id,
                'product_category_id' => $product_category_id,
                'product_subcategory_id' => $product_subcategory_id,
                'price' => $price,
                'is_point' => $is_point,
                'commision_type' => $commision_type,
                'stock' => $stock,
                'unit_commision' => $unit_commision,
                'stock_unit_name' => $stock_unit_name,
                'small_unit' => $small_unit,
                'unit_conversion' => $unit_conversion,
                'purchase_lead_time' => $purchase_lead_time,
                'minimal_order' => $minimal_order,
                'product_age_standard' => $product_age_standard,
                'level_stock' => $level_stock,
                'is_active' => $is_active,
                'created_by' => Auth::user()->id
            );

            if($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'product_'.Str::random(16);
                $upload_product = GlobalHelpers::UploadFile($image, 'product/'.md5($product->code), $filename);
                $input['image'] = $upload_product;
                GlobalHelpers::deleteFile($product->image);
            }

            $product->update($input);

            DB::commit();
            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $data = array(
                'is_active' => false,
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            );
            Product::where('id', $id)->update($data);
            return $this->successResponse('Berhasil menghapus data');
        } catch(\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
