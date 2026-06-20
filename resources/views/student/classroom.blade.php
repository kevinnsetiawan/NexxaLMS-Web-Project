@extends('layouts.app')

@section('content')
<div class="classroom-layout animate-fade-in">
    <!-- Left Sidebar: Syllabus -->
    <aside class="syllabus-sidebar">
        <div class="syllabus-header">
            <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.2;">{{ $course->title }}</h2>
            <div style="margin-top: 1rem; margin-bottom: 0.5rem;">
                <div class="flex-between" style="font-size: 0.75rem; margin-bottom: 0.25rem;">
                    <span class="text-muted">Course Completion</span>
                    <span style="font-weight: 700; color: var(--secondary);">{{ $enrollment->progress_percentage }}%</span>
                </div>
                <div class="progress-bar-container" style="height: 6px;">
                    <div class="progress-bar-fill" style="width: {{ $enrollment->progress_percentage }}%;"></div>
                </div>
            </div>
        </div>

        <div style="flex-grow: 1; overflow-y: auto;">
            @foreach($course->chapters as $chapter)
                <div class="syllabus-chapter">
                    <div class="chapter-title">
                        <span>Chapter {{ $loop->iteration }}: {{ $chapter->title }}</span>
                        <i class="ph ph-caret-down" style="font-size: 0.8rem;"></i>
                    </div>

                    <ul class="lesson-list">
                        @foreach($chapter->lessons as $lesson)
                            @php
                                $isCompleted = in_array($lesson->id, $completedLessonIds);
                                $isActive = $lesson->id == $currentLesson->id;
                            @endphp
                            <li>
                                <a href="{{ route('student.classroom', [$course->slug, $lesson->id]) }}" 
                                   class="lesson-item-link {{ $isActive ? 'active' : '' }}">
                                    <!-- Completion indicator check circle -->
                                    @if($isCompleted)
                                        <i class="ph-fill ph-check-circle" style="color: var(--success); font-size: 1.25rem; margin-right: 0.75rem;"></i>
                                    @else
                                        <i class="ph ph-circle" style="color: var(--text-muted); font-size: 1.25rem; margin-right: 0.75rem;"></i>
                                    @endif
                                    
                                    <span style="flex-grow: 1; line-height: 1.3;">{{ $lesson->title }}</span>
                                    <span class="text-muted" style="font-size: 0.75rem; margin-left: 0.5rem;">{{ $lesson->duration }}m</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </aside>

    <!-- Right Content Area: Lesson Player -->
    <section class="player-content">
        <!-- Breadcrumbs inside classroom -->
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
            <span>{{ $currentLesson->chapter->title }}</span> / 
            <span style="color: var(--text-primary);">{{ $currentLesson->title }}</span>
        </div>

        @if($currentLesson->video_url)
            <!-- Video Container -->
            <div class="video-container">
                <iframe src="{{ $currentLesson->video_url }}" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            </div>
        @endif

        <!-- Lesson Info Header & Complete button -->
        <div class="flex-between" style="border-bottom: 1px solid var(--border-glass); padding-bottom: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem;">
            <div style="max-width: 70%;">
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.2;">{{ $currentLesson->title }}</h1>
                <p class="text-muted" style="font-size: 0.95rem;">{{ $currentLesson->description }}</p>
            </div>

            <div class="flex-align gap-2">
                <!-- Toggle complete form -->
                <form action="{{ route('student.lesson.complete', $currentLesson->id) }}" method="POST">
                    @csrf
                    @php
                        $isCompleted = in_array($currentLesson->id, $completedLessonIds);
                    @endphp
                    @if($isCompleted)
                        <button type="submit" class="btn btn-secondary">
                            <i class="ph ph-check-square-offset" style="color: var(--success); font-size: 1.25rem;"></i> Completed
                        </button>
                    @else
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="ph ph-square"></i> Mark as Complete
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Lesson HTML/Markdown Content box -->
        <div class="glass-card" style="margin-bottom: 3rem; padding: 2.5rem;">
            <div style="line-height: 1.8; font-size: 1.05rem; color: var(--text-secondary);">
                <!-- Basic markdown conversion for visualization -->
                {!! nl2br(e($currentLesson->content)) !!}
            </div>
        </div>

        <!-- Quiz Integration Block -->
        @if($currentLesson->quiz)
            <div class="glass-card" style="border-color: var(--primary); padding: 2rem; background: linear-gradient(135deg, var(--bg-card), rgba(80, 50, 150, 0.1));">
                <div class="flex-between flex-wrap gap-3">
                    <div class="flex-align gap-3">
                        <div class="stat-icon" style="background: var(--primary-glow); color: var(--primary); font-size: 2rem; width: 60px; height: 60px;">
                            <i class="ph-duotone ph-brain"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 0.25rem;">Lesson Assessment Quiz</h3>
                            <p class="text-muted" style="font-size: 0.88rem;">{{ $currentLesson->quiz->title }} • Passing score: {{ $currentLesson->quiz->passing_score }}%</p>
                        </div>
                    </div>

                    <div>
                        @php
                            // Check if student has passed this quiz before
                            $bestSubmission = auth()->user()->submissions()
                                ->where('quiz_id', $currentLesson->quiz->id)
                                ->orderBy('score', 'desc')
                                ->first();
                        @endphp
                        
                        @if($bestSubmission && $bestSubmission->passed)
                            <div class="flex-align gap-3">
                                <div style="text-align: right;">
                                    <span class="badge badge-success" style="font-size: 0.8rem; padding: 0.3rem 0.75rem;">Quiz Passed</span>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Best Score: {{ $bestSubmission->score }}%</div>
                                </div>
                                <a href="{{ route('student.quiz.show', $currentLesson->quiz->id) }}" class="btn btn-secondary btn-sm">Retake Quiz</a>
                            </div>
                        @else
                            @if($bestSubmission)
                                <div class="flex-align gap-3">
                                    <div style="text-align: right; margin-right: 0.5rem;">
                                        <span class="badge badge-warning" style="font-size: 0.8rem; padding: 0.3rem 0.75rem;">Failed</span>
                                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Last Try: {{ $bestSubmission->score }}%</div>
                                    </div>
                                    <a href="{{ route('student.quiz.show', $currentLesson->quiz->id) }}" class="btn btn-primary btn-sm">Try Again</a>
                                </div>
                            @else
                                <a href="{{ route('student.quiz.show', $currentLesson->quiz->id) }}" class="btn btn-primary btn-sm">Start Quiz</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
