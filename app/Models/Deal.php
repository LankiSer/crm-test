<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'value',
        'status',
        'expected_close_date',
        'description',
        'company_id',
        'contact_id',
        'user_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'expected_close_date' => 'date',
    ];
    
    /**
     * Get the user that owns the deal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the company that the deal belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the contact that the deal belongs to.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    
    /**
     * Get the tasks associated with the deal.
     */
    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }
    
    /**
     * Get all available deal statuses.
     */
    public static function statuses()
    {
        return [
            'new' => 'New',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal',
            'negotiation' => 'Negotiation',
            'closed_won' => 'Closed Won',
            'closed_lost' => 'Closed Lost',
        ];
    }
}
