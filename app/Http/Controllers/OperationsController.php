<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\EmployeeActivity;
use App\Models\ActivityAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class OperationsController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if (in_array($user->role, ['technician', 'employee'])) {
            return redirect()->route('operations.workspace');
        }

        $totalClients = Client::count();
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'in_progress')->count();
        $completedProjects = Project::where('status', 'completed')->count();

        $projects = Project::with('client')->latest()->limit(10)->get();
        $activities = EmployeeActivity::with(['user', 'project'])->latest()->limit(10)->get();

        return view('operations.dashboard', compact(
            'totalClients',
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'projects',
            'activities'
        ));
    }

    public function clients()
    {
        $clients = Client::latest()->paginate(10);
        return view('operations.clients.index', compact('clients'));
    }

    public function storeClient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'business_type' => 'nullable|string|max:255',
        ]);

        Client::create($validated);

        return back()->with('success', 'Client added successfully.');
    }

    public function projects()
    {
        $projects = Project::with('client')->latest()->paginate(10);
        $clients = Client::orderBy('name')->get();

        return view('operations.projects.index', compact('projects', 'clients'));
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'service_type' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,normal,urgent,critical',
        ]);

        $year = date('Y');
        $lastProject = Project::whereYear('created_at', $year)->latest('id')->first();
        $sequence = $lastProject ? ((int) substr($lastProject->project_number, -4)) + 1 : 1;

        $validated['project_number'] = 'PROJ-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'pending';
        $validated['created_by'] = Auth::id();

        Project::create($validated);

        return redirect()->route('operations.projects')->with('success', 'Project created successfully.');
    }

    public function showProject($id)
    {
        $project = Project::with(['client', 'tasks.employee', 'activities.user', 'activities.attachments'])->findOrFail($id);
        $employees = User::orderBy('name')->get();

        return view('operations.projects.show', compact('project', 'employees'));
    }

    public function editProject($id)
    {
        $project = Project::findOrFail($id);
        $clients = Client::orderBy('name')->get();

        return view('operations.projects.edit', compact('project', 'clients'));
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,normal,urgent,critical',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $project->update($validated);

        return redirect()
            ->route('operations.projects.show', $project->id)
            ->with('success', 'Project updated successfully.');
    }


    public function storeTask(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,done,cancelled',
        ]);

        $validated['project_id'] = $project->id;
        $validated['status'] = $validated['status'] ?? 'pending';

        ProjectTask::create($validated);

        $this->recalculateProjectStatus($project);

        return back()->with('success', 'Task assigned successfully.');
    }

    public function updateTaskStatus(Request $request, $taskId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,done,cancelled',
        ]);

        $task = ProjectTask::findOrFail($taskId);
        $task->update([
            'status' => $validated['status'],
        ]);

        $this->recalculateProjectStatus($task->project);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'task_id' => $task->id,
                'status' => $task->status,
            ]);
        }

        return back()->with('success', 'Task status updated successfully.');
    }

    public function activities()
    {
        $activities = EmployeeActivity::with(['user', 'project', 'task', 'attachments'])
            ->latest()
            ->paginate(15);

        $projects = Project::orderBy('title')->get();
        $tasks = ProjectTask::orderBy('title')->get();

        return view('operations.activities.index', compact('activities', 'projects', 'tasks'));
    }

    public function storeActivity(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'project_task_id' => 'nullable|exists:project_tasks,id',
            'activity_type' => 'nullable|string|max:255',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'time_started' => 'nullable',
            'time_ended' => 'nullable',
            'status' => 'required|in:pending,in_progress,completed',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $validated['user_id'] = Auth::id();

        $activity = EmployeeActivity::create($validated);

        if (!empty($validated['project_task_id'])) {
            $task = ProjectTask::with('project')->find($validated['project_task_id']);

            if ($task) {
                if ($validated['status'] === 'completed') {
                    $task->update(['status' => 'done']);
                } elseif ($validated['status'] === 'in_progress') {
                    $task->update(['status' => 'in_progress']);
                }

                if ($task->project) {
                    $this->recalculateProjectStatus($task->project);
                }
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('activity_photos', 'public');

                ActivityAttachment::create([
                    'employee_activity_id' => $activity->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'file_type' => $photo->getClientMimeType(),
                ]);
            }
        }

        return back()->with('success', 'Activity logged successfully.');
    }

    public function showActivity($id)
    {
        $activity = EmployeeActivity::with([
            'user',
            'project.client',
            'task',
            'attachments'
        ])->findOrFail($id);

        return view('operations.activities.show', compact('activity'));
    }

    public function editActivity($id)
    {
        $activity = EmployeeActivity::with(['attachments'])->findOrFail($id);
        $projects = Project::orderBy('title')->get();
        $tasks = ProjectTask::with('project')->orderBy('title')->get();

        return view('operations.activities.edit', compact(
            'activity',
            'projects',
            'tasks'
        ));
    }

    public function updateActivity(Request $request, $id)
    {
        $activity = EmployeeActivity::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'project_task_id' => 'nullable|exists:project_tasks,id',
            'activity_type' => 'nullable|string|max:255',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'time_started' => 'nullable',
            'time_ended' => 'nullable',
            'status' => 'required|in:pending,in_progress,completed',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $activity->update($validated);

        if (!empty($validated['project_task_id'])) {
            $task = ProjectTask::with('project')->find($validated['project_task_id']);

            if ($task) {
                if ($validated['status'] === 'completed') {
                    $task->update(['status' => 'done']);
                } elseif ($validated['status'] === 'in_progress') {
                    $task->update(['status' => 'in_progress']);
                }

                if ($task->project) {
                    $this->recalculateProjectStatus($task->project);
                }
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('activity_photos', 'public');

                ActivityAttachment::create([
                    'employee_activity_id' => $activity->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'file_type' => $photo->getClientMimeType(),
                ]);
            }
        }

        return redirect()
            ->route('operations.activities.show', $activity->id)
            ->with('success', 'Activity updated successfully.');
    }



    public function employees()
    {
        $employees = User::latest()->paginate(10);

        return view('operations.employees.index', compact('employees'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,manager,technician,inventory,accounting,employee',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return back()->with('success', 'Employee account created successfully.');
    }

    public function showEmployee($id)
    {
        $employee = User::findOrFail($id);

        $tasks = ProjectTask::with(['project.client'])
            ->where('assigned_to', $employee->id)
            ->latest()
            ->get();

        $activities = EmployeeActivity::with(['project', 'task', 'attachments'])
            ->where('user_id', $employee->id)
            ->latest()
            ->get();

        return view('operations.employees.show', compact(
            'employee',
            'tasks',
            'activities'
        ));
    }

    public function editEmployee($id)
    {
        $employee = User::findOrFail($id);

        return view('operations.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,manager,technician,inventory,accounting,employee',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return redirect()
            ->route('operations.employees.show', $employee->id)
            ->with('success', 'Employee updated successfully.');
    }

    public function myTasks()
    {
        $tasks = ProjectTask::with(['project.client'])
            ->where('assigned_to', Auth::id())
            ->latest()
            ->paginate(10);

        return view('operations.my_tasks.index', compact('tasks'));
    }

    public function myActivities()
    {
        $activities = EmployeeActivity::with(['project', 'task', 'attachments'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('operations.my_activities.index', compact('activities'));
    }

    public function workspace()
    {
        $tasks = ProjectTask::with(['project.client'])
            ->where('assigned_to', Auth::id())
            ->latest()
            ->get();

        return view('operations.workspace.index', compact('tasks'));
    }

    public function quickLogTask(Request $request, $taskId)
    {
        $task = ProjectTask::with('project')
            ->where('assigned_to', Auth::id())
            ->findOrFail($taskId);

        $validated = $request->validate([
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
            'activity_date' => 'required|date',
            'time_started' => 'nullable',
            'time_ended' => 'nullable',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $activity = EmployeeActivity::create([
            'project_id' => $task->project_id,
            'project_task_id' => $task->id,
            'user_id' => Auth::id(),
            'activity_type' => 'Task Update',
            'description' => $validated['description'],
            'activity_date' => $validated['activity_date'],
            'time_started' => $validated['time_started'] ?? null,
            'time_ended' => $validated['time_ended'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($validated['status'] === 'completed') {
            $task->update(['status' => 'done']);
        } elseif ($validated['status'] === 'in_progress') {
            $task->update(['status' => 'in_progress']);
        }

        if ($task->project) {
            $this->recalculateProjectStatus($task->project);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('activity_photos', 'public');

                ActivityAttachment::create([
                    'employee_activity_id' => $activity->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'file_type' => $photo->getClientMimeType(),
                ]);
            }
        }

        return back()->with('success', 'Task update submitted successfully.');
    }

    private function recalculateProjectStatus(Project $project): void
    {
        $tasks = $project->tasks()->get();

        if ($tasks->count() > 0 && $tasks->every(fn ($item) => $item->status === 'done')) {
            $project->update(['status' => 'completed']);
        } elseif ($tasks->contains(fn ($item) => $item->status === 'in_progress' || $item->status === 'done')) {
            $project->update(['status' => 'in_progress']);
        } else {
            $project->update(['status' => 'pending']);
        }
    }

    public function createProject()
    {
        $clients = Client::orderBy('name')->get();

        return view('operations.projects.create', compact('clients'));
    }

    public function updateTask(Request $request, $taskId)
    {
        $task = ProjectTask::findOrFail($taskId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,done,cancelled',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated successfully.');
    }

    public function createActivity()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();
        $tasks = ProjectTask::with('project')->orderBy('created_at', 'desc')->get();

        return view('operations.activities.create', compact('projects', 'tasks'));
    }

    public function createClient()
    {
        return view('operations.clients.create');
    }

    public function createEmployee()
    {
        return view('operations.employees.create');
    }

    public function showClient($id)
    {
        $client = Client::with([
            'projects.tasks',
            'projects.activities'
        ])->findOrFail($id);    

        $projects = $client->projects()->latest()->get();

        return view('operations.clients.show', compact(
            'client',
            'projects'
        ));
    }

    public function editClient($id)
    {
        $client = Client::findOrFail($id);

        return view('operations.clients.edit', compact('client'));
    }

    public function updateClient(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()
            ->route('operations.clients.show', $client->id)
            ->with('success', 'Client updated successfully.');
    }


}