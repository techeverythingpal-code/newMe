<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TeacherInfo;

class SuperVisor extends Authenticatable
{
    use Notifiable;

    protected $table = 'super_visors';
    protected $primaryKey = 'SuperVisor_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'SuperVisor_id',
        'SuperVisor_Name',
        'SuperVisor_Major',
        'directorate_id',
        'role',
        'password',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'must_change_password' => 'boolean',
        ];
    }

    public function teachers()
    {
        return $this->hasMany(TeacherInfo::class, 'supervisor_id', 'SuperVisor_id');
    }

    public function directorate()
    {
        return $this->belongsTo(Directorate::class, 'directorate_id', 'Directorate_id');
    }
}