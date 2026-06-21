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
                <span class="badge badge-primary" style="font-size: 0.7rem;">Portal Dosen</span>
                <h1 style="font-size: 1.85rem; font-weight: 800; margin-top: 0.25rem; margin-bottom: 0.25rem;">
                    {{ auth()->user()->name }}
                </h1>
                <p class="text-muted" style="font-size: 0.95rem;">Kelola kelas Anda, lihat statistik mahasiswa, dan susun kurikulum pembelajaran.</p>
            </div>
        </div>
        
        <!-- Stats summary -->
        <div class="flex-align gap-3" style="flex-wrap: wrap;">
            <!-- Stat 1 -->
            <div class="glass-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: var(--radius-md); transform: none; background: rgba(0,0,0,0.2);">
                <div class="stat-icon"><i class="ph ph-student"></i></div>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800;">{{ $totalStudents }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Total Students</div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="glass-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: var(--radius-md); transform: none; background: rgba(0,0,0,0.2);">
                <div class="stat-icon" style="background: hsla(280, 80%, 45%, 0.2); color: var(--accent);"><i class="ph ph-books"></i></div>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800;">{{ $totalCourses }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Total Courses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Created Courses list -->
    <div class="flex-between" style="margin-bottom: 1.5rem;">
        <h2>My Published Courses</h2>
        <a href="{{ route('instructor.course.create') }}" class="btn btn-primary btn-sm">
            <i class="ph ph-plus-circle"></i> Create New Course
        </a>
    </div>

    @if($courses->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 4rem 2rem;">
            <i class="ph ph-books" style="font-size: 4rem; color: var(--primary); margin-bottom: 1.5rem; display: inline-block;"></i>
            <h3>No courses published yet</h3>
            <p class="text-muted" style="margin-top: 0.5rem; margin-bottom: 1.5rem;">Create a course and share your expertise with our students.</p>
            <a href="{{ route('instructor.course.create') }}" class="btn btn-primary">
                Create First Course
            </a>
        </div>
    @else
        <div class="grid-3">
            @foreach($courses as $course)
                <div class="glass-card flex-between" style="flex-direction: column; align-items: stretch; height: 100%; padding: 1.5rem;">
                    <div>
                        <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="course-card-img">
                        
                        <div class="flex-between" style="margin-bottom: 0.75rem;">
                            <span class="badge badge-secondary">{{ $course->category->name }}</span>
                            <span class="badge badge-primary">{{ $course->level }}</span>
                        </div>
                        
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; font-weight: 700; line-height: 1.3;">{{ $course->title }}</h3>
                        
                        <!-- Mini stats rows -->
                        <div style="display: flex; gap: 1rem; font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.5rem; border-top: 1px solid var(--border-glass); border-bottom: 1px solid var(--border-glass); padding: 0.5rem 0;">
                            <div><i class="ph ph-student"></i> <strong>{{ $course->enrollments->count() }}</strong> students</div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                        <a href="{{ route('instructor.course.builder', $course->id) }}" class="btn btn-secondary" style="flex: 1; justify-content: center; padding: 0.6rem 0.5rem; font-size: 0.88rem;">
                            <i class="ph ph-gear-six"></i> Syllabus
                        </a>
                        <a href="{{ route('instructor.course.edit', $course->id) }}" class="btn btn-secondary" style="flex: 1; justify-content: center; padding: 0.6rem 0.5rem; font-size: 0.88rem; border-color: rgba(255, 255, 255, 0.15);">
                            <i class="ph ph-pencil-simple"></i> Edit
                        </a>
                        <form action="{{ route('instructor.course.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini beserta seluruh bab dan materinya secara permanen?')" style="display: inline; margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.6rem 0.75rem; font-size: 0.88rem; height: 100%;">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
