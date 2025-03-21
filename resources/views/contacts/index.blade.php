@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Контакты</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('contacts.create') }}" class="btn btn-primary">Добавить контакт</a>
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
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Компания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>
                                    <a href="{{ route('contacts.show', $contact) }}">
                                        {{ $contact->first_name }} {{ $contact->last_name }}
                                    </a>
                                </td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>
                                    @if($contact->company)
                                        <a href="{{ route('companies.show', $contact->company) }}">
                                            {{ $contact->company->name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот контакт?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Контакты не найдены</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection