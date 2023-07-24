@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Pengguna</h3>
        </div>
    </div>

    <div class="clearfix"></div>
    @yield('content-users')
@endsection
@push('custom-js')
    <script src="{{ asset('assets/js/custom/settings/users.js') }}"></script>
@endpush
