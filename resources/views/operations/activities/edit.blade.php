<x-app-layout>
    <x-slot name="header">
        Edit Activity
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
              action="{{ route('operations.activities.update', $activity->id) }}"
              enctype="multipart/form-data"
              class="space-y-4">

            @csrf
            @method('PUT')

            <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(147,51,234,.32),_transparent_35%)]"></div>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('operations.activities.show', $activity->id) }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 text-[11px] font-black transition">
                                    ← Activity
                                </a>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    Activity #{{ $activity->id }}
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                                Edit Activity
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Update work details, project/task connection, status, time, and upload additional proof.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ route('operations.activities.show', $activity->id) }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-black shadow-lg shadow-violet-900/30 transition">
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
                                Work Connection
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Connect this activity to a project and optional task.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Project
                                </label>

                                <select name="project_id"
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                    <option value="">No Project</option>

                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" @selected(old('project_id', $activity->project_id) == $project->id)>
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
                                    <option value="">No Task</option>

                                    @foreach($tasks as $task)
                                        <option value="{{ $task->id }}" @selected(old('project_task_id', $activity->project_task_id) == $task->id)>
                                            {{ $task->project->project_number ?? 'No Project' }} - {{ $task->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Activity Type
                                </label>

                                <input name="activity_type"
                                       value="{{ old('activity_type', $activity->activity_type) }}"
                                       placeholder="Example: Installation Update"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                            </div>

                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Activity Details
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Main work log information.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Description <span class="text-red-500">*</span>
                                </label>

                                <textarea name="description"
                                          rows="7"
                                          required
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm text-slate-700 resize-none">{{ old('description', $activity->description) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Activity Date <span class="text-red-500">*</span>
                                    </label>

                                    <input type="date"
                                           name="activity_date"
                                           required
                                           value="{{ old('activity_date', $activity->activity_date) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Time Started
                                    </label>

                                    <input type="time"
                                           name="time_started"
                                           value="{{ old('time_started', $activity->time_started) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Time Ended
                                    </label>

                                    <input type="time"
                                           name="time_ended"
                                           value="{{ old('time_ended', $activity->time_ended) }}"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>

                                <select name="status"
                                        required
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 px-4 py-3 text-sm font-bold text-slate-800">
                                    <option value="pending" @selected(old('status', $activity->status) === 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status', $activity->status) === 'in_progress')>In Progress</option>
                                    <option value="completed" @selected(old('status', $activity->status) === 'completed')>Completed</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Add More Proof
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Upload additional photos. Existing photos will stay.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5">
                            <input type="file"
                                   name="photos[]"
                                   multiple
                                   accept="image/*"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-violet-600 file:px-4 file:py-2 file:text-sm file:font-black file:text-white hover:file:bg-violet-700">
                        </div>
                    </div>

                </div>

                <div class="2xl:col-span-4">
                    <div class="2xl:sticky 2xl:top-5 space-y-4">

                        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                                <h3 class="text-lg font-black text-slate-900">
                                    Current Proof
                                </h3>
                            </div>

                            <div class="p-4 sm:p-5">
                                @if($activity->attachments->count())
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($activity->attachments as $file)
                                            <a href="{{ asset('storage/' . $file->file_path) }}"
                                               target="_blank"
                                               class="aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                                <img src="{{ asset('storage/' . $file->file_path) }}"
                                                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-10 text-center">
                                        <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-2xl">
                                            🖼️
                                        </div>

                                        <p class="mt-3 text-sm font-black text-slate-900">
                                            No proof yet
                                        </p>
                                    </div>
                                @endif

                                <div class="mt-4 grid grid-cols-1 gap-3">
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-black shadow-lg shadow-violet-900/20 transition">
                                        Save Activity Changes
                                    </button>

                                    <a href="{{ route('operations.activities.show', $activity->id) }}"
                                       class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                                        Back to Activity
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>