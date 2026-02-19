<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class film extends Model
{
    protected $table = 'film';// dis la table sur laquel il faut travailler

    protected $primaryKey = 'idFil';//la clé primaire

    public $timestamps = false;//si il y a un timestamp ou non parmis nos champs (pas le cas pour cette tbale)

    protected $fillable = [
        'nomFil',
        'datFil',
        'afiFil',
        'desFil',
        'typeFil',
        'malVoyEnt',
        'banAnn'
        // le nom de tout nos champs pour dire qu'il sont remplissable par un fomrulaire (eite les fille mass assignement)
    ];
}
