<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Courses exploration
Route::get('/', [CourseController::class, 'index'])->name('courses.index');
Route::get('/course/{slug}', [CourseController::class, 'show'])->name('courses.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Session Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Student Classroom and Dashboard Routes
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/course/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/classroom/{slug}/lesson/{lessonId}', [StudentController::class, 'classroom'])->name('student.classroom');
    Route::post('/lesson/{lessonId}/complete', [StudentController::class, 'completeLesson'])->name('student.lesson.complete');
    
    // Quiz assessment taker
    Route::get('/quiz/{quizId}', [StudentController::class, 'showQuiz'])->name('student.quiz.show');
    Route::post('/quiz/{quizId}/submit', [StudentController::class, 'submitQuiz'])->name('student.quiz.submit');
    Route::get('/quiz/results/{submissionId}', [StudentController::class, 'quizResults'])->name('student.quiz.results');

    // Instructor Management Routes
    Route::get('/instructor', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
    Route::get('/instructor/course/create', [InstructorController::class, 'createCourse'])->name('instructor.course.create');
    Route::post('/instructor/course/store', [InstructorController::class, 'storeCourse'])->name('instructor.course.store');
    Route::get('/instructor/course/{id}/builder', [InstructorController::class, 'courseBuilder'])->name('instructor.course.builder');
    Route::get('/instructor/course/{id}/edit', [InstructorController::class, 'editCourse'])->name('instructor.course.edit');
    Route::put('/instructor/course/{id}/update', [InstructorController::class, 'updateCourse'])->name('instructor.course.update');
    Route::delete('/instructor/course/{id}/delete', [InstructorController::class, 'destroyCourse'])->name('instructor.course.destroy');
    Route::post('/instructor/course/{courseId}/chapter/store', [InstructorController::class, 'storeChapter'])->name('instructor.chapter.store');
    Route::post('/instructor/chapter/{chapterId}/lesson/store', [InstructorController::class, 'storeLesson'])->name('instructor.lesson.store');

    // Platform Administrative Routes
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/category/store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
});

