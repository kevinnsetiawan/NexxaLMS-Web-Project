@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem;">
    <!-- Welcome Header banner -->
    <div class="glass-card flex-between" style="padding: 2.5rem; margin-bottom: 3rem; background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.005) 100%); border-color: rgba(255, 255, 255, 0.08);">
        <div class="flex-align gap-3">
            <div class="avatar-circle lg" style="flex-shrink: 0;">
                @php
                    $names = explode(' ', auth()->user()->name);
                    $initials = count($names) > 1 ? substr($names[0], 0, 1) . substr($names[1], 0, 1) : substr($names[0], 0, 2);
                    echo strtoupper($initials);
                @endphp
            </div>
            <div>
                <span class="badge badge-secondary" style="font-size: 0.7rem;">Dashboard Mahasiswa</span>
                <h1 style="font-size: 1.85rem; font-weight: 800; margin-top: 0.25rem; margin-bottom: 0.25rem;">
                    Halo, {{ auth()->user()->name }}!
                </h1>
                <p class="text-muted" style="font-size: 0.95rem;">Selamat datang kembali di portal pembelajaran digital NexaLearn.</p>
            </div>
        </div>
        
        <!-- Stats summary -->
        <div class="flex-align gap-3" style="flex-wrap: wrap;">
            <!-- Stat 1 -->
            <div class="glass-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: var(--radius-md); transform: none; background: rgba(0,0,0,0.2);">
                <div class="stat-icon" style="background: var(--secondary-glow); color: var(--secondary);"><i class="ph ph-books"></i></div>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800;">{{ $enrollments->count() }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Enrolled</div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="glass-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: var(--radius-md); transform: none; background: rgba(0,0,0,0.2);">
                <div class="stat-icon" style="background: hsla(145, 80%, 45%, 0.2); color: var(--success);"><i class="ph ph-award"></i></div>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800;">{{ $enrollments->where('status', 'completed')->count() }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Completed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses Section -->
    <h2 style="margin-bottom: 1.5rem;"><i class="ph ph-play-circle" style="color: var(--primary);"></i> My Enrolled Courses</h2>
    
    @if($enrollments->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 4rem 2rem;">
            <i class="ph ph-compass" style="font-size: 4rem; color: var(--primary); margin-bottom: 1.5rem; display: inline-block;"></i>
            <h3>No active enrollments yet</h3>
            <p class="text-muted" style="margin-top: 0.5rem; margin-bottom: 1.5rem;">Explore our high-quality catalog and start learning today.</p>
            <a href="{{ route('courses.index') }}" class="btn btn-primary">
                Browse Courses <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    @else
        <div class="grid-2">
            @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $firstLesson = $course->lessons()->orderBy('lessons.id')->first();
                @endphp
                <div class="glass-card flex-between" style="gap: 1.5rem; flex-wrap: wrap; align-items: flex-start; padding: 1.5rem;">
                    <!-- Course Cover Image Column -->
                    <div style="width: 150px; height: 110px; border-radius: var(--radius-md); overflow: hidden; flex-shrink: 0;">
                        <img src="{{ $course->image_url ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop' }}" 
                             alt="{{ $course->title }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <!-- Content details Column -->
                    <div style="flex-grow: 1; min-width: 250px;">
                        <span class="badge badge-secondary" style="font-size: 0.65rem;">{{ $course->category->name }}</span>
                        <h3 style="font-size: 1.15rem; margin-top: 0.25rem; margin-bottom: 0.5rem; font-weight: 700; line-height: 1.3;">{{ $course->title }}</h3>
                        
                        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.25rem;">Instructor: <strong>{{ $course->instructor->name }}</strong></p>
                        
                        <!-- Progress bar -->
                        <div style="margin-bottom: 1.25rem;">
                            <div class="flex-between" style="font-size: 0.8rem; margin-bottom: 0.4rem;">
                                <span class="text-muted">Progress</span>
                                <span style="font-weight: 700; color: var(--secondary);">{{ $enrollment->progress_percentage }}%</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: {{ $enrollment->progress_percentage }}%;"></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        @if($firstLesson)
                            <a href="{{ route('student.classroom', [$course->slug, $firstLesson->id]) }}" class="btn btn-primary btn-sm" style="width: 100%;">
                                <i class="ph-bold ph-graduation-cap"></i> Resume Class <i class="ph ph-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
