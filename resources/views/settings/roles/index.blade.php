@extends('layouts.app')
@section('title', 'Peran')
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Peran</h3>
        </div>
    </div>

    <div class="clearfix"></div>
    @yield('content-roles')
@endsection
@push('custom-js')
    <script src="{{ asset('assets/js/custom/settings/roles.js') }}"></script>
@endpush
