@extends('settings.users.index')
@section('title', 'Pengguna')
@section('content-users')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Data Pengguna</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if (can('add'))
                        <a href="{{ route('settings.users.create') }}" class="btn btn-sm btn-success mb-3"><i
                                class="fa fa-plus me-2"></i> Tambah</a>
                    @endif
                    <div class="table table-responsive">
                        <table class="table table-striped datatable" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
