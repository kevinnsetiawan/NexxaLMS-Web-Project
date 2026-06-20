@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem;">
    <!-- Course Title & Hub Back navigation -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('instructor.dashboard') }}" style="color: var(--secondary); font-size: 0.9rem; font-weight: 600;"><i class="ph ph-arrow-left"></i> Back to Hub</a>
        <h1 style="font-size: 2.25rem; font-weight: 800; margin-top: 0.5rem; line-height: 1.2;">Course Builder &amp; Syllabus</h1>
        <p class="text-muted">Manage Chapters &amp; Lessons for: <strong>{{ $course->title }}</strong></p>
    </div>

    @if(session('success'))
        <div style="background: rgba(45, 200, 100, 0.1); border: 1px solid var(--success); color: hsl(145, 95%, 75%); padding: 0.85rem 1.2rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.88rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; align-items: start;">
        
        <!-- Left Side: Chapter & Lessons Structure List -->
        <div>
            <h2>Course Outline Structure</h2>
            <div style="display: flex; flex-direction: column; gap: 2rem; margin-top: 1.5rem; margin-bottom: 5rem;">
                @if($course->chapters->isEmpty())
                    <div class="glass-card" style="text-align: center; padding: 3rem 1.5rem; color: var(--text-muted);">
                        <i class="ph ph-list-numbers" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <h3>No Chapters added yet</h3>
                        <p>Create a chapter on the right column to start structure your syllabus.</p>
                    </div>
                @else
                    @foreach($course->chapters as $chapter)
                        <div class="glass-card" style="padding: 1.75rem;">
                            <!-- Chapter Title header -->
                            <div class="flex-between" style="border-bottom: 1px solid var(--border-glass); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                                <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary);">
                                    Chapter {{ $loop->iteration }}: {{ $chapter->title }}
                                </h3>
                                <span class="badge badge-secondary" style="font-size: 0.7rem;">{{ $chapter->lessons->count() }} Lessons</span>
                            </div>

                            <!-- Lessons inside this chapter -->
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.5rem;">
                                @if($chapter->lessons->isEmpty())
                                    <div style="padding: 1rem; text-align: center; font-size: 0.88rem; color: var(--text-muted); background: rgba(0,0,0,0.1); border-radius: var(--radius-md);">
                                        No lessons in this chapter yet.
                                    </div>
                                @else
                                    @foreach($chapter->lessons as $lesson)
                                        <div class="flex-between" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-glass); border-radius: var(--radius-sm); padding: 0.75rem 1rem;">
                                            <div class="flex-align gap-2">
                                                <i class="ph ph-play-circle" style="color: var(--secondary); font-size: 1.15rem;"></i>
                                                <span style="font-size: 0.92rem; font-weight: 600;">{{ $lesson->title }}</span>
                                            </div>
                                            <div class="flex-align gap-2">
                                                <span class="text-muted" style="font-size: 0.8rem;">{{ $lesson->duration }}m</span>
                                                @if($lesson->is_free)
                                                    <span class="badge badge-success" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">Free Preview</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Button to slide toggle Add Lesson panel -->
                            <button class="btn btn-secondary btn-sm" onclick="toggleAddLessonForm({{ $chapter->id }})" style="width: 100%;">
                                <i class="ph ph-plus-circle"></i> Add Lesson to this Chapter
                            </button>

                            <!-- Add Lesson Hidden form (toggled) -->
                            <div id="add-lesson-form-{{ $chapter->id }}" class="glass-card" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background: rgba(0, 0, 0, 0.25); border-color: var(--border-glass-bright);">
                                <h4 style="margin-bottom: 1rem; font-size: 1rem;"><i class="ph ph-plus-circle"></i> Add New Lesson</h4>
                                <form action="{{ route('instructor.lesson.store', $chapter->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">Lesson Title</label>
                                        <input type="text" name="title" class="form-input" placeholder="e.g. Setting up models" required style="padding: 0.6rem 0.9rem;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Brief Description</label>
                                        <input type="text" name="description" class="form-input" placeholder="Short description of the lesson content" style="padding: 0.6rem 0.9rem;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Rich Tutorial Content (Markdown supported)</label>
                                        <textarea name="content" class="form-input" rows="4" placeholder="Write lesson tutorial content here..." required style="padding: 0.6rem 0.9rem; font-family: monospace;"></textarea>
                                    </div>
                                    <div class="grid-2" style="gap: 1rem;">
                                        <div class="form-group">
                                            <label class="form-label">Video Embed URL (Optional)</label>
                                            <input type="url" name="video_url" class="form-input" placeholder="e.g. https://www.youtube.com/embed/..." style="padding: 0.6rem 0.9rem;">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Duration (Minutes)</label>
                                            <input type="number" name="duration" class="form-input" placeholder="10" required min="1" style="padding: 0.6rem 0.9rem;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="flex-align gap-2" style="cursor: pointer;">
                                            <input type="checkbox" name="is_free" value="1" style="accent-color: var(--primary); width: 16px; height: 16px;">
                                            <span class="text-muted" style="font-size: 0.88rem;">Allow free preview (non-enrolled visitors can watch/read this)</span>
                                        </label>
                                    </div>
                                    <div class="flex-between gap-2" style="margin-top: 1.5rem;">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAddLessonForm({{ $chapter->id }})">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Save Lesson</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Side: Sticky Forms to add Chapter -->
        <div class="glass-card" style="position: sticky; top: 110px; padding: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.25rem; line-height: 1.2;"><i class="ph ph-folder-open"></i> Add Chapter</h3>
            <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.5rem;">Chapters define the structure of your course. Inside them, you can create modular lessons.</p>
            
            <form action="{{ route('instructor.chapter.store', $course->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="ch_title" class="form-label">Chapter Title</label>
                    <input type="text" name="title" id="ch_title" class="form-input" placeholder="e.g. Getting Started with Routes" required style="padding: 0.6rem 1rem;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                    <i class="ph ph-plus-circle"></i> Create Chapter
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function toggleAddLessonForm(chapterId) {
        const formDiv = document.getElementById('add-lesson-form-' + chapterId);
        if (formDiv.style.display === 'none') {
            formDiv.style.display = 'block';
        } else {
            formDiv.style.display = 'none';
        }
    }
</script>
@endsection
