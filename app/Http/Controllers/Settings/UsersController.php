<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Users\StoreRequest;
use App\Http\Requests\Settings\Users\UpdateRequest;
use App\Models\Settings\Roles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        return view('settings.users.list');
    }

    public function getData(Request $request)
    {
        $total_data = User::with('roles:id,name')->count();

        $limit = $request->length;
        $start = $request->start;

        if(is_null($request->search['value'])) {
            $query = User::with('roles:id,name')->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);
        } else {
            $search = $request->search['value'];
            $query = User::with('roles:id,name')
            ->where('username', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->offset($start)
            ->limit($limit);

            $total_data = User::with('roles:id,name')
            ->where('username', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orderBy('id', 'DESC')->count();
        }

        $data = array();
        if(count($query->get()) > 0) {
            $no = $start + 1;
            foreach($query->get() as $value) {
                $arr_data = array(
                    'no' => $no++,
                    'role' => $value['roles']['name'],
                    'username' => $value['username'],
                    'name' => $value['name'],
                    'status' => $value['is_active'] ? '<span class="badge bg-success text-light">Aktif</span>' : '<span class="badge bg-danger text-light">Tidak Aktif</span>',
                    'action' => '<a href="'.route('settings.users.detail', ['id' => $value->id]) .'"
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
        $role = Roles::get();
        return view('settings.users.create', compact('role'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $input = array(
                'name' => Str::upper($request->name),
                'username' => Str::lower(str_replace(' ', '_', $request->user)),
                'password' => Hash::make($request->pass),
                'role_id' => $request->role_id,
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id
            );
            User::create($input);

            return $this->successResponse('Data berhasil disimpan.', $input);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        $users = User::where('id', $id)->first();
        if(empty($users)) {
            return $this->errorPage(404, 'Data tidak ditemukan.');
        }
        $role = Roles::get();
        return view('settings.users.detail', compact('users','role'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $input = array(
                'name' => Str::upper($request->name),
                'role_id' => $request->role_id,
                'is_active' => $request->is_active,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::user()->id,
            );

            if($request->password != '') {
                $input['password'] = Hash::make($request->password);
            }

            User::where('id', $id)->update($input);

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
            User::where('id', $id)->update($data);

            return $this->successResponse('Data berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
