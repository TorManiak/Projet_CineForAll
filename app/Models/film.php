<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class film extends Model
{
    protected $table = 'film'; // car ta table s'appelle film

    protected $primaryKey = 'idFil';

    public $timestamps = false;

    protected $fillable = [
        'nomFil',
        'datFil',
        'afiFil',
        'desFil',
        'typeFil',
        'malVoyEnt',
        'banAnn'
    ];
}
