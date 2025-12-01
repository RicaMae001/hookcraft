<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    protected $table = 'chat_histories';

    protected $fillable = [
        'user_id',
        'session_id',
        'message',
        'sender',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope to get messages by session
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId)
                     ->orderBy('created_at', 'asc');
    }

    // Scope to get user messages only
    public function scopeUserMessages($query)
    {
        return $query->where('sender', 'user');
    }

    // Scope to get bot messages only
    public function scopeBotMessages($query)
    {
        return $query->where('sender', 'bot');
    }

    // Get chat history for authenticated user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}