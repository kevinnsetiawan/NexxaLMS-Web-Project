<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Platform wide metrics
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();

        $categories = Category::withCount('courses')->get();

        return view('admin.dashboard', compact(
            'totalStudents', 
            'totalInstructors', 
            'totalCourses', 
            'totalEnrollments',
            'categories'
        ));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?? 'folder',
        ]);

        return back()->with('success', 'Category created successfully!');
    }
}
