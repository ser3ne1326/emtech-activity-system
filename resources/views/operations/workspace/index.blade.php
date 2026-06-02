<x-app-layout>
    <x-slot name="header">
        Workspace
    </x-slot>

    @php
        $totalTasks = $tasks->count();
        $pendingTasks = $tasks->where('status', 'pending')->count();
        $activeTasks = $tasks->where('status', 'in_progress')->count();
        $doneTasks = $tasks->where('status', 'done')->count();
    @endphp

    <div class="space-y-6 pt-2">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HERO -->
        <div class="rounded-[2rem] bg-slate-950 text-white overflow-hidden shadow-2xl">
            <div class="p-7 bg-[radial-gradient(circle_at_top_right,_rgba(59,130,246,.35),_transparent_35%)]">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                    <div>
                        <p class="text-slate-300 text-sm font-bold">
                            Today’s Work
                        </p>

                        <h1 class="text-4xl lg:text-5xl font-black tracking-tight mt-2">
                            Good day, {{ Auth::user()->name }} 👋
                        </h1>

                        <p class="text-slate-300 mt-3 text-lg">
                            Start work, submit updates, upload proof, and finish tasks faster.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 w-full xl:w-auto">
                        <div class="rounded-3xl bg-white/10 border border-white/10 px-6 py-5 text-center">
                            <p class="text-3xl font-black">{{ $pendingTasks }}</p>
                            <p class="text-xs text-slate-400 mt-1">To Do</p>
                        </div>

                        <div class="rounded-3xl bg-white/10 border border-white/10 px-6 py-5 text-center">
                            <p class="text-3xl font-black">{{ $activeTasks }}</p>
                            <p class="text-xs text-slate-400 mt-1">Active</p>
                        </div>

                        <div class="rounded-3xl bg-white/10 border border-white/10 px-6 py-5 text-center">
                            <p class="text-3xl font-black">{{ $doneTasks }}</p>
                            <p class="text-xs text-slate-400 mt-1">Done</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- TODAY'S WORK TABLE -->
<div class="glass-card rounded-[2rem] overflow-hidden">

    <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h2 class="text-2xl font-black text-slate-900">
                Today's Assignments
            </h2>

            <p class="text-slate-500 mt-1">
                Active technician tasks and operational updates.
            </p>
        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full min-w-[1100px]">

            <thead class="bg-slate-50 border-b border-slate-100">

                <tr class="text-left">

                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">
                        Task
                    </th>

                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">
                        Project
                    </th>

                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">
                        Client
                    </th>

                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">
                        Due Date
                    </th>

                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500 text-center">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($tasks as $task)

                    <tr class="hover:bg-slate-50 transition">

                        <!-- TASK -->
                        <td class="px-6 py-5 align-top">

                            <div class="max-w-[280px]">

                                <p class="font-black text-slate-900 leading-snug">
                                    {{ $task->title }}
                                </p>

                                <p class="text-sm text-slate-500 mt-2 line-clamp-2">
                                    {{ $task->description ?? 'No description.' }}
                                </p>

                            </div>

                        </td>

                        <!-- PROJECT -->
                        <td class="px-6 py-5 align-top">

                            <div>
                                <p class="font-black text-slate-900">
                                    {{ $task->project->title ?? '-' }}
                                </p>

                                <p class="text-sm text-blue-600 mt-1 font-bold">
                                    {{ $task->project->project_number ?? '-' }}
                                </p>
                            </div>

                        </td>

                        <!-- CLIENT -->
                        <td class="px-6 py-5 align-top">

                            <p class="font-semibold text-slate-700">
                                {{ $task->project->client->name ?? '-' }}
                            </p>

                        </td>

                        <!-- DUE -->
                        <td class="px-6 py-5 align-top">

                            <span class="font-bold
                                @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'done')
                                    text-red-600
                                @else
                                    text-slate-700
                                @endif">

                                {{ $task->due_date ?? '-' }}

                            </span>

                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-5 align-top">
                            <button type="button"
                                    data-task-id="{{ $task->id }}"
                                    data-status="{{ $task->status }}"
                                    onclick="cycleTaskStatus(this)"
                                    class="task-status-toggle px-4 py-2 rounded-full text-xs font-black transition-all duration-300 ease-out

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
                            </button>
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-6 py-5 align-top">

                            <div class="flex flex-col gap-2 min-w-[170px]">

                                <button type="button"
                                        onclick="openQuickLogModal('quickLogModal{{ $task->id }}')"
                                        class="w-full px-4 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black transition">
                                    Add Update
                                </button>

                                <a href="{{ route('operations.projects.show', $task->project_id) }}"
                                class="w-full text-center px-4 py-2.5 rounded-2xl bg-slate-950 hover:bg-slate-800 text-white text-sm font-black transition">
                                    View Project
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-6 py-16 text-center">

                            <h3 class="text-2xl font-black text-slate-900">
                                No Work Assigned
                            </h3>

                            <p class="text-slate-500 mt-3">
                                You currently have no assigned tasks.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

                    <!-- MODAL -->
                    <div id="quickLogModal{{ $task->id }}"
                         class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 overflow-y-auto">

                        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden mx-auto">

                            <div class="p-6 border-b border-slate-100 flex items-start justify-between gap-4">

                                <div>
                                    <h2 class="text-2xl font-black text-slate-900">
                                        Add Work Update
                                    </h2>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $task->title }}
                                    </p>
                                </div>

                                <button type="button"
                                        onclick="closeQuickLogModal('quickLogModal{{ $task->id }}')"
                                        class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 font-black">

                                    ×

                                </button>

                            </div>

                            <form method="POST"
                                  action="{{ route('operations.workspace.quick_log', $task->id) }}"
                                  enctype="multipart/form-data"
                                  class="p-6 space-y-5">

                                @csrf

                                <textarea name="description"
                                          required
                                          rows="4"
                                          placeholder="Add work update..."
                                          class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 px-4 py-3 resize-none"></textarea>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <input type="time"
                                           name="time_started"
                                           class="rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 px-4 py-3">

                                    <input type="time"
                                           name="time_ended"
                                           class="rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 px-4 py-3">

                                </div>

                                <input type="file"
                                       name="photos[]"
                                       multiple
                                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3">

                                <button class="w-full px-6 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black transition">

                                    Submit Update

                                </button>

                            </form>

                        </div>

                    </div>


    <script>
        function openQuickLogModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeQuickLogModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

    <script>

    function cycleTaskStatus(button) {

        const statuses = ['pending', 'in_progress', 'done', 'cancelled'];

        const labels = {
            pending: 'To Do',
            in_progress: 'In Progress',
            done: 'Done',
            cancelled: 'Cancelled'
        };

        const styles = {
            pending: ['bg-slate-100', 'text-slate-700'],
            in_progress: ['bg-amber-100', 'text-amber-700'],
            done: ['bg-emerald-100', 'text-emerald-700'],
            cancelled: ['bg-red-100', 'text-red-700']
        };

        const currentStatus = button.dataset.status;
        const currentIndex = statuses.indexOf(currentStatus);

        const nextStatus = statuses[(currentIndex + 1) % statuses.length];

        button.disabled = true;

        // ANIMATION OUT
        button.classList.add(
            'scale-95',
            'opacity-60'
        );

        fetch(`/operations/tasks/${button.dataset.taskId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: nextStatus
            })
        })
        .then(response => response.json())
        .then(data => {

            if (!data.success) {
                window.location.reload();
                return;
            }

            setTimeout(() => {

                button.dataset.status = nextStatus;

                button.textContent = labels[nextStatus];

                // RESET
                button.className =
                    'task-status-toggle px-4 py-2 rounded-full text-xs font-black transition-all duration-300 ease-out';

                // APPLY NEW STYLE
                button.classList.add(...styles[nextStatus]);

                // ANIMATION IN
                button.classList.remove(
                    'scale-95',
                    'opacity-60'
                );

                button.classList.add(
                    'scale-100'
                );

                button.disabled = false;

            }, 180);

        })
        .catch(() => {
            window.location.reload();
        });
    }
</script>

</x-app-layout>