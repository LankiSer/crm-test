<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'notes',
        'company_id',
        'user_id',
    ];
    
    /**
     * Get the user that owns the contact.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the company that the contact belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the deals associated with the contact.
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
    
    /**
     * Get the tasks associated with the contact.
     */
    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }
    
    /**
     * Get the contact's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
