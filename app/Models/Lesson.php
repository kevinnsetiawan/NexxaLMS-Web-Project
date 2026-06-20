<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'content', 'video_url', 'duration', 'chapter_id', 'sort_order', 'is_free'])]
class Lesson extends Model
{
    use HasFactory;

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function completedByStudents()
    {
        return $this->belongsToMany(User::class, 'completed_lessons', 'lesson_id', 'student_id')->withTimestamps();
    }
}
