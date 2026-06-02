<x-app-layout>
    <x-slot name="header">
        Employee Details
    </x-slot>

    @php
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'done')->count();
        $pendingTasks = $tasks->where('status', 'pending')->count();
        $inProgressTasks = $tasks->where('status', 'in_progress')->count();
        $cancelledTasks = $tasks->where('status', 'cancelled')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    <div class="space-y-4">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-black">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-2xl font-black shadow-lg shrink-0">
                            {{ strtoupper(substr($employee->name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                    {{ $employee->name }}
                                </h1>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    Employee #{{ $employee->id }}
                                </span>

                                <span class="px-3 py-1.5 rounded-full bg-blue-600/80 border border-blue-400/20 text-[11px] font-black">
                                    {{ ucwords(str_replace('_', ' ', $employee->role)) }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-slate-300 max-w-3xl leading-relaxed">
                                {{ $employee->position ?: 'Operations Team Member' }}
                            </p>

                            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 font-black">
                                    {{ $employee->email }}
                                </span>

                                @if($employee->phone)
                                    <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 font-black">
                                        {{ $employee->phone }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('operations.employees.edit', $employee->id) }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                            Edit Employee
                        </a>

                        <a href="{{ route('operations.employees') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                            Back →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-6 gap-3">
            <div class="rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-4">
                <p class="text-[11px] font-black opacity-70 uppercase tracking-wide">Total Tasks</p>
                <h3 class="mt-2 text-3xl font-black">{{ $totalTasks }}</h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">Completed</p>
                <h3 class="mt-2 text-3xl font-black text-emerald-600">{{ $completedTasks }}</h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">In Progress</p>
                <h3 class="mt-2 text-3xl font-black text-blue-600">{{ $inProgressTasks }}</h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">Pending</p>
                <h3 class="mt-2 text-3xl font-black text-orange-500">{{ $pendingTasks }}</h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">Activities</p>
                <h3 class="mt-2 text-3xl font-black text-violet-600">{{ $activities->count() }}</h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">Rate</p>
                <h3 class="mt-2 text-3xl font-black text-slate-900">{{ $completionRate }}%</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4">

            <div class="2xl:col-span-8 rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                        Assigned Tasks
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        All tasks currently assigned to this employee.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">Task</th>
                                <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">Project</th>
                                <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">Client</th>
                                <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">Due Date</th>
                                <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($tasks as $task)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-black text-slate-900">
                                            {{ $task->title }}
                                        </p>
                                        <p class="text-xs text-slate-400 font-bold mt-1 line-clamp-1">
                                            {{ $task->description ?: 'No description' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($task->project)
                                            <a href="{{ route('operations.projects.show', $task->project->id) }}"
                                               class="text-sm font-black text-blue-600 hover:text-blue-700">
                                                {{ $task->project->title }}
                                            </a>
                                        @else
                                            <span class="text-sm text-slate-400 font-bold">-</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm font-bold text-slate-700">
                                            {{ $task->project?->client?->name ?: '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="text-sm font-bold text-slate-700">
                                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : '-' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black
                                            @if($task->status === 'done')
                                                bg-emerald-50 text-emerald-700
                                            @elseif($task->status === 'in_progress')
                                                bg-blue-50 text-blue-700
                                            @elseif($task->status === 'cancelled')
                                                bg-red-50 text-red-700
                                            @else
                                                bg-orange-50 text-orange-700
                                            @endif">
                                            {{ strtoupper(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                            ✅
                                        </div>
                                        <h3 class="mt-4 text-xl font-black text-slate-900">No assigned tasks</h3>
                                        <p class="mt-2 text-sm text-slate-500">This employee does not have assigned tasks yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="2xl:col-span-4 rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-900">
                        Recent Activities
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Latest activity logs and proof updates.
                    </p>
                </div>

                <div class="p-4 sm:p-5 space-y-3 max-h-[650px] overflow-y-auto">
                    @forelse($activities as $activity)
                        <div class="rounded-[1.4rem] border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-black text-slate-900">
                                        {{ $activity->activity_type ?: 'Activity Update' }}
                                    </p>

                                    <p class="mt-1 text-xs font-bold text-slate-500">
                                        {{ $activity->activity_date ? \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y') : '-' }}
                                    </p>
                                </div>

                                <span class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[10px] font-black text-slate-600">
                                    {{ strtoupper(str_replace('_', ' ', $activity->status)) }}
                                </span>
                            </div>

                            <p class="mt-3 text-sm text-slate-600 leading-relaxed line-clamp-3">
                                {{ $activity->description }}
                            </p>

                            @if($activity->project)
                                <p class="mt-3 text-xs font-black text-blue-600">
                                    {{ $activity->project->title }}
                                </p>
                            @endif

                            @if($activity->attachments->count())
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($activity->attachments->take(3) as $attachment)
                                        <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                             class="w-14 h-14 rounded-xl object-cover border border-slate-200">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-14 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                📝
                            </div>
                            <h3 class="mt-4 text-xl font-black text-slate-900">No activities yet</h3>
                            <p class="mt-2 text-sm text-slate-500">This employee has not submitted activity logs yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>