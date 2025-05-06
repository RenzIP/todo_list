@extends('layouts.app')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card auth-card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">

                                <h2 class="todo fw-bold mb-4">To-Do-List</h2>
                                <p class="text-muted">Buat akun dan mulai membuat tugas baru</p>
                            </div>

                            <form method="POST" action="{{ route('create.user') }}">
                                @csrf

                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" name="nama"
                                            class="form-control border-start-0 @error('nama') is-invalid @enderror"
                                            placeholder="Nama Lengkap" value="{{ old('nama') }}" required autocomplete="nama"
                                            autofocus>
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                        <input type="text" name="username"
                                            class="form-control border-start-0 @error('username') is-invalid @enderror"
                                            placeholder="Username" value="{{ old('username') }}" required autocomplete="username">
                                    </div>
                                    @error('username')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope-at"></i>
                                        </span>
                                        <input type="email" name="email"
                                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            placeholder="Email Address" value="{{ old('email') }}" required
                                            autocomplete="email">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-key"></i>
                                        </span>
                                        <input type="password" name="password"
                                            class="form-control border-start-0 @error('password') is-invalid @enderror"
                                            placeholder="Password" required autocomplete="new-password">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-key"></i>
                                        </span>
                                        <input type="password" name="password_confirmation"
                                            class="form-control border-start-0" placeholder="Confirm Password" required
                                            autocomplete="new-password">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-purple w-100 mb-3 position-relative overflow-hidden">
                                    <span class="d-flex align-items-center justify-content-center">
                                        Buat Akun
                                        <i class="ms-2">➜</i>
                                    </span>
                                </button>

                                <div class="text-center">
                                    <a href="{{ route('login') }}" class="link-purple">
                                        Sudah Punya Akun? Silahkan Login
                                    </a>
                                </div>

                                <div class="mt-4 pt-4 border-top">
                                    <div class="row g-4">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="feature-icon me-2"><i class="bi bi-check-circle"></i></div>
                                                <span class="small text-muted">Task Management</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="feature-icon me-2"><i class="bi bi-check-circle"></i></div>
                                                <span class="small text-muted">Progress Tracking</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="feature-icon me-2"><i class="bi bi-check-circle"></i></div>
                                                <span class="small text-muted">Team Collaboration</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="feature-icon me-2"><i class="bi bi-check-circle"></i></div>
                                                <span class="small text-muted">Priority Settings</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
