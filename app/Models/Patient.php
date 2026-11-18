<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'medical_record_number',
        'name',
        'gender',
        'birth_date',
        'address',
        'phone',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exerciseHistories(): HasMany
    {
        return $this->hasMany(ExerciseHistory::class);
    }
}
