@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{ $deal->name }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('deals.index') }}" class="btn btn-secondary">Back to Deals</a>
                <a href="{{ route('deals.edit', $deal) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this deal?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Deal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Value:</strong> ${{ number_format($deal->value, 2) }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong> 
                                <span class="badge 
                                    @if(in_array($deal->status, ['closed_won'])) bg-success
                                    @elseif(in_array($deal->status, ['closed_lost'])) bg-danger
                                    @elseif(in_array($deal->status, ['negotiation', 'proposal'])) bg-primary
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $deal->status)) }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Expected Close Date:</strong> 
                                {{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Company:</strong> 
                                @if($deal->company)
                                    <a href="{{ route('companies.show', $deal->company) }}">{{ $deal->company->name }}</a>
                                @else
                                    N/A
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Contact:</strong> 
                                @if($deal->contact)
                                    <a href="{{ route('contacts.show', $deal->contact) }}">
                                        {{ $deal->contact->first_name }} {{ $deal->contact->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Created:</strong> {{ $deal->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    
                    @if($deal->description)
                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p>{{ $deal->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tasks</h5>
                    <a href="{{ route('tasks.create', ['deal_id' => $deal->id]) }}" class="btn btn-sm btn-primary">Add Task</a>
                </div>
                <div class="card-body">
                    @if($deal->tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                        <th>Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deal->tasks as $task)
                                        <tr>
                                            <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                                            <td>{{ ucfirst($task->priority) }}</td>
                                            <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $task->user->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="card-text">No tasks associated with this deal.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Activity Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Deal Created</h6>
                                <p class="timeline-text">{{ $deal->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        @if($deal->status == 'closed_won')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Deal Won</h6>
                                <p class="timeline-text">{{ $deal->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @elseif($deal->status == 'closed_lost')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Deal Lost</h6>
                                <p class="timeline-text">{{ $deal->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Deal Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Progress</label>
                        @php
                            $progressMap = [
                                'new' => 10,
                                'qualified' => 30,
                                'proposal' => 50,
                                'negotiation' => 70,
                                'closed_won' => 100,
                                'closed_lost' => 100
                            ];
                            $progress = $progressMap[$deal->status] ?? 0;
                        @endphp
                        <div class="progress">
                            <div class="progress-bar 
                                @if($deal->status == 'closed_won') bg-success
                                @elseif($deal->status == 'closed_lost') bg-danger
                                @endif" 
                                role="progressbar" style="width: {{ $progress }}%" 
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $progress }}%
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Days Open:</strong> 
                        {{ $deal->created_at->diffInDays(now()) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Tasks Completed:</strong> 
                        {{ $deal->tasks->where('status', 'completed')->count() }} of {{ $deal->tasks->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}
.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
    border-left: 1px solid #dee2e6;
    padding-left: 20px;
}
.timeline-marker {
    position: absolute;
    top: 0;
    left: -8px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
}
.timeline-content {
    padding-top: 0.25rem;
}
.timeline-title {
    margin-bottom: 0.25rem;
}
.timeline-text {
    color: #6c757d;
    font-size: 0.875rem;
}
</style>
@endsection 