<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ProductSubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Master\ProductSubCategory\StoreUpdateRequest;
use App\Models\User;
use App\Helpers\GlobalHelpers;
use App\Models\Master\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Libraries\JurnalLib;

class ProductSubCategoryController extends Controller
{
    protected $jurnalLib;
    public function __construct(JurnalLib $jurnalLib)
    {
        $this->product_subcategory = new ProductSubCategory;
        $this->jurnalLib = $jurnalLib;
    }
    public function index()
    {
        return view('master.product_subcategory.list');
    }

    public function getData(Request $request)
    {
        $total_data = ProductSubCategory::with('category')->count();
        
        $limit = $request->length;
        $start = $request->start;

        if(is_null($request->search['value'])) {
            $query = ProductSubCategory::with('category')->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);
        } else {
            $search = $request->search['value'];
            $query = ProductSubCategory::with('category')->where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);

            $total_data = ProductSubCategory::with('category')->where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')->count();
        }

        $data = array();
        if(count($query->get()) > 0) {
            $no = $start + 1;
            foreach($query->get() as $value) {
                $arr_data = array(
                    'no' => $no++,
                    'code' => $value['code'],
                    'name' => $value['name'],
                    'category' => $value['category']['name'],
                    'status' => $value['is_active'] ? '<span class="badge bg-success text-light">Aktif</span>' : '<span class="badge bg-danger text-light">Tidak Aktif</span>',
                    'action' => '<a href="'.route('master.product_subcategory.detail', ['id' => $value->id]) .'"
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

    public function create()
    {
        $category = ProductCategory::where('is_active', true)->get();
        return view('master.product_subcategory.create', compact('category'));
    }

    public function store(StoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {            
            $code = GlobalHelpers::generateCode('code_product_subcategories',date('Y'),date('m'));
            $input = array(
                'code' => $code,
                'category_id' => $request->category_id,
                'name' => Str::upper($request->name),
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id
            );

            $params_mapping = array(
                'table' => $this->product_subcategory->getTable(),
                'internal_id' => $code,
                'action' => 'insert'
            );
            
            ProductSubCategory::create($input);
            $this->jurnalLib->sendData($params_mapping);

            DB::commit();
            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        $data = ProductSubCategory::where('id', $id)->first();
        $category = ProductCategory::where('is_active', true)->get();
        if(empty($data)) {
            return $this->errorPage(404,'Data tidak ditemukan');
        }
        return view('master.product_subcategory.detail', compact('data','category'));
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product_sub_category = ProductSubCategory::find($id);
            if($product_sub_category) {
                $input = array(
                    'name' => Str::upper($request->name),
                    'category_id' => $request->category_id,
                    'is_active' => $request->is_active,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::user()->id,
                );
                
                $product_sub_category->update($input);
    
                $params_mapping = array(
                    'table' => $this->product_subcategory->getTable(),
                    'internal_id' => $product_sub_category->code,
                    'action' => 'update'
                );
                $this->jurnalLib->sendData($params_mapping);

                DB::commit();
    
                return $this->successResponse('Data berhasil disimpan.');
            }

            return $this->errorResponse('Data tidak ditemukan'); 
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $product_sub_category = ProductSubCategory::find($id);
            if($product_sub_category) {
                $product_sub_category->delete();
    
                $params_mapping = array(
                    'table' => $this->product_subcategory->getTable(),
                    'internal_id' => $product_sub_category->code,
                    'action' => 'delete'
                );
                $this->jurnalLib->sendData($params_mapping);
    
                return $this->successResponse('Berhasil menghapus data');
            }
            return $this->errorResponse('Data tidak ditemukan'); 
        } catch(\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
