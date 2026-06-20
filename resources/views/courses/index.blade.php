@extends('layouts.app')

@section('content')
<div class="container animate-fade-in" style="margin-top: 3rem;">
    <!-- Hero / Title -->
    <div style="text-align: center; margin-bottom: 4rem;">
        <h1 class="gradient-text" style="font-size: 3.5rem; margin-bottom: 0.5rem; font-weight: 800;">Discover Your Next Skill</h1>
        <p class="text-muted" style="font-size: 1.15rem; max-width: 600px; margin: 0 auto;">Learn from industry professionals with video tutorials, rich articles, and interactive quizzes.</p>
    </div>

    <!-- Search & Filter Controls -->
    <div class="glass-card" style="padding: 1.5rem; margin-bottom: 3rem;">
        <form action="{{ route('courses.index') }}" method="GET" class="flex-between gap-3" style="flex-wrap: wrap;">
            <!-- Category Tabs -->
            <div class="flex-align gap-2" style="flex-wrap: wrap;">
                <a href="{{ route('courses.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-secondary' }}">
                    All Categories
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('courses.index', ['category' => $category->slug, 'search' => request('search')]) }}" 
                       class="btn btn-sm {{ request('category') == $category->slug ? 'btn-primary' : 'btn-secondary' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Search input -->
            <div class="flex-align gap-2" style="flex-grow: 1; max-width: 450px; width: 100%;">
                <!-- preserve category filter in search request -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="search" class="form-input" placeholder="Search courses..." value="{{ request('search') }}" style="padding: 0.6rem 1rem;">
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.65rem 1.25rem;">
                    <i class="ph ph-magnifying-glass"></i> Search
                </button>
            </div>
        </form>
    </div>

    <!-- Courses Grid -->
    @if($courses->isEmpty())
        <div style="text-align: center; margin: 5rem 0; color: var(--text-muted);">
            <i class="ph ph-warning-circle" style="font-size: 3rem; margin-bottom: 1rem; color: var(--warning);"></i>
            <h3>No courses found</h3>
            <p>Try clearing your filters or searching for something else.</p>
        </div>
    @else
        <div class="grid-3">
            @foreach($courses as $course)
                <div class="glass-card flex-between" style="flex-direction: column; align-items: stretch; height: 100%; padding: 1.5rem;">
                    <div>
                        <!-- Course Image -->
                        <img src="{{ $course->image_url ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop' }}" 
                             alt="{{ $course->title }}" 
                             class="course-card-img">
                        
                        <!-- Badges and Info -->
                        <div class="flex-between" style="margin-bottom: 0.75rem;">
                            <span class="badge badge-secondary">{{ $course->category->name }}</span>
                            <span class="badge badge-primary">{{ $course->level }}</span>
                        </div>
                        
                        <!-- Title & Description -->
                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; line-height: 1.3; font-weight: 700;">{{ $course->title }}</h3>
                        <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 1.5rem; line-height: 1.5;">
                            {{ Str::limit($course->description, 100) }}
                        </p>
                    </div>

                    <!-- Footer Row: Instructor, Price, and Action -->
                    <div style="border-top: 1px solid var(--border-glass); padding-top: 1rem; margin-top: auto;">
                        <div class="flex-between">
                            <div class="flex-align gap-2">
                                <img src="{{ $course->instructor->avatar }}" alt="{{ $course->instructor->name }}" style="width: 30px; height: 30px; border-radius: 50%;">
                                <span style="font-size: 0.85rem; font-weight: 500;">{{ $course->instructor->name }}</span>
                            </div>
                            
                            <div style="text-align: right;">
                                <div style="font-size: 1.15rem; font-weight: 800; color: var(--secondary);">
                                    @if($course->price == 0)
                                        Free
                                    @else
                                        ${{ number_format($course->price, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary" style="width: 100%; margin-top: 1.25rem; padding: 0.6rem 1rem;">
                            View Details <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
