<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'amount',
        'emotion',
        'date',
        'user_id',
        'type_id',
        'category_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id', 'type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function emotion()
    {
        return $this->belongsTo(Emotion::class, 'emotion', 'id');
    }

    public static function emotions(): array
    {
        return [
            1 => 'Happy',
            2 => 'Stressed',
            3 => 'Bored',
            4 => 'Sad/Down',
            5 => 'Excited',
            6 => 'Frustrated',
            7 => 'Neutral',
            8 => 'Overwhelmed',
        ];
    }

    public function getEmotionLabelAttribute(): string
    {
        return self::emotions()[$this->emotion] ?? 'Unknown';
    }
}