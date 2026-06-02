<x-app-layout>
    <x-slot name="header">
        Log Activity
    </x-slot>

    <div class="space-y-4">

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

        <form method="POST"
              action="{{ route('operations.activities.store') }}"
              enctype="multipart/form-data"
              class="space-y-4">

            @csrf

            <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(147,51,234,.32),_transparent_35%)]"></div>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('operations.activities') }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 text-[11px] font-black transition">
                                    ← Activities
                                </a>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    New Work Log
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                                Log Activity
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Submit work progress, time record, task update, and proof photos.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ route('operations.activities') }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-black shadow-lg shadow-violet-900/30 transition">
                                Save Activity →
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
                                Work Reference
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Connect this activity to a project and task.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Project
                                </label>

                                <select name="project_id"
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                                            {{ $project->project_number }} - {{ $project->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Task
                                </label>

                                <select name="project_task_id"
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                    <option value="">Select Task</option>
                                    @foreach($tasks as $task)
                                        <option value="{{ $task->id }}" @selected(old('project_task_id') == $task->id)>
                                            {{ $task->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Activity Details
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Add time, status, type, and description.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Activity Date
                                    </label>

                                    <input type="date"
                                           name="activity_date"
                                           required
                                           value="{{ old('activity_date', now()->format('Y-m-d')) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Time Started
                                    </label>

                                    <input type="time"
                                           name="time_started"
                                           value="{{ old('time_started') }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Time Ended
                                    </label>

                                    <input type="time"
                                           name="time_ended"
                                           value="{{ old('time_ended') }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Status
                                    </label>

                                    <select name="status"
                                            required
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                                        <option value="in_progress" @selected(old('status') == 'in_progress')>In Progress</option>
                                        <option value="completed" @selected(old('status') == 'completed')>Completed</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Activity Type
                                </label>

                                <input type="text"
                                       name="activity_type"
                                       value="{{ old('activity_type') }}"
                                       placeholder="Example: Camera Installation, Cable Management, System Configuration"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Description
                                </label>

                                <textarea name="description"
                                          required
                                          rows="5"
                                          placeholder="Describe the work completed, issues found, progress made, or proof context..."
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm text-slate-700 resize-none">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Proof Photos
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Upload photo proof for this work update.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5">
                            <label class="block rounded-[1.5rem] border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-white hover:border-violet-300 transition cursor-pointer p-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-black text-slate-900">
                                            Drop proof images or click to upload
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Upload multiple work photos as proof of progress or completion.
                                        </p>
                                    </div>

                                    <span class="px-4 py-2 rounded-2xl bg-violet-600 text-white text-xs font-black w-fit">
                                        Choose Photos
                                    </span>
                                </div>

                                <input type="file"
                                       name="photos[]"
                                       multiple
                                       accept="image/*"
                                       class="hidden">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="2xl:col-span-4">
                    <div class="2xl:sticky 2xl:top-5 rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h3 class="text-lg font-black text-slate-900">
                                Submit Summary
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                This activity will be saved under your account.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500 font-bold">Submitted By</span>
                                <span class="text-slate-900 font-black truncate max-w-[180px]">
                                    {{ Auth::user()->name }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500 font-bold">Proof Upload</span>
                                <span class="px-3 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-black">
                                    Optional
                                </span>
                            </div>

                            <div class="pt-3 border-t border-slate-100">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-black shadow-lg shadow-violet-900/20 transition">
                                    Save Activity →
                                </button>

                                <a href="{{ route('operations.activities') }}"
                                   class="mt-3 w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>