<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'film';
    protected $primaryKey = 'idFil';

    public $timestamps = false;
    protected $guarded = [];

    public function langue()
    {
        return $this->belongsToMany(Langue::class, 'film_langue', 'idFil', 'idLan');
    }
}
