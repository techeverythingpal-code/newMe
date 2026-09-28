<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';
    protected $primaryKey = 'School_ID';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'School_ID',
        'SchoolName',
        'directorate_id',
    ];

    public function directorate()
    {
        return $this->belongsTo(Directorate::class, 'directorate_id', 'Directorate_id');
    }

    public function teachers()
    {
        return $this->hasMany(TeacherInfo::class, 'school_id', 'School_ID');
    }
}