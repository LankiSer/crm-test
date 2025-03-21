@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Компании</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('companies.create') }}" class="btn btn-primary">Добавить компанию</a>
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
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Сайт</th>
                            <th>Контакты</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>
                                    <a href="{{ route('companies.show', $company) }}">
                                        {{ $company->name }}
                                    </a>
                                </td>
                                <td>{{ $company->email }}</td>
                                <td>{{ $company->phone }}</td>
                                <td>
                                    @if($company->website)
                                        <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $company->contacts()->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту компанию?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Компании не найдены</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 