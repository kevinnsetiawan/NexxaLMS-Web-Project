<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isInstructor()) {
            return redirect('/');
        }

        // Get courses created by this instructor
        $courses = Course::where('instructor_id', $user->id)
            ->with(['enrollments', 'category'])
            ->get();

        // Calculate statistics
        $totalStudents = 0;
        $totalEarnings = 0;

        foreach ($courses as $course) {
            $enrollmentsCount = $course->enrollments->count();
            $totalStudents += $enrollmentsCount;
            $totalEarnings += $enrollmentsCount * $course->price;
        }

        return view('instructor.dashboard', compact('courses', 'totalStudents', 'totalEarnings'));
    }

    public function createCourse()
    {
        $categories = Category::all();
        return view('instructor.create-course', compact('categories'));
    }

    public function storeCourse(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
        ]);

        $course = Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(1000, 9999),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'instructor_id' => $user->id,
            'level' => $request->level,
            'price' => $request->price,
            'image_url' => $request->image_url ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop',
            'status' => 'published', // Publish instantly for ease of demo
        ]);

        return redirect()->route('instructor.course.builder', $course->id)->with('success', 'Course created! Now add chapters and lessons.');
    }

    public function courseBuilder($id)
    {
        $course = Course::with('chapters.lessons')->findOrFail($id);
        
        // Ensure instructor owns this course
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        return view('instructor.builder', compact('course'));
    }

    public function storeChapter(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $sortOrder = $course->chapters()->count() + 1;

        Chapter::create([
            'title' => $request->title,
            'course_id' => $course->id,
            'sort_order' => $sortOrder,
        ]);

        return back()->with('success', 'Chapter added successfully!');
    }

    public function storeLesson(Request $request, $chapterId)
    {
        $chapter = Chapter::with('course')->findOrFail($chapterId);
        
        if ($chapter->course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'video_url' => ['nullable', 'url'],
            'duration' => ['required', 'integer', 'min:1'],
        ]);

        $sortOrder = $chapter->lessons()->count() + 1;

        Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'duration' => $request->duration,
            'chapter_id' => $chapter->id,
            'sort_order' => $sortOrder,
            'is_free' => $request->has('is_free'),
        ]);

        return back()->with('success', 'Lesson added successfully!');
    }

    public function editCourse($id)
    {
        $course = Course::findOrFail($id);
        
        // Ensure instructor owns this course
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('instructor.edit-course', compact('course', 'categories'));
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        // Ensure instructor owns this course
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'url'],
        ]);

        $course->update([
            'title' => $request->title,
            'slug' => $course->title !== $request->title ? Str::slug($request->title) . '-' . rand(1000, 9999) : $course->slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'level' => $request->level,
            'price' => $request->price,
            'image_url' => $request->image_url ?? $course->image_url,
        ]);

        return redirect()->route('instructor.dashboard')->with('success', 'Course updated successfully!');
    }

    public function destroyCourse($id)
    {
        $course = Course::findOrFail($id);
        
        // Ensure instructor owns this course
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $course->delete();

        return redirect()->route('instructor.dashboard')->with('success', 'Course deleted successfully!');
    }
}
