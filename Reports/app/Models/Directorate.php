<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directorate extends Model
{
    protected $table = 'directorates';
    protected $primaryKey = 'Directorate_id';  // 👈 this is the fix
    public $incrementing = false;               // 👈 because you set the ID manually
    protected $keyType = 'int';

    protected $fillable = [
        'Directorate_id',
        'Directorate_Name',
    ];

    public function schools()
    {
        return $this->hasMany(School::class, 'directorate_id', 'Directorate_id');
    }
}