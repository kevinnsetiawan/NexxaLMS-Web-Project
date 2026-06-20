@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem; max-width: 800px;">
    <!-- Quiz Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge badge-primary" style="margin-bottom: 0.5rem; font-size: 0.75rem;">Lesson Assessment</span>
        <h1 style="font-size: 2.25rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.5rem;">{{ $quiz->title }}</h1>
        <p class="text-muted" style="font-size: 0.95rem;">Course: <strong>{{ $course->title }}</strong> • Passing score: {{ $quiz->passing_score }}%</p>
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST" id="quiz-form">
        @csrf
        
        @foreach($quiz->questions as $question)
            <div class="glass-card quiz-question-box" style="margin-bottom: 2rem; padding: 2rem;">
                <div class="flex-align gap-2" style="margin-bottom: 1.25rem;">
                    <span class="badge badge-secondary" style="border-radius: 6px; padding: 0.2rem 0.5rem; font-size: 0.75rem;">Question {{ $loop->iteration }}</span>
                </div>
                <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.4;">{{ $question->question_text }}</h3>

                <!-- Options -->
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($question->options as $option)
                        <label class="quiz-option-label" id="label-option-{{ $option->id }}">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="quiz-option-input" required onclick="selectOption({{ $question->id }}, {{ $option->id }}, [{{ $question->options->pluck('id')->implode(',') }}])">
                            <span>{{ $option->option_text }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            @php
                $firstLesson = $course->lessons()->orderBy('lessons.id')->first();
            @endphp
            @if($firstLesson)
                <a href="{{ route('student.classroom', [$course->slug, $quiz->lesson_id]) }}" class="btn btn-secondary" style="flex-grow: 1; padding: 0.85rem;">
                    <i class="ph ph-arrow-left"></i> Back to Lesson
                </a>
            @endif
            <button type="submit" class="btn btn-primary" style="flex-grow: 2; padding: 0.85rem;">
                <i class="ph ph-check-square"></i> Submit Answers
            </button>
        </div>
    </form>
</div>

<!-- Option selection client highlight script -->
<script>
    function selectOption(questionId, optionId, allOptionIds) {
        // Remove 'selected' class from all options of this question
        allOptionIds.forEach(id => {
            const label = document.getElementById('label-option-' + id);
            if (label) {
                label.classList.remove('selected');
            }
        });
        
        // Add 'selected' class to chosen option
        const selectedLabel = document.getElementById('label-option-' + optionId);
        if (selectedLabel) {
            selectedLabel.classList.add('selected');
        }
    }
</script>
@endsection
