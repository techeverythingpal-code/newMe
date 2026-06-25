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

    // Returns ['label' => '...', 'color' => 'green|blue|yellow|orange|red']
    public function getAssessmentAttribute(): array
    {
        $total = $this->total ?? 0;

        return match (true) {
            $total >= 85 => ['label' => 'ممتاز',      'color' => 'green'],
            $total >= 75 => ['label' => 'جيد جداً',    'color' => 'blue'],
            $total >= 65 => ['label' => 'جيد',         'color' => 'yellow'],
            $total >= 55 => ['label' => 'متوسط',       'color' => 'orange'],
            default      => ['label' => 'ضعيف/مقبول', 'color' => 'red'],
        };
    }
}
