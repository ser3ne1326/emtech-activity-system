<x-app-layout>
    <x-slot name="header">
        Edit Project
    </x-slot>

    <div class="space-y-4">

        @if ($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 px-4 py-3">
                <p class="font-black text-red-700 text-sm mb-2">Please fix these errors:</p>
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('operations.projects.update', $project->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('operations.projects.show', $project->id) }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 text-[11px] font-black transition">
                                    ← Project
                                </a>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    {{ $project->project_number }}
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                                Edit Project
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Update project details, client, priority, timeline, and current operational status.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ route('operations.projects.show', $project->id) }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                                Save Changes →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4">

                <div class="2xl:col-span-8 space-y-4">

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Project Identity
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Main project information shown across dashboard, tables, and workspace.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Project Title <span class="text-red-500">*</span>
                                </label>

                                <input name="title"
                                       required
                                       value="{{ old('title', $project->title) }}"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Client <span class="text-red-500">*</span>
                                    </label>

                                    <select name="client_id"
                                            required
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id) == $client->id)>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Service Type <span class="text-red-500">*</span>
                                    </label>

                                    <input name="service_type"
                                           required
                                           value="{{ old('service_type', $project->service_type) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="5"
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm text-slate-700 resize-none">{{ old('description', $project->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Timeline & Control
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Manage deadline, urgency, and current project progress state.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Start Date
                                    </label>

                                    <input type="date"
                                           name="start_date"
                                           value="{{ old('start_date', $project->start_date) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Due Date
                                    </label>

                                    <input type="date"
                                           name="due_date"
                                           value="{{ old('due_date', $project->due_date) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Priority <span class="text-red-500">*</span>
                                    </label>

                                    <select name="priority"
                                            required
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="low" @selected(old('priority', $project->priority) === 'low')>Low</option>
                                        <option value="normal" @selected(old('priority', $project->priority) === 'normal')>Normal</option>
                                        <option value="urgent" @selected(old('priority', $project->priority) === 'urgent')>Urgent</option>
                                        <option value="critical" @selected(old('priority', $project->priority) === 'critical')>Critical</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>

                                    <select name="status"
                                            required
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="pending" @selected(old('status', $project->status) === 'pending')>Pending</option>
                                        <option value="in_progress" @selected(old('status', $project->status) === 'in_progress')>In Progress</option>
                                        <option value="completed" @selected(old('status', $project->status) === 'completed')>Completed</option>
                                        <option value="cancelled" @selected(old('status', $project->status) === 'cancelled')>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="2xl:col-span-4">
                    <div class="2xl:sticky 2xl:top-5 space-y-4">
                        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                                <h3 class="text-lg font-black text-slate-900">
                                    Project Snapshot
                                </h3>
                            </div>

                            <div class="p-4 sm:p-5 space-y-3">
                                <div class="rounded-[1.4rem] bg-slate-950 text-white p-4">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                                        Project No.
                                    </p>
                                    <p class="mt-1 text-xl font-black">
                                        {{ $project->project_number }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                                        <p class="text-[10px] font-black text-slate-400 uppercase">
                                            Tasks
                                        </p>
                                        <p class="mt-1 text-2xl font-black text-slate-900">
                                            {{ $project->tasks()->count() }}
                                        </p>
                                    </div>

                                    <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                                        <p class="text-[10px] font-black text-slate-400 uppercase">
                                            Activities
                                        </p>
                                        <p class="mt-1 text-2xl font-black text-slate-900">
                                            {{ $project->activities()->count() }}
                                        </p>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                                    Save Project Changes
                                </button>

                                <a href="{{ route('operations.projects.show', $project->id) }}"
                                   class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                                    Back to Project
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>