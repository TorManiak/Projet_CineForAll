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
     * Laravel doit utiliser mailUti comme identifiant
     */
    public function getAuthIdentifierName()
    {
        return 'mailUti';
    }

    /**
     * Laravel doit utiliser mdpUti comme mot de passe
     */
    public function getAuthPassword()
    {
        return $this->mdpUti;
    }
}
