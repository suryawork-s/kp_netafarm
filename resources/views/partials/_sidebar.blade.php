<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        {{-- <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8"> --}}
        {{-- <span class="brand-text font-weight-light">AdminLTE 3</span> --}}

        <img src="{{ asset('logoo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="">
        <span class="brand-text font-weight-light">Sistem Kehadiran</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="35" fill="white"
                    class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    <path fill-rule="evenodd"
                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                </svg>
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @can('akses-dashboard')
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                @endcan

                <li class="nav-item">
                    @if (auth()->user()->role === 'superadmin' || auth()->user()->position_id == 1)
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Data Karyawan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @can('melihat-department')
                            <li class="nav-item">
                                <a href="{{ route('dashboard.departments.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Departemen</p>
                                </a>
                            </li>
                        @endcan
                        @can('melihat-jabatan')
                            <li class="nav-item">
                                <a href="{{ route('dashboard.positions.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jabatan</p>
                                </a>
                            </li>
                        @endcan
                        @can('melihat-role')
                            <li class="nav-item">
                                <a href="{{ route('dashboard.roles.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                        @endcan
                        @can('melihat-karyawan')
                            <li class="nav-item">
                                <a href="{{ route('dashboard.users.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Karyawan</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Izin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.create', ['type' => 'izin']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buat Pengajuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'izin', 'status' => 'pending']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $pendingCount = \App\Models\Leave::where('status', 'pending')
                                        ->where(function ($query) {
                                            $query->where('type', 'izin')->orWhere('type', 'sakit');
                                        })
                                        ->count();
                                @endphp
                                <p>Menunggu Disetujui
                                    @if ($pendingCount > 0)
                                        <span class="badge badge-info right">{{ $pendingCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'izin', 'status' => 'rejected']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $rejectedCount = \App\Models\Leave::where('status', 'rejected')
                                        ->where(function ($query) {
                                            $query->where('type', 'izin')->orWhere('type', 'sakit');
                                        })
                                        ->count();
                                @endphp
                                <p>Ditolak
                                    @if ($rejectedCount > 0)
                                        <span class="badge badge-danger right">{{ $rejectedCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'izin', 'status' => 'approved']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $approvedCount = \App\Models\Leave::where('status', 'approved')
                                        ->where(function ($query) {
                                            $query->where('type', 'izin')->orWhere('type', 'sakit');
                                        })
                                        ->count();
                                @endphp
                                <p>Disetujui
                                    @if ($approvedCount > 0)
                                        <span class="badge badge-success right">{{ $approvedCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    </ul>

                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Cuti
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.create', ['type' => 'cuti']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buat Pengajuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'cuti', 'status' => 'pending']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'cuti')
                                        ->where('status', 'pending')
                                        ->count();
                                @endphp
                                <p>Menunggu Disetujui
                                    @if ($count > 0)
                                        <span class="badge badge-info right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'cuti', 'status' => 'rejected']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'cuti')
                                        ->where('status', 'rejected')
                                        ->count();
                                @endphp
                                <p>Ditolak
                                    @if ($count > 0)
                                        <span class="badge badge-danger right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'cuti', 'status' => 'approved']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'cuti')
                                        ->where('status', 'approved')
                                        ->count();
                                @endphp
                                <p>Disetujui
                                    @if ($count > 0)
                                        <span class="badge badge-success right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Lembur
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.create', ['type' => 'lembur']) }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buat Pengajuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'lembur', 'status' => 'pending']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'lembur')
                                        ->where('status', 'pending')
                                        ->count();
                                @endphp
                                <p>Menunggu Disetujui
                                    @if ($count > 0)
                                        <span class="badge badge-info right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'lembur', 'status' => 'rejected']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'lembur')
                                        ->where('status', 'rejected')
                                        ->count();
                                @endphp
                                <p>Ditolak
                                    @if ($count > 0)
                                        <span class="badge badge-danger right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'lembur', 'status' => 'approved']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'lembur')
                                        ->where('status', 'approved')
                                        ->count();
                                @endphp
                                <p>Disetujui
                                    @if ($count > 0)
                                        <span class="badge badge-success right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Perjalanan Dinas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.create', ['type' => 'perjalanan-dinas']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buat Pengajuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'perjalanan-dinas', 'status' => 'pending']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'perjalanan-dinas')
                                        ->where('status', 'pending')
                                        ->count();
                                @endphp
                                <p>Menunggu Disetujui
                                    @if ($count > 0)
                                        <span class="badge badge-info right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'perjalanan-dinas', 'status' => 'rejected']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'perjalanan-dinas')
                                        ->where('status', 'rejected')
                                        ->count();
                                @endphp
                                <p>Ditolak
                                    @if ($count > 0)
                                        <span class="badge badge-danger right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard.leaves.index', ['type' => 'perjalanan-dinas', 'status' => 'approved']) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                @php
                                    $count = \App\Models\Leave::where('type', 'perjalanan-dinas')
                                        ->where('status', 'approved')
                                        ->count();
                                @endphp
                                <p>Disetujui
                                    @if ($count > 0)
                                        <span class="badge badge-success right">{{ $count }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Status</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ route('dashboard.report.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Laporan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link">
                            <i class="nav-icon fa fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
