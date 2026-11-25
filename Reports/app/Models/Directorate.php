<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directorate extends Model
{
    protected $table = 'directorates';
    protected $primaryKey = 'Directorate_id';
    public $incrementing = false;               // the ID is set manually
    protected $keyType = 'int';

    protected $fillable = [
        'Directorate_id',
        'Directorate_Name',
        'address',
        'phone',
        'email',
        'manager_name',
        'notes',
    ];

    public function schools()
    {
        return $this->hasMany(School::class, 'directorate_id', 'Directorate_id');
    }

    public function supervisors()
    {
        return $this->hasMany(SuperVisor::class, 'directorate_id', 'Directorate_id');
    }
}