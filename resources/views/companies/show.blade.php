@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{ $company->name }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back to Companies</a>
                <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $company->name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $company->email ?? 'N/A' }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phone:</strong> {{ $company->phone ?? 'N/A' }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Website:</strong> 
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                        @else
                            N/A
                        @endif
                    </div>
                    
                    @if($company->address)
                    <div class="mb-3">
                        <strong>Address:</strong> {{ $company->address }}
                    </div>
                    @endif
                </div>
            </div>
            
            @if($company->description)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Description</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $company->description }}</p>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Contacts</h5>
                    <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">Add Contact</a>
                </div>
                <div class="card-body">
                    @if($company->contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->contacts as $contact)
                                        <tr>
                                            <td>
                                                <a href="{{ route('contacts.show', $contact) }}">
                                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                                </a>
                                            </td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ $contact->phone }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="card-text">No contacts associated with this company.</p>
                    @endif
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Deals</h5>
                    <a href="{{ route('deals.create') }}" class="btn btn-sm btn-primary">Add Deal</a>
                </div>
                <div class="card-body">
                    @if($company->deals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->deals as $deal)
                                        <tr>
                                            <td><a href="{{ route('deals.show', $deal) }}">{{ $deal->name }}</a></td>
                                            <td>${{ number_format($deal->value, 2) }}</td>
                                            <td>{{ App\Models\Deal::statuses()[$deal->status] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="card-text">No deals associated with this company.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 