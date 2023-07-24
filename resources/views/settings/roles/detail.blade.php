@extends('settings.roles.index')
@section('content-roles')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Detail Pengguna</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form>
                        <input type="hidden" name="id" id="id" value="{{ $data->id }}">
                        <div class="row mb-3 mt-3 input-group has-validation">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" required name="name" id="name" class="form-control"
                                    value="{{ old('name') ? old('name') : $data->name }}">
                                <div class="invalid-feedback" id="name_error_message"></div>
                            </div>
                        </div>
                        <fieldset class="row mb-3 input-group has-validation">
                            <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active" id="active"
                                        value="1" {{ $data->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input is_active" type="radio" name="is_active"
                                        id="not_active" value="0" {{ !$data->is_active ? 'checked' : '' }}
                                        {{ $count_user_roles > 0 ? 'disabled' : '' }}>
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
                                @if ($count_user_roles > 0)
                                    <div class="alert alert-danger" role="alert">
                                        Peran yang sudah memiliki pengguna tidak dapat dihapus / dinonaktifkan.
                                    </div>
                                @endif
                                @if (can('edit'))
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                            class="fa fa-save"></i>&nbsp;Simpan</button>
                                @endif
                                @if (can('delete'))
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="destroy('{{ $data->id }}')"
                                        {{ $count_user_roles > 0 ? 'disabled' : '' }}><i
                                            class="fa fa-trash"></i>&nbsp;Hapus</button>
                                @endif
                                <a href="{{ route('settings.roles.index') }}" class="btn btn-secondary btn-sm"><i
                                        class="fa fa-arrow-left"></i>&nbsp;Batal</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
