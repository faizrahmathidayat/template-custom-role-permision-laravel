@extends('settings.users.index')
@section('title', 'Pengguna')
@section('content-users')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Detail Pengguna</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form>
                        <input type="hidden" name="id" id="id" value="{{ $users->id }}">
                        <div class="row mb-3 mt-3">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $users->name }}">
                                <div class="invalid-feedback" id="name_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="user" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="user" id="user" disabled class="form-control"
                                    value="{{ $users->username }}">
                                <small class="text-danger fst-italic">Username tidak dapat diperbaruai.</small>
                                <div class="invalid-feedback" id="user_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-select form-control select2-custom" name="role_id" id="role_id">
                                    <option>Pilih Role</option>
                                    @foreach ($role as $roles)
                                        <option value="{{ $roles['id'] }}"
                                            {{ old('role_id') == $roles['id'] || $users->roles->id == $roles['id'] ? 'selected' : '' }}>
                                            {{ $roles['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="role_id_error_message"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="pass" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="pass" id="pass" class="form-control">
                                <div class="invalid-feedback" id="pass_error_message"></div>
                            </div>
                        </div>
                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active" id="active"
                                        value="1" {{ $users->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active"
                                        id="not_active" value="0" {{ !$users->is_active ? 'checked' : '' }}
                                        {{ $users->id == Auth::id() ? 'disabled' : '' }}>
                                    <label class="form-check-label">
                                        Tidak Aktif
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="row" {{ $users->id == Auth::id() ? '' : 'hidden' }}>
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <div class="alert alert-danger" role="alert">
                                    Anda tidak dapat menghapus akun sendiri.
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                @if (can('edit'))
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                            class="fa fa-save"></i>&nbsp;Simpan</button>
                                @endif
                                @if (can('delete'))
                                    <button type="button" class="btn btn-danger btn-sm"
                                        {{ $users->id == Auth::id() ? 'disabled' : '' }}
                                        onclick="destroy('{{ $users->id }}')"><i
                                            class="fa fa-trash"></i>&nbsp;Hapus</button>
                                @endif
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
