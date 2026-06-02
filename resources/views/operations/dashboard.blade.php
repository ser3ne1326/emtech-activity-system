<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900">
                Operations Dashboard
            </h1>
            <p class="text-slate-500 mt-1">
                Monitor projects, technicians, activities, and operational performance.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('operations.clients') }}"
            class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-500/20 transition">
                Add Client
            </a>

            <a href="{{ route('operations.projects') }}"
            class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-lg shadow-emerald-500/20 transition">
                New Project
            </a>

            <a href="{{ route('operations.activities') }}"
            class="px-5 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-semibold shadow-lg shadow-violet-500/20 transition">
                Log Activity
            </a>
        </div>
    </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">

            <div class="glass-card rounded-[1.7rem] p-4 sm:p-5 xl:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-slate-500">
                            Total Projects
                        </p>

                        <h3 class="mt-2 text-2xl sm:text-3xl xl:text-4xl font-black text-slate-900">
                            {{ $totalProjects ?? 0 }}
                        </h3>

                        <p class="mt-2 text-sm text-emerald-600 font-semibold">
                            +12% this month
                        </p>
                    </div>

                    <div class="w-11 h-11 sm:w-12 sm:h-12 xl:w-14 xl:h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-7 h-7 text-blue-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-[1.7rem] p-4 sm:p-5 xl:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-slate-500">
                            Active Projects
                        </p>

                        <h3 class="mt-2 text-2xl sm:text-3xl xl:text-4xl font-black text-slate-900">
                            {{ $activeProjects ?? 0 }}
                        </h3>

                        <p class="mt-2 text-sm text-amber-600 font-semibold">
                            Ongoing operations
                        </p>
                    </div>

                    <div class="w-11 h-11 sm:w-12 sm:h-12 xl:w-14 xl:h-14 rounded-2xl bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-7 h-7 text-amber-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-[1.7rem] p-4 sm:p-5 xl:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-slate-500">
                            Employees
                        </p>

                        <h3 class="mt-2 text-2xl sm:text-3xl xl:text-4xl font-black text-slate-900">
                            {{ $totalEmployees ?? $employeesCount ?? 0 }}
                        </h3>

                        <p class="mt-2 text-sm text-blue-600 font-semibold">
                            Active workforce
                        </p>
                    </div>

                    <div class="w-11 h-11 sm:w-12 sm:h-12 xl:w-14 xl:h-14 rounded-2xl bg-violet-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-7 h-7 text-violet-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-[1.7rem] p-4 sm:p-5 xl:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-slate-500">
                            Completed
                        </p>

                        <h3 class="mt-2 text-2xl sm:text-3xl xl:text-4xl font-black text-slate-900">
                            {{ $completedProjects ?? 0 }}
                        </h3>

                        <p class="mt-2 text-sm text-emerald-600 font-semibold">
                            Finished projects
                        </p>
                    </div>

                    <div class="w-11 h-11 sm:w-12 sm:h-12 xl:w-14 xl:h-14 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-7 h-7 text-emerald-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- RECENT PROJECTS -->
            <div class="xl:col-span-2 glass-card rounded-3xl overflow-hidden">

                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">
                            Recent Projects
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Latest operational and field service projects.
                        </p>
                    </div>

                    <a href="{{ route('operations.projects') }}"
                       class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">

                        <thead class="bg-slate-50">
                            <tr class="text-left text-sm text-slate-500">
                                <th class="px-6 py-4 font-semibold">Project</th>
                                <th class="px-6 py-4 font-semibold">Client</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Due Date</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse(($recentProjects ?? $projects ?? collect()) as $project)
                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-6 py-5">
                                        <div>
                                            <p class="font-bold text-slate-900">
                                                {{ $project->project_number }}
                                            </p>

                                            <p class="text-sm text-slate-500 mt-1">
                                                {{ $project->title }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 text-slate-700">
                                        {{ $project->client->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                            @if($project->status === 'completed')
                                                bg-emerald-100 text-emerald-700
                                            @elseif($project->status === 'in_progress')
                                                bg-amber-100 text-amber-700
                                            @else
                                                bg-slate-100 text-slate-700
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-slate-600">
                                        {{ $project->due_date ?? '-' }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                        No projects available.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>
            </div>

            <!-- ACTIVITY FEED -->
            <div class="glass-card rounded-3xl overflow-hidden">

                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-xl font-black text-slate-900">
                        Recent Activities
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Latest updates from employees and technicians.
                    </p>
                </div>

                <div class="divide-y divide-slate-100">

                    @forelse(($recentActivities ?? $activities ?? collect()) as $activity)
                        <div class="p-5 flex items-start gap-4">

                            <div class="w-11 h-11 rounded-2xl bg-blue-100 flex items-center justify-center font-bold text-blue-700">
                                {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">

                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="font-bold text-slate-900 truncate">
                                        {{ $activity->user->name ?? 'Unknown User' }}
                                    </h4>

                                    <span class="text-xs text-slate-400">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-600 mt-1">
                                    {{ $activity->description }}
                                </p>

                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $activity->project->title ?? 'No Project' }}
                                </p>

                            </div>

                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400">
                            No activity logs yet.
                        </div>
                    @endforelse

                </div>

            </div>

        </div>

    </div>
</x-app-layout>