<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherJustification extends Model
{
    protected $primaryKey = 'teacher_id';
    public $incrementing = false;

    protected $fillable = [
        'teacher_id',
        'strengths',
        'weaknesses',
        'recommendations',
        'preparer_opinion',
        'approver_notes',
    ];

    public function teacher()
    {
        return $this->belongsTo(TeacherInfo::class, 'teacher_id', 'Teacher_id');
    }
}