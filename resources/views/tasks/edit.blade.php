@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Edit Task</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="title">Task Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $task->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ old('status', $task->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority">
                                @foreach($priorities as $key => $value)
                                    <option value="{{ $key }}" {{ old('priority', $task->priority) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Assign To</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('user_id', $task->user_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label>Related To</label>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="related_type" id="related_none" value="" {{ !$task->taskable_type ? 'checked' : '' }}>
                                <label class="form-check-label" for="related_none">None</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="related_type" id="related_deal" value="deal" {{ $task->taskable_type == 'App\Models\Deal' ? 'checked' : '' }}>
                                <label class="form-check-label" for="related_deal">Deal</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="related_type" id="related_contact" value="contact" {{ $task->taskable_type == 'App\Models\Contact' ? 'checked' : '' }}>
                                <label class="form-check-label" for="related_contact">Contact</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="related_type" id="related_company" value="company" {{ $task->taskable_type == 'App\Models\Company' ? 'checked' : '' }}>
                                <label class="form-check-label" for="related_company">Company</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="related-selects">
                    <div class="form-group mb-3 related-select" id="deal-select" style="{{ $task->taskable_type == 'App\Models\Deal' ? '' : 'display: none;' }}">
                        <label for="deal_id">Select Deal</label>
                        <select class="form-control @error('deal_id') is-invalid @enderror" id="deal_id" name="deal_id">
                            <option value="">Select a deal</option>
                            @foreach($deals as $id => $name)
                                <option value="{{ $id }}" {{ ($task->taskable_type == 'App\Models\Deal' && $task->taskable_id == $id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('deal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3 related-select" id="contact-select" style="{{ $task->taskable_type == 'App\Models\Contact' ? '' : 'display: none;' }}">
                        <label for="contact_id">Select Contact</label>
                        <select class="form-control @error('contact_id') is-invalid @enderror" id="contact_id" name="contact_id">
                            <option value="">Select a contact</option>
                            @foreach($contacts as $id => $name)
                                <option value="{{ $id }}" {{ ($task->taskable_type == 'App\Models\Contact' && $task->taskable_id == $id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3 related-select" id="company-select" style="{{ $task->taskable_type == 'App\Models\Company' ? '' : 'display: none;' }}">
                        <label for="company_id">Select Company</label>
                        <select class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id">
                            <option value="">Select a company</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ ($task->taskable_type == 'App\Models\Company' && $task->taskable_id == $id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const relatedTypeRadios = document.querySelectorAll('input[name="related_type"]');
    const relatedSelects = document.querySelectorAll('.related-select');
    
    relatedTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            relatedSelects.forEach(select => {
                select.style.display = 'none';
            });
            
            const value = this.value;
            if (value) {
                document.getElementById(`${value}-select`).style.display = 'block';
            }
        });
    });
});
</script>
@endsection 