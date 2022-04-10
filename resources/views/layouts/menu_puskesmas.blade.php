<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="/home/puskesmas" class="nav-link {{Request::is('home/admin') ? 'active' : ''}}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Beranda
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/puskesmas/profil" class="nav-link {{Request::is('admin/profil') ? 'active' : ''}}">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Profil
                </p>
            </a>
        </li>

        @if (session()->get('uuid') != null)
        <li class="nav-item">
            <a href="/puskesmas/dinkes/{{session()->get('uuid')}}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Ke Dinas Kesehatan
                </p>
            </a>
        </li>
        @else
        <li class="nav-item">
            <a href="/logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Logout
                </p>
            </a>
        </li>
        @endif
    </ul>
</nav>