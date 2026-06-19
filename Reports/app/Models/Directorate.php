<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directorate extends Model
{
    protected $fillable = ['Directorate_id', 'Directorate_Name'];

public function schools()
{
    return $this->hasMany(School::class, 'directorate_id', 'Directorate_id');
}
}
