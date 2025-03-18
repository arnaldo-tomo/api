<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalContribution extends Model
{
    use HasFactory;

    protected $fillable = ['goal_id', 'user_id', 'amount', 'contribution_date'];

    public function goal()
    {
        return $this->belongsTo(FinancialGoal::class, 'goal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}