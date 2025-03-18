<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_amount',
        'initial_amount',
        'current_amount',
        'target_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contributions()
    {
        return $this->hasMany(GoalContribution::class, 'goal_id');
    }

    // Ao criar um novo objetivo, definir o montante atual como o montante inicial
    protected static function booted()
    {
        static::creating(function ($goal) {
            $goal->current_amount = $goal->initial_amount;
        });
    }
}