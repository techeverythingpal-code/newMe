<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TeacherInfo;

class SuperVisor extends Authenticatable
{
    

use Notifiable;

    protected $table = 'super_visors';
    protected $primaryKey = 'SuperVisor_id';   // 👈 here
    public $incrementing = false;                // 👈 here
    protected $keyType = 'int';                  // 👈 here

    protected $fillable = [
        'SuperVisor_id',
        'SuperVisor_Name',
        'SuperVisor_Major',
        'directorate_id',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function teachers()
    {
        return $this->hasMany(TeacherInfo::class, 'supervisor_id', 'SuperVisor_id');
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class, 'directorate_id', 'Directorate_id');
    }
}