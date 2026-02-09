<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $table = 'types';
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    protected $fillable = ['type_name'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'type_id', 'type_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'type_id', 'type_id');
    }
}

