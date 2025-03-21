@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Settings</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active" id="profile-tab">Profile</a>
                <a href="#" class="list-group-item list-group-item-action" id="security-tab">Security</a>
                <a href="#" class="list-group-item list-group-item-action" id="notifications-tab">Notifications</a>
                <a href="#" class="list-group-item list-group-item-action" id="appearance-tab">Appearance</a>
                <a href="#" class="list-group-item list-group-item-action" id="customization-tab">Customization</a>
                <a href="#" class="list-group-item list-group-item-action" id="api-tab">API Settings</a>
                <a href="#" class="list-group-item list-group-item-action" id="integrations-tab">Integrations</a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Settings</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <div class="avatar-lg position-relative">
                                        <div style="width: 100px; height: 100px; background-color: #ebf3fc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; color: #0b66c3;">
                                            U
                                        </div>
                                        <div class="position-absolute bottom-0 end-0">
                                            <button class="btn btn-sm btn-primary rounded-circle">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                    <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="job_title" class="form-label">Job Title</label>
                                <input type="text" class="form-control" id="job_title" value="Sales Manager">
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" value="Sales">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" value="+1 (123) 456-7890">
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" rows="3">Experienced sales professional with over 5 years in B2B software solutions.</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Language</label>
                            <select class="form-select">
                                <option selected>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                                <option>Russian</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Time Zone</label>
                            <select class="form-select">
                                <option selected>(UTC-08:00) Pacific Time (US & Canada)</option>
                                <option>(UTC-05:00) Eastern Time (US & Canada)</option>
                                <option>(UTC+00:00) UTC</option>
                                <option>(UTC+01:00) Central European Time</option>
                                <option>(UTC+03:00) Moscow, St. Petersburg</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 