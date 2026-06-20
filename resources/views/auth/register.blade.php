@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="max-width: 550px; margin: 3rem auto 0 auto;">
    <div class="glass-card" style="box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="ph-duotone ph-user-plus" style="font-size: 3.5rem; color: var(--secondary);"></i>
            <h2 class="gradient-text" style="margin-top: 1rem; margin-bottom: 0.25rem;">Create Account</h2>
            <p class="text-muted" style="font-size: 0.9rem;">Join NovaLMS today to start learning or teaching</p>
        </div>

        @if($errors->any())
            <div style="background: rgba(255, 50, 50, 0.1); border: 1px solid var(--danger); color: hsl(355, 95%, 75%); padding: 0.85rem 1.2rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.88rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="e.g. John Doe" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="e.g. email@domain.com" value="{{ old('email') }}" required>
            </div>

            <!-- Role Selector Cards -->
            <div class="form-group">
                <label class="form-label">I want to...</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.5rem;">
                    <!-- Student Option -->
                    <label class="flex-align gap-3" style="border: 1px solid var(--border-glass); border-radius: var(--radius-md); padding: 1rem; cursor: pointer; background: rgba(0,0,0,0.15); transition: var(--transition-fast);">
                        <input type="radio" name="role" value="student" style="accent-color: var(--secondary); width: 18px; height: 18px;" checked>
                        <div>
                            <div style="font-weight: 600; font-size: 0.95rem;">Learn</div>
                            <span class="text-muted" style="font-size: 0.75rem;">Browse & enroll in courses</span>
                        </div>
                    </label>

                    <!-- Instructor Option -->
                    <label class="flex-align gap-3" style="border: 1px solid var(--border-glass); border-radius: var(--radius-md); padding: 1rem; cursor: pointer; background: rgba(0,0,0,0.15); transition: var(--transition-fast);">
                        <input type="radio" name="role" value="instructor" style="accent-color: var(--primary); width: 18px; height: 18px;">
                        <div>
                            <div style="font-weight: 600; font-size: 0.95rem;">Teach</div>
                            <span class="text-muted" style="font-size: 0.75rem;">Publish courses & lessons</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 0.9rem;">
                <i class="ph-bold ph-user-plus"></i> Get Started
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem;">
            <span class="text-muted">Already have an account?</span>
            <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; margin-left: 0.25rem;">Sign In</a>
        </div>
    </div>
</div>
@endsection
