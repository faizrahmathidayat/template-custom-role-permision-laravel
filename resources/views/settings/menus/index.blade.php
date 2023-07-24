@extends('layouts.app')
@section('title', 'Menu')
@push('custom-css')
    <link href="{{ asset('assets/css/custom/treeview.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Menu</h3>
        </div>
    </div>

    <div class="clearfix"></div>
    @yield('content-menus')
@endsection
@push('custom-js')
    <script src="{{ asset('assets/js/custom/settings/menus.js') }}"></script>
@endpush
