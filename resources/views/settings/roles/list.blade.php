@extends('settings.roles.index')
@section('content-roles')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Data Peran</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if (can('add'))
                        <a href="{{ route('settings.roles.create') }}" class="btn btn-sm btn-success mb-3"><i
                                class="fa fa-plus me-2"></i> Tambah</a>
                    @endif
                    <div class="table table-responsive">
                        <table class="table table-striped datatable" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
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
