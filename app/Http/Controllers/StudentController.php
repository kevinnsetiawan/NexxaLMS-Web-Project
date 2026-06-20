<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isStudent()) {
            return redirect('/');
        }

        $enrollments = Enrollment::where('student_id', $user->id)
            ->with(['course.instructor', 'course.lessons'])
            ->get();

        return view('student.dashboard', compact('enrollments'));
    }

    public function classroom($courseSlug, $lessonId)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        
        // Ensure user is enrolled
        $enrollment = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $currentLesson = Lesson::findOrFail($lessonId);
        
        // Load course chapters and lessons
        $course->load(['chapters.lessons' => function($query) {
            $query->orderBy('sort_order');
        }]);

        // Get completed lessons list for this student
        $completedLessonIds = $user->completedLessons()->pluck('lesson_id')->toArray();

        return view('student.classroom', compact('course', 'currentLesson', 'completedLessonIds', 'enrollment'));
    }

    public function completeLesson($lessonId)
    {
        $user = Auth::user();
        $lesson = Lesson::findOrFail($lessonId);
        $course = $lesson->chapter->course;

        // Toggle completion
        if ($user->completedLessons()->where('lesson_id', $lessonId)->exists()) {
            $user->completedLessons()->detach($lessonId);
        } else {
            $user->completedLessons()->attach($lessonId);
        }

        // Recalculate course progress percentage
        $totalLessons = $course->lessons()->count();
        if ($totalLessons > 0) {
            $completedCount = $user->completedLessons()
                ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
                ->count();
            
            $progress = round(($completedCount / $totalLessons) * 100);

            // Update enrollment progress
            Enrollment::where('student_id', $user->id)
                ->where('course_id', $course->id)
                ->update([
                    'progress_percentage' => $progress,
                    'status' => $progress >= 100 ? 'completed' : 'active'
                ]);
        }

        return back()->with('success', 'Progress updated!');
    }

    public function showQuiz($quizId)
    {
        $quiz = Quiz::with('questions.options', 'lesson.chapter.course')->findOrFail($quizId);
        $course = $quiz->lesson->chapter->course;

        // Ensure student is enrolled
        Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        return view('student.quiz', compact('quiz', 'course'));
    }

    public function submitQuiz(Request $request, $quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::findOrFail($quizId);
        $questions = $quiz->questions;
        
        $totalQuestions = $questions->count();
        if ($totalQuestions === 0) {
            return redirect()->back()->withErrors('Quiz does not have questions.');
        }

        $correctAnswers = 0;
        $answers = $request->input('answers', []);

        foreach ($questions as $question) {
            $selectedOptionId = $answers[$question->id] ?? null;
            if ($selectedOptionId) {
                $option = Option::where('question_id', $question->id)
                    ->where('id', $selectedOptionId)
                    ->first();
                
                if ($option && $option->is_correct) {
                    $correctAnswers++;
                }
            }
        }

        $score = round(($correctAnswers / $totalQuestions) * 100);
        $passed = $score >= $quiz->passing_score;

        // Save submission attempt
        $submission = Submission::create([
            'student_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
        ]);

        return redirect()->route('student.quiz.results', $submission->id);
    }

    public function quizResults($submissionId)
    {
        $submission = Submission::with('quiz.lesson.chapter.course')->findOrFail($submissionId);
        
        // Ensure student owns the submission
        if ($submission->student_id !== Auth::id()) {
            abort(403);
        }

        return view('student.quiz-result', compact('submission'));
    }
}
