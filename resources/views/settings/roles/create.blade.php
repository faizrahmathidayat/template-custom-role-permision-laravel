@extends('settings.roles.index')
@section('content-roles')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Tambah Peran</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form>
                        <div class="row mb-3 mt-3 input-group has-validation">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" required name="name" id="name" class="form-control">
                                <div class="invalid-feedback" id="name_error_message"></div>
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
                                <div class="invalid-feedback">Silakan pilih status.</div>
                            </div>
                        </fieldset>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                @if (can('add'))
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                            class="fa fa-save"></i>&nbsp;Simpan</button>
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
