<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title">
                <span>{{ env('APP_NAME') }}</span>
            </a>
        </div>
        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_info">
                <span>{{ Auth::user()->roles->name }},</span>
                <h2>{{ Auth::user()->name }}</h2>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /menu profile quick info -->

        <br />


        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    @foreach (menu() as $menus)
                        @if (count($menus['main_menu']['sub_menu']) > 0)
                            <li class="{{ request()->is($menus['main_menu']['url_group'] . '*') ? 'active' : '' }}">
                                <a><i class="{{ $menus['main_menu']['icon'] }}"></i>{{ $menus['main_menu']['title'] }}
                                    <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu"
                                    style="display: {{ request()->is($menus['main_menu']['url_group'] . '*') ? 'block' : 'none' }};">
                                    @foreach ($menus['main_menu']['sub_menu'] as $sub_menus)
                                        <li class="{{ request()->is($sub_menus['url'] . '*') ? 'current-page' : '' }}">
                                            <a href="{{ url($sub_menus['url']) }}">{{ $sub_menus['title'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="{{ request()->is($menus['main_menu']['url_group'] . '*') ? 'active' : '' }}">
                                <a href="{{ url($menus['main_menu']['url']) }}"><i
                                        class="{{ $menus['main_menu']['icon'] }}"></i>
                                    {{ $menus['main_menu']['title'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- sidebar menu -->

        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small bg-danger">
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
