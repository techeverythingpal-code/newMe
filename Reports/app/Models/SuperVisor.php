<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperVisor extends Model
{
    protected $fillable = ['SuperVisor_Name', 'SuperVisor_Major'];

public function teachers()
{
    return $this->hasMany(TeacherInfo::class, 'supervisor_id', 'SuperVisor_id');
}
}
