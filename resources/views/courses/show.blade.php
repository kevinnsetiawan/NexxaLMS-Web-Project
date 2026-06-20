@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem;">
    <!-- Course Detail Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; align-items: start;">
        
        <!-- Left Side: Content, Syllabus, Instructor -->
        <div>
            <!-- Breadcrumbs -->
            <div style="margin-bottom: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">
                <a href="{{ route('courses.index') }}" style="color: var(--secondary); font-weight: 500;">Explore</a> &gt; 
                <span style="color: var(--text-primary);">{{ $course->category->name }}</span>
            </div>

            <!-- Course Header -->
            <h1 style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem;">{{ $course->title }}</h1>
            <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 2rem;">{{ $course->description }}</p>

            <!-- Course Info Meta Row -->
            <div class="flex-align gap-3" style="margin-bottom: 3rem; flex-wrap: wrap; background: var(--bg-card); padding: 1rem 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-glass);">
                <div class="flex-align gap-2">
                    <i class="ph ph-chart-bar" style="color: var(--primary); font-size: 1.25rem;"></i>
                    <div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Level</div>
                        <div style="font-size: 0.9rem; font-weight: 600;">{{ ucfirst($course->level) }}</div>
                    </div>
                </div>
                
                <div style="width: 1px; height: 30px; background: var(--border-glass);"></div>
                
                <div class="flex-align gap-2">
                    <i class="ph ph-clock" style="color: var(--secondary); font-size: 1.25rem;"></i>
                    <div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Total Lessons</div>
                        <div style="font-size: 0.9rem; font-weight: 600;">{{ $course->lessons()->count() }} Lessons</div>
                    </div>
                </div>

                <div style="width: 1px; height: 30px; background: var(--border-glass);"></div>

                <div class="flex-align gap-2">
                    <i class="ph ph-student" style="color: var(--success); font-size: 1.25rem;"></i>
                    <div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Students enrolled</div>
                        <div style="font-size: 0.9rem; font-weight: 600;">{{ $course->enrollments()->count() }} Students</div>
                    </div>
                </div>
            </div>

            <!-- Syllabus Section -->
            <h2 style="margin-bottom: 1.5rem;">Syllabus Course Outline</h2>
            <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 4rem;">
                @foreach($course->chapters as $chapter)
                    <div class="glass-card" style="padding: 1.25rem 2rem;">
                        <div class="flex-between" style="margin-bottom: 1rem;">
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary);">
                                Chapter {{ $loop->iteration }}: {{ $chapter->title }}
                            </h3>
                            <span class="text-muted" style="font-size: 0.85rem;">{{ $chapter->lessons->count() }} Lessons</span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.5rem; border-top: 1px solid var(--border-glass); padding-top: 0.75rem;">
                            @foreach($chapter->lessons as $lesson)
                                <div class="flex-between" style="padding: 0.5rem 0;">
                                    <div class="flex-align gap-2">
                                        @if($isEnrolled)
                                            <i class="ph ph-play-circle" style="color: var(--primary); font-size: 1.15rem;"></i>
                                        @else
                                            @if($lesson->is_free)
                                                <i class="ph ph-eye" style="color: var(--success); font-size: 1.15rem;" title="Preview Available"></i>
                                            @else
                                                <i class="ph ph-lock" style="color: var(--text-muted); font-size: 1.15rem;"></i>
                                            @endif
                                        @endif
                                        <span style="font-size: 0.95rem; color: var(--text-secondary);">{{ $lesson->title }}</span>
                                    </div>
                                    <div class="flex-align gap-2">
                                        <span class="text-muted" style="font-size: 0.8rem;">{{ $lesson->duration }}m</span>
                                        @if($lesson->is_free && !$isEnrolled)
                                            <span class="badge badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">Preview</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Instructor Section -->
            <h2>Dosen Pengampu</h2>
            <div class="glass-card flex-align gap-3" style="margin-top: 1.5rem; padding: 2rem;">
                <div class="avatar-circle lg" style="flex-shrink: 0; background-color: var(--accent);">
                    @php
                        $names = explode(' ', $course->instructor->name);
                        $initials = count($names) > 1 ? substr($names[0], 0, 1) . substr($names[1], 0, 1) : substr($names[0], 0, 2);
                        echo strtoupper($initials);
                    @endphp
                </div>
                <div>
                    <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $course->instructor->name }}</h3>
                    <div class="badge badge-secondary" style="font-size: 0.7rem; margin-bottom: 0.75rem;">Dosen Pengampu</div>
                    <p class="text-muted" style="font-size: 0.9rem;">{{ $course->instructor->bio }}</p>
                </div>
            </div>

        </div>

        <!-- Right Side: Sidebar card with video preview thumbnail / price / enroll -->
        <div class="glass-card" style="position: sticky; top: 110px; padding: 1.5rem; box-shadow: var(--shadow-lg);">
            <!-- Mock course video cover -->
            <div style="position: relative; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1.5rem;">
                <img src="{{ $course->image_url ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop' }}" alt="cover" style="width: 100%; height: 180px; object-fit: cover;">
                <div style="position: absolute; top: 0; left: 0; width:100%; height:100%; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                    <i class="ph-fill ph-play-circle" style="color: #fff; font-size: 3.5rem; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.5)); cursor: pointer;"></i>
                </div>
            </div>

            <!-- Pricing info -->
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 0.85rem; color: var(--text-muted);">Course Price</div>
                <div class="flex-align gap-2" style="margin-top: 0.25rem;">
                    <span style="font-size: 2.25rem; font-weight: 800; color: var(--text-primary);">
                        @if($course->price == 0)
                            Free
                        @else
                            ${{ number_format($course->price, 2) }}
                        @endif
                    </span>
                    @if($course->price > 0)
                        <span class="text-muted" style="text-decoration: line-through; font-size: 1.1rem; margin-left: 0.5rem;">${{ number_format($course->price * 1.5, 2) }}</span>
                    @endif
                </div>
            </div>

            <!-- Main Button Action -->
            @if($isEnrolled)
                <div style="margin-bottom: 1.5rem;">
                    <div class="flex-between" style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                        <span class="text-muted">Your Learning Progress</span>
                        <span style="font-weight: 700; color: var(--secondary);">{{ $progress }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>
                
                @php
                    $firstLesson = $course->lessons()->orderBy('lessons.id')->first();
                @endphp
                @if($firstLesson)
                    <a href="{{ route('student.classroom', [$course->slug, $firstLesson->id]) }}" class="btn btn-primary" style="width: 100%; padding: 0.85rem;">
                        <i class="ph-bold ph-graduation-cap"></i> Open Classroom
                    </a>
                @endif
            @else
                <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem;">
                        <i class="ph-bold ph-lightning"></i> Enroll Now (Free Mock)
                    </button>
                </form>
            @endif

            <!-- Mini List info -->
            <ul style="list-style: none; margin-top: 2rem; font-size: 0.88rem; display: flex; flex-direction: column; gap: 0.85rem; border-top: 1px solid var(--border-glass); padding-top: 1.5rem;">
                <li class="flex-align gap-2"><i class="ph ph-shield-check" style="color: var(--secondary); font-size: 1.15rem;"></i> Lifetime Access</li>
                <li class="flex-align gap-2"><i class="ph ph-award" style="color: var(--secondary); font-size: 1.15rem;"></i> Completion Certificate</li>
                <li class="flex-align gap-2"><i class="ph ph-notebook" style="color: var(--secondary); font-size: 1.15rem;"></i> Downloadable Resources</li>
            </ul>
        </div>

    </div>
</div>
@endsection
