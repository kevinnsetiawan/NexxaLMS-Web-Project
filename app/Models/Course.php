<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'slug', 'description', 'image_url', 'price', 'instructor_id', 'category_id', 'status', 'level'])]
class Course extends Model
{
    use HasFactory;

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('sort_order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Easy access to all lessons in the course
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }
}
