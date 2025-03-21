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
        ];
    }
    
    /**
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->where('name', $role)->isNotEmpty();
        }
        
        return !!$role->intersect($this->roles)->count();
    }
    
    /**
     * Get the contacts that belong to the user.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    
    /**
     * Get the companies that belong to the user.
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
    
    /**
     * Get the deals that belong to the user.
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
    
    /**
     * Get the tasks assigned to the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    /**
     * Get the tasks created by the user.
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    
    /**
     * Get the chat rooms that the user is part of.
     */
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class)
            ->withPivot('last_read_at')
            ->withTimestamps();
    }
    
    /**
     * Get messages sent by the user.
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
    
    /**
     * Get unread messages for the user.
     */
    public function unreadMessages()
    {
        return ChatMessage::whereHas('chatRoom', function($query) {
            $query->whereHas('users', function($q) {
                $q->where('users.id', $this->id);
            });
        })
        ->where('user_id', '!=', $this->id)
        ->where('is_read', false)
        ->orderBy('created_at', 'desc');
    }
}
