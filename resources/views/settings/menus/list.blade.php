@extends('settings.menus.index')
@section('title', 'Menu')
@section('content-menus')
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Data Menu</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if (can('add'))
                        <a href="{{ route('settings.menus.create') }}" class="btn btn-sm btn-success mb-3"><i
                                class="fa fa-plus me-2"></i> Tambah</a>
                    @endif
                    <div class="tree">
                        <ul>
                            @if (count($menu) > 0)
                                @foreach ($menu as $menus)
                                    <li class="font-weight-bold"><a
                                            href="{{ route('settings.menus.detail', ['id' => $menus['id']]) }}">{{ $menus['title'] }}
                                            <span class="badge badge-{{ $menus['is_active'] ? 'success' : 'danger' }}">
                                            </span></a>
                                        @if (count($menus['sub_menus']) > 0)
                                            <ul>
                                                @foreach ($menus['sub_menus'] as $submenus)
                                                    <li><a
                                                            href="{{ route('settings.menus.detail', ['id' => $submenus['id']]) }}">{{ $submenus['title'] }}</a>
                                                        <span
                                                            class="badge badge-{{ $submenus['is_active'] ? 'success' : 'danger' }}">
                                                        </span></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            @else
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
