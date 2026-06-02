<x-app-layout>
    <x-slot name="header">
        Employee Activities
    </x-slot>

    @php
        $activityItems = method_exists($activities, 'items') ? collect($activities->items()) : collect($activities);

        $totalActivities = method_exists($activities, 'total') ? $activities->total() : $activities->count();
        $showingActivities = $activities->count();
        $completedActivities = $activityItems->where('status', 'completed')->count();
        $progressActivities = $activityItems->where('status', 'in_progress')->count();
        $proofActivities = $activityItems->filter(fn($activity) => $activity->attachments->count() > 0)->count();
        $currentPage = method_exists($activities, 'currentPage') ? $activities->currentPage() : 1;
        $lastPage = method_exists($activities, 'lastPage') ? $activities->lastPage() : 1;
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

        <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(147,51,234,.32),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                Employee Activities
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                Page {{ $currentPage }} / {{ $lastPage }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ $showingActivities }} shown
                            </span>
                        </div>

                        <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Monitor employee work logs, operational progress, task updates, time records, and proof photos.
                        </p>
                    </div>

                    <a href="{{ route('operations.activities.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-black shadow-lg shadow-violet-900/30 transition">
                        <span>＋</span>
                        <span>Log Activity</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
            <button type="button" onclick="filterActivities('all', this)"
                    class="activity-filter active-filter rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-3 sm:p-4 text-left shadow-sm transition">
                <p class="text-[11px] sm:text-xs font-black opacity-70 uppercase tracking-wide">Total Activities</p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black">{{ $totalActivities }}</h3>
                    <span class="text-xs font-bold opacity-70">All</span>
                </div>
            </button>

            <button type="button" onclick="filterActivities('completed', this)"
                    class="activity-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">Completed</p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">{{ $completedActivities }}</h3>
                    <span class="text-xs font-bold text-slate-400">Done</span>
                </div>
            </button>

            <button type="button" onclick="filterActivities('in_progress', this)"
                    class="activity-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">In Progress</p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-amber-500">{{ $progressActivities }}</h3>
                    <span class="text-xs font-bold text-slate-400">Active</span>
                </div>
            </button>

            <button type="button" onclick="filterActivities('proof', this)"
                    class="activity-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">With Proof</p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-violet-600">{{ $proofActivities }}</h3>
                    <span class="text-xs font-bold text-slate-400">Photos</span>
                </div>
            </button>
        </div>

        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Recent Activity Logs
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Table is best for scanning. Timeline is best for proof review.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔎</span>

                            <input id="activitySearch"
                                   type="text"
                                   oninput="searchActivities()"
                                   placeholder="Search employee, project, task, description..."
                                   class="w-full sm:w-96 pl-9 pr-3 py-2.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm">
                        </div>

                        <div class="flex items-center gap-1 rounded-2xl bg-slate-100 p-1">
                            <button id="tableViewBtn" type="button" onclick="setActivityView('table')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Table
                            </button>

                            <button id="timelineViewBtn" type="button" onclick="setActivityView('timeline')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Timeline
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="activityTableView" class="overflow-x-auto">
                <table class="w-full min-w-[1300px]">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Employee</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Project</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Task</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Type</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Date / Time</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Proof</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Description</th>
                            <th class="px-5 py-3 text-right text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($activities as $activity)
                            @php
                                $hasProof = $activity->attachments->count() > 0;

                                $searchText = strtolower(
                                    ($activity->user->name ?? '') . ' ' .
                                    ($activity->project->title ?? '') . ' ' .
                                    ($activity->project->project_number ?? '') . ' ' .
                                    ($activity->task->title ?? '') . ' ' .
                                    ($activity->activity_type ?? '') . ' ' .
                                    ($activity->status ?? '') . ' ' .
                                    ($activity->description ?? '')
                                );
                            @endphp

                            <tr class="activity-row hover:bg-slate-50 transition"
                                data-status="{{ $activity->status }}"
                                data-proof="{{ $hasProof ? 'yes' : 'no' }}"
                                data-search="{{ $searchText }}">

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-black shrink-0">
                                            {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-slate-900 max-w-[160px] truncate">
                                                {{ $activity->user->name ?? 'Unknown User' }}
                                            </p>
                                            <p class="text-xs text-slate-400 font-bold">Employee</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-slate-900 max-w-[210px] truncate">
                                        {{ $activity->project->project_number ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-bold mt-1 max-w-[210px] truncate">
                                        {{ $activity->project->title ?? 'No Project' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-700 font-bold max-w-[200px] truncate">
                                        {{ $activity->task->title ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-violet-50 text-violet-700 text-xs font-black max-w-[180px] truncate">
                                        {{ $activity->activity_type ?? 'Update' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-slate-700">{{ $activity->activity_date ? \Carbon\Carbon::parse($activity->activity_date)->format('M d, Y') : '-' }}</p>
                                    <p class="text-xs text-slate-500 font-bold mt-1">
                                        {{ $activity->time_started ? \Carbon\Carbon::parse($activity->time_started)->format('g:iA') : '--:--' }} - {{ $activity->time_ended ? \Carbon\Carbon::parse($activity->time_ended)->format('g:iA') : '--:--' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black
                                        @if($activity->status === 'completed')
                                            bg-emerald-100 text-emerald-700
                                        @elseif($activity->status === 'in_progress')
                                            bg-amber-100 text-amber-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if($activity->attachments->count())
                                        <div class="flex items-center">
                                            @foreach($activity->attachments->take(3) as $index => $file)
                                                <a href="{{ asset('storage/' . $file->file_path) }}"
                                                   target="_blank"
                                                   class="relative group"
                                                   style="margin-left: {{ $index > 0 ? '-10px' : '0' }}">
                                                    <img src="{{ asset('storage/' . $file->file_path) }}"
                                                         class="w-11 h-11 rounded-xl object-cover border-2 border-white shadow-sm hover:scale-105 transition duration-200 bg-slate-100">
                                                </a>
                                            @endforeach

                                            @if($activity->attachments->count() > 3)
                                                <div class="-ml-2 w-11 h-11 rounded-xl bg-slate-950 text-white flex items-center justify-center text-xs font-black border-2 border-white shadow-sm">
                                                    +{{ $activity->attachments->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-slate-400">No Proof</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-600 max-w-[360px] line-clamp-2 leading-relaxed">
                                        {{ $activity->description }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('operations.activities.edit', $activity->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-black transition">
                                            Edit
                                        </a>

                                        <a href="{{ route('operations.activities.show', $activity->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 hover:bg-violet-600 text-white text-xs font-black transition">
                                            Open
                                            <span>→</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                        📝
                                    </div>

                                    <h3 class="mt-4 text-xl font-black text-slate-900">
                                        No activity logs yet
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Employee work updates will appear here.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="activityTimelineView" class="hidden divide-y divide-slate-100">
                @forelse($activities as $activity)
                    @php
                        $hasProof = $activity->attachments->count() > 0;

                        $searchText = strtolower(
                            ($activity->user->name ?? '') . ' ' .
                            ($activity->project->title ?? '') . ' ' .
                            ($activity->project->project_number ?? '') . ' ' .
                            ($activity->task->title ?? '') . ' ' .
                            ($activity->activity_type ?? '') . ' ' .
                            ($activity->status ?? '') . ' ' .
                            ($activity->description ?? '')
                        );
                    @endphp

                    <div class="activity-item p-4 sm:p-5 hover:bg-slate-50 transition"
                         data-status="{{ $activity->status }}"
                         data-proof="{{ $hasProof ? 'yes' : 'no' }}"
                         data-search="{{ $searchText }}">

                        <div class="flex gap-3 sm:gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-sm font-black shrink-0">
                                {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-black text-slate-900 text-base sm:text-lg truncate">
                                        {{ $activity->user->name ?? 'Unknown User' }}
                                    </h3>

                                    <span class="px-3 py-1 rounded-full text-[11px] font-black
                                        @if($activity->status === 'completed')
                                            bg-emerald-100 text-emerald-700
                                        @elseif($activity->status === 'in_progress')
                                            bg-amber-100 text-amber-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                    </span>

                                    @if($hasProof)
                                        <span class="px-3 py-1 rounded-full bg-violet-50 text-violet-700 text-[11px] font-black">
                                            {{ $activity->attachments->count() }} Proof
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2 flex flex-wrap gap-2 text-[11px] sm:text-xs text-slate-500 font-bold">
                                    <span class="px-3 py-1.5 rounded-full bg-slate-100">{{ \Carbon\Carbon::parse($activity->activity_date)->format('F d, Y') }}</span>
                                    <span class="px-3 py-1.5 rounded-full bg-slate-100">
                                        {{ $activity->time_started ? \Carbon\Carbon::parse($activity->time_started)->format('g:iA') : '--:--' }} - {{ $activity->time_ended ? \Carbon\Carbon::parse($activity->time_ended)->format('g:iA') : '--:--' }}
                                    </span>

                                    @if($activity->activity_type)
                                        <span class="px-3 py-1.5 rounded-full bg-violet-50 text-violet-700">
                                            {{ $activity->activity_type }}
                                        </span>
                                    @endif
                                </div>

                                @if($activity->project)
                                    <div class="mt-3">
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black">
                                            {{ $activity->project->project_number }} - {{ $activity->project->title }}
                                        </span>
                                    </div>
                                @endif

                                @if($activity->task)
                                    <div class="mt-2">
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-slate-950 text-white text-xs font-black">
                                            Task: {{ $activity->task->title }}
                                        </span>
                                    </div>
                                @endif

                                <p class="mt-4 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                    {{ $activity->description }}
                                </p>

                                @if($hasProof)
                                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-2.5">
                                        @foreach($activity->attachments as $file)
                                            <a href="{{ asset('storage/' . $file->file_path) }}"
                                               target="_blank"
                                               class="group aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                                                <img src="{{ asset('storage/' . $file->file_path) }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-14 text-center text-slate-400">
                        No activity logs yet.
                    </div>
                @endforelse
            </div>

            <div id="noActivityResults" class="hidden px-5 py-14 text-center border-t border-slate-100">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                    🔎
                </div>

                <h3 class="mt-4 text-xl font-black text-slate-900">
                    No matching activity logs
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Try changing the search text or filter.
                </p>
            </div>

            @if(method_exists($activities, 'links'))
                <div class="p-4 sm:p-5 border-t border-slate-100">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        let activeActivityFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('adminActivitiesView') || 'table';
            setActivityView(savedView);
            applyActivityVisibility();
        });

        function setActivityView(view) {
            const tableView = document.getElementById('activityTableView');
            const timelineView = document.getElementById('activityTimelineView');
            const tableBtn = document.getElementById('tableViewBtn');
            const timelineBtn = document.getElementById('timelineViewBtn');

            localStorage.setItem('adminActivitiesView', view);

            if (view === 'timeline') {
                tableView.classList.add('hidden');
                timelineView.classList.remove('hidden');
                timelineBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
                tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
                return;
            }

            timelineView.classList.add('hidden');
            tableView.classList.remove('hidden');
            tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
            timelineBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
        }

        function filterActivities(filter, button) {
            activeActivityFilter = filter;

            document.querySelectorAll('.activity-filter').forEach(function (btn) {
                btn.classList.remove('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
                btn.classList.add('bg-white/80', 'border-slate-200');
            });

            if (button) {
                button.classList.remove('bg-white/80', 'border-slate-200');
                button.classList.add('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
            }

            applyActivityVisibility();
        }

        function searchActivities() {
            applyActivityVisibility();
        }

        function activityMatchesFilter(element) {
            if (activeActivityFilter === 'all') {
                return true;
            }

            if (activeActivityFilter === 'proof') {
                return element.dataset.proof === 'yes';
            }

            return element.dataset.status === activeActivityFilter;
        }

        function activityMatchesSearch(element) {
            const searchInput = document.getElementById('activitySearch');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            if (!query) {
                return true;
            }

            return (element.dataset.search || '').includes(query);
        }

        function applyActivityVisibility() {
            const tableRows = document.querySelectorAll('.activity-row');
            const timelineItems = document.querySelectorAll('.activity-item');
            const noResults = document.getElementById('noActivityResults');

            let visibleCount = 0;

            tableRows.forEach(function (row) {
                const visible = activityMatchesFilter(row) && activityMatchesSearch(row);
                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            timelineItems.forEach(function (item) {
                const visible = activityMatchesFilter(item) && activityMatchesSearch(item);
                item.classList.toggle('hidden', !visible);
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || tableRows.length === 0);
            }
        }
    </script>
</x-app-layout>