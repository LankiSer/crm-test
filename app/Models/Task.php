<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'user_id',
        'created_by',
        'taskable_id',
        'taskable_type',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
    ];
    
    /**
     * Get the user that the task is assigned to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the user that created the task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the parent taskable model (contact, company, deal, etc.).
     */
    public function taskable()
    {
        return $this->morphTo();
    }
    
    /**
     * Get all available task priorities.
     */
    public static function priorities()
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
        ];
    }
    
    /**
     * Get all available task statuses.
     */
    public static function statuses()
    {
        return [
            'not_started' => 'Not Started',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'deferred' => 'Deferred',
        ];
    }
}
