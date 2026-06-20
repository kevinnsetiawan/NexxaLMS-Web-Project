@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem;">
    <!-- Welcome Header banner -->
    <div class="glass-card flex-between" style="padding: 2.5rem; margin-bottom: 3rem; background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.005) 100%); border-color: rgba(255, 255, 255, 0.08);">
        <div class="flex-align gap-3">
            <div class="avatar-circle lg" style="flex-shrink: 0; background-color: var(--accent);">
                @php
                    $names = explode(' ', auth()->user()->name);
                    $initials = count($names) > 1 ? substr($names[0], 0, 1) . substr($names[1], 0, 1) : substr($names[0], 0, 2);
                    echo strtoupper($initials);
                @endphp
            </div>
            <div>
                <span class="badge badge-primary" style="font-size: 0.7rem;">Panel Administrasi</span>
                <h1 style="font-size: 1.85rem; font-weight: 800; margin-top: 0.25rem; margin-bottom: 0.25rem;">
                    {{ auth()->user()->name }}
                </h1>
                <p class="text-muted" style="font-size: 0.95rem;">Pantau metrik sistem, kelola kategori mata kuliah, dan administrasi akun pengguna.</p>
            </div>
        </div>
    </div>

    <!-- Platform wide Stats Grid -->
    <div class="grid-4" style="margin-bottom: 4rem;">
        <!-- Stat Card 1 -->
        <div class="glass-card dashboard-stat-card" style="padding: 1.5rem;">
            <div>
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Students</div>
                <div style="font-size: 2rem; font-weight: 800; margin-top: 0.25rem;">{{ $totalStudents }}</div>
            </div>
            <div class="stat-icon" style="background: var(--secondary-glow); color: var(--secondary);"><i class="ph ph-student"></i></div>
        </div>

        <!-- Stat Card 2 -->
        <div class="glass-card dashboard-stat-card" style="padding: 1.5rem;">
            <div>
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Instructors</div>
                <div style="font-size: 2rem; font-weight: 800; margin-top: 0.25rem;">{{ $totalInstructors }}</div>
            </div>
            <div class="stat-icon" style="background: var(--primary-glow); color: var(--primary);"><i class="ph ph-chalkboard-teacher"></i></div>
        </div>

        <!-- Stat Card 3 -->
        <div class="glass-card dashboard-stat-card" style="padding: 1.5rem;">
            <div>
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Courses</div>
                <div style="font-size: 2rem; font-weight: 800; margin-top: 0.25rem;">{{ $totalCourses }}</div>
            </div>
            <div class="stat-icon" style="background: hsla(40, 95%, 55%, 0.2); color: var(--warning);"><i class="ph ph-books"></i></div>
        </div>

        <!-- Stat Card 4 -->
        <div class="glass-card dashboard-stat-card" style="padding: 1.5rem;">
            <div>
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Enrollments</div>
                <div style="font-size: 2rem; font-weight: 800; margin-top: 0.25rem;">{{ $totalEnrollments }}</div>
            </div>
            <div class="stat-icon" style="background: hsla(145, 80%, 45%, 0.2); color: var(--success);"><i class="ph ph-users-three"></i></div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(45, 200, 100, 0.1); border: 1px solid var(--success); color: hsl(145, 95%, 75%); padding: 0.85rem 1.2rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.88rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; align-items: start;">
        
        <!-- Left Column: Categories List -->
        <div>
            <h2>Platform Categories</h2>
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem; margin-bottom: 5rem;">
                @foreach($categories as $cat)
                    <div class="glass-card flex-between" style="padding: 1.25rem 2rem;">
                        <div class="flex-align gap-3">
                            <div class="stat-icon" style="background: rgba(255,255,255,0.05); color: var(--text-primary); width: 45px; height: 45px; font-size: 1.25rem;">
                                <i class="ph ph-{{ $cat->icon ?? 'folder' }}"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.05rem; font-weight: 700;">{{ $cat->name }}</h3>
                                <p class="text-muted" style="font-size: 0.82rem; margin-top: 0.15rem;">{{ $cat->description }}</p>
                            </div>
                        </div>
                        <div class="badge badge-secondary" style="font-size: 0.75rem;">
                            {{ $cat->courses_count }} Courses
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column: Create Category Card -->
        <div class="glass-card" style="position: sticky; top: 110px; padding: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.25rem; line-height: 1.2;"><i class="ph ph-plus-circle"></i> Add Category</h3>
            <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.5rem;">Create a new department category that instructors can publish courses under.</p>
            
            <form action="{{ route('admin.category.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cat_name" class="form-label">Category Name</label>
                    <input type="text" name="name" id="cat_name" class="form-input" placeholder="e.g. Health & Nutrition" required style="padding: 0.6rem 1rem;">
                </div>
                <div class="form-group">
                    <label for="cat_desc" class="form-label">Brief Description</label>
                    <textarea name="description" id="cat_desc" class="form-input" rows="3" placeholder="Brief summary of this category..." style="padding: 0.6rem 1rem;"></textarea>
                </div>
                <div class="form-group">
                    <label for="cat_icon" class="form-label">Phosphor Icon Name (Optional)</label>
                    <input type="text" name="icon" id="cat_icon" class="form-input" placeholder="e.g. heartbeat, code, palette" style="padding: 0.6rem 1rem;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                    <i class="ph ph-plus-circle"></i> Save Category
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
