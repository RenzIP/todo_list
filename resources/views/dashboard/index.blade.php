@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-3 text-primary"></i>
                        <span>Selamat Datang, {{ Auth::user()->nama }}!</span>
                    </h1>
          
                </div>
                <div class="d-none d-sm-inline-block">
                    <div class="text-end text-muted">
                        <div class="small">{{ now()->format('l') }}</div>
                        <div class="h5 mb-0">{{ now()->format('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-1 h-100 bg-primary"></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-xs text-uppercase fw-bold text-primary">Total Pengguna</div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                            <i class="fas fa-users fa-fw text-primary"></i>
                        </div>
                    </div>
                    <div class="h2 mb-0 fw-bold mt-2 justify-center text-center">{{ number_format($totalUsers) }}</div>
                    <div class="text-muted small mt-2 justify-center text-center">
                        <i class="fas fa-chart-line me-1"></i>
                        Pengguna Aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- Users by Role Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-1 h-100 bg-success"></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-xs text-uppercase fw-bold text-success">Pengguna per Role</div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-2">
                            <i class="fas fa-user-tag fa-fw text-success"></i>
                        </div>
                    </div>
                    @foreach($usersByRole as $role)
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="text-muted">{{ $role->nama }}</span>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold me-2">{{ $role->users_count }}</span>
                            <div class="progress" style="width: 50px; height: 4px;">
                                <div class="progress-bar bg-success" style="width: {{ ($role->users_count / $totalUsers) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Latest Users Card -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary d-flex align-items-center">
                            <i class="fas fa-user-clock me-2"></i>
                            Pengguna Terbaru
                        </h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4">Nama</th>
                                    <th class="border-0">Email</th>
                                    <th class="border-0">Role</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestUsers as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                                <div class="text-muted small">ID: #{{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
                                            {{ $user->role->nama ?? 'Tidak ada role' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $user->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-history me-2"></i>
                            Aktivitas Terakhir
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($latestUsers->take(5) as $user)
                        <div class="timeline-item pb-4 position-relative ms-4">
                            <div class="timeline-marker bg-primary position-absolute top-0 start-0 translate-middle-x rounded-circle" style="width: 12px; height: 12px;"></div>
                            <div class="timeline-content ps-4 border-start">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold">{{ $user->nama }} bergabung</div>
                                    <div class="text-muted small">{{ $user->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <p class="text-muted mb-0">Pengguna baru dengan role {{ $user->role->nama ?? 'Tidak ada role' }} telah bergabung ke sistem.</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection