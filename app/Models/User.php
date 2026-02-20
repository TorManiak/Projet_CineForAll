<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'idUti';
    public $timestamps = false;

    protected $fillable = [
        'nomUti',
        'preUti',
        'mdpUti',
        'datInsUti',
        'mailUti',
        'idRolUti',
    ];

    protected $hidden = [
        'mdpUti',
    ];

    /**
     * IMPORTANT: Laravel Auth utilise ce champ comme "password".
     * Ici on renvoie mdpUti (même si tu ne fais pas de bcrypt).
     */
    public function getAuthPassword()
    {
        return $this->mdpUti;
    }
}
