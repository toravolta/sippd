<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-olive elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link" style="padding-left: 20px;">
        <!-- <img src="{{ asset('images/noimage.jpg') }}" style="height: 50px; width:200px;" alt="IGN Logo" style="opacity: .8"> -->
        <h3 class="text-center">SIPD</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(Auth::user()->image)
                <img src="<?php echo asset(Auth::user()->image) ?>" class="img-circle elevation-2" alt="User Image">
                @else
                <img src="{{ asset('images/propic.png') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{route('home')}}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @canany(['user-list', 'user-create', 'user-delete', 'user-edit'])
                <li class="nav-item">
                    <a href="{{route('users.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>
                            User Management
                        </p>
                    </a>
                </li>
                @endcanany

                <li class="nav-item has-treeview">
                    <span style="cursor: pointer; color:#1f2d3d;" class="nav-link">
                        <i class="nav-icon fas fa-user-lock"></i>
                        <p>
                            Access Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </span>
                    <ul class="nav nav-treeview">
                        @canany(['role-list', 'role-create', 'role-delete', 'role-edit'])
                        <li class="nav-item">
                            <a href="{{route('roles.index')}}" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Roles
                                </p>
                            </a>
                        </li>
                        @endcanany

                        @canany(['permission-list', 'permission-create', 'permission-delete', 'permission-edit'])
                        <li class="nav-item">
                            <a href="{{route('permission.index')}}" class="nav-link">
                                <i class="nav-icon fas fa-key"></i>
                                <p>
                                    Permission
                                </p>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
