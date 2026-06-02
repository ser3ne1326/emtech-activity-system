<x-app-layout>
    <x-slot name="header">
        My Activity Logs
    </x-slot>

    @php
        $totalLogs = $activities->total();
        $showingLogs = $activities->count();
        $currentPage = $activities->currentPage();
        $lastPage = $activities->lastPage();

        $completedCount = collect($activities->items())->where('status', 'completed')->count();
        $progressCount = collect($activities->items())->where('status', 'in_progress')->count();
        $proofCount = collect($activities->items())->filter(function ($activity) {
            return $activity->attachments->count() > 0;
        })->count();
    @endphp

    <div class="space-y-4">

        <!-- HERO -->
        <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.30),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                My Activity Logs
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                Page {{ $currentPage }} / {{ $lastPage }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ $showingLogs }} shown
                            </span>
                        </div>

                        <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Review your submitted work updates, task progress, proof photos, and completed activity records.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 w-full xl:w-auto">
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center">
                            <p class="text-xl font-black">{{ $totalLogs }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Total</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center">
                            <p class="text-xl font-black">{{ $completedCount }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Done</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-center">
                            <p class="text-xl font-black">{{ $proofCount }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Proof</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- COMPACT METRICS -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
            <button type="button"
                    onclick="filterActivities('all', this)"
                    class="activity-filter active-filter rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-3 sm:p-4 text-left shadow-sm transition">
                <p class="text-[11px] sm:text-xs font-black opacity-70 uppercase tracking-wide">
                    Total Logs
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black">
                        {{ $totalLogs }}
                    </h3>
                    <span class="text-xs font-bold opacity-70">All</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterActivities('completed', this)"
                    class="activity-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Completed
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">
                        {{ $completedCount }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Done</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterActivities('in_progress', this)"
                    class="activity-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    In Progress
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-amber-500">
                        {{ $progressCount }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Active</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterActivities('proof', this)"
                    class="activity-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    With Proof
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-blue-600">
                        {{ $proofCount }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Photos</span>
                </div>
            </button>
        </div>

        <!-- WORKSPACE PANEL -->
        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- TOOLBAR -->
            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">

                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Activity History
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Switch between timeline view and table view.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                                🔎
                            </span>

                            <input id="activitySearch"
                                   type="text"
                                   oninput="searchActivities()"
                                   placeholder="Search project, task, description..."
                                   class="w-full sm:w-80 pl-9 pr-3 py-2.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="flex items-center gap-1 rounded-2xl bg-slate-100 p-1">
                            <button id="timelineViewBtn"
                                    type="button"
                                    onclick="setActivityView('timeline')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Timeline
                            </button>

                            <button id="tableViewBtn"
                                    type="button"
                                    onclick="setActivityView('table')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Table
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TIMELINE VIEW -->
            <div id="activityTimelineView" class="divide-y divide-slate-100">
                @forelse($activities as $activity)
                    @php
                        $hasProof = $activity->attachments->count() > 0;
                        $searchText = strtolower(
                            ($activity->project->title ?? '') . ' ' .
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
                            <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-3">

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-black text-slate-900 text-base sm:text-lg truncate">
                                                {{ $activity->project->title ?? 'No Project' }}
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
                                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-black">
                                                    {{ $activity->attachments->count() }} Proof
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-2 flex flex-wrap gap-2 text-[11px] sm:text-xs text-slate-500 font-bold">
                                            <span class="px-3 py-1.5 rounded-full bg-slate-100">
                                                {{ $activity->activity_date }}
                                            </span>

                                            <span class="px-3 py-1.5 rounded-full bg-slate-100">
                                                {{ $activity->time_started ?? '--:--' }} - {{ $activity->time_ended ?? '--:--' }}
                                            </span>

                                            @if($activity->activity_type)
                                                <span class="px-3 py-1.5 rounded-full bg-violet-50 text-violet-700">
                                                    {{ $activity->activity_type }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($activity->task)
                                            <div class="mt-3">
                                                <span class="inline-flex px-3 py-1.5 rounded-full bg-slate-950 text-white text-xs font-black">
                                                    Task: {{ $activity->task->title }}
                                                </span>
                                            </div>
                                        @endif

                                        <p class="mt-4 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                            {{ $activity->description }}
                                        </p>
                                    </div>

                                </div>

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
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                            📝
                        </div>

                        <h3 class="mt-4 text-xl font-black text-slate-900">
                            No activity logs yet
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            Your submitted work updates will appear here.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- TABLE VIEW -->
            <div id="activityTableView" class="hidden overflow-x-auto">
                <table class="w-full min-w-[1250px]">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Project</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Task</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Type</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Date / Time</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Proof</th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">Description</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($activities as $activity)
                            @php
                                $hasProof = $activity->attachments->count() > 0;
                                $searchText = strtolower(
                                    ($activity->project->title ?? '') . ' ' .
                                    ($activity->task->title ?? '') . ' ' .
                                    ($activity->activity_type ?? '') . ' ' .
                                    ($activity->status ?? '') . ' ' .
                                    ($activity->description ?? '')
                                );
                            @endphp

                            <tr class="activity-table-row hover:bg-slate-50 transition"
                                data-status="{{ $activity->status }}"
                                data-proof="{{ $hasProof ? 'yes' : 'no' }}"
                                data-search="{{ $searchText }}">

                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-slate-900 max-w-[220px] truncate">
                                        {{ $activity->project->title ?? 'No Project' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-600 font-bold max-w-[200px] truncate">
                                        {{ $activity->task->title ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-violet-50 text-violet-700 text-xs font-black">
                                        {{ $activity->activity_type ?? 'Update' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-black text-slate-700">
                                        {{ $activity->activity_date }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-bold mt-1">
                                        {{ $activity->time_started ?? '--:--' }} - {{ $activity->time_ended ?? '--:--' }}
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
                                    <span class="text-xs font-bold text-slate-400">
                                        No Proof
                                    </span>
                                @endif
                            </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-600 max-w-[420px] line-clamp-2 leading-relaxed">
                                        {{ $activity->description }}
                                    </p>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- NO RESULTS -->
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

            <div class="p-4 sm:p-5 border-t border-slate-100">
                {{ $activities->links() }}
            </div>

        </div>

    </div>

    <script>
        let activeActivityFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('myActivitiesView') || 'table';
            setActivityView(savedView);
            applyActivityVisibility();
        });

        function setActivityView(view) {
            const timelineView = document.getElementById('activityTimelineView');
            const tableView = document.getElementById('activityTableView');
            const timelineBtn = document.getElementById('timelineViewBtn');
            const tableBtn = document.getElementById('tableViewBtn');

            localStorage.setItem('myActivitiesView', view);

            if (view === 'table') {
                timelineView.classList.add('hidden');
                tableView.classList.remove('hidden');

                tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
                timelineBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
                return;
            }

            tableView.classList.add('hidden');
            timelineView.classList.remove('hidden');

            timelineBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
            tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
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
            const timelineItems = document.querySelectorAll('.activity-item');
            const tableRows = document.querySelectorAll('.activity-table-row');
            const noResults = document.getElementById('noActivityResults');

            let visibleCount = 0;

            timelineItems.forEach(function (item) {
                const visible = activityMatchesFilter(item) && activityMatchesSearch(item);
                item.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            tableRows.forEach(function (row) {
                const visible = activityMatchesFilter(row) && activityMatchesSearch(row);
                row.classList.toggle('hidden', !visible);
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || timelineItems.length === 0);
            }
        }
    </script>
</x-app-layout>