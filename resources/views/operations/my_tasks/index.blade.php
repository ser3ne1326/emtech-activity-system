<x-app-layout>
    <x-slot name="header">
        My Tasks
    </x-slot>

    @php
        $taskItems = method_exists($tasks, 'items') ? collect($tasks->items()) : collect($tasks);

        $totalTasks = method_exists($tasks, 'total') ? $tasks->total() : $taskItems->count();
        $shownTasks = $taskItems->count();

        $pendingTasks = $taskItems->where('status', 'pending')->count();
        $progressTasks = $taskItems->where('status', 'in_progress')->count();
        $doneTasks = $taskItems->where('status', 'done')->count();
        $cancelledTasks = $taskItems->where('status', 'cancelled')->count();

        $dueSoonTasks = $taskItems->filter(function ($task) {
            if (!$task->due_date) {
                return false;
            }

            $dueDate = \Carbon\Carbon::parse($task->due_date)->startOfDay();
            $today = now()->startOfDay();

            return $dueDate->greaterThanOrEqualTo($today)
                && $today->diffInDays($dueDate) <= 3;
        })->count();

        $overdueTasks = $taskItems->filter(function ($task) {
            if (!$task->due_date) {
                return false;
            }

            $dueDate = \Carbon\Carbon::parse($task->due_date)->startOfDay();
            $today = now()->startOfDay();

            return $dueDate->lt($today)
                && $task->status !== 'done'
                && $task->status !== 'cancelled';
        })->count();

        $currentPage = method_exists($tasks, 'currentPage') ? $tasks->currentPage() : 1;
        $lastPage = method_exists($tasks, 'lastPage') ? $tasks->lastPage() : 1;

        $taskGroups = [
            'pending' => [
                'label' => 'To Do',
                'items' => $taskItems->where('status', 'pending'),
                'dot' => 'bg-slate-400',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'items' => $taskItems->where('status', 'in_progress'),
                'dot' => 'bg-amber-400',
            ],
            'done' => [
                'label' => 'Done',
                'items' => $taskItems->where('status', 'done'),
                'dot' => 'bg-emerald-400',
            ],
        ];
    @endphp

    <div class="space-y-4">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-black">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <p class="font-black mb-2">Please fix these errors:</p>
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HERO -->
        <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                My Assigned Tasks
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                Page {{ $currentPage }} / {{ $lastPage }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ $shownTasks }} shown
                            </span>
                        </div>

                        <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Review assigned work, update progress, open task details, and log activity proof.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 w-full xl:w-auto">
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center">
                            <p id="heroPendingCount" class="text-xl font-black">{{ $pendingTasks }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Todo</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center">
                            <p id="heroProgressCount" class="text-xl font-black">{{ $progressTasks }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Active</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center">
                            <p id="heroDoneCount" class="text-xl font-black">{{ $doneTasks }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Done</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- FILTER CARDS -->
        <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">

            <button type="button"
                    onclick="filterTasks('all', this)"
                    class="task-filter active-filter rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-3 sm:p-4 text-left shadow-sm transition">
                <p class="text-[11px] sm:text-xs font-black opacity-70 uppercase tracking-wide">
                    Total Tasks
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black">
                        {{ $totalTasks }}
                    </h3>
                    <span class="text-xs font-bold opacity-70">All</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterTasks('in_progress', this)"
                    class="task-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    In Progress
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 id="metricProgressCount" class="text-2xl sm:text-3xl font-black text-amber-500">
                        {{ $progressTasks }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Active</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterTasks('due_soon', this)"
                    class="task-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Due Soon
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-blue-600">
                        {{ $dueSoonTasks }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">3 days</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterTasks('overdue', this)"
                    class="task-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Overdue
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-red-500">
                        {{ $overdueTasks }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Late</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterTasks('done', this)"
                    class="task-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition col-span-2 xl:col-span-1">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Completed
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 id="metricDoneCount" class="text-2xl sm:text-3xl font-black text-emerald-500">
                        {{ $doneTasks }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Done</span>
                </div>
            </button>

        </div>

        <!-- TASK WORKSPACE -->
        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">

                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Task Workspace
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Table is default for scanning. Board is for workflow movement.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                                🔎
                            </span>

                            <input id="taskSearch"
                                   type="text"
                                   oninput="searchTasks()"
                                   placeholder="Search task, project, client..."
                                   class="w-full sm:w-96 pl-9 pr-3 py-2.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="flex items-center gap-1 rounded-2xl bg-slate-100 p-1">
                            <button id="tableViewBtn"
                                    type="button"
                                    onclick="setTaskView('table')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Table
                            </button>

                            <button id="boardViewBtn"
                                    type="button"
                                    onclick="setTaskView('board')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Board
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TABLE VIEW -->
            <div id="taskTableView" class="overflow-x-auto">
                <table class="w-full min-w-[1280px]">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Task</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Project</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Client</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Due Date</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Description</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($taskItems as $task)
                            @php
                                $dueStatus = 'normal';

                                if ($task->due_date) {
                                    $dueDate = \Carbon\Carbon::parse($task->due_date)->startOfDay();
                                    $today = now()->startOfDay();

                                    if (
                                        $dueDate->lt($today)
                                        && $task->status !== 'done'
                                        && $task->status !== 'cancelled'
                                    ) {
                                        $dueStatus = 'overdue';
                                    } elseif (
                                        $dueDate->greaterThanOrEqualTo($today)
                                        && $today->diffInDays($dueDate) <= 3
                                    ) {
                                        $dueStatus = 'due_soon';
                                    }
                                }

                                $formattedDue = $task->due_date
                                    ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y')
                                    : '-';

                                $searchText = strtolower(
                                    ($task->title ?? '') . ' ' .
                                    ($task->description ?? '') . ' ' .
                                    ($task->status ?? '') . ' ' .
                                    ($task->project->title ?? '') . ' ' .
                                    ($task->project->project_number ?? '') . ' ' .
                                    ($task->project->client->name ?? '')
                                );
                            @endphp

                            <tr class="task-row hover:bg-slate-50 transition"
                                data-task-id="{{ $task->id }}"
                                data-status="{{ $task->status }}"
                                data-due-status="{{ $dueStatus }}"
                                data-search="{{ $searchText }}">

                                <td class="px-5 py-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-slate-900 max-w-[260px] truncate">
                                            {{ $task->title }}
                                        </p>

                                        <p class="text-xs text-slate-400 font-bold mt-1">
                                            Task #{{ $task->id }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-slate-900 max-w-[230px] truncate">
                                        {{ $task->project->project_number ?? '-' }}
                                    </p>

                                    <p class="text-xs text-slate-500 font-bold mt-1 max-w-[230px] truncate">
                                        {{ $task->project->title ?? 'No Project' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-black shrink-0">
                                            {{ strtoupper(substr($task->project->client->name ?? 'C', 0, 1)) }}
                                        </div>

                                        <p class="text-sm font-bold text-slate-700 max-w-[180px] truncate">
                                            {{ $task->project->client->name ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black
                                        @if($dueStatus === 'overdue')
                                            bg-red-100 text-red-700
                                        @elseif($dueStatus === 'due_soon')
                                            bg-blue-100 text-blue-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ $formattedDue }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black task-status-badge
                                        @if($task->status === 'done')
                                            bg-emerald-100 text-emerald-700
                                        @elseif($task->status === 'in_progress')
                                            bg-amber-100 text-amber-700
                                        @elseif($task->status === 'cancelled')
                                            bg-red-100 text-red-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ $task->status === 'pending' ? 'To Do' : ucwords(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-600 max-w-[320px] line-clamp-2 leading-relaxed">
                                        {{ $task->description ?? 'No description.' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('operations.tasks.edit', $task->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-black transition">
                                            Edit
                                        </a>

                                        <a href="{{ route('operations.tasks.show', $task->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 hover:bg-blue-600 text-white text-xs font-black transition">
                                            Open
                                            <span>→</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                        ✅
                                    </div>

                                    <h3 class="mt-4 text-xl font-black text-slate-900">
                                        No assigned tasks
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Assigned work will appear here.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- BOARD VIEW -->
            <div id="taskBoardView" class="hidden p-4 sm:p-5">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                    @foreach($taskGroups as $status => $group)
                        <div class="task-column rounded-[1.5rem] bg-slate-100/80 border border-slate-200 p-3 flex flex-col">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $group['dot'] }}"></span>
                                    <h3 class="font-black text-sm text-slate-900">{{ $group['label'] }}</h3>
                                </div>

                                <span class="task-column-count px-2.5 py-1 rounded-full bg-white text-slate-600 text-xs font-black shadow-sm">
                                    {{ $group['items']->count() }}
                                </span>
                            </div>

                            <div class="task-drop-zone space-y-2.5 h-[620px] overflow-y-auto custom-scrollbar pr-1"
                                 data-status="{{ $status }}">

                                @forelse($group['items'] as $task)
                                    @php
                                        $dueStatus = 'normal';

                                        if ($task->due_date) {
                                            $dueDate = \Carbon\Carbon::parse($task->due_date)->startOfDay();
                                            $today = now()->startOfDay();

                                            if (
                                                $dueDate->lt($today)
                                                && $task->status !== 'done'
                                                && $task->status !== 'cancelled'
                                            ) {
                                                $dueStatus = 'overdue';
                                            } elseif (
                                                $dueDate->greaterThanOrEqualTo($today)
                                                && $today->diffInDays($dueDate) <= 3
                                            ) {
                                                $dueStatus = 'due_soon';
                                            }
                                        }

                                        $formattedDue = $task->due_date
                                            ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y')
                                            : '-';

                                        $searchText = strtolower(
                                            ($task->title ?? '') . ' ' .
                                            ($task->description ?? '') . ' ' .
                                            ($task->status ?? '') . ' ' .
                                            ($task->project->title ?? '') . ' ' .
                                            ($task->project->project_number ?? '') . ' ' .
                                            ($task->project->client->name ?? '')
                                        );
                                    @endphp

                                    <div data-task-id="{{ $task->id }}"
                                         data-status="{{ $task->status }}"
                                         data-due-status="{{ $dueStatus }}"
                                         data-search="{{ $searchText }}"
                                         onclick="openTaskPanelFromData(
                                             '{{ $task->id }}',
                                             @js($task->title),
                                             @js($task->description ?? 'No description.'),
                                             @js($task->project->title ?? 'No Project'),
                                             @js($task->project->client->name ?? '-'),
                                             '{{ $formattedDue }}',
                                             '{{ $task->status }}',
                                             '{{ route('operations.projects.show', $task->project_id) }}'
                                         )"
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
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black
                                                    @if($dueStatus === 'overdue')
                                                        bg-red-100 text-red-700
                                                    @elseif($dueStatus === 'due_soon')
                                                        bg-blue-100 text-blue-700
                                                    @else
                                                        bg-slate-100 text-slate-700
                                                    @endif">
                                                    Due {{ $formattedDue }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-3 rounded-2xl bg-slate-50 p-3 space-y-1">
                                            <p class="text-xs text-slate-500 truncate">
                                                <span class="font-black text-slate-700">Project:</span>
                                                {{ $task->project->title ?? 'No Project' }}
                                            </p>

                                            <p class="text-xs text-slate-500 truncate">
                                                <span class="font-black text-slate-700">Client:</span>
                                                {{ $task->project->client->name ?? '-' }}
                                            </p>
                                        </div>
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

            <!-- FILTERED CARD VIEW FOR DUE SOON / OVERDUE -->
            <div id="taskFilteredCardsView" class="hidden p-4 sm:p-5">
                <div id="taskFilteredCardsGrid" class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
                    @foreach($taskItems as $task)
                        @php
                            $dueStatus = 'normal';

                            if ($task->due_date) {
                                $dueDate = \Carbon\Carbon::parse($task->due_date)->startOfDay();
                                $today = now()->startOfDay();

                                if (
                                    $dueDate->lt($today)
                                    && $task->status !== 'done'
                                    && $task->status !== 'cancelled'
                                ) {
                                    $dueStatus = 'overdue';
                                } elseif (
                                    $dueDate->greaterThanOrEqualTo($today)
                                    && $today->diffInDays($dueDate) <= 3
                                ) {
                                    $dueStatus = 'due_soon';
                                }
                            }

                            $formattedDue = $task->due_date
                                ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y')
                                : '-';

                            $searchText = strtolower(
                                ($task->title ?? '') . ' ' .
                                ($task->description ?? '') . ' ' .
                                ($task->status ?? '') . ' ' .
                                ($task->project->title ?? '') . ' ' .
                                ($task->project->project_number ?? '') . ' ' .
                                ($task->project->client->name ?? '')
                            );
                        @endphp

                        <div data-task-id="{{ $task->id }}"
                             data-status="{{ $task->status }}"
                             data-due-status="{{ $dueStatus }}"
                             data-search="{{ $searchText }}"
                             onclick="openTaskPanelFromData(
                                 '{{ $task->id }}',
                                 @js($task->title),
                                 @js($task->description ?? 'No description.'),
                                 @js($task->project->title ?? 'No Project'),
                                 @js($task->project->client->name ?? '-'),
                                 '{{ $formattedDue }}',
                                 '{{ $task->status }}',
                                 '{{ route('operations.projects.show', $task->project_id) }}'
                             )"
                             class="task-filter-card cursor-pointer rounded-[1.5rem] bg-white border border-slate-200 p-4 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition">

                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs text-slate-400 font-black">
                                        Task #{{ $task->id }}
                                    </p>

                                    <h3 class="mt-2 text-lg font-black text-slate-900 leading-tight">
                                        {{ $task->title }}
                                    </h3>
                                </div>

                                <span class="shrink-0 px-2.5 py-1 rounded-full text-[10px] font-black
                                    @if($task->status === 'done') bg-emerald-100 text-emerald-700
                                    @elseif($task->status === 'in_progress') bg-amber-100 text-amber-700
                                    @elseif($task->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    {{ $task->status === 'pending' ? 'Todo' : ucwords(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>

                            <p class="mt-3 text-sm text-slate-600 line-clamp-2">
                                {{ $task->description ?? 'No description.' }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="px-3 py-1.5 rounded-full text-xs font-black
                                    @if($dueStatus === 'overdue')
                                        bg-red-100 text-red-700
                                    @elseif($dueStatus === 'due_soon')
                                        bg-blue-100 text-blue-700
                                    @else
                                        bg-slate-100 text-slate-700
                                    @endif">
                                    Due {{ $formattedDue }}
                                </span>

                                <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-black">
                                    {{ $task->project->project_number ?? '-' }}
                                </span>
                            </div>

                            <div class="mt-4 rounded-2xl bg-slate-50 p-3">
                                <p class="text-xs text-slate-500 truncate">
                                    <span class="font-black text-slate-700">Project:</span>
                                    {{ $task->project->title ?? 'No Project' }}
                                </p>

                                <p class="text-xs text-slate-500 truncate mt-1">
                                    <span class="font-black text-slate-700">Client:</span>
                                    {{ $task->project->client->name ?? '-' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="noTaskResults" class="hidden px-5 py-14 text-center border-t border-slate-100">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                    🔎
                </div>

                <h3 class="mt-4 text-xl font-black text-slate-900">
                    No matching tasks
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Try changing the search text or filter.
                </p>
            </div>

            @if(method_exists($tasks, 'links'))
                <div class="p-4 sm:p-5 border-t border-slate-100">
                    {{ $tasks->links() }}
                </div>
            @endif

        </div>

    </div>

    <!-- TASK DETAILS PANEL -->
    <div id="taskPanel" class="fixed inset-0 z-[9999] hidden">
        <div onclick="closeTaskPanel()" class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

        <div class="absolute right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl overflow-hidden flex flex-col">
            <div class="shrink-0 border-b border-slate-200 px-5 py-4 flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="panelStatus" class="px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-700">
                            Status
                        </span>

                        <span id="panelTaskId" class="px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-500">
                            Task
                        </span>
                    </div>

                    <h2 id="panelTitle" class="mt-3 text-2xl font-black text-slate-900 leading-tight">
                        Task Title
                    </h2>
                </div>

                <button onclick="closeTaskPanel()"
                        class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xl font-black transition">
                    ×
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                            Project
                        </p>

                        <p id="panelProject" class="mt-3 text-sm font-black text-slate-900 leading-relaxed">
                            -
                        </p>
                    </div>

                    <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                            Client
                        </p>

                        <p id="panelClient" class="mt-3 text-sm font-black text-slate-900 leading-relaxed">
                            -
                        </p>
                    </div>

                    <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                            Due Date
                        </p>

                        <p id="panelDue" class="mt-3 text-sm font-black text-slate-900 leading-relaxed">
                            -
                        </p>
                    </div>

                    <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                            Assigned To
                        </p>

                        <p class="mt-3 text-sm font-black text-slate-900 leading-relaxed">
                            Me
                        </p>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h3 class="text-lg font-black text-slate-900">
                            Description
                        </h3>

                        <p class="text-xs text-slate-500 mt-1">
                            Full task scope and instructions.
                        </p>
                    </div>

                    <div class="p-5">
                        <p id="panelDescription" class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            No description.
                        </p>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-slate-200 bg-slate-50 p-4 flex flex-col sm:flex-row sm:justify-end gap-3">
                <button onclick="closeTaskPanel()"
                        class="px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-black transition">
                    Close
                </button>

                <a id="panelProjectLink"
                   href="#"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                    Open Full Project Workspace
                    <span>→</span>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        let activeTaskFilter = 'all';
        let activeTaskView = 'table';

        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('myTasksView') || 'table';
            setTaskView(savedView);
            applyTaskVisibility();

            document.querySelectorAll('.task-drop-zone').forEach(function (zone) {
                new Sortable(zone, {
                    group: 'my-tasks',
                    animation: 180,
                    ghostClass: 'opacity-40',
                    dragClass: 'scale-[1.02]',

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
        });

        function setTaskView(view) {
            activeTaskView = view;

            localStorage.setItem('myTasksView', view);

            const tableView = document.getElementById('taskTableView');
            const boardView = document.getElementById('taskBoardView');
            const filteredCardsView = document.getElementById('taskFilteredCardsView');

            const tableBtn = document.getElementById('tableViewBtn');
            const boardBtn = document.getElementById('boardViewBtn');

            tableView.classList.add('hidden');
            boardView.classList.add('hidden');
            filteredCardsView.classList.add('hidden');

            if (view === 'board') {
                boardView.classList.remove('hidden');
                boardBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
                tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
            } else {
                tableView.classList.remove('hidden');
                tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
                boardBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
            }

            applyTaskVisibility();
        }

        function filterTasks(filter, button) {
            activeTaskFilter = filter;

            document.querySelectorAll('.task-filter').forEach(function (btn) {
                btn.classList.remove('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
                btn.classList.add('bg-white/80', 'border-slate-200');
            });

            if (button) {
                button.classList.remove('bg-white/80', 'border-slate-200');
                button.classList.add('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
            }

            applyTaskVisibility();
        }

        function searchTasks() {
            applyTaskVisibility();
        }

        function taskMatchesFilter(element) {
            if (activeTaskFilter === 'all') {
                return true;
            }

            if (activeTaskFilter === 'due_soon') {
                return element.dataset.dueStatus === 'due_soon';
            }

            if (activeTaskFilter === 'overdue') {
                return element.dataset.dueStatus === 'overdue';
            }

            return element.dataset.status === activeTaskFilter;
        }

        function taskMatchesSearch(element) {
            const searchInput = document.getElementById('taskSearch');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            if (!query) {
                return true;
            }

            return (element.dataset.search || '').includes(query);
        }

        function applyTaskVisibility() {
            const tableView = document.getElementById('taskTableView');
            const boardView = document.getElementById('taskBoardView');
            const filteredCardsView = document.getElementById('taskFilteredCardsView');

            const rows = document.querySelectorAll('.task-row');
            const boardCards = document.querySelectorAll('.task-card');
            const filterCards = document.querySelectorAll('.task-filter-card');
            const noResults = document.getElementById('noTaskResults');

            let visibleCount = 0;

            rows.forEach(function (row) {
                const visible = taskMatchesFilter(row) && taskMatchesSearch(row);
                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            boardCards.forEach(function (card) {
                const visible = taskMatchesFilter(card) && taskMatchesSearch(card);
                card.classList.toggle('hidden', !visible);
            });

            filterCards.forEach(function (card) {
                const visible = taskMatchesFilter(card) && taskMatchesSearch(card);
                card.classList.toggle('hidden', !visible);
            });

            if (activeTaskView === 'board' && ['due_soon', 'overdue'].includes(activeTaskFilter)) {
                boardView.classList.add('hidden');
                filteredCardsView.classList.remove('hidden');
            } else if (activeTaskView === 'board') {
                filteredCardsView.classList.add('hidden');
                boardView.classList.remove('hidden');
            }

            if (activeTaskView === 'table') {
                filteredCardsView.classList.add('hidden');
                boardView.classList.add('hidden');
                tableView.classList.remove('hidden');
            }

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }

            updateColumnStates();
        }

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
                const count = column.querySelectorAll('.task-card:not(.hidden)').length;
                const countBadge = column.querySelector('.task-column-count');
                const emptyMessage = column.querySelector('.empty-column-message');

                if (countBadge) countBadge.textContent = count;

                if (emptyMessage) {
                    count === 0
                        ? emptyMessage.classList.remove('hidden')
                        : emptyMessage.classList.add('hidden');
                }
            });

            const pending = document.querySelector('[data-status="pending"]')?.querySelectorAll('.task-card:not(.hidden)').length || 0;
            const progress = document.querySelector('[data-status="in_progress"]')?.querySelectorAll('.task-card:not(.hidden)').length || 0;
            const done = document.querySelector('[data-status="done"]')?.querySelectorAll('.task-card:not(.hidden)').length || 0;

            document.getElementById('heroPendingCount').textContent = pending;
            document.getElementById('heroProgressCount').textContent = progress;
            document.getElementById('heroDoneCount').textContent = done;

            const metricProgress = document.getElementById('metricProgressCount');
            const metricDone = document.getElementById('metricDoneCount');

            if (metricProgress) metricProgress.textContent = progress;
            if (metricDone) metricDone.textContent = done;
        }

        function openTaskPanelFromData(id, title, description, project, client, due, status, projectUrl) {
            const panel = document.getElementById('taskPanel');

            document.getElementById('panelTaskId').textContent = 'Task #' + id;
            document.getElementById('panelTitle').textContent = title || 'Untitled Task';
            document.getElementById('panelDescription').textContent = description || 'No description.';
            document.getElementById('panelProject').textContent = project || 'No Project';
            document.getElementById('panelClient').textContent = client || '-';
            document.getElementById('panelDue').textContent = due || '-';
            document.getElementById('panelProjectLink').href = projectUrl || '#';

            const badge = document.getElementById('panelStatus');

            badge.textContent =
                status === 'pending' ? 'To Do' :
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

            panel.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeTaskPanel() {
            document.getElementById('taskPanel').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
</x-app-layout>