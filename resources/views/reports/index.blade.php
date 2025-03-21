@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Reports</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Sales Reports</h5>
                    <p class="card-text">View sales data by period, product, or team member.</p>
                    <a href="#" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Customer Analytics</h5>
                    <p class="card-text">Analyze customer behavior and segmentation data.</p>
                    <a href="#" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Deal Pipeline</h5>
                    <p class="card-text">Review your sales pipeline and forecast.</p>
                    <a href="#" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Team Performance</h5>
                    <p class="card-text">Analyze team and individual performance metrics.</p>
                    <a href="#" class="btn btn-primary">Generate Report</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Custom Reports</h5>
                </div>
                <div class="card-body">
                    <p>Create a custom report by selecting data points and filters below.</p>
                    
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-select" id="reportType">
                                    <option>Sales Report</option>
                                    <option>Activity Report</option>
                                    <option>Customer Report</option>
                                    <option>Deal Report</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <select class="form-select" id="dateRange">
                                    <option>Today</option>
                                    <option>Yesterday</option>
                                    <option>This Week</option>
                                    <option>Last Week</option>
                                    <option>This Month</option>
                                    <option>Last Month</option>
                                    <option>Custom Range</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="format" class="form-label">Format</label>
                                <select class="form-select" id="format">
                                    <option>PDF</option>
                                    <option>Excel</option>
                                    <option>CSV</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="includeCharts">
                                    <label class="form-check-label" for="includeCharts">
                                        Include Charts and Graphs
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Generate Custom Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 