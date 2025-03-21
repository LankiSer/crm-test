<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deals = Deal::with(['company', 'contact', 'user'])->get();
        return view('deals.index', compact('deals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::pluck('name', 'id');
        $contacts = Contact::selectRaw("CONCAT(first_name, ' ', last_name) as full_name, id")
            ->pluck('full_name', 'id');
        $statuses = Deal::statuses();
        
        return view('deals.create', compact('companies', 'contacts', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:new,qualified,proposal,negotiation,won,closed_won,closed_lost',
            'expected_close_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'description' => 'nullable|string',
        ]);
        
        $validated['user_id'] = auth()->id();
        
        $deal = Deal::create($validated);
        
        return redirect()->route('deals.show', $deal)->with('success', 'Deal created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Deal $deal)
    {
        $deal->load(['company', 'contact', 'user', 'tasks']);
        return view('deals.show', compact('deal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deal $deal)
    {
        $companies = Company::pluck('name', 'id');
        $contacts = Contact::selectRaw("CONCAT(first_name, ' ', last_name) as full_name, id")
            ->pluck('full_name', 'id');
        $statuses = Deal::statuses();
        
        return view('deals.edit', compact('deal', 'companies', 'contacts', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:new,qualified,proposal,negotiation,won,closed_won,closed_lost',
            'expected_close_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'description' => 'nullable|string',
        ]);
        
        $deal->update($validated);
        
        return redirect()->route('deals.show', $deal)->with('success', 'Deal updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index')->with('success', 'Deal deleted successfully.');
    }
}
