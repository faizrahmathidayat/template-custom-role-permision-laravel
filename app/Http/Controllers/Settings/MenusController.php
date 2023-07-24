<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Libraries\PermissionsLib;
use App\Models\Permissions;
use App\Models\Settings\Menus;
use App\Models\Settings\Roles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\Settings\Menus\StoreUpdateRequest;

class MenusController extends Controller
{
    public function index()
    {
        $menu = Menus::with(['sub_menus' => function($q) {
            $q->orderBy('position', 'ASC');
        }])->where(['is_parent' => true])->orderBy('position', 'ASC')->get();
        return view('settings.menus.list', compact('menu'));
    }

    public function create()
    {
        $parent = Menus::where([
            'is_parent' => true,
            'is_active' => true
        ])->get();
        return view('settings.menus.create', compact('parent'));
    }

    public function store(StoreUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $role_id = $request->has('role_id') ? $request->role_id : [];
            $view_access = $request->has('view_access') ? $request->view_access : [];
            $add_access = $request->has('add_access') ? $request->add_access : [];
            $edit_access = $request->has('edit_access') ? $request->edit_access : [];
            $delete_access = $request->has('delete_access') ? $request->delete_access : [];
            $input = array(
                'title' => ucwords($request->title),
                'url' => Str::lower($request->url),
                'url_group' => Str::lower($request->url_group),
                'icon' => $request->icon,
                'position' => $request->position,
                'is_parent' => $request->is_parent,
                'parent_menu_id' => $request->has('parent_menu_id') ? $request->parent_menu_id : null,
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id
            );
            $menu = Menus::create($input);
            PermissionsLib::assignRoleToMenuAccess($menu->id, $role_id, $view_access, $add_access, $edit_access, $delete_access);

            DB::commit();

            return $this->successResponse('Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $menu = Menus::where('id', $id)->first();
            $permission = Permissions::where('menu_id', $id)->get();
            $role = Roles::where('is_active', true)->get();

            if(empty($menu)) {
                return $this->errorPage(400,'Data tidak ditemukan');
            }

            $parent = Menus::where([
                'is_parent' => true,
                'is_active' => true,
            ])->where('id', '<>' , $menu->id)->get();

            return view('settings.menus.detail', compact('menu','parent', 'permission', 'role'));
        } catch(\Throwable $e) {
            return $this->errorPage(500, $e->getMessage());
        }
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $role_id = $request->has('role_id') ? $request->role_id : [];
            $view_access = $request->has('view_access') ? $request->view_access : [];
            $add_access = $request->has('add_access') ? $request->add_access : [];
            $edit_access = $request->has('edit_access') ? $request->edit_access : [];
            $delete_access = $request->has('delete_access') ? $request->delete_access : [];

            $input = array(
                'title' => ucwords($request->title),
                'url' => Str::lower($request->url),
                'url_group' => Str::lower($request->url_group),
                'icon' => $request->icon,
                'position' => $request->position,
                'is_parent' => $request->is_parent,
                'parent_menu_id' => $request->has('parent_menu_id') ? $request->parent_menu_id : null,
                'is_active' => $request->is_active,
                'updated_by' => Auth::user()->id
            );
            Menus::where('id', $id)->update($input);
            PermissionsLib::destoryRoleMenuAccess($id);
            PermissionsLib::assignRoleToMenuAccess($id, $role_id, $view_access, $add_access, $edit_access, $delete_access, 'update');

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
            DB::beginTransaction();
            $data = array(
                'is_active' => false,
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            );
            Menus::where('id', $id)->update($data);
            Permissions::where('menu_id', $id)->update($data);
            DB::commit();
            return $this->successResponse('Berhasil menghapus data');
        } catch(\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function listRoles(Request $request)
    {
        $data = Roles::where('is_active', true)->get();
        return response()->json($data);
    }

    public function checkExistUrl(Request $request)
    {
        $url = $request->url;
        $original_url = $request->original_url;
        $data = Menus::selectRaw('COUNT(*) AS TotalUrl')->where('url', $url);

        if(!is_null($original_url)) {
            $data->where('url','<>', $original_url);
        }

        if($url != '#') {
            if ((int)$data->first()->TotalUrl > 0) {
                return "false";
            }
        }
        return "true";
    }
}
