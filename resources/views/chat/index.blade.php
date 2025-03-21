@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 h-100" style="height: calc(100vh - var(--header-height) - 40px);">
        <!-- Sidebar with contacts -->
        <div class="col-md-3 border-end" style="height: 100%;">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0">Чаты</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#newChatModal">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <div class="dropdown d-inline">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newGroupModal">Создать группу</a></li>
                            <li><a class="dropdown-item" href="#">Настройки</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 bg-light" placeholder="Поиск чатов...">
                </div>
            </div>
            <div class="chat-contacts overflow-auto" style="height: calc(100% - 120px);">
                @forelse($chatRooms as $room)
                    <a href="{{ route('chat.show', $room) }}" class="text-decoration-none text-dark">
                        <div class="chat-contact p-2 px-3 border-bottom d-flex align-items-center">
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
                            </div>
                            <div class="ms-3 flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 text-truncate">
                                        @if($room->is_group)
                                            {{ $room->name }}
                                        @else
                                            {{ $otherUser ? $otherUser->name : 'Неизвестный пользователь' }}
                                        @endif
                                    </h6>
                                    @if($room->messages->isNotEmpty())
                                        <small class="text-muted ms-2">{{ $room->messages->first()->created_at->format('H:i') }}</small>
                                    @endif
                                </div>
                                <p class="m-0 text-truncate text-muted small">
                                    @if($room->messages->isNotEmpty())
                                        @if($room->messages->first()->user_id == auth()->id())
                                            Вы: {{ $room->messages->first()->message }}
                                        @else
                                            {{ $room->messages->first()->user->name }}: {{ $room->messages->first()->message }}
                                        @endif
                                    @else
                                        Нет сообщений
                                    @endif
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-3 text-center text-muted">
                        <p>Нет чатов.</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newChatModal">
                            Начать новый разговор
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Chat welcome screen -->
        <div class="col-md-9 d-flex flex-column align-items-center justify-content-center" style="height: 100%; background-color: #f5f5f5;">
            <div class="text-center p-4">
                <div class="mb-4">
                    <i class="bi bi-chat-dots text-primary" style="font-size: 5rem;"></i>
                </div>
                <h3 class="mb-3">Добро пожаловать в чат CRM</h3>
                <p class="text-muted mb-4">Выберите разговор или начните новый</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                    <i class="bi bi-plus-lg me-2"></i> Новый разговор
                </button>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Новый разговор</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form action="{{ route('chat.create') }}" method="POST">
                @csrf
                <input type="hidden" name="is_group" value="0">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Выберите пользователя</label>
                        <select class="form-select" name="user_id" id="user_id" required>
                            <option value="">-- Выберите пользователя --</option>
                            @foreach(App\Models\User::where('id', '!=', auth()->id())->pluck('name', 'id') as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="chat_name" class="form-label">Название чата (необязательно)</label>
                        <input type="text" class="form-control" id="chat_name" name="name" placeholder="Оставьте пустым для автоматического названия">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Начать чат</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New Group Modal -->
<div class="modal fade" id="newGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Создать групповой чат</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form action="{{ route('chat.create') }}" method="POST">
                @csrf
                <input type="hidden" name="is_group" value="1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Название группы</label>
                        <input type="text" class="form-control" id="group_name" name="name" placeholder="Введите название группы" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание (необязательно)</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Выберите участников</label>
                        <div class="border p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach(App\Models\User::where('id', '!=', auth()->id())->pluck('name', 'id') as $id => $name)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="user_ids[]" value="{{ $id }}" id="user_{{ $id }}">
                                    <label class="form-check-label" for="user_{{ $id }}">
                                        {{ $name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Создать группу</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .chat-contact {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .chat-contact:hover {
        background-color: rgba(0,0,0,0.03);
    }
    
    .chat-contact.active {
        background-color: var(--bitrix-light-blue);
    }
</style>
@endsection 