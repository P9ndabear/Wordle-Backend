<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'user_id',
        'opponent_id',
        'winner_id',
        'word',
        'result',
        'status',
        'started_at',
        'finished_at',
        'current_turn_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function opponent()
    {
        return $this->belongsTo(User::class, 'opponent_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }
}
