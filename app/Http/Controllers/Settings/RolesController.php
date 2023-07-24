<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\Roles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Settings\Roles\StoreUpdateRequest;
use App\Models\User;

class RolesController extends Controller
{
    public function index()
    {
        return view('settings.roles.list');
    }

    public function getData(Request $request)
    {
        $total_data = Roles::count();
        
        $limit = $request->length;
        $start = $request->start;

        if(is_null($request->search['value'])) {
            $query = Roles::orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);
        } else {
            $search = $request->search['value'];
            $query = Roles::where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);

            $total_data = Roles::where('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')->count();
        }

        $data = array();
        if(count($query->get()) > 0) {
            $no = $start + 1;
            foreach($query->get() as $value) {
                $arr_data = array(
                    'no' => $no++,
                    'name' => $value['name'],
                    'status' => $value['is_active'] ? '<span class="badge bg-success text-light">Aktif</span>' : '<span class="badge bg-danger text-light">Tidak Aktif</span>',
                    'action' => '<a href="'.route('settings.roles.detail', ['id' => $value->id]) .'"
                    class="btn btn-sm btn-primary"><i class="fa fa-list"></i>&nbsp;Detail</a>'
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
        return view('settings.roles.create');
    }

    public function store(StoreUpdateRequest $request)
    {
        try {
            $input = array(
                'name' => Str::upper($request->name),
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id
            );
            Roles::create($input);

            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        $data = Roles::where('id', $id)->first();
        $count_user_roles = User::where(['role_id' => $id, 'is_active' => true])->count();
        if(empty($data)) {
            return $this->errorPage(404,'Data tidak ditemukan');
        }
        return view('settings.roles.detail', compact('data','count_user_roles'));
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
            
            Roles::where('id', $id)->update($input);

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
            Roles::where('id', $id)->update($data);
            return $this->successResponse('Berhasil menghapus data');
        } catch(\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
