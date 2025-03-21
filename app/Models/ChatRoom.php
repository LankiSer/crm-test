<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_group',
        'created_by'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_group' => 'boolean',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'unread_count',
    ];
    
    /**
     * Get the user who created this chat room.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the users that are part of this chat room.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('last_read_at')
            ->withTimestamps();
    }
    
    /**
     * Get the messages for this chat room.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
    
    /**
     * Get the count of unread messages for the authenticated user.
     */
    public function getUnreadCountAttribute()
    {
        if (!Auth::check()) {
            return 0;
        }
        
        $userId = Auth::id();
        $pivot = $this->users()->where('user_id', $userId)->first()->pivot ?? null;
        
        if (!$pivot || !$pivot->last_read_at) {
            return $this->messages()->where('user_id', '!=', $userId)->count();
        }
        
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('created_at', '>', $pivot->last_read_at)
            ->count();
    }
    
    /**
     * Mark all messages in this room as read for the authenticated user.
     */
    public function markAsRead()
    {
        if (Auth::check()) {
            $this->users()->updateExistingPivot(Auth::id(), [
                'last_read_at' => now()
            ]);
        }
    }
}
