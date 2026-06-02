<x-app-layout>
    <x-slot name="header">
        Activity Details
    </x-slot>

    <div class="space-y-4">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-black">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(147,51,234,.32),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-violet-600 text-white flex items-center justify-center text-2xl font-black shadow-lg shrink-0">
                            {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                    {{ $activity->activity_type ?: 'Activity Update' }}
                                </h1>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    Activity #{{ $activity->id }}
                                </span>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Submitted by {{ $activity->user->name ?? 'Unknown User' }}
                            </p>

                            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 font-black">
                                    {{ $activity->activity_date ? \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y') : '-' }}
                                </span>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 font-black">
                                    {{ $activity->time_started ? \Carbon\Carbon::parse($activity->time_started)->format('g:iA') : '--:--' }}
                                    -
                                    {{ $activity->time_ended ? \Carbon\Carbon::parse($activity->time_ended)->format('g:iA') : '--:--' }}
                                </span>

                                <span class="px-3 py-1.5 rounded-full bg-violet-600/80 border border-violet-400/20 font-black">
                                    {{ $activity->attachments->count() }} Proof
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('operations.activities.edit', $activity->id) }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                            Edit Activity
                        </a>

                        <a href="{{ route('operations.activities') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-black shadow-lg shadow-violet-900/30 transition">
                            Back →
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4">

            <div class="2xl:col-span-8 space-y-4">

                <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Work Description
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Full activity notes submitted by the employee.
                        </p>
                    </div>

                    <div class="p-5">
                        <p class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $activity->description }}
                        </p>
                    </div>
                </div>

                <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Proof Gallery
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Uploaded photos or proof connected to this activity.
                        </p>
                    </div>

                    <div class="p-5">
                        @if($activity->attachments->count())
                            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                                @foreach($activity->attachments as $file)
                                    <a href="{{ asset('storage/' . $file->file_path) }}"
                                       target="_blank"
                                       class="group overflow-hidden rounded-[1.4rem] border border-slate-200 bg-slate-100 shadow-sm">
                                        <div class="aspect-square overflow-hidden">
                                            <img src="{{ asset('storage/' . $file->file_path) }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>

                                        <div class="p-3 bg-white">
                                            <p class="text-xs font-black text-slate-700 truncate">
                                                {{ $file->file_name }}
                                            </p>

                                            <p class="mt-1 text-[11px] font-bold text-slate-400">
                                                Click to open
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="py-14 text-center">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                    🖼️
                                </div>

                                <h3 class="mt-4 text-xl font-black text-slate-900">
                                    No proof uploaded
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">
                                    You can add proof by editing this activity.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="2xl:col-span-4 space-y-4">

                <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-900">
                            Related Records
                        </h2>
                    </div>

                    <div class="p-5 space-y-3">

                        <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                                Employee
                            </p>

                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $activity->user->name ?? 'Unknown User' }}
                            </p>

                            <p class="mt-1 text-xs font-bold text-slate-500">
                                {{ $activity->user->email ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                                Project
                            </p>

                            @if($activity->project)
                                <a href="{{ route('operations.projects.show', $activity->project->id) }}"
                                   class="mt-1 block text-sm font-black text-blue-600 hover:text-blue-700">
                                    {{ $activity->project->project_number }} - {{ $activity->project->title }}
                                </a>

                                <p class="mt-1 text-xs font-bold text-slate-500">
                                    Client: {{ $activity->project->client->name ?? '-' }}
                                </p>
                            @else
                                <p class="mt-1 text-sm font-black text-slate-400">
                                    No Project
                                </p>
                            @endif
                        </div>

                        <div class="rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                                Task
                            </p>

                            <p class="mt-1 text-sm font-black text-slate-900">
                                {{ $activity->task->title ?? 'No Task' }}
                            </p>

                            @if($activity->task)
                                <p class="mt-1 text-xs font-bold text-slate-500">
                                    Status: {{ ucfirst(str_replace('_', ' ', $activity->task->status)) }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-900">
                            Activity Timeline
                        </h2>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-black">
                                1
                            </div>

                            <div>
                                <p class="text-sm font-black text-slate-900">
                                    Activity created
                                </p>

                                <p class="text-xs font-bold text-slate-500 mt-1">
                                    {{ $activity->created_at ? $activity->created_at->format('M d, Y h:i A') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-black">
                                2
                            </div>

                            <div>
                                <p class="text-sm font-black text-slate-900">
                                    Last updated
                                </p>

                                <p class="text-xs font-bold text-slate-500 mt-1">
                                    {{ $activity->updated_at ? $activity->updated_at->format('M d, Y h:i A') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-black">
                                3
                            </div>

                            <div>
                                <p class="text-sm font-black text-slate-900">
                                    Current status
                                </p>

                                <p class="text-xs font-bold text-slate-500 mt-1">
                                    {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>