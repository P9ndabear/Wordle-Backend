<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'user_id');
    }

    public function opponentGames()
    {
        return $this->hasMany(Game::class, 'opponent_id');
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted');
    }

    public function preferences()
    {
        return $this->hasMany(Preference::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }

    public function allFriends()
    {
        $friends1 = $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')->get();
        $friends2 = $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->wherePivot('status', 'accepted')->get();
        return $friends1->merge($friends2);
    }

    // Method to get total wins all time
    public function winsAllTime()
    {
        return $this->hasMany(Game::class, 'winner_id')
                    ->where('status', 'finished')
                    ->count();
    }

    // Method to get wins today
    public function winsToday()
    {
        return $this->hasMany(Game::class, 'winner_id')
                    ->where('status', 'finished')
                    ->whereDate('updated_at', today())
                    ->count();
    }

    // Method to get wins this week
    public function winsThisWeek()
    {
        return $this->hasMany(Game::class, 'winner_id')
                    ->where('status', 'finished')
                    ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count();
    }

    public function getPreference($key)
    {
        return optional($this->preferences()->where('key', $key)->first())->value;
    }

    public function setPreference($key, $value)
    {
        $this->preferences()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
