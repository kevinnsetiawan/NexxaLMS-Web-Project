@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="max-width: 500px; margin: 4rem auto 0 auto;">
    <div class="glass-card" style="box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="ph-duotone ph-shield-check" style="font-size: 3.5rem; color: var(--primary);"></i>
            <h2 class="gradient-text" style="margin-top: 1rem; margin-bottom: 0.25rem;">Welcome Back</h2>
            <p class="text-muted" style="font-size: 0.9rem;">Sign in to access your classroom and courses</p>
        </div>

        @if($errors->any())
            <div style="background: rgba(255, 50, 50, 0.1); border: 1px solid var(--danger); color: hsl(355, 95%, 75%); padding: 0.85rem 1.2rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.88rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="e.g. student@lms.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
            </div>

            <div class="form-group flex-between" style="margin-top: 1rem; font-size: 0.88rem;">
                <label class="flex-align gap-2" style="cursor: pointer;">
                    <input type="checkbox" name="remember" style="accent-color: var(--primary); width: 16px; height: 16px;">
                    <span class="text-muted">Remember me</span>
                </label>
                <!-- Optional: Forgot Password placeholder -->
                <a href="#" style="color: var(--primary); font-weight: 500;">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 0.9rem;">
                <i class="ph-bold ph-sign-in"></i> Sign In
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem;">
            <span class="text-muted">Don't have an account?</span>
            <a href="{{ route('register') }}" style="color: var(--secondary); font-weight: 600; margin-left: 0.25rem;">Create account</a>
        </div>
    </div>
</div>
@endsection
