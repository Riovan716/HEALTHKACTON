@extends('layouts.auth')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
          <form method="POST" action="{{
  route('post.login') }}">
            @csrf
            <div class="form-group mb-2">
              <label for="email">Alamat
                Email</label>
              <input id="email" type="email" class="form-control
@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
              @error('email')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message
        }}</strong>
                </span>
        @enderror
            </div>
            <div class="form-group mb-2">
              <label for="password">Kata
                Sandi</label>
              <input id="password" type="password" class="form-control
@error('password') is-invalid @enderror" name="password" required>
              @error('password')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message
        }}</strong>
                </span>
        @enderror
            </div>
            <div class="form-group form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' :
  '' }}>
              <label class="form-check-label" for="remember">
                Ingat Saya
              </label>
            </div>
            <div class="text-end">
              <button type="submit" class="btn
btn-primary">
                Masuk
              </button>
            </div>
            <hr />
            <div class="mb-2 text-center">
              <span>Belum memiliki akun? <a href="{{ route('register') }}">daftar disini</a></span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection