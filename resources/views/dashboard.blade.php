@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-4">Панель управления</h1>
            
            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'manager')
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-primary-subtle p-3 me-3">
                                <i class="bi bi-person fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $totalContacts ?? 0 }}</h3>
                                <p class="text-muted mb-0">Всего контактов</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('contacts.index') }}" class="text-decoration-none">Все контакты <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-success-subtle p-3 me-3">
                                <i class="bi bi-building fs-3 text-success"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $totalCompanies ?? 0 }}</h3>
                                <p class="text-muted mb-0">Всего компаний</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('companies.index') }}" class="text-decoration-none">Все компании <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-info-subtle p-3 me-3">
                                <i class="bi bi-cash-coin fs-3 text-info"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $totalDeals ?? 0 }}</h3>
                                <p class="text-muted mb-0">Всего сделок</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('deals.index') }}" class="text-decoration-none">Все сделки <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-warning-subtle p-3 me-3">
                                <i class="bi bi-check2-square fs-3 text-warning"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $openTasks ?? 0 }}</h3>
                                <p class="text-muted mb-0">Открытых задач</p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('tasks.index') }}" class="text-decoration-none">Все задачи <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="row">
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'manager' || auth()->user()->role == 'sales')
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Воронка продаж</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Сделка</th>
                                    <th>Компания</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th>Ожидаемая дата закрытия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDeals ?? [] as $deal)
                                <tr>
                                    <td><a href="{{ route('deals.show', $deal) }}" class="text-decoration-none">{{ $deal->name }}</a></td>
                                    <td>{{ $deal->company->name ?? 'Нет' }}</td>
                                    <td>₽{{ number_format($deal->amount, 2) }}</td>
                                    <td>
                                        @if($deal->status == 'closed_won' || $deal->status == 'won')
                                            <span class="badge bg-success">Выиграна</span>
                                        @elseif($deal->status == 'closed_lost')
                                            <span class="badge bg-danger">Проиграна</span>
                                        @elseif($deal->status == 'negotiation')
                                            <span class="badge bg-warning">Переговоры</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($deal->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $deal->expected_close_date ? $deal->expected_close_date->format('d.m.Y') : 'Не указана' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Сделки не найдены</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('deals.index') }}" class="text-decoration-none">Все сделки <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @endif

        <div class="col-lg-{{ auth()->user()->role == 'support' ? '12' : '4' }} mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Мои задачи</h5>
                    <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">Добавить задачу</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($myTasks ?? [] as $task)
                        <li class="list-group-item px-3 py-3">
                            <div class="d-flex align-items-center">
                                @if($task->status == 'completed')
                                    <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                                @elseif($task->status == 'in_progress')
                                    <i class="bi bi-clock-fill text-warning me-2 fs-5"></i>
                                @else
                                    <i class="bi bi-circle text-secondary me-2 fs-5"></i>
                                @endif
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">{{ $task->title }}</a>
                                    </div>
                                    <small class="text-muted">
                                        Срок: {{ $task->due_date ? $task->due_date->format('d.m.Y') : 'Не указан' }}
                                        @if($task->due_date && $task->due_date->isPast() && $task->status != 'completed')
                                            <span class="text-danger ms-2">Просрочена</span>
                                        @endif
                                    </small>
                                </div>
                                <span class="badge {{ $task->priority == 'high' ? 'bg-danger' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-info') }}">
                                    {{ $task->priority == 'high' ? 'Высокий' : ($task->priority == 'medium' ? 'Средний' : 'Низкий') }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item px-3 py-3 text-center">
                            У вас нет назначенных задач
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('tasks.index') }}" class="text-decoration-none">Все задачи <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    @if(auth()->user()->role == 'admin')
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Последние контакты</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentContacts ?? [] as $contact)
                        <li class="list-group-item px-3 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('contacts.show', $contact) }}" class="text-decoration-none">
                                            {{ $contact->first_name }} {{ $contact->last_name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $contact->company->name ?? 'Без компании' }}</small>
                                </div>
                                <small class="text-muted">Добавлен {{ $contact->created_at->diffForHumans() }}</small>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item px-3 py-3 text-center">
                            Нет недавних контактов
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('contacts.index') }}" class="text-decoration-none">Все контакты <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Последние компании</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentCompanies ?? [] as $company)
                        <li class="list-group-item px-3 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('companies.show', $company) }}" class="text-decoration-none">
                                            {{ $company->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $company->industry ?? 'Не указана' }}</small>
                                </div>
                                <small class="text-muted">Добавлена {{ $company->created_at->diffForHumans() }}</small>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item px-3 py-3 text-center">
                            Нет недавних компаний
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('companies.index') }}" class="text-decoration-none">Все компании <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 