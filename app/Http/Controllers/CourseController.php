<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Course::where('status', 'published')->with(['instructor', 'category']);

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Search Term
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $courses = $query->orderBy('created_at', 'desc')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('status', 'published')
            ->with(['instructor', 'category', 'chapters.lessons'])
            ->firstOrFail();

        $isEnrolled = false;
        $progress = 0;

        if (Auth::check()) {
            $enrollment = Enrollment::where('student_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();
            
            if ($enrollment) {
                $isEnrolled = true;
                $progress = $enrollment->progress_percentage;
            }
        }

        return view('courses.show', compact('course', 'isEnrolled', 'progress'));
    }

    public function enroll($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Please log in to enroll in this course.');
        }

        $user = Auth::user();
        if (!$user->isStudent()) {
            return back()->withErrors('Only student accounts can enroll in courses.');
        }

        $course = Course::findOrFail($id);

        // Check if already enrolled
        $existing = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return redirect()->route('student.classroom', [$course->slug, $course->chapters->first()->lessons->first()->id]);
        }

        // Enroll student
        $enrollment = Enrollment::create([
            'student_id' => $user->id,
            'course_id' => $course->id,
            'progress_percentage' => 0,
            'status' => 'active',
        ]);

        // Redirect to classroom (first lesson)
        $firstLesson = $course->lessons()->orderBy('lessons.id')->first();
        
        if ($firstLesson) {
            return redirect()->route('student.classroom', [$course->slug, $firstLesson->id]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Enrolled successfully!');
    }
}
