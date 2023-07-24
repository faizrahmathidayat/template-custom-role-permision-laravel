@extends('settings.menus.index')
@section('content-menus')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Detail Menu</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form>
                        <input type="hidden" name="id" id="id" value="{{ $menu->id }}">
                        <input type="hidden" name="original_url" id="original_url" value="{{ $menu->url }}">
                        <fieldset class="row mb-3 mt-3 input-group">
                            <legend class="col-form-label col-sm-2 pt-0">Jenis</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_parent" type="radio" name="is_parent" id="menu"
                                        value="1" {{ $menu->is_parent ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Menu Utama
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_parent" type="radio" name="is_parent" id="sub_menu"
                                        value="0" {{ !$menu->is_parent ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Sub Menu
                                    </label>
                                </div>
                                <div class="invalid-feedback">Silakan pilih jenis menu.</div>
                            </div>
                        </fieldset>
                        <div class="row mb-3 input-group has-parent-menu" {{ $menu->is_parent ? 'hidden' : '' }}>
                            <label class="col-sm-2 col-form-label">Parent Menu</label>
                            <div class="col-sm-10">
                                <select class="form-select select2-custom" {{ $menu->is_parent ? 'disabled' : '' }} required
                                    name="parent_menu_id" id="parent_menu_id">
                                    <option value="">Pilih Parent Menu</option>
                                    @foreach ($parent as $parents)
                                        <option value="{{ $parents['id'] }}"
                                            {{ old('parent_menu_id') == $parents['id'] || $parents['id'] == $menu->parent_menu_id ? 'selected' : '' }}>
                                            {{ $parents['title'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Silakan pilih parent menu.</div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" required name="title" id="title" class="form-control"
                                    value="{{ old('title') ? old('title') : $menu->title }}" placeholder="Masukan judul">
                                <div class="invalid-feedback">Judul tidak boleh kosong.</div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Url</label>
                            <div class="col-sm-10">
                                <input type="text" required name="url" id="url" class="form-control"
                                    placeholder="Masukan Url" value="{{ $menu->url }}">
                                <div class="invalid-feedback">Url tidak boleh kosong.</div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Posisi Menu</label>
                            <div class="col-sm-10">
                                <input type="number" required name="position" id="position" class="form-control"
                                    placeholder="Masukan Posisi" value="{{ $menu->position }}">
                                <div class="invalid-feedback">Url tidak boleh kosong.</div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Url Group</label>
                            <div class="col-sm-10">
                                <input type="text" name="url_group" id="url_group" class="form-control"
                                    placeholder="Masukan Url Group"
                                    value="{{ old('url_group') ? old('url_group') : $menu->url_group }}">
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Icon</label>
                            <div class="col-sm-10">
                                <input type="text" name="icon" id="icon" class="form-control"
                                    placeholder="Contoh : bi bi-grid"
                                    value="{{ old('icon') ? old('icon') : $menu->icon }}">
                                <small>Sumber Icon : <a href="https://fontawesome.com/" target="_blank">Font
                                        Awesome</a></small>
                            </div>
                        </div>
                        <fieldset class="row mb-3 input-group">
                            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active" id="active"
                                        value="1" {{ $menu->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active"
                                        id="not_active" value="0" {{ !$menu->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Tidak Aktif
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-12">
                            <div class="card overflow-auto">
                                <div class="card-body">
                                    <h5 class="card-title">Users Access</h5>
                                    <table id="tbl_users_access" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Role</th>
                                                <th scope="col" class="text-center">View</th>
                                                <th scope="col" class="text-center">Add</th>
                                                <th scope="col" class="text-center">Edit</th>
                                                <th scope="col" class="text-center">Delete</th>
                                                <th scope="col" class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="detail">
                                            <?php $no = 0; ?>
                                            @foreach ($permission as $permissions)
                                                <?php $no++; ?>
                                                <tr id="row_{{ $no }}">
                                                    <td class="form-group text-center">
                                                        <select class="form-control select2-custom"
                                                            id="role_id_{{ $no }}" name="role_id[]"required>
                                                            <option value="">Pilih Role</option>
                                                            @foreach ($role as $roles)
                                                                <option value="{{ $roles['id'] }}"
                                                                    {{ $roles['id'] == $permissions['role_id'] ? 'selected' : '' }}>
                                                                    {{ $roles['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class='invalid-feedback'
                                                            id="invalid_feedback_role_{{ $no }}">Peran tidak
                                                            boleh kosong.</div>
                                                    </td>
                                                    <td class="form-group text-center">
                                                        <input class="form-check-input" type="checkbox"
                                                            data-initial="view_access_{{ $no }}"
                                                            id="view_access_checked_{{ $no }}"
                                                            onclick="isCheckedAccess(this);"
                                                            {{ $permissions['view'] ? 'checked' : '' }}>
                                                        <input class="form-check-input" type="hidden"
                                                            name="view_access[]" id="view_access_{{ $no }}"
                                                            name="view_access[]" value="{{ $permissions['view'] }}">
                                                    </td>
                                                    <td class="form-group text-center">
                                                        <input class="form-check-input" type="checkbox"
                                                            data-initial="add_access_{{ $no }}"
                                                            id="add_access_checked_{{ $no }}"
                                                            onclick="isCheckedAccess(this);"
                                                            {{ $permissions['add'] ? 'checked' : '' }}>
                                                        <input class="form-check-input" type="hidden"
                                                            name="add_access[]" id="add_access_{{ $no }}"
                                                            name="add_access[]" value="{{ $permissions['add'] }}">
                                                    </td>
                                                    <td class="form-group text-center">
                                                        <input class="form-check-input" type="checkbox"
                                                            data-initial="edit_access_{{ $no }}"
                                                            id="edit_access_checked_{{ $no }}"
                                                            onclick="isCheckedAccess(this);"
                                                            {{ $permissions['edit'] ? 'checked' : '' }}>
                                                        <input class="form-check-input" type="hidden"
                                                            name="edit_access[]" id="edit_access_{{ $no }}"
                                                            name="edit_access[]" value="{{ $permissions['edit'] }}">
                                                    </td>
                                                    <td class="form-group text-center">
                                                        <input class="form-check-input" type="checkbox"
                                                            data-initial="delete_access_{{ $no }}"
                                                            id="delete_access_checked_{{ $no }}"
                                                            onclick="isCheckedAccess(this);"
                                                            {{ $permissions['delete'] ? 'checked' : '' }}>
                                                        <input class="form-check-input" type="hidden"
                                                            name="delete_access[]" id="delete_access_{{ $no }}"
                                                            name="delete_access[]" value="{{ $permissions['delete'] }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteRow('{{ $no }}')"
                                                            id="delete_row_{{ $no }}">Hapus</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="counter_row" value="{{ $no }}">
                                    <button type="button" class="btn btn-info btn-sm" onclick="addRow()"><i
                                            class="fa fa-plus"></i>&nbsp;Tambah Baris</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-12">
                                @if (can('edit'))
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                            class="fa fa-save"></i>&nbsp;Simpan</button>
                                @endif
                                @if (can('delete'))
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="destroy('{{ $menu->id }}')"><i
                                            class="fa fa-trash"></i>&nbsp;Hapus</button>
                                @endif
                                <a href="{{ route('settings.menus.index') }}" class="btn btn-secondary btn-sm"><i
                                        class="fa fa-arrow-left"></i>&nbsp;Batal</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
