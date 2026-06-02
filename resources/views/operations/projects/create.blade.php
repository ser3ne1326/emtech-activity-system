<x-app-layout>
    <x-slot name="header">
        Create Project
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

        <form method="POST" action="{{ route('operations.projects.store') }}" id="createProjectForm">
            @csrf

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('operations.projects') }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 text-[11px] font-black transition">
                                    ← Projects
                                </a>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    New Workspace
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                                Create Project
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Set up the project workspace first, then assign tasks, monitor updates, and collect proof of work.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <button type="button"
                                    onclick="window.history.back()"
                                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                                <span>Create Workspace</span>
                                <span>→</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 2xl:grid-cols-12 gap-4 mt-4">

                <!-- MAIN FORM -->
                <div class="2xl:col-span-8 space-y-4">

                    <!-- SECTION: BASIC INFO -->
                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-black text-slate-900">
                                    Project Identity
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                    The core details that identify the project.
                                </p>
                            </div>

                            <span class="hidden sm:inline-flex px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-black">
                                Required
                            </span>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">

                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                    Project Title <span class="text-red-500">*</span>
                                </label>

                                <input type="text"
                                       name="title"
                                       id="projectTitleInput"
                                       value="{{ old('title') }}"
                                       required
                                       oninput="updateProjectPreview()"
                                       placeholder="Example: CCTV Installation - Office Building"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800 placeholder:text-slate-400">
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                        Client <span class="text-red-500">*</span>
                                    </label>

                                    <select name="client_id"
                                            id="clientInput"
                                            required
                                            onchange="updateProjectPreview()"
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                        Service Type <span class="text-red-500">*</span>
                                    </label>

                                    <select name="service_type"
                                            id="serviceInput"
                                            required
                                            onchange="updateProjectPreview()"
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="">Select Service</option>
                                        <option value="CCTV Installation" {{ old('service_type') == 'CCTV Installation' ? 'selected' : '' }}>CCTV Installation</option>
                                        <option value="Networking" {{ old('service_type') == 'Networking' ? 'selected' : '' }}>Networking</option>
                                        <option value="PC Deployment" {{ old('service_type') == 'PC Deployment' ? 'selected' : '' }}>PC Deployment</option>
                                        <option value="Maintenance" {{ old('service_type') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="Business System" {{ old('service_type') == 'Business System' ? 'selected' : '' }}>Business System</option>
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- SECTION: PLANNING -->
                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Planning Details
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Set the project priority and timeline.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                        Priority
                                    </label>

                                    <select name="priority"
                                            id="priorityInput"
                                            onchange="updateProjectPreview()"
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                        Start Date
                                    </label>

                                    <input type="date"
                                           name="start_date"
                                           id="startDateInput"
                                           value="{{ old('start_date') }}"
                                           onchange="updateProjectPreview()"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                        Due Date
                                    </label>

                                    <input type="date"
                                           name="due_date"
                                           id="dueDateInput"
                                           value="{{ old('due_date') }}"
                                           onchange="updateProjectPreview()"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wide mb-2">
                                    Description / Scope
                                </label>

                                <textarea name="description"
                                          id="descriptionInput"
                                          rows="5"
                                          oninput="updateProjectPreview()"
                                          placeholder="Describe the scope, site/location, requirements, expected output, and important notes..."
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 resize-none">{{ old('description') }}</textarea>

                                <div class="mt-2 flex justify-between gap-3 text-[11px] text-slate-400 font-bold">
                                    <span>Keep it clear for the assigned team.</span>
                                    <span id="descriptionCounter">0 characters</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- MOBILE ACTIONS -->
                    <div class="2xl:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('operations.projects') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                            Create Project
                        </button>
                    </div>

                </div>

                <!-- RIGHT PREVIEW PANEL -->
                <div class="2xl:col-span-4">
                    <div class="2xl:sticky 2xl:top-5 space-y-4">

                        <!-- LIVE PREVIEW -->
                        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-black text-slate-900">
                                        Workspace Preview
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1">
                                        What this project will look like.
                                    </p>
                                </div>

                                <span id="previewPriority"
                                      class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-black">
                                    Normal
                                </span>
                            </div>

                            <div class="p-4 sm:p-5">

                                <div class="rounded-[1.4rem] bg-slate-950 text-white p-4 overflow-hidden relative">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.35),_transparent_40%)]"></div>

                                    <div class="relative">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="px-3 py-1 rounded-full bg-white/10 text-[10px] font-black">
                                                Pending
                                            </span>

                                            <span class="text-[10px] text-slate-400 font-bold">
                                                Auto-generated #
                                            </span>
                                        </div>

                                        <h4 id="previewTitle"
                                            class="mt-4 text-xl font-black leading-tight">
                                            Untitled Project
                                        </h4>

                                        <p id="previewClientService"
                                           class="mt-2 text-xs text-slate-300 leading-relaxed">
                                            Select client and service type
                                        </p>

                                        <div class="mt-4 grid grid-cols-2 gap-2">
                                            <div class="rounded-2xl bg-white/10 p-3">
                                                <p class="text-[10px] text-slate-400 font-black uppercase">
                                                    Start
                                                </p>
                                                <p id="previewStart" class="mt-1 text-xs font-black">
                                                    -
                                                </p>
                                            </div>

                                            <div class="rounded-2xl bg-white/10 p-3">
                                                <p class="text-[10px] text-slate-400 font-black uppercase">
                                                    Due
                                                </p>
                                                <p id="previewDue" class="mt-1 text-xs font-black">
                                                    -
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                                        Description Preview
                                    </p>

                                    <p id="previewDescription"
                                       class="mt-2 text-sm text-slate-600 leading-relaxed line-clamp-5">
                                        No description yet.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- SETUP SUMMARY -->
                        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                                <h3 class="text-lg font-black text-slate-900">
                                    Setup Summary
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">
                                    This is how the project will start.
                                </p>
                            </div>

                            <div class="p-4 sm:p-5 space-y-3 text-sm">

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500 font-bold">
                                        Initial Status
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-black">
                                        Pending
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500 font-bold">
                                        Project Number
                                    </span>
                                    <span class="text-slate-900 font-black">
                                        Auto
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500 font-bold">
                                        Created By
                                    </span>
                                    <span class="text-slate-900 font-black truncate max-w-[180px]">
                                        {{ Auth::user()->name }}
                                    </span>
                                </div>

                                <div class="pt-3 border-t border-slate-100">
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        After creating this workspace, open it to add tasks, assign employees, and track proof updates.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- DESKTOP ACTIONS -->
                        <div class="hidden 2xl:grid grid-cols-1 gap-3">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                                <span>Create Project</span>
                                <span>→</span>
                            </button>

                            <a href="{{ route('operations.projects') }}"
                               class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                                Back to Projects
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateProjectPreview();
        });

        function formatDateForPreview(value) {
            if (!value) {
                return '-';
            }

            const parts = value.split('-');

            if (parts.length !== 3) {
                return value;
            }

            const date = new Date(parts[0], parts[1] - 1, parts[2]);

            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            });
        }

        function updateProjectPreview() {
            const titleInput = document.getElementById('projectTitleInput');
            const clientInput = document.getElementById('clientInput');
            const serviceInput = document.getElementById('serviceInput');
            const priorityInput = document.getElementById('priorityInput');
            const startDateInput = document.getElementById('startDateInput');
            const dueDateInput = document.getElementById('dueDateInput');
            const descriptionInput = document.getElementById('descriptionInput');

            const title = titleInput.value.trim();
            const client = clientInput.options[clientInput.selectedIndex]?.text || '';
            const service = serviceInput.value || '';
            const priority = priorityInput.value || 'normal';
            const startDate = startDateInput.value;
            const dueDate = dueDateInput.value;
            const description = descriptionInput.value.trim();

            document.getElementById('previewTitle').textContent = title || 'Untitled Project';

            if (clientInput.value && service) {
                document.getElementById('previewClientService').textContent = client + ' • ' + service;
            } else if (clientInput.value) {
                document.getElementById('previewClientService').textContent = client + ' • Select service type';
            } else if (service) {
                document.getElementById('previewClientService').textContent = 'Select client • ' + service;
            } else {
                document.getElementById('previewClientService').textContent = 'Select client and service type';
            }

            document.getElementById('previewStart').textContent = formatDateForPreview(startDate);
            document.getElementById('previewDue').textContent = formatDateForPreview(dueDate);
            document.getElementById('previewDescription').textContent = description || 'No description yet.';
            document.getElementById('descriptionCounter').textContent = description.length + ' characters';

            const priorityBadge = document.getElementById('previewPriority');

            priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
            priorityBadge.className = 'px-3 py-1.5 rounded-full text-[11px] font-black';

            if (priority === 'urgent') {
                priorityBadge.classList.add('bg-orange-100', 'text-orange-700');
            } else if (priority === 'high') {
                priorityBadge.classList.add('bg-red-100', 'text-red-700');
            } else {
                priorityBadge.classList.add('bg-slate-100', 'text-slate-700');
            }
        }
    </script>
</x-app-layout>