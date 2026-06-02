<x-app-layout>
    <x-slot name="header">
        Project Workspace
    </x-slot>

    @php
        $totalTasks = $project->tasks->count();
        $doneTasks = $project->tasks->where('status', 'done')->count();
        $inProgressTasks = $project->tasks->where('status', 'in_progress')->count();
        $pendingTasks = $project->tasks->where('status', 'pending')->count();
        $cancelledTasks = $project->tasks->where('status', 'cancelled')->count();
        $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        $taskGroups = [
            'pending' => [
                'label' => 'To Do',
                'items' => $project->tasks->where('status', 'pending'),
                'dot' => 'bg-slate-400',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'items' => $project->tasks->where('status', 'in_progress'),
                'dot' => 'bg-amber-400',
            ],
            'done' => [
                'label' => 'Done',
                'items' => $project->tasks->where('status', 'done'),
                'dot' => 'bg-emerald-400',
            ],
        ];

        $activitiesByTask = $project->activities->groupBy('project_task_id');
    @endphp

    <div class="space-y-4 pt-1">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-black">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HERO -->
        <div class="rounded-[1.6rem] bg-slate-950 text-white overflow-hidden shadow-xl">
            <div class="p-4 sm:p-5 bg-[radial-gradient(circle_at_top_right,_rgba(59,130,246,.30),_transparent_35%)]">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                    <div class="min-w-0">
                        <a href="{{ route('operations.projects') }}"
                           class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-white font-black">
                            ← Back to Projects
                        </a>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight truncate">
                                {{ $project->title }}
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ ucwords(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 text-[11px] sm:text-xs font-bold text-slate-300">
                            <span class="px-3 py-1.5 rounded-full bg-white/10">{{ $project->project_number }}</span>
                            <span class="px-3 py-1.5 rounded-full bg-white/10">{{ $project->client->name ?? 'No Client' }}</span>
                            <span class="px-3 py-1.5 rounded-full bg-white/10">{{ $project->service_type }}</span>
                            <span class="px-3 py-1.5 rounded-full bg-white/10">Due: {{ $project->due_date ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="w-full xl:w-[360px]">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-slate-400 font-black">Progress</span>
                            <span id="projectProgressText" class="text-lg font-black">{{ $progress }}%</span>
                        </div>

                        <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                            <div id="projectProgressBar"
                                 class="h-full rounded-full bg-blue-500 transition-all duration-300"
                                 style="width: {{ $progress }}%"></div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 mt-3 text-center">
                            <div class="rounded-2xl bg-white/10 py-2">
                                <p id="heroPendingCount" class="text-lg font-black">{{ $pendingTasks }}</p>
                                <p class="text-[10px] text-slate-400 font-bold">Todo</p>
                            </div>

                            <div class="rounded-2xl bg-white/10 py-2">
                                <p id="heroProgressCount" class="text-lg font-black">{{ $inProgressTasks }}</p>
                                <p class="text-[10px] text-slate-400 font-bold">Active</p>
                            </div>

                            <div class="rounded-2xl bg-white/10 py-2">
                                <p id="heroDoneCount" class="text-lg font-black">{{ $doneTasks }}</p>
                                <p class="text-[10px] text-slate-400 font-bold">Done</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4">

            <!-- MAIN BOARD -->
            <div class="2xl:col-span-8 space-y-4">

                <!-- ADD TASK -->
                <details class="rounded-[1.5rem] bg-white/85 border border-slate-200 shadow-sm overflow-hidden">
                    <summary class="cursor-pointer list-none px-4 sm:px-5 py-4 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Create Task</h2>
                            <p class="text-xs text-slate-500 font-semibold">Add work directly into this project board.</p>
                        </div>

                        <span class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-black">
                            + Add
                        </span>
                    </summary>

                    <form method="POST"
                          action="{{ route('operations.tasks.store', $project->id) }}"
                          class="p-4 sm:p-5 border-t border-slate-100 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input name="title"
                                   required
                                   placeholder="Task title"
                                   class="rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold">

                            <select name="assigned_to"
                                    class="rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold">
                                <option value="">Assign employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name }} - {{ ucfirst($employee->role ?? 'employee') }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="date"
                                   name="due_date"
                                   class="rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold">

                            <select name="status"
                                    class="rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="done">Done</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <textarea name="description"
                                  rows="3"
                                  placeholder="Task details..."
                                  class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm resize-none"></textarea>

                        <div class="flex justify-end">
                            <button class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black">
                                Save Task
                            </button>
                        </div>
                    </form>
                </details>

                <!-- BOARD -->
                <div class="rounded-[1.6rem] bg-white/80 border border-slate-200 shadow-sm p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">Task Board</h2>
                            <p class="text-xs text-slate-500 font-semibold">Drag cards to update status. Click cards to view and edit details.</p>
                        </div>

                        <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-black">
                            {{ $totalTasks }} tasks
                        </span>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                        @foreach($taskGroups as $status => $group)
                            <div class="task-column min-w-0 rounded-2xl bg-slate-100/80 border border-slate-200 p-3 flex flex-col">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $group['dot'] }}"></span>
                                        <h3 class="font-black text-sm text-slate-900">{{ $group['label'] }}</h3>
                                    </div>

                                    <span class="task-column-count px-2.5 py-1 rounded-full bg-white text-slate-600 text-xs font-black">
                                        {{ $group['items']->count() }}
                                    </span>
                                </div>

                                <div class="task-drop-zone space-y-2.5 h-[610px] overflow-y-auto custom-scrollbar pr-1"
                                     data-status="{{ $status }}">

                                    @forelse($group['items'] as $task)
                                        @php
                                            $taskActivities = $activitiesByTask->get($task->id, collect());
                                            $taskImages = $taskActivities->flatMap->attachments;
                                            $formattedDue = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : '-';
                                        @endphp

                                        <div data-task-id="{{ $task->id }}"
                                             data-title="{{ e($task->title) }}"
                                             data-description="{{ e($task->description ?? '') }}"
                                             data-assigned="{{ $task->assigned_to }}"
                                             data-assignee="{{ e($task->employee->name ?? 'Unassigned') }}"
                                             data-due="{{ $formattedDue }}"
                                             data-due-raw="{{ $task->due_date }}"
                                             data-status="{{ $task->status }}"
                                             data-update-url="{{ route('operations.tasks.update', $task->id) }}"
                                             onclick="openTaskPanel(this)"
                                             class="task-card cursor-pointer rounded-2xl bg-white border border-slate-200 p-3.5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

                                            <div class="flex items-start justify-between gap-3">
                                                <h4 class="font-black text-sm text-slate-900 leading-snug">
                                                    {{ $task->title }}
                                                </h4>

                                                <span class="task-status-badge shrink-0 px-2.5 py-1 rounded-full text-[10px] font-black
                                                    @if($task->status === 'done') bg-emerald-100 text-emerald-700
                                                    @elseif($task->status === 'in_progress') bg-amber-100 text-amber-700
                                                    @elseif($task->status === 'cancelled') bg-red-100 text-red-700
                                                    @else bg-slate-100 text-slate-700
                                                    @endif">
                                                    {{ $task->status === 'pending' ? 'Todo' : ucwords(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </div>

                                            <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                                {{ $task->description ?? 'No description.' }}
                                            </p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @if($task->due_date)
                                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black">
                                                        Due {{ $formattedDue }}
                                                    </span>
                                                @endif

                                                @if($taskActivities->count())
                                                    <span class="px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 text-[10px] font-black">
                                                        {{ $taskActivities->count() }} updates
                                                    </span>
                                                @endif

                                                @if($taskImages->count())
                                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black">
                                                        {{ $taskImages->count() }} proofs
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-3 flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-black">
                                                        {{ strtoupper(substr($task->employee->name ?? 'U', 0, 1)) }}
                                                    </div>

                                                    <p class="text-xs text-slate-500 truncate">
                                                        {{ $task->employee->name ?? 'Unassigned' }}
                                                    </p>
                                                </div>

                                                <div class="flex items-center gap-2 shrink-0">
                                                    <a href="{{ route('operations.tasks.edit', $task->id) }}"
                                                    onclick="event.stopPropagation()"
                                                    class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[10px] font-black transition">
                                                        Edit
                                                    </a>

                                                    <a href="{{ route('operations.tasks.show', $task->id) }}"
                                                    onclick="event.stopPropagation()"
                                                    class="px-2.5 py-1 rounded-lg bg-slate-950 hover:bg-blue-600 text-white text-[10px] font-black transition">
                                                        Open
                                                    </a>
                                                </div>
                                            </div>

                                            <template id="task-activities-{{ $task->id }}">
                                                @forelse($taskActivities->sortByDesc('created_at') as $activity)
                                                    <div class="rounded-[1.3rem] border border-slate-200 bg-white p-4 shadow-sm">
                                                        <div class="flex gap-3">
                                                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black shrink-0">
                                                                {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                                                            </div>

                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-start justify-between gap-2">
                                                                    <div class="min-w-0">
                                                                        <p class="text-sm font-black text-slate-900 truncate">
                                                                            {{ $activity->user->name ?? 'Unknown User' }}
                                                                        </p>

                                                                        <p class="text-[11px] text-slate-400 font-bold">
                                                                            {{ $activity->created_at->diffForHumans() }}
                                                                        </p>
                                                                    </div>

                                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black
                                                                        @if($activity->status === 'completed')
                                                                            bg-emerald-100 text-emerald-700
                                                                        @elseif($activity->status === 'in_progress')
                                                                            bg-amber-100 text-amber-700
                                                                        @else
                                                                            bg-slate-100 text-slate-700
                                                                        @endif">
                                                                        {{ ucwords(str_replace('_', ' ', $activity->status ?? 'pending')) }}
                                                                    </span>
                                                                </div>

                                                                <p class="mt-3 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                                                    {{ $activity->description }}
                                                                </p>

                                                                @if($activity->time_started || $activity->time_ended)
                                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                                        @if($activity->time_started)
                                                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                                                                Start: {{ $activity->time_started }}
                                                                            </span>
                                                                        @endif

                                                                        @if($activity->time_ended)
                                                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                                                                End: {{ $activity->time_ended }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                @endif

                                                                @if($activity->attachments->count())
                                                                    <div class="mt-3 grid grid-cols-3 gap-2">
                                                                        @foreach($activity->attachments as $file)
                                                                            <a href="{{ asset('storage/' . $file->file_path) }}"
                                                                               target="_blank"
                                                                               class="group aspect-square overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                                                                <img src="{{ asset('storage/' . $file->file_path) }}"
                                                                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="rounded-[1.3rem] border border-slate-200 bg-white p-4 shadow-sm">
                                                        <p class="text-sm font-black text-slate-900">No activity yet.</p>
                                                        <p class="text-xs text-slate-500 mt-1">Updates and proof uploads connected to this task will appear here.</p>
                                                    </div>
                                                @endforelse
                                            </template>
                                        </div>
                                    @empty
                                        <div class="empty-column-message rounded-2xl border border-dashed border-slate-300 bg-white/50 p-8 text-center text-xs text-slate-400 font-bold">
                                            No cards yet.
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- PROJECT ACTIVITY FEED -->
            <div class="2xl:col-span-4">
                <div class="rounded-[1.6rem] bg-white/80 border border-slate-200 shadow-sm overflow-hidden 2xl:sticky 2xl:top-5">
                    <div class="px-4 sm:px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Project Feed</h2>
                            <p class="text-xs text-slate-500 font-semibold">Latest proof and updates.</p>
                        </div>

                        <span class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black">
                            {{ $project->activities->count() }}
                        </span>
                    </div>

                    <div class="max-h-[770px] overflow-y-auto custom-scrollbar divide-y divide-slate-100">
                        @forelse($project->activities->sortByDesc('created_at') as $activity)
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black shrink-0">
                                        {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-slate-900 truncate">
                                                    {{ $activity->user->name ?? 'Unknown User' }}
                                                </p>

                                                <p class="text-[11px] text-slate-400 font-bold">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </p>
                                            </div>

                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black
                                                @if($activity->status === 'completed')
                                                    bg-emerald-100 text-emerald-700
                                                @elseif($activity->status === 'in_progress')
                                                    bg-amber-100 text-amber-700
                                                @else
                                                    bg-slate-100 text-slate-700
                                                @endif">
                                                {{ ucwords(str_replace('_', ' ', $activity->status ?? 'pending')) }}
                                            </span>
                                        </div>

                                        <p class="mt-3 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                            {{ $activity->description }}
                                        </p>

                                        @if($activity->task)
                                            <div class="mt-3">
                                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-black">
                                                    {{ $activity->task->title }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($activity->attachments->count())
                                            <div class="mt-3 grid grid-cols-3 gap-2">
                                                @foreach($activity->attachments as $file)
                                                    <a href="{{ asset('storage/' . $file->file_path) }}"
                                                       target="_blank"
                                                       class="group aspect-square overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                                        <img src="{{ asset('storage/' . $file->file_path) }}"
                                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-slate-400 text-sm font-bold">
                                No activity updates yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- PREMIUM TASK SIDE PANEL -->
    <div id="taskPanel" class="fixed inset-0 z-[9999] hidden">
        <div onclick="closeTaskPanel()" class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

        <div class="absolute right-0 top-0 h-full w-full max-w-[1100px] bg-slate-50 shadow-2xl overflow-hidden flex flex-col">

            <div class="shrink-0 bg-white border-b border-slate-200 px-4 sm:px-5 py-4 flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="panelStatusBadge" class="px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-700">
                            Pending
                        </span>

                        <span id="panelTaskId" class="px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-500">
                            Task
                        </span>
                    </div>

                    <h2 id="panelTitle" class="mt-3 text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        Task Title
                    </h2>
                </div>

                <button onclick="closeTaskPanel()"
                        class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xl font-black transition shrink-0">
                    ×
                </button>
            </div>

            <div class="flex-1 overflow-hidden">
                <div class="grid grid-cols-1 xl:grid-cols-12 h-full">

                    <div class="xl:col-span-7 bg-white border-r border-slate-200 overflow-y-auto custom-scrollbar">
                        <div class="p-4 sm:p-5 space-y-4">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">Assigned To</p>

                                    <div class="mt-3 flex items-center gap-3">
                                        <div id="panelAssigneeAvatar" class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black">
                                            U
                                        </div>

                                        <div class="min-w-0">
                                            <p id="panelAssignee" class="text-sm font-black text-slate-900 truncate">Unassigned</p>
                                            <p class="text-xs text-slate-500">Team Member</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">Due Date</p>
                                    <p id="panelDueDate" class="mt-3 text-lg font-black text-slate-900">-</p>
                                    <p class="text-xs text-slate-500 mt-1">Deadline</p>
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
                                <div class="px-5 py-4 border-b border-slate-100">
                                    <h3 class="text-lg font-black text-slate-900">Description</h3>
                                    <p class="text-xs text-slate-500 mt-1">Full task scope and instructions.</p>
                                </div>

                                <div class="p-5">
                                    <p id="panelDescription" class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                        No description available.
                                    </p>
                                </div>
                            </div>

                            <form id="panelEditForm" method="POST" class="rounded-[1.5rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
                                @csrf
                                @method('PATCH')

                                <div class="px-5 py-4 border-b border-slate-100">
                                    <h3 class="text-lg font-black text-slate-900">Edit Task</h3>
                                    <p class="text-xs text-slate-500 mt-1">Admin can update task information here.</p>
                                </div>

                                <div class="p-5 space-y-4">
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                            Title
                                        </label>

                                        <input id="panelEditTitle"
                                               name="title"
                                               required
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                            Description
                                        </label>

                                        <textarea id="panelEditDescription"
                                                  name="description"
                                                  rows="6"
                                                  class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm text-slate-700 resize-none"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                                Assigned To
                                            </label>

                                            <select id="panelEditAssigned"
                                                    name="assigned_to"
                                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                                <option value="">Unassigned</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->name }} - {{ ucfirst($employee->role ?? 'employee') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                                Due Date
                                            </label>

                                            <input id="panelEditDueDate"
                                                   type="date"
                                                   name="due_date"
                                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                            Status
                                        </label>

                                        <select id="panelEditStatus"
                                                name="status"
                                                class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                            <option value="pending">To Do</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="done">Done</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-2">
                                        <button type="button"
                                                onclick="closeTaskPanel()"
                                                class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-black transition">
                                            Cancel
                                        </button>

                                        <button type="submit"
                                                class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="xl:col-span-5 bg-slate-50 overflow-y-auto custom-scrollbar">
                        <div class="p-4 sm:p-5 space-y-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Task Activity</h3>
                                <p class="text-xs text-slate-500 mt-1">Proof uploads and updates for this task.</p>
                            </div>

                            <div id="panelActivities" class="space-y-3">
                                <div class="rounded-[1.3rem] border border-slate-200 bg-white p-4 shadow-sm">
                                    <p class="text-sm font-black text-slate-900">No activity yet.</p>
                                    <p class="text-xs text-slate-500 mt-1">Task updates will appear here.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.task-drop-zone').forEach(function (zone) {
                new Sortable(zone, {
                    group: 'project-tasks',
                    animation: 180,
                    ghostClass: 'opacity-40',
                    dragClass: 'scale-[1.02]',

                    onStart: function () {
                        document.querySelectorAll('.empty-column-message').forEach(function (emptyBox) {
                            emptyBox.classList.add('hidden');
                        });
                    },

                    onEnd: function (event) {
                        const taskCard = event.item;
                        const taskId = taskCard.dataset.taskId;
                        const newStatus = event.to.dataset.status;

                        taskCard.classList.add('opacity-60');

                        fetch(`/operations/tasks/${taskId}/status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                window.location.reload();
                                return;
                            }

                            taskCard.dataset.status = newStatus;
                            taskCard.classList.remove('opacity-60');
                            updateTaskBadge(taskCard, newStatus);
                            updateColumnStates();
                        })
                        .catch(() => {
                            window.location.reload();
                        });
                    }
                });
            });

            function updateTaskBadge(taskCard, status) {
                const badge = taskCard.querySelector('.task-status-badge');

                if (!badge) return;

                badge.textContent =
                    status === 'pending' ? 'Todo' :
                    status === 'in_progress' ? 'In Progress' :
                    status === 'done' ? 'Done' :
                    'Cancelled';

                badge.className = 'task-status-badge shrink-0 px-2.5 py-1 rounded-full text-[10px] font-black';

                if (status === 'done') {
                    badge.classList.add('bg-emerald-100', 'text-emerald-700');
                } else if (status === 'in_progress') {
                    badge.classList.add('bg-amber-100', 'text-amber-700');
                } else if (status === 'cancelled') {
                    badge.classList.add('bg-red-100', 'text-red-700');
                } else {
                    badge.classList.add('bg-slate-100', 'text-slate-700');
                }
            }

            function updateColumnStates() {
                document.querySelectorAll('.task-column').forEach(function (column) {
                    const count = column.querySelectorAll('[data-task-id]').length;
                    const countBadge = column.querySelector('.task-column-count');
                    const emptyMessage = column.querySelector('.empty-column-message');

                    if (countBadge) countBadge.textContent = count;

                    if (emptyMessage) {
                        count === 0
                            ? emptyMessage.classList.remove('hidden')
                            : emptyMessage.classList.add('hidden');
                    }
                });

                const pending = document.querySelector('[data-status="pending"]').querySelectorAll('[data-task-id]').length;
                const progress = document.querySelector('[data-status="in_progress"]').querySelectorAll('[data-task-id]').length;
                const done = document.querySelector('[data-status="done"]').querySelectorAll('[data-task-id]').length;
                const total = pending + progress + done;
                const percentage = total > 0 ? Math.round((done / total) * 100) : 0;

                document.getElementById('heroPendingCount').textContent = pending;
                document.getElementById('heroProgressCount').textContent = progress;
                document.getElementById('heroDoneCount').textContent = done;
                document.getElementById('projectProgressText').textContent = percentage + '%';
                document.getElementById('projectProgressBar').style.width = percentage + '%';
            }

            updateColumnStates();

            window.openTaskPanel = function(card) {
                const panel = document.getElementById('taskPanel');
                const form = document.getElementById('panelEditForm');
                const taskId = card.dataset.taskId;
                const assignee = card.dataset.assignee || 'Unassigned';
                const status = card.dataset.status || 'pending';
                const activityTemplate = document.getElementById('task-activities-' + taskId);

                form.action = card.dataset.updateUrl;

                document.getElementById('panelTaskId').textContent = 'Task #' + taskId;
                document.getElementById('panelTitle').textContent = card.dataset.title || 'Untitled Task';
                document.getElementById('panelDescription').textContent = card.dataset.description || 'No description available.';
                document.getElementById('panelAssignee').textContent = assignee;
                document.getElementById('panelAssigneeAvatar').textContent = assignee.charAt(0).toUpperCase();
                document.getElementById('panelDueDate').textContent = card.dataset.due || '-';

                document.getElementById('panelEditTitle').value = card.dataset.title || '';
                document.getElementById('panelEditDescription').value = card.dataset.description || '';
                document.getElementById('panelEditAssigned').value = card.dataset.assigned || '';
                document.getElementById('panelEditDueDate').value = card.dataset.dueRaw || '';
                document.getElementById('panelEditStatus').value = status;

                const badge = document.getElementById('panelStatusBadge');

                badge.textContent =
                    status === 'pending' ? 'Todo' :
                    status === 'in_progress' ? 'In Progress' :
                    status === 'done' ? 'Done' :
                    'Cancelled';

                badge.className = 'px-3 py-1 rounded-full text-[11px] font-black';

                if (status === 'done') {
                    badge.classList.add('bg-emerald-100', 'text-emerald-700');
                } else if (status === 'in_progress') {
                    badge.classList.add('bg-amber-100', 'text-amber-700');
                } else if (status === 'cancelled') {
                    badge.classList.add('bg-red-100', 'text-red-700');
                } else {
                    badge.classList.add('bg-slate-100', 'text-slate-700');
                }

                if (activityTemplate) {
                    document.getElementById('panelActivities').innerHTML = activityTemplate.innerHTML;
                }

                panel.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            window.closeTaskPanel = function() {
                document.getElementById('taskPanel').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };
        });
    </script>
</x-app-layout>