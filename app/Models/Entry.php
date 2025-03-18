<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'amount', 'entry_date','origim'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}