@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{ $task->title }}</h1>
            <span class="badge 
                @if($task->status == 'completed') bg-success
                @elseif($task->status == 'in_progress') bg-primary
                @elseif($task->status == 'not_started') bg-secondary
                @elseif($task->status == 'deferred') bg-warning
                @endif">
                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
            </span>
            <span class="badge 
                @if($task->priority == 'high') bg-danger
                @elseif($task->priority == 'medium') bg-warning
                @elseif($task->priority == 'low') bg-info
                @endif">
                {{ ucfirst($task->priority) }}
            </span>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');" style="display: inline;">
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
                    <h5 class="card-title">Task Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Due Date:</div>
                        <div class="col-md-9">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}
                            @if($task->due_date && $task->due_date->isPast() && $task->status != 'completed')
                                <span class="badge bg-danger">Overdue</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Assigned To:</div>
                        <div class="col-md-9">{{ $task->user->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Created By:</div>
                        <div class="col-md-9">{{ $task->createdBy->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Created On:</div>
                        <div class="col-md-9">{{ $task->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Last Updated:</div>
                        <div class="col-md-9">{{ $task->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                    
                    @if($task->taskable)
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Related To:</div>
                            <div class="col-md-9">
                                @if($task->taskable_type == 'App\Models\Deal')
                                    <a href="{{ route('deals.show', $task->taskable) }}">
                                        <span class="badge bg-primary">Deal</span> {{ $task->taskable->name }}
                                    </a>
                                @elseif($task->taskable_type == 'App\Models\Contact')
                                    <a href="{{ route('contacts.show', $task->taskable) }}">
                                        <span class="badge bg-info">Contact</span> {{ $task->taskable->first_name }} {{ $task->taskable->last_name }}
                                    </a>
                                @elseif($task->taskable_type == 'App\Models\Company')
                                    <a href="{{ route('companies.show', $task->taskable) }}">
                                        <span class="badge bg-secondary">Company</span> {{ $task->taskable->name }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if($task->description)
                        <div class="row">
                            <div class="col-md-3 fw-bold">Description:</div>
                            <div class="col-md-9">{{ $task->description }}</div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Comments</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCommentModal">
                        Add Comment
                    </button>
                </div>
                <div class="card-body">
                    @if(isset($task->comments) && count($task->comments) > 0)
                        @foreach($task->comments as $comment)
                            <div class="comment mb-3 p-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                    </div>
                                    <div class="text-muted small">
                                        {{ $comment->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                                <div class="mt-2">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">No comments yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="quick_action" value="true">
                        <input type="hidden" name="title" value="{{ $task->title }}">
                        <input type="hidden" name="description" value="{{ $task->description }}">
                        <input type="hidden" name="priority" value="{{ $task->priority }}">
                        <input type="hidden" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                        <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                        
                        @if($task->taskable_type == 'App\Models\Deal')
                            <input type="hidden" name="related_type" value="deal">
                            <input type="hidden" name="deal_id" value="{{ $task->taskable_id }}">
                        @elseif($task->taskable_type == 'App\Models\Contact')
                            <input type="hidden" name="related_type" value="contact">
                            <input type="hidden" name="contact_id" value="{{ $task->taskable_id }}">
                        @elseif($task->taskable_type == 'App\Models\Company')
                            <input type="hidden" name="related_type" value="company">
                            <input type="hidden" name="company_id" value="{{ $task->taskable_id }}">
                        @endif
                        
                        <div class="mb-3">
                            <label for="quick_status" class="form-label">Update Status</label>
                            <select class="form-select" id="quick_status" name="status" onchange="this.form.submit()">
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ $task->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    
                    <hr>
                    
                    @if($task->taskable_type == 'App\Models\Deal')
                        <a href="{{ route('deals.show', $task->taskable) }}" class="btn btn-block btn-outline-primary w-100 mb-2">
                            View Related Deal
                        </a>
                    @elseif($task->taskable_type == 'App\Models\Contact')
                        <a href="{{ route('contacts.show', $task->taskable) }}" class="btn btn-block btn-outline-info w-100 mb-2">
                            View Related Contact
                        </a>
                    @elseif($task->taskable_type == 'App\Models\Company')
                        <a href="{{ route('companies.show', $task->taskable) }}" class="btn btn-block btn-outline-secondary w-100 mb-2">
                            View Related Company
                        </a>
                    @endif
                    
                    @if($task->status != 'completed')
                        <form action="{{ route('tasks.complete', $task) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Mark as Completed</button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Task Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Task Created</h6>
                                <p class="timeline-text">{{ $task->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        @if($task->updated_at->gt($task->created_at))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Last Updated</h6>
                                <p class="timeline-text">{{ $task->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->status == 'completed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Completed</h6>
                                <p class="timeline-text">{{ $task->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding comments -->
<div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('task.comments.store', $task) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentModalLabel">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="content">Comment</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Comment</button>
                </div>
            </form>
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