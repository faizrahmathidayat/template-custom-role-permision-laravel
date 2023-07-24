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
                        <fieldset class="row mb-3 mt-3 input-group has-validation">
                            <legend class="col-form-label col-sm-2 pt-0">Jenis</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_parent" type="radio" name="is_parent" value="1"
                                        id="menu" checked="">
                                    <label class="form-check-label">
                                        Menu Utama
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_parent" type="radio" name="is_parent" id="sub_menu"
                                        value="0">
                                    <label class="form-check-label">
                                        Sub Menu
                                    </label>
                                </div>
                                <div class="invalid-feedback" id="is_parent_error_message"></div>
                            </div>
                        </fieldset>
                        <div class="row mb-3 input-group has-validation has-parent-menu" hidden>
                            <label class="col-sm-2 col-form-label">Parent Menu</label>
                            <div class="col-sm-10">
                                <select class="form-control select2-custom" disabled required name="parent_menu_id"
                                    id="parent_menu_id">
                                    <option value="">Pilih Parent Menu</option>
                                    @foreach ($parent as $parents)
                                        <option value="{{ $parents['id'] }}"
                                            {{ old('parent_menu_id') == $parents['id'] ? 'selected' : '' }}>
                                            {{ $parents['title'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="parent_menu_id_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group has-validation">
                            <label class="col-sm-2 col-form-label">Judul</label>
                            <div class="col-sm-10">
                                <input type="text" required name="title" id="title" class="form-control"
                                    value="{{ old('title') }}" placeholder="Masukan judul">
                                <div class="invalid-feedback" id="title_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group has-validation">
                            <label class="col-sm-2 col-form-label">Url</label>
                            <div class="col-sm-10">
                                <input type="text" required name="url" id="url" class="form-control"
                                    placeholder="Masukan Url">
                                <div class="invalid-feedback" id="url_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group has-validation">
                            <label class="col-sm-2 col-form-label">Posisi Menu</label>
                            <div class="col-sm-10">
                                <input type="number" required name="position" id="position" class="form-control"
                                    value="0">
                                <div class="invalid-feedback" id="position_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Url Group</label>
                            <div class="col-sm-10">
                                <input type="text" name="url_group" id="url_group" class="form-control"
                                    placeholder="Masukan Url Group">
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label class="col-sm-2 col-form-label">Icon</label>
                            <div class="col-sm-10">
                                <input type="text" name="icon" id="icon" class="form-control"
                                    placeholder="Contoh : bi bi-grid">
                                <small>Sumber Icon : <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap
                                        Icon</a> | <a href="https://remixicon.com/" target="_blank">Remix Icon</a> | <a
                                        href="https://boxicons.com/" target="_blank">Box Icon</a></small>
                            </div>
                        </div>
                        <fieldset class="row mb-3 input-group has-validation">
                            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active" id="active"
                                        value="1" checked="">
                                    <label class="form-check-label">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active"
                                        id="not_active" value="0">
                                    <label class="form-check-label">
                                        Tidak Aktif
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
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
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="counter_row" value="0">
                                    <button type="button" class="btn btn-info btn-sm" onclick="addRow()"><i
                                            class="fa fa-plus"></i>&nbsp;Tambah</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm"><i
                                        class="fa fa-save"></i>&nbsp;Simpan</button>
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
