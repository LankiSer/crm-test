<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'address',
        'description',
        'user_id',
    ];
    
    /**
     * Get the user that owns the company.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the contacts for the company.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    
    /**
     * Get the deals for the company.
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
    
    /**
     * Get the tasks associated with the company.
     */
    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
