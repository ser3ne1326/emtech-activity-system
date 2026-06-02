<x-app-layout>
    <x-slot name="header">
        Client Details
    </x-slot>

    @php
        $totalProjects = $projects->count();
        $completedProjects = $projects->where('status', 'completed')->count();
        $activeProjects = $projects->where('status', 'in_progress')->count();

        $totalTasks = $projects->sum(fn($project) => $project->tasks->count());
        $totalActivities = $projects->sum(fn($project) => $project->activities->count());
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
                            {{ strtoupper(substr($client->name ?? 'C', 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                    {{ $client->name }}
                                </h1>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    Client #{{ $client->id }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-slate-300 max-w-3xl leading-relaxed">
                                {{ $client->business_type ?: 'General Business' }}
                            </p>

                            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                                @if($client->email)
                                    <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 font-black">
                                        {{ $client->email }}
                                    </span>
                                @endif

                                @if($client->phone)
                                    <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 font-black">
                                        {{ $client->phone }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('operations.clients.edit', $client->id) }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                            Edit Client
                        </a>

                        <a href="{{ route('operations.projects.create') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                            New Project →
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">

            <div class="rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-4">
                <p class="text-[11px] font-black opacity-70 uppercase tracking-wide">
                    Total Projects
                </p>

                <h3 class="mt-2 text-3xl font-black">
                    {{ $totalProjects }}
                </h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                    Active
                </p>

                <h3 class="mt-2 text-3xl font-black text-blue-600">
                    {{ $activeProjects }}
                </h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                    Completed
                </p>

                <h3 class="mt-2 text-3xl font-black text-emerald-600">
                    {{ $completedProjects }}
                </h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                    Tasks
                </p>

                <h3 class="mt-2 text-3xl font-black text-violet-600">
                    {{ $totalTasks }}
                </h3>
            </div>

            <div class="rounded-[1.4rem] bg-white border border-slate-200 p-4">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                    Activities
                </p>

                <h3 class="mt-2 text-3xl font-black text-orange-500">
                    {{ $totalActivities }}
                </h3>
            </div>

        </div>

        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                    Client Projects
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Connected operational projects for this client.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px]">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">
                                Project
                            </th>

                            <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">
                                Service
                            </th>

                            <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">
                                Priority
                            </th>

                            <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">
                                Status
                            </th>

                            <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">
                                Tasks
                            </th>

                            <th class="px-5 py-3 text-left text-[11px] font-black text-slate-500 uppercase">
                                Activities
                            </th>

                            <th class="px-5 py-3 text-right text-[11px] font-black text-slate-500 uppercase">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($projects as $project)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-5 py-4">
                                    <div>
                                        <p class="text-sm font-black text-slate-900">
                                            {{ $project->title }}
                                        </p>

                                        <p class="text-xs text-slate-400 font-bold mt-1">
                                            {{ $project->project_number }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm font-bold text-slate-700">
                                        {{ $project->service_type }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 text-xs font-black">
                                        {{ strtoupper($project->priority) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black">
                                        {{ strtoupper(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm font-black text-slate-700">
                                        {{ $project->tasks->count() }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm font-black text-slate-700">
                                        {{ $project->activities->count() }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('operations.projects.show', $project->id) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 hover:bg-blue-600 text-white text-xs font-black transition">
                                        Open →
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">

                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                        📁
                                    </div>

                                    <h3 class="mt-4 text-xl font-black text-slate-900">
                                        No projects yet
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Create the first project connected to this client.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
</x-app-layout>