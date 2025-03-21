<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with key statistics.
     */
    public function index()
    {
        $totalContacts = Contact::count();
        $totalCompanies = Company::count();
        $totalDeals = Deal::count();
        $openTasks = Task::where('status', '!=', 'completed')->count();
        
        // Get deals by status for chart
        $dealsByStatus = Deal::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
            
        // Get total deal value
        $totalDealValue = Deal::sum('value');
        
        // Get recent tasks
        $recentTasks = Task::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('dashboard', compact(
            'totalContacts', 
            'totalCompanies', 
            'totalDeals', 
            'openTasks', 
            'dealsByStatus', 
            'totalDealValue',
            'recentTasks'
        ));
    }
}
