@extends('settings.users.index')
@section('title', 'Pengguna')
@section('content-users')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Tambah Pengguna</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form>
                        <div class="row mb-3 mt-3 input-group">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" required name="name" id="name" class="form-control">
                                <div class="invalid-feedback" id="name_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label for="user" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" required name="user" id="user" class="form-control">
                                <div class="invalid-feedback" id="username_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label for="role_id" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control select2-custom" required name="role_id" id="role_id">
                                    <option value="">Pilih Role</option>
                                    @foreach ($role as $roles)
                                        <option value="{{ $roles['id'] }}"
                                            {{ old('role_id') == $roles['id'] ? 'selected' : '' }}>{{ $roles['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="role_id_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3 input-group">
                            <label for="pass" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" required name="pass" id="pass" class="form-control">
                                <div class="invalid-feedback" id="pass_error_message"></div>
                            </div>
                        </div>
                        <fieldset class="row mb-3 input-group">
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
                                <div class="invalid-feedback">Silakan pilih status.</div>
                            </div>
                        </fieldset>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary btn-sm"><i
                                        class="fa fa-save"></i>&nbsp;Simpan</button>
                                <a href="{{ route('settings.users.index') }}" class="btn btn-secondary btn-sm"><i
                                        class="fa fa-arrow-left"></i>&nbsp;Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
