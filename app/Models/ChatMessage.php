<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'is_read',
        'chat_room_id',
        'user_id',
        'attachment',
        'attachment_type'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];
    
    /**
     * Get the user who sent this message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the chat room this message belongs to.
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
    
    /**
     * Check if the message has an attachment.
     */
    public function hasAttachment()
    {
        return !is_null($this->attachment);
    }
    
    /**
     * Check if the attachment is an image.
     */
    public function isImageAttachment()
    {
        return $this->hasAttachment() && str_starts_with($this->attachment_type, 'image/');
    }
    
    /**
     * Check if the attachment is a PDF.
     */
    public function isPdfAttachment()
    {
        return $this->hasAttachment() && $this->attachment_type === 'application/pdf';
    }
}
