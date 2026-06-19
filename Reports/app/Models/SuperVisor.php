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
    public $incrementing = true;                // 👈 here
    protected $keyType = 'int';                  // 👈 here

    protected $fillable = [
        'SuperVisor_Name',
        'SuperVisor_Major',
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
}