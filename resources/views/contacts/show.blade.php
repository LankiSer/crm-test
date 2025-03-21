@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{ $contact->first_name }} {{ $contact->last_name }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back to Contacts</a>
                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?');" style="display: inline;">
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
                    <h5 class="card-title">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Full Name:</strong> {{ $contact->first_name }} {{ $contact->last_name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $contact->email ?? 'N/A' }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phone:</strong> {{ $contact->phone ?? 'N/A' }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Company:</strong> 
                        @if($contact->company)
                            <a href="{{ route('companies.show', $contact->company) }}">{{ $contact->company->name }}</a>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
            
            @if($contact->notes)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Notes</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $contact->notes }}</p>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Deals</h5>
                    <a href="{{ route('deals.create') }}" class="btn btn-sm btn-primary">Add Deal</a>
                </div>
                <div class="card-body">
                    @if($contact->deals->count() > 0)
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
                                    @foreach($contact->deals as $deal)
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
                        <p class="card-text">No deals associated with this contact.</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tasks</h5>
                    <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">Add Task</a>
                </div>
                <div class="card-body">
                    @if($contact->tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contact->tasks as $task)
                                        <tr>
                                            <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                                            <td>{{ App\Models\Task::statuses()[$task->status] }}</td>
                                            <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="card-text">No tasks associated with this contact.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 