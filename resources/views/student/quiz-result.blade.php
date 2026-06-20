@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 5rem; max-width: 600px;">
    <div class="glass-card" style="text-align: center; padding: 3rem 2rem; box-shadow: var(--shadow-lg); border-color: {{ $submission->passed ? 'var(--success)' : 'var(--danger)' }};">
        
        @if($submission->passed)
            <!-- Pass Illustration / Icon -->
            <div class="stat-icon" style="background: hsla(145, 80%, 45%, 0.15); color: var(--success); width: 80px; height: 80px; font-size: 3rem; margin: 0 auto 2rem auto; border-radius: 50%;">
                <i class="ph-bold ph-seal-check"></i>
            </div>
            
            <span class="badge badge-success" style="font-size: 0.9rem; padding: 0.35rem 1rem; margin-bottom: 1rem;">Congratulations!</span>
            <h1 class="gradient-text" style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem;">Quiz Passed</h1>
            <p class="text-muted" style="margin-bottom: 2rem;">You successfully cleared the assessment for this lesson.</p>
        @else
            <!-- Fail Illustration / Icon -->
            <div class="stat-icon" style="background: hsla(355, 85%, 55%, 0.15); color: var(--danger); width: 80px; height: 80px; font-size: 3rem; margin: 0 auto 2rem auto; border-radius: 50%;">
                <i class="ph-bold ph-x-circle"></i>
            </div>
            
            <span class="badge badge-warning" style="font-size: 0.9rem; padding: 0.35rem 1rem; margin-bottom: 1rem; background: rgba(255, 150, 0, 0.15); color: hsl(40, 95%, 75%);">Keep Trying!</span>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem;">Quiz Failed</h1>
            <p class="text-muted" style="margin-bottom: 2rem;">You didn't reach the required score. Review the material and try again.</p>
        @endif

        <!-- Score details card -->
        <div style="background: rgba(0,0,0,0.2); border: 1px solid var(--border-glass); border-radius: var(--radius-md); padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 3rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Your Score</div>
                <div style="font-size: 2rem; font-weight: 800; color: {{ $submission->passed ? 'var(--success)' : 'var(--danger)' }};">{{ $submission->score }}%</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Required Score</div>
                <div style="font-size: 2rem; font-weight: 800; color: var(--text-primary);">{{ $submission->quiz->passing_score }}%</div>
            </div>
        </div>

        <!-- Call to actions -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @php
                $course = $submission->quiz->lesson->chapter->course;
            @endphp
            
            <a href="{{ route('student.classroom', [$course->slug, $submission->quiz->lesson_id]) }}" class="btn btn-primary" style="padding: 0.85rem;">
                <i class="ph ph-graduation-cap"></i> Return to Classroom
            </a>

            @if(!$submission->passed)
                <a href="{{ route('student.quiz.show', $submission->quiz_id) }}" class="btn btn-secondary" style="padding: 0.85rem;">
                    <i class="ph ph-arrow-counter-clockwise"></i> Retake Assessment
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
