<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperVisor extends Authenticatable
{
    use Notifiable;

    protected $table = 'super_visors';
    protected $primaryKey = 'SuperVisor_id';

    protected $fillable = [
        'SuperVisor_Name',
        'SuperVisor_Major',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tell Laravel there's no 'email' field — we use SuperVisor_id
    public function getEmailForPasswordReset() 
    { 
        return $this->SuperVisor_id; 
    }
}