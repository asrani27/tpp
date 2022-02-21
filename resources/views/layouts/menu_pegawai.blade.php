<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent" data-widget="treeview" role="menu"
        data-accordion="false">
        <li class="nav-item">
            <a href="/home/pegawai" class="nav-link {{Request::is('home/pegawai') ? 'active' : ''}}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Beranda
                </p>
            </a>
        </li>
        <li class="nav-item">
        <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link {{Request::is('pegawai/skp*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-address-book"></i>
                <p>
                    SKP
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="display: block">
                <li class="nav-item">
                    <a href="/pegawai/skp/rencana-kegiatan"
                        class="nav-link {{ Request::is('pegawai/skp/rencana-kegiatan') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle nav-icon"></i>
                        <p>Rencana Kegiatan</p>
                    </a>
                </li>
                @if (Auth::user()->pegawai->jabatan != null )
                @if (count(Auth::user()->pegawai->jabatan->bawahan) != 0)
                <li class="nav-item">
                    <a href="/pegawai/skp/validasi"
                        class="nav-link {{ Request::is('pegawai/skp/validasi') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle nav-icon"></i>
                        <p>Validasi SKP</p>
                    </a>
                </li>
                @endif
                @endif

                @if (Auth::user()->pegawai->jabatan_plt != null)
                <li class="nav-item">
                    <a href="/pegawai/skp/plt/validasi"
                        class="nav-link {{ Request::is('pegawai/skp/plt/validasi*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle nav-icon"></i>
                        <p>
                            Validasi SKP PLT
                        </p>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="nav-item has-treeview  menu-open">
            <a href="#" class="nav-link {{Request::is('pegawai/aktivitas*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-clock"></i>
                <p>
                    Aktivitas
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="display: block">
                <li class="nav-item">
                    <a href="/pegawai/aktivitas/harian"
                        class="nav-link {{ Request::is('pegawai/aktivitas/harian') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle nav-icon"></i>
                        <p>Aktivitas Harian</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/pegawai/aktivitas/keberatan"
                        class="nav-link {{ Request::is('pegawai/aktivitas/keberatan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Pengajuan Keberatan</p>
                    </a>
                </li> --}}
            </ul>
        </li>
        <li class="nav-item has-treeview  menu-open">
            <a href="#" class="nav-link {{Request::is('pegawai/validasi*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-user-check"></i>
                <p>
                    Validasi Aktivitas
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="display: block">
                <li class="nav-item">
                    <a href="/pegawai/validasi/harian"
                        class="nav-link {{ Request::is('pegawai/validasi/harian') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle nav-icon"></i>
                        <p>Validasi Aktivitas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pegawai/validasi/riwayat"
                        class="nav-link {{ Request::is('pegawai/validasi/riwayat') ? 'active' : '' }}">
                        <i class="nav-icon far fa-circle nav-icon"></i>
                        <p>Riwayat Validasi</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/pegawai/validasi/keberatan"
                        class="nav-link {{ Request::is('pegawai/validasi/keberatan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Validasi Keberatan</p>
                    </a>
                </li> --}}
            </ul>
        </li>

        @if (Auth::user()->pegawai->jabatan_plt != null)
        <li class="nav-item">
            <a href="/pegawai/plt/validasi/harian"
                class="nav-link {{ Request::is('pegawai/plt/validasi/harian*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-check"></i>
                <p>
                    Validasi Aktivitas PLT
                </p>
            </a>
        </li>
        @endif

        @if (Auth::user()->pegawai->jabatan_plh != null)
        <li class="nav-item">
            <a href="/pegawai/plh/validasi/harian"
                class="nav-link {{ Request::is('pegawai/plh/validasi/harian*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-check"></i>
                <p>
                    Validasi Aktivitas PLH
                </p>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a href="/pegawai/profil" class="nav-link {{ Request::is('pegawai/profil') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Profil
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="/pegawai/gaji" class="nav-link {{ Request::is('pegawai/gaji') ? 'active' : '' }}">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                    Gaji
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
                {{-- <li class="nav-item">
                    <a href="/pegawai/laporan/tpp"
                        class="nav-link {{ Request::is('pegawai/laporan/tpp') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Laporan TPP</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="/pegawai/laporan/aktivitas"
                        class="nav-link {{ Request::is('pegawai/laporan/aktivitas') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Laporan Aktivitas & presensi</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/pegawai/laporan/penghasilan"
                        class="nav-link {{ Request::is('pegawai/laporan/penghasilan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Laporan Penghasilan</p>
                    </a>
                </li> --}}
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