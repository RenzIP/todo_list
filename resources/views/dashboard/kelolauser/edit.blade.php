@extends('dashboard.layouts.main')

@section('container')
<div class="pagetitle">
  <h1>Kelola User</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Kelola User</a></li>
      <li class="breadcrumb-item active">Edit User</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit User</h5>

          <form action="{{ route('users.update', $user->id) }}" method="POST">
            @method('put')
            @csrf
            <div class="row mb-3">
              <label for="nama" class="col-sm-2 col-form-label">Nama</label>
              <div class="col-sm-10">
                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="username" class="col-sm-2 col-form-label">Username</label>
              <div class="col-sm-10">
                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                       id="username" name="username" value="{{ old('username', $user->username) }}" required>
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="email" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="password" class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="password_confirmation" class="col-sm-2 col-form-label">Konfirmasi Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation">
              </div>
            </div>

            <div class="row mb-3">
              <label for="id_role" class="col-sm-2 col-form-label">Role</label>
              <div class="col-sm-10">
                <select class="form-select @error('id_role') is-invalid @enderror" 
                        id="id_role" name="id_role" required>
                  <option value="">Pilih Role</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}" 
                      {{ old('id_role', $user->id_role) == $role->id ? 'selected' : '' }}>
                      {{ $role->nama }}
                    </option>
                  @endforeach
                </select>
                @error('id_role')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
              </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</section>
@endsection