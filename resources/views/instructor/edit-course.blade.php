@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem; max-width: 700px;">
    <div class="glass-card" style="box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('instructor.dashboard') }}" style="color: var(--secondary); font-size: 0.9rem; font-weight: 600;"><i class="ph ph-arrow-left"></i> Back to Hub</a>
            <h2 class="gradient-text" style="margin-top: 1rem; margin-bottom: 0.25rem;">Edit Course</h2>
            <p class="text-muted" style="font-size: 0.9rem;">Modify the fields below to update the course configuration.</p>
        </div>

        @if($errors->any())
            <div style="background: rgba(255, 50, 50, 0.1); border: 1px solid var(--danger); color: hsl(355, 95%, 75%); padding: 0.85rem 1.2rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.88rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('instructor.course.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">Course Title</label>
                <input type="text" name="title" id="title" class="form-input" placeholder="e.g. Master React in 30 Days" value="{{ old('title', $course->title) }}" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description / Summary</label>
                <textarea name="description" id="description" class="form-input" rows="4" placeholder="Describe what students will learn in this course..." required>{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-input" style="appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill=%22white%22 height=%2224%22 viewBox=%220 0 24 24%22 width=%2224%22 xmlns=%22http://www.w3.org/2000/svg%22><path d=%22M7 10l5 5 5-5z%22/></svg>'); background-repeat: no-repeat; background-position: right 10px center;" required>
                        <option value="" disabled>Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="level" class="form-label">Difficulty Level</label>
                    <select name="level" id="level" class="form-input" required>
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="image_url" class="form-label">Cover Image URL (Optional)</label>
                <input type="url" name="image_url" id="image_url" class="form-input" placeholder="https://images.unsplash.com/..." value="{{ old('image_url', $course->image_url) }}">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 0.9rem;">
                <i class="ph-bold ph-floppy-disk"></i> Update Course Details
            </button>
        </form>
    </div>
</div>
@endsection
