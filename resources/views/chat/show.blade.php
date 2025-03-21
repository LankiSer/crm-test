@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 h-100" style="height: calc(100vh - var(--header-height) - 40px);">
        <!-- Sidebar with contacts -->
        <div class="col-md-3 border-end" style="height: 100%;">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0">Chats</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <div class="dropdown d-inline">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newGroupModal">Create Group</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 bg-light" placeholder="Search chats...">
                </div>
            </div>
            <div class="chat-contacts overflow-auto" style="height: calc(100% - 120px);">
                @foreach($chatRooms as $room)
                    <a href="{{ route('chat.show', $room) }}" class="text-decoration-none text-dark">
                        <div class="chat-contact p-2 px-3 border-bottom d-flex align-items-center {{ $currentRoom->id == $room->id ? 'active' : '' }}">
                            <div class="position-relative">
                                @if($room->is_group)
                                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                @else
                                    @php
                                        $otherUser = $room->users->where('id', '!=', auth()->id())->first();
                                        $initials = $otherUser ? strtoupper(substr($otherUser->name, 0, 2)) : 'U';
                                        $colors = ['primary', 'success', 'warning', 'danger', 'info'];
                                        $colorIndex = $otherUser ? crc32($otherUser->id) % count($colors) : 0;
                                        $color = $colors[$colorIndex];
                                    @endphp
                                    <div class="rounded-circle bg-{{ $color }} text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        {{ $initials }}
                                    </div>
                                @endif
                                @if($room->unread_count > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $room->unread_count }}
                                    </span>
                                @endif
                            </div>
                            <div class="ms-3 flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 text-truncate">
                                        @if($room->is_group)
                                            {{ $room->name }}
                                        @else
                                            {{ $otherUser ? $otherUser->name : 'Unknown User' }}
                                        @endif
                                    </h6>
                                    @if($room->messages->isNotEmpty())
                                        <small class="text-muted ms-2">{{ $room->messages->sortByDesc('created_at')->first()->created_at->format('H:i') }}</small>
                                    @endif
                                </div>
                                <p class="m-0 text-truncate text-muted small">
                                    @if($room->messages->isNotEmpty())
                                        @php $lastMessage = $room->messages->sortByDesc('created_at')->first(); @endphp
                                        @if($lastMessage->user_id == auth()->id())
                                            You: {{ $lastMessage->message }}
                                        @else
                                            {{ $lastMessage->user->name }}: {{ $lastMessage->message }}
                                        @endif
                                    @else
                                        No messages yet
                                    @endif
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- Chat area -->
        <div class="col-md-9 d-flex flex-column" style="height: 100%;">
            <!-- Chat header -->
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if($currentRoom->is_group)
                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h5 class="m-0">{{ $currentRoom->name }}</h5>
                            <small class="text-muted">{{ $currentRoom->users->count() }} members</small>
                        </div>
                    @else
                        @php
                            $otherUser = $currentRoom->users->where('id', '!=', auth()->id())->first();
                            $initials = $otherUser ? strtoupper(substr($otherUser->name, 0, 2)) : 'U';
                            $colors = ['primary', 'success', 'warning', 'danger', 'info'];
                            $colorIndex = $otherUser ? crc32($otherUser->id) % count($colors) : 0;
                            $color = $colors[$colorIndex];
                        @endphp
                        <div class="rounded-circle bg-{{ $color }} text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            {{ $initials }}
                        </div>
                        <div>
                            <h5 class="m-0">{{ $otherUser ? $otherUser->name : 'Unknown User' }}</h5>
                            <small class="text-muted">{{ $otherUser && $otherUser->last_seen_at ? 'Last seen ' . $otherUser->last_seen_at->diffForHumans() : 'Offline' }}</small>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="dropdown d-inline">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if($currentRoom->is_group)
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#groupInfoModal"><i class="bi bi-info-circle me-2"></i> Group Info</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addMemberModal"><i class="bi bi-person-plus me-2"></i> Add Member</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Leave Group</a></li>
                            @else
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> View Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-bell me-2"></i> Mute Notifications</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i> Delete Chat</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Chat messages -->
            <div class="flex-grow-1 p-3 chat-messages overflow-auto" id="chat-messages" style="background-color: #f5f5f5;">
                @php
                    $messages = $currentRoom->messages()->with('user')->orderBy('created_at')->get();
                    $currentDate = null;
                @endphp
                
                @forelse($messages as $message)
                    @php
                        $messageDate = $message->created_at->format('Y-m-d');
                        $showDateDivider = $currentDate !== $messageDate;
                        $currentDate = $messageDate;
                    @endphp
                    
                    @if($showDateDivider)
                        <div class="text-center my-3">
                            <span class="badge bg-secondary">
                                @if($message->created_at->isToday())
                                    Today
                                @elseif($message->created_at->isYesterday())
                                    Yesterday
                                @else
                                    {{ $message->created_at->format('F j, Y') }}
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    @if($message->user_id == auth()->id())
                        <!-- Sent message -->
                        <div class="d-flex flex-row-reverse mb-3">
                            <div class="ms-2 d-none d-md-block">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 12px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="me-md-2">
                                <div class="bg-primary text-white p-3 rounded shadow-sm chat-message-bubble" style="max-width: 75%;">
                                    @if($message->attachment)
                                        <div class="mb-2">
                                            @if(Str::startsWith($message->attachment_type, 'image'))
                                                <img src="{{ asset('storage/' . $message->attachment) }}" class="img-fluid rounded" alt="Attachment">
                                            @elseif(Str::startsWith($message->attachment_type, 'application/pdf'))
                                                <div class="d-flex align-items-center p-2 bg-light rounded text-dark">
                                                    <i class="bi bi-file-pdf text-danger fs-4 me-2"></i>
                                                    <div>
                                                        <p class="m-0 small">{{ basename($message->attachment) }}</p>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $message->attachment) }}" class="ms-auto btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center p-2 bg-light rounded text-dark">
                                                    <i class="bi bi-file-earmark text-secondary fs-4 me-2"></i>
                                                    <div>
                                                        <p class="m-0 small">{{ basename($message->attachment) }}</p>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $message->attachment) }}" class="ms-auto btn btn-sm btn-outline-primary" download>
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <p class="m-0">{{ $message->message }}</p>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                    <small class="ms-1 text-primary">
                                        <i class="bi {{ $message->is_read ? 'bi-check2-all' : 'bi-check2' }}"></i>
                                    </small>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Received message -->
                        <div class="d-flex mb-3">
                            <div class="me-2 d-none d-md-block">
                                @php
                                    $initials = strtoupper(substr($message->user->name, 0, 2));
                                    $colorIndex = crc32($message->user->id) % count($colors);
                                    $color = $colors[$colorIndex];
                                @endphp
                                <div class="rounded-circle bg-{{ $color }} text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 12px;">
                                    {{ $initials }}
                                </div>
                            </div>
                            <div class="ms-md-0">
                                @if($currentRoom->is_group)
                                    <small class="text-{{ $color }}">{{ $message->user->name }}</small>
                                @endif
                                <div class="bg-white p-3 rounded shadow-sm chat-message-bubble" style="max-width: 75%;">
                                    @if($message->attachment)
                                        <div class="mb-2">
                                            @if(Str::startsWith($message->attachment_type, 'image'))
                                                <img src="{{ asset('storage/' . $message->attachment) }}" class="img-fluid rounded" alt="Attachment">
                                            @elseif(Str::startsWith($message->attachment_type, 'application/pdf'))
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="bi bi-file-pdf text-danger fs-4 me-2"></i>
                                                    <div>
                                                        <p class="m-0 small">{{ basename($message->attachment) }}</p>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $message->attachment) }}" class="ms-auto btn btn-sm btn-outline-secondary" target="_blank">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <i class="bi bi-file-earmark text-secondary fs-4 me-2"></i>
                                                    <div>
                                                        <p class="m-0 small">{{ basename($message->attachment) }}</p>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $message->attachment) }}" class="ms-auto btn btn-sm btn-outline-secondary" download>
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <p class="m-0">{{ $message->message }}</p>
                                </div>
                                <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center my-5 text-muted">
                        <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                        <p class="mt-3">No messages yet.</p>
                        <p>Send a message to start the conversation!</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Chat input -->
            <div class="p-3 border-top">
                <form class="d-flex align-items-center" action="{{ route('chat.message.send', $currentRoom) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-secondary rounded-circle me-2" style="width: 40px; height: 40px;" data-bs-toggle="dropdown">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <label class="dropdown-item" for="attachment">
                                    <i class="bi bi-file-earmark me-2"></i> Attach File
                                </label>
                                <input type="file" name="attachment" id="attachment" class="d-none" onchange="updateFileName(this)">
                            </li>
                            <li>
                                <label class="dropdown-item" for="image">
                                    <i class="bi bi-image me-2"></i> Image
                                </label>
                                <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="updateFileName(this)">
                            </li>
                        </ul>
                    </div>
                    <div class="input-group position-relative">
                        <input type="text" class="form-control" id="message-input" name="message" placeholder="Type a message..." autocomplete="off">
                        <button class="btn btn-primary" type="submit" id="send-button">
                            <i class="bi bi-send"></i>
                        </button>
                        <div id="attachment-preview" class="position-absolute start-0 bottom-100 mb-2 d-none bg-light p-2 rounded shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark me-2"></i>
                                <span id="file-name"></span>
                                <button type="button" class="btn-close ms-2" aria-label="Remove" onclick="removeAttachment()"></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Group Info Modal -->
@if($currentRoom->is_group)
<div class="modal fade" id="groupInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Group Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h4>{{ $currentRoom->name }}</h4>
                    <p class="text-muted">Created {{ $currentRoom->created_at->format('M d, Y') }}</p>
                </div>
                
                @if($currentRoom->description)
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <p>{{ $currentRoom->description }}</p>
                </div>
                @endif
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Members ({{ $currentRoom->users->count() }})</label>
                    <div class="list-group">
                        @foreach($currentRoom->users as $user)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @php
                                        $initials = strtoupper(substr($user->name, 0, 2));
                                        $colorIndex = crc32($user->id) % count($colors);
                                        $color = $colors[$colorIndex];
                                    @endphp
                                    <div class="rounded-circle bg-{{ $color }} text-white d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-size: 12px;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="mb-0">{{ $user->name }}</p>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                                @if($user->id == $currentRoom->created_by)
                                    <span class="badge bg-primary">Admin</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Include other modals (new chat, new group) -->
@include('chat.partials.chat_modals')

<style>
    .chat-contact {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .chat-contact:hover {
        background-color: rgba(0,0,0,0.03);
    }
    
    .chat-contact.active {
        background-color: rgba(0,123,255,0.1);
    }
    
    .chat-message-bubble {
        border-radius: 18px;
        word-break: break-word;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark messages as read when chat is opened
        fetch('{{ route("chat.messages.read", $currentRoom) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        // Existing JavaScript code
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('message');
        const fileInput = document.getElementById('attachment');
        const messagesContainer = document.getElementById('chat-messages');
        
        // Auto-scroll to bottom of messages
        function scrollToBottom() {
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }
        
        // Scroll to bottom on page load
        scrollToBottom();
        
        // Submit form when Enter is pressed and shift is not held
        const messageInput = document.getElementById('message-input');
        if (messageInput) {
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    document.getElementById('send-button').click();
                }
            });
        }
    });
    
    function updateFileName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('file-name').textContent = fileName;
            document.getElementById('attachment-preview').classList.remove('d-none');
        }
    }
    
    function removeAttachment() {
        document.getElementById('attachment').value = '';
        document.getElementById('image').value = '';
        document.getElementById('attachment-preview').classList.add('d-none');
    }
</script>
@endsection 