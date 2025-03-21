@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Задачи</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Добавить задачу</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Фильтры задач</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('tasks.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Статус</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Все статусы</option>
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="priority" class="form-label">Приоритет</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">Все приоритеты</option>
                            @foreach($priorities as $key => $value)
                                <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="due_date" class="form-label">Срок выполнения</label>
                        <select class="form-select" id="due_date" name="due_date">
                            <option value="">Все</option>
                            <option value="today" {{ request('due_date') == 'today' ? 'selected' : '' }}>Сегодня</option>
                            <option value="overdue" {{ request('due_date') == 'overdue' ? 'selected' : '' }}>Просрочено</option>
                            <option value="week" {{ request('due_date') == 'week' ? 'selected' : '' }}>Эта неделя</option>
                            <option value="month" {{ request('due_date') == 'month' ? 'selected' : '' }}>Этот месяц</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary">Фильтровать</button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary ms-2">Сбросить</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Приоритет</th>
                            <th>Срок</th>
                            <th>Связано с</th>
                            <th>Назначено</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr class="{{ $task->due_date && $task->due_date->isPast() && $task->status != 'completed' ? 'table-danger' : '' }}">
                                <td>
                                    <a href="{{ route('tasks.show', $task) }}">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($task->status == 'completed') bg-success
                                        @elseif($task->status == 'in_progress') bg-primary
                                        @elseif($task->status == 'not_started') bg-secondary
                                        @elseif($task->status == 'deferred') bg-warning
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($task->priority == 'high') bg-danger
                                        @elseif($task->priority == 'medium') bg-warning
                                        @elseif($task->priority == 'low') bg-info
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}
                                    @if($task->due_date && $task->due_date->isPast() && $task->status != 'completed')
                                        <span class="badge bg-danger">Просрочено</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->taskable)
                                        @if($task->taskable_type == 'App\Models\Deal')
                                            <a href="{{ route('deals.show', $task->taskable) }}">
                                                <span class="badge bg-primary">Сделка</span> {{ $task->taskable->name }}
                                            </a>
                                        @elseif($task->taskable_type == 'App\Models\Contact')
                                            <a href="{{ route('contacts.show', $task->taskable) }}">
                                                <span class="badge bg-info">Контакт</span> {{ $task->taskable->first_name }} {{ $task->taskable->last_name }}
                                            </a>
                                        @elseif($task->taskable_type == 'App\Models\Company')
                                            <a href="{{ route('companies.show', $task->taskable) }}">
                                                <span class="badge bg-secondary">Компания</span> {{ $task->taskable->name }}
                                            </a>
                                        @endif
                                    @else
                                        Нет
                                    @endif
                                </td>
                                <td>{{ $task->user->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту задачу?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Задачи не найдены</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 