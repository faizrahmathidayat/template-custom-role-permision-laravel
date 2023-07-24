<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Master\ProductCategory\StoreUpdateRequest;
use App\Models\User;
use App\Helpers\GlobalHelpers;
use App\Models\Master\ProductSubCategory;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('master.product_category.list');
    }

    public function getData(Request $request)
    {
        $total_data = ProductCategory::count();
        
        $limit = $request->length;
        $start = $request->start;

        if(is_null($request->search['value'])) {
            $query = ProductCategory::orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);
        } else {
            $search = $request->search['value'];
            $query = ProductCategory::where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);

            $total_data = ProductCategory::where('name', 'like', '%'.$search.'%')
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
                    'status' => $value['is_active'] ? '<span class="badge bg-success text-light">Aktif</span>' : '<span class="badge bg-danger text-light">Tidak Aktif</span>',
                    'action' => '<a href="'.route('master.product_category.detail', ['id' => $value->id]) .'"
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
        return view('master.product_category.create');
    }

    public function store(StoreUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $code = GlobalHelpers::generateCode('code_product_categories',date('Y'),date('m'));
            $input = array(
                'code' => $code,
                'name' => Str::upper($request->name),
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id
            );
            ProductCategory::create($input);

            DB::commit();
            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        $data = ProductCategory::where('id', $id)->first();
        
        if(empty($data)) {
            return $this->errorPage(404,'Data tidak ditemukan');
        }
        $subcategory = ProductSubCategory::where(['category_id'=>$data->id, 'is_active' => true])->count();
        return view('master.product_category.detail', compact('data','subcategory'));
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        try {
            $input = array(
                'name' => Str::upper($request->name),
                'is_active' => $request->is_active,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::user()->id,
            );
            
            ProductCategory::where('id', $id)->update($input);

            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
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
            ProductCategory::where('id', $id)->update($data);
            return $this->successResponse('Berhasil menghapus data');
        } catch(\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
