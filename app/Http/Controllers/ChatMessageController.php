<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatMessageController extends Controller
{
    /**
     * Store a new message.
     */
    public function store(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'message' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        // Ensure user is part of the chat room
        if (!$chatRoom->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $messageData = [
            'message' => $request->message,
            'user_id' => Auth::id(),
            'chat_room_id' => $chatRoom->id,
            'is_read' => false,
        ];
        
        // Handle file attachment if present
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat_attachments', $filename, 'public');
            
            $messageData['attachment'] = $path;
            $messageData['attachment_type'] = $file->getMimeType();
        }
        
        $message = ChatMessage::create($messageData);
        
        return response()->json([
            'message' => $message->message,
            'user' => [
                'id' => Auth::id(),
                'name' => Auth::user()->name
            ],
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            'has_attachment' => $message->hasAttachment(),
            'attachment_url' => $message->hasAttachment() ? Storage::url($message->attachment) : null,
            'attachment_type' => $message->attachment_type,
        ]);
    }
    
    /**
     * Mark messages as read for the authenticated user in a specific chat room.
     */
    public function markAsRead(ChatRoom $chatRoom)
    {
        // Ensure user is part of the chat room
        if (!$chatRoom->users->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Mark all messages not from the current user as read
        ChatMessage::where('chat_room_id', $chatRoom->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        // Update last_read_at timestamp
        $chatRoom->markAsRead();
        
        return response()->json(['success' => true]);
    }
} 