<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherGrade extends Model
{
    protected $table = 'teacher_grades';
    protected $primaryKey = 'teacher_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'teacher_id',
        'score1','score2','score3','score4','score5',
        'score6','score7','score8','score9','score10',
        'score11','score12','score13','score14','score15',
        'score16','score17','score18','score19','score20',
        'score21','score22','total',
    ];

    public function teacher()
    {
        return $this->belongsTo(TeacherInfo::class, 'teacher_id', 'Teacher_id');
    }
}