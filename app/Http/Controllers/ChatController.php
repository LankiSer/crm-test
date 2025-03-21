<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Display a listing of the chat rooms.
     */
    public function index()
    {
        $chatRooms = Auth::user()->chatRooms()
            ->with(['users', 'messages' => function($query) {
                $query->latest()->with('user')->limit(5);
            }])
            ->get();
        
        return view('chat.index', compact('chatRooms'));
    }
    
    /**
     * Display a specific chat room with messages.
     */
    public function show(ChatRoom $chatRoom)
    {
        // Check if user is part of the chat room
        if (!$chatRoom->users->contains(Auth::id())) {
            return redirect()->route('chat.index')->with('error', 'You do not have access to this chat room.');
        }
        
        // Mark the chat room as read for the current user
        $chatRoom->markAsRead();
        
        // Get all chat rooms for the sidebar
        $chatRooms = Auth::user()->chatRooms()
            ->with(['users', 'messages' => function($query) {
                $query->latest()->with('user')->limit(5);
            }])
            ->get();
        
        // Set the current room for the view
        $currentRoom = $chatRoom;
        
        return view('chat.show', compact('currentRoom', 'chatRooms'));
    }
    
    /**
     * Create a new chat room.
     */
    public function createRoom(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'is_group' => 'required|boolean',
            'user_ids' => 'required_if:is_group,true|array',
            'user_ids.*' => 'exists:users,id',
            'user_id' => 'required_if:is_group,false|exists:users,id',
            'description' => 'nullable|string',
        ]);
        
        // If it's a direct chat, check if one already exists
        if (!$request->is_group) {
            $userId = $request->user_id;
            
            // Check if a direct chat already exists between these users
            $existingRoom = $this->findDirectChatRoom(Auth::id(), $userId);
            
            if ($existingRoom) {
                return redirect()->route('chat.show', $existingRoom);
            }
            
            // If name is not provided, use recipient's name
            if (empty($request->name)) {
                $recipient = User::find($userId);
                $roomName = $recipient->name;
            } else {
                $roomName = $request->name;
            }
        } else {
            // Group chat requires a name
            if (empty($request->name)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['name' => 'Group name is required']);
            }
            
            $roomName = $request->name;
        }
        
        // Create the chat room
        $chatRoom = ChatRoom::create([
            'name' => $roomName,
            'description' => $request->description,
            'is_group' => $request->is_group,
            'created_by' => Auth::id(),
        ]);
        
        // Add the current user
        $chatRoom->users()->attach(Auth::id(), ['last_read_at' => now()]);
        
        // Add other users
        if ($request->is_group) {
            if (!empty($request->user_ids)) {
                $chatRoom->users()->attach($request->user_ids);
            }
        } else {
            // Add the recipient
            $chatRoom->users()->attach($userId);
        }
        
        // Create initial system message for group chats
        if ($request->is_group) {
            ChatMessage::create([
                'message' => Auth::user()->name . ' created this group',
                'chat_room_id' => $chatRoom->id,
                'user_id' => Auth::id(),
                'is_read' => true,
            ]);
        }
        
        return redirect()->route('chat.show', $chatRoom)
            ->with('success', 'Chat created successfully.');
    }
    
    /**
     * Send a message in a chat room.
     */
    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'message' => 'required_without_all:attachment,image|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);
        
        // Check if user is part of the chat room
        if (!$chatRoom->users->contains(Auth::id())) {
            return redirect()->route('chat.index')
                ->with('error', 'You do not have access to this chat room.');
        }
        
        // Handle file upload if present
        $attachmentPath = null;
        $attachmentType = null;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $attachmentPath = $file->storeAs('chat_attachments', $fileName, 'public');
            $attachmentType = $file->getMimeType();
        } elseif ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $attachmentPath = $file->storeAs('chat_images', $fileName, 'public');
            $attachmentType = $file->getMimeType();
        }
        
        // Create the message
        $message = ChatMessage::create([
            'message' => $request->message ?? '',
            'chat_room_id' => $chatRoom->id,
            'user_id' => Auth::id(),
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);
        
        // Mark the room as read for the current user
        $chatRoom->markAsRead();
        
        return redirect()->route('chat.show', $chatRoom);
    }
    
    /**
     * Add members to a group chat.
     */
    public function addMembers(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        
        // Check if user is part of the chat room and it's a group
        if (!$chatRoom->users->contains(Auth::id()) || !$chatRoom->is_group) {
            return redirect()->route('chat.index')
                ->with('error', 'You do not have access to this chat room or it is not a group chat.');
        }
        
        // Add new members
        $addedUsers = [];
        foreach ($request->user_ids as $userId) {
            if (!$chatRoom->users->contains($userId)) {
                $chatRoom->users()->attach($userId);
                $addedUsers[] = User::find($userId);
            }
        }
        
        // Create a system message for each added user
        foreach ($addedUsers as $user) {
            ChatMessage::create([
                'message' => Auth::user()->name . ' added ' . $user->name . ' to the group',
                'chat_room_id' => $chatRoom->id,
                'user_id' => Auth::id(),
                'is_read' => true,
            ]);
        }
        
        return redirect()->route('chat.show', $chatRoom)
            ->with('success', count($addedUsers) . ' member(s) added to the group.');
    }
    
    /**
     * Leave a group chat.
     */
    public function leaveGroup(ChatRoom $chatRoom)
    {
        // Check if user is part of the chat room and it's a group
        if (!$chatRoom->users->contains(Auth::id()) || !$chatRoom->is_group) {
            return redirect()->route('chat.index')
                ->with('error', 'You do not have access to this chat room or it is not a group chat.');
        }
        
        // Create a system message
        ChatMessage::create([
            'message' => Auth::user()->name . ' left the group',
            'chat_room_id' => $chatRoom->id,
            'user_id' => Auth::id(),
            'is_read' => true,
        ]);
        
        // Remove the user from the group
        $chatRoom->users()->detach(Auth::id());
        
        return redirect()->route('chat.index')
            ->with('success', 'You have left the group.');
    }
    
    /**
     * Delete a direct chat room.
     */
    public function deleteChat(ChatRoom $chatRoom)
    {
        // Check if user is part of the chat room and it's not a group
        if (!$chatRoom->users->contains(Auth::id()) || $chatRoom->is_group) {
            return redirect()->route('chat.index')
                ->with('error', 'You do not have access to this chat room or it is a group chat.');
        }
        
        // For now, just remove the user from the chat
        $chatRoom->users()->detach(Auth::id());
        
        // If no users left, delete the chat room
        if ($chatRoom->users()->count() === 0) {
            // Delete attachments first
            foreach ($chatRoom->messages as $message) {
                if ($message->attachment) {
                    Storage::disk('public')->delete($message->attachment);
                }
            }
            
            $chatRoom->delete();
        }
        
        return redirect()->route('chat.index')
            ->with('success', 'Chat deleted successfully.');
    }
    
    /**
     * Find a direct chat room between two users.
     */
    private function findDirectChatRoom($user1Id, $user2Id)
    {
        $user1Rooms = ChatRoom::whereHas('users', function($query) use ($user1Id) {
            $query->where('users.id', $user1Id);
        })
        ->where('is_group', false)
        ->pluck('id');
        
        if ($user1Rooms->isEmpty()) {
            return null;
        }
        
        return ChatRoom::whereIn('id', $user1Rooms)
            ->whereHas('users', function($query) use ($user2Id) {
                $query->where('users.id', $user2Id);
            })
            ->where('is_group', false)
            ->first();
    }
}
