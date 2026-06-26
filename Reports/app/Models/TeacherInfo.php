<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherInfo extends Model
{
    protected $table = 'teacher_infos';
    protected $primaryKey = 'Teacher_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'supervisor_id',
        'Teacher_Name',
        'Teacher_id',
        'school_id',
        'date',
        'teacher_qualify',
        'teacher_major',
    ];

    public function supervisor()
    {
        return $this->belongsTo(SuperVisor::class, 'supervisor_id', 'SuperVisor_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'School_ID');
    }

    public function grades()
    {
        return $this->hasOne(TeacherGrade::class, 'teacher_id', 'Teacher_id');
    }

    public function justification()
    {
        return $this->hasOne(TeacherJustification::class, 'teacher_id', 'Teacher_id');
    }
}
