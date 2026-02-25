<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
    protected $table = 'emotions';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['name'];
}