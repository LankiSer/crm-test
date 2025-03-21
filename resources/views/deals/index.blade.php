@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Сделки</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('deals.create') }}" class="btn btn-primary">Добавить сделку</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Компания</th>
                            <th>Контакт</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Ожидаемое закрытие</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deals as $deal)
                            <tr>
                                <td>
                                    <a href="{{ route('deals.show', $deal) }}">
                                        {{ $deal->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($deal->company)
                                        <a href="{{ route('companies.show', $deal->company) }}">
                                            {{ $deal->company->name }}
                                        </a>
                                    @else
                                        Нет
                                    @endif
                                </td>
                                <td>
                                    @if($deal->contact)
                                        <a href="{{ route('contacts.show', $deal->contact) }}">
                                            {{ $deal->contact->first_name }} {{ $deal->contact->last_name }}
                                        </a>
                                    @else
                                        Нет
                                    @endif
                                </td>
                                <td>${{ number_format($deal->value, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if(in_array($deal->status, ['closed_won'])) bg-success
                                        @elseif(in_array($deal->status, ['closed_lost'])) bg-danger
                                        @elseif(in_array($deal->status, ['negotiation', 'proposal'])) bg-primary
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $deal->status)) }}
                                    </span>
                                </td>
                                <td>{{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : 'Нет' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('deals.edit', $deal) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту сделку?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Сделки не найдены</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 