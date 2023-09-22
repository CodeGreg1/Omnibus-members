<!-- Start Sidebar Section -->
<div class="main-sidebar">
    <aside id="sidebar-wrapper" class="sidebar-menu-top-70px">

        <ul class="sidebar-menu sidebar-menu-adjustment">
            <li class="{{ Route::currentRouteName() == 'dashboard.index' ? 'active' : '' }}">
                <a class="nav-item {{ Route::currentRouteName() == 'dashboard.index' ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
            </li>
        </ul>

        {!! HtmlMenuBuilder::parse([
            'menu' => 'User',
            'header' => 'User Panel',
            'template' => [
                'main_menu_ul' => '<ul class="sidebar-menu {class}">',
                'main_menu_li' => '<li class="{has_dropdown:dropdown} {class}"><a class="nav-item {has_dropdown:has-dropdown}" href="{link}">{icon}<span>{label}</span></a>',
                'sub_menu_ul' => '<ul class="dropdown-menu">',
                'sub_menu_li' => '<li class="{class}"><a class="nav-link" {target} href="{link}">{label}</a>'
            ]
        ]) !!}

        {!! HtmlMenuBuilder::parse([
            'menu' => 'Admin',
            'header' => 'Admin Panel',
            'template' => [
                'main_menu_ul' => '<ul class="sidebar-menu sidebar-menu-adjustment {class}">',
                'main_menu_li' => '<li class="{has_dropdown:dropdown} {class}"><a class="nav-item {has_dropdown:has-dropdown}" href="{link}">{icon}<span>{label}</span></a>',
                'sub_menu_ul' => '<ul class="dropdown-menu">',
                'sub_menu_li' => '<li class="{class}"><a class="nav-link" {target} href="{link}">{label}</a>'
            ]
        ]) !!}
    </aside>
</div>
<!-- End Sidebar Section -->