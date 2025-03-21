<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Contact;
use App\Models\Company;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['user', 'creator', 'taskable'])->paginate(10);
        $statuses = Task::statuses();
        $priorities = Task::priorities();
        return view('tasks.index', compact('tasks', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::selectRaw('name, id')->pluck('name', 'id');
        $statuses = Task::statuses();
        $priorities = Task::priorities();
        
        // Get related entities for assignment
        $contacts = Contact::selectRaw("CONCAT(first_name, ' ', last_name) as full_name, id")
            ->pluck('full_name', 'id');
        $companies = Company::pluck('name', 'id');
        $deals = Deal::pluck('name', 'id');
        
        return view('tasks.create', compact(
            'users', 
            'statuses', 
            'priorities', 
            'contacts', 
            'companies', 
            'deals'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'priority' => 'required|in:' . implode(',', array_keys(Task::priorities())),
            'status' => 'required|in:' . implode(',', array_keys(Task::statuses())),
            'due_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'taskable_type' => 'nullable|in:contact,company,deal',
            'taskable_id' => 'nullable|required_with:taskable_type',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id(); // Current user is the creator
        
        // Set the taskable type based on selection
        if ($request->filled('taskable_type') && $request->filled('taskable_id')) {
            $taskableType = $request->input('taskable_type');
            $taskableId = $request->input('taskable_id');
            
            switch ($taskableType) {
                case 'contact':
                    $data['taskable_type'] = Contact::class;
                    break;
                case 'company':
                    $data['taskable_type'] = Company::class;
                    break;
                case 'deal':
                    $data['taskable_type'] = Deal::class;
                    break;
            }
        }

        Task::create($data);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['user', 'creator', 'taskable']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $users = User::selectRaw('name, id')->pluck('name', 'id');
        $statuses = Task::statuses();
        $priorities = Task::priorities();
        
        // Get taskable information
        $taskableType = null;
        if ($task->taskable) {
            if ($task->taskable instanceof Contact) {
                $taskableType = 'contact';
            } elseif ($task->taskable instanceof Company) {
                $taskableType = 'company';
            } elseif ($task->taskable instanceof Deal) {
                $taskableType = 'deal';
            }
        }
        
        // Get related entities for assignment
        $contacts = Contact::selectRaw("CONCAT(first_name, ' ', last_name) as full_name, id")
            ->pluck('full_name', 'id');
        $companies = Company::pluck('name', 'id');
        $deals = Deal::pluck('name', 'id');
        
        return view('tasks.edit', compact(
            'task',
            'users', 
            'statuses', 
            'priorities', 
            'contacts', 
            'companies', 
            'deals',
            'taskableType'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'priority' => 'required|in:' . implode(',', array_keys(Task::priorities())),
            'status' => 'required|in:' . implode(',', array_keys(Task::statuses())),
            'due_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'taskable_type' => 'nullable|in:contact,company,deal',
            'taskable_id' => 'nullable|required_with:taskable_type',
        ]);

        $data = $request->all();
        
        // Set the taskable type based on selection
        if ($request->filled('taskable_type') && $request->filled('taskable_id')) {
            $taskableType = $request->input('taskable_type');
            $taskableId = $request->input('taskable_id');
            
            switch ($taskableType) {
                case 'contact':
                    $data['taskable_type'] = Contact::class;
                    break;
                case 'company':
                    $data['taskable_type'] = Company::class;
                    break;
                case 'deal':
                    $data['taskable_type'] = Deal::class;
                    break;
            }
        } else {
            $data['taskable_id'] = null;
            $data['taskable_type'] = null;
        }

        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
