@extends('layouts.app')

@section('title', 'Login')
@section('main_class', 'login-main')

@section('content')
<div class="login-page">
    <h1>Admin Login</h1>
    @if(session('error'))
        <div class="notice error">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
<style>
.login-page{max-width:400px;margin:60px auto;padding:32px;border-radius:18px;background:#fff;box-shadow:0 8px 32px rgba(15,23,42,0.12)}
.login-page h1{margin-bottom:24px;font-size:2rem;text-align:center}
.form-group{margin-bottom:18px}
label{display:block;margin-bottom:6px;font-weight:700;color:#0f172a}
input[type="email"],input[type="password"]{width:100%;padding:10px 12px;border-radius:8px;border:1px solid #cbd5e1;font-size:1rem}
.btn{display:block;width:100%;padding:12px 0;border:none;border-radius:8px;background:#0f766e;color:#fff;font-weight:700;font-size:1.1rem;cursor:pointer;transition:background .18s}
.btn:hover{background:#115e59}
.notice.error{background:#fff1f2;border:1px solid #fda4af;color:#9f1239;padding:12px;border-radius:8px;margin-bottom:16px;text-align:center}
</style>
@endsection