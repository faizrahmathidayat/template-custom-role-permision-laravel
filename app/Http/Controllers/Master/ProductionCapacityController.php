<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ProductionCapacity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Master\ProductionCapacity\StoreUpdateRequest;
use App\Helpers\GlobalHelpers;
use Illuminate\Support\Facades\DB;

class ProductionCapacityController extends Controller
{
    public function index()
    {
        return view('master.production_capacity.list');
    }

    public function getData(Request $request)
    {
        $total_data = ProductionCapacity::count();
        
        $limit = $request->length;
        $start = $request->start;

        if(is_null($request->search['value'])) {
            $query = ProductionCapacity::with('subcategory:id,code,name')->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);
        } else {
            $search = $request->search['value'];
            $query = ProductionCapacity::with('subcategory:id,code,name')->where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);

            $total_data = ProductionCapacity::with('subcategory:id,code,name')->where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')->count();
        }

        $data = array();
        if(count($query->get()) > 0) {
            $no = $start + 1;
            foreach($query->get() as $value) {
                $arr_data = array(
                    'no' => $no++,
                    'subcategory' => $value['subcategory'] ? $value['subcategory']['name'].' ('.$value['subcategory']['code'].')' : '-',
                    'qty' => $value['qty'],
                    'total_sdm' => $value['total_sdm'],
                    'status' => $value['is_active'] ? '<span class="badge bg-success text-light">Aktif</span>' : '<span class="badge bg-danger text-light">Tidak Aktif</span>',
                    'action' => '<a href="'.route('master.production_capacity.detail', ['id' => $value['id']]) .'"
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
        $subcategory = GlobalHelpers::getProductSubCategory();
        return view('master.production_capacity.create',compact('subcategory'));
    }

    public function store(StoreUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = array(
                'subcategory_id' => $request->subcategory_id,
                'qty' => $request->qty,
                'total_sdm' => $request->total_sdm,
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id
            );
            ProductionCapacity::create($input);

            DB::commit();
            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        $subcategory = GlobalHelpers::getProductSubCategory();
        $data = ProductionCapacity::where('id', $id)->first();
        if(empty($data)) {
            return $this->errorPage(404,'Data tidak ditemukan');
        }
        return view('master.production_capacity.detail', compact('data','subcategory'));
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $production_capacity = ProductionCapacity::find($id);
            if($production_capacity) {
                $input = array(
                    'subcategory_id' => $request->subcategory_id,
                    'qty' => $request->qty,
                    'total_sdm' => $request->total_sdm,
                    'is_active' => $request->is_active,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::user()->id,
                );
                
                $production_capacity->update($input);
                
                DB::commit();
                return $this->successResponse('Data berhasil disimpan.');
            }
            return $this->errorPage(404,'Data tidak ditemukan');            
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
            ProductionCapacity::where('id', $id)->update($data);
            return $this->successResponse('Berhasil menghapus data');
        } catch(\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
