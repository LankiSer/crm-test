@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Deal</h5>
                    <div>
                        <a href="{{ route('deals.show', $deal) }}" class="btn btn-sm btn-info me-2">View Deal</a>
                        <a href="{{ route('deals.index') }}" class="btn btn-sm btn-secondary">Back to Deals</a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('deals.update', $deal) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Deal Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $deal->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_id" class="form-label">Company</label>
                                    <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $deal->company_id) == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_id" class="form-label">Contact</label>
                                    <select class="form-select @error('contact_id') is-invalid @enderror" id="contact_id" name="contact_id">
                                        <option value="">Select Contact</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact->id }}" {{ old('contact_id', $deal->contact_id) == $contact->id ? 'selected' : '' }}>
                                                {{ $contact->first_name }} {{ $contact->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contact_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Deal Value</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $deal->amount) }}" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Deal Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="new" {{ old('status', $deal->status) == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="qualified" {{ old('status', $deal->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                        <option value="proposal" {{ old('status', $deal->status) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                        <option value="negotiation" {{ old('status', $deal->status) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                        <option value="won" {{ old('status', $deal->status) == 'won' ? 'selected' : '' }}>Won</option>
                                        <option value="closed_won" {{ old('status', $deal->status) == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                                        <option value="closed_lost" {{ old('status', $deal->status) == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="expected_close_date" class="form-label">Expected Close Date</label>
                                    <input type="date" class="form-control @error('expected_close_date') is-invalid @enderror" id="expected_close_date" name="expected_close_date" value="{{ old('expected_close_date', $deal->expected_close_date ? $deal->expected_close_date->format('Y-m-d') : '') }}">
                                    @error('expected_close_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $deal->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Update Deal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const companySelect = document.getElementById('company_id');
        const contactSelect = document.getElementById('contact_id');
        const originalContacts = Array.from(contactSelect.options).map(opt => {
            return {
                id: opt.value,
                text: opt.text,
                companyId: opt.dataset.companyId
            };
        });
        
        companySelect.addEventListener('change', function() {
            const selectedCompanyId = this.value;
            
            // Clear current options (except the first one)
            while (contactSelect.options.length > 1) {
                contactSelect.remove(1);
            }
            
            // If no company is selected, don't filter
            if (!selectedCompanyId) {
                originalContacts.forEach(contact => {
                    if (contact.id) { // Skip the empty option
                        const option = new Option(contact.text, contact.id);
                        option.dataset.companyId = contact.companyId;
                        contactSelect.add(option);
                    }
                });
                return;
            }
            
            // Filter contacts by selected company
            originalContacts.forEach(contact => {
                if (contact.id && contact.companyId == selectedCompanyId) {
                    const option = new Option(contact.text, contact.id);
                    option.dataset.companyId = contact.companyId;
                    contactSelect.add(option);
                }
            });
        });
    });
</script>
@endsection 