
<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
    <a href="/home/pegawai" class="nav-link">
        <i class="nav-icon fas fa-home"></i>
        <p>
        Beranda
        </p>
    </a>
    </li>
    <li class="nav-item">
    
    <li class="nav-item has-treeview {{Request::is('pegawai/skp*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('pegawai/skp*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-address-book"></i>
          <p>
            SKP
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('pegawai/skp*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/pegawai/skp/rencana-kegiatan" class="nav-link {{ Request::is('pegawai/skp/rencana-kegiatan') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Rencana Kegiatan</p>
            </a>
            </li>
        </ul>
    </li>
    
    <li class="nav-item has-treeview {{Request::is('pegawai/aktivitas*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('pegawai/aktivitas*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-clock"></i>
          <p>
            Aktivitas
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('pegawai/aktivitas*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/pegawai/aktivitas/harian" class="nav-link {{ Request::is('pegawai/aktivitas/harian') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Aktivitas Harian</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/pegawai/aktivitas/keberatan" class="nav-link {{ Request::is('pegawai/aktivitas/keberatan') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Pengajuan Keberatan</p>
            </a>
            </li>
        </ul>
    </li>
    <li class="nav-item has-treeview {{Request::is('pegawai/validasi*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('pegawai/validasi*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-user-check"></i>
          <p>
            Validasi Aktivitas
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('pegawai/validasi*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/pegawai/validasi/harian" class="nav-link {{ Request::is('pegawai/validasi/harian') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Validasi Aktivitas</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/pegawai/validasi/keberatan" class="nav-link {{ Request::is('pegawai/validasi/keberatan') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Validasi Keberatan</p>
            </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
    <a href="/pegawai/profil" class="nav-link">
        <i class="nav-icon fas fa-user"></i>
        <p>
        Profil
        </p>
    </a>
    </li>
    <li class="nav-item has-treeview {{Request::is('pegawai/laporan*') ? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{Request::is('pegawai/laporan*') ? 'active' : ''}}">
          <i class="nav-icon fas fa-file"></i>
          <p>
            laporan
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{Request::is('pegawai/laporan*') ? 'block' : 'none'}};">
            <li class="nav-item">
            <a href="/pegawai/laporan/harian" class="nav-link {{ Request::is('pegawai/laporan/harian') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Laporan TPP</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/pegawai/laporan/keberatan" class="nav-link {{ Request::is('pegawai/laporan/keberatan') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Laporan Aktivitas</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/pegawai/laporan/keberatan" class="nav-link {{ Request::is('pegawai/laporan/keberatan') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>Laporan Penghasilan</p>
            </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
    <a href="/logout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>
        Logout
        </p>
    </a>
    </li>
</ul>
</nav>