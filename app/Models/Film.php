<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'film';
    protected $primaryKey = 'idFil';

    public $timestamps = false;
    protected $guarded = [];
}
