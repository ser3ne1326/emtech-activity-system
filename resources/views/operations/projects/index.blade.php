<x-app-layout>
    <x-slot name="header">
        Projects
    </x-slot>

    @php
        $projectItems = method_exists($projects, 'items') ? collect($projects->items()) : collect($projects);

        $shownProjects = $projects->count();
        $currentPage = method_exists($projects, 'currentPage') ? $projects->currentPage() : 1;

        $urgentProjects = $projectItems->where('priority', 'urgent')->count();
        $completedProjects = $projectItems->where('status', 'completed')->count();
        $activeProjects = $projectItems->whereIn('status', ['active', 'in_progress', 'ongoing'])->count();
        $pendingProjects = $projectItems->where('status', 'pending')->count();
    @endphp

    <div class="space-y-4">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- COMPACT HERO -->
        <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.30),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                Projects
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                Page {{ $currentPage }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ $shownProjects }} shown
                            </span>
                        </div>

                        <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Manage operational projects, review clients, track status, and open each project workspace.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if(in_array(Auth::user()->role, ['admin', 'manager']))
                            <a href="{{ route('operations.projects.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                                <span>＋</span>
                                <span>Create Project</span>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- COMPACT METRICS -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
            <button type="button"
                    onclick="filterProjects('all', this)"
                    class="project-filter active-filter rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-3 sm:p-4 text-left shadow-sm transition">
                <p class="text-[11px] sm:text-xs font-black opacity-70 uppercase tracking-wide">
                    Projects Shown
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black">
                        {{ $shownProjects }}
                    </h3>
                    <span class="text-xs font-bold opacity-70">
                        All
                    </span>
                </div>
            </button>

            <button type="button"
                    onclick="filterProjects('urgent', this)"
                    class="project-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Urgent
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-orange-500">
                        {{ $urgentProjects }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">
                        Priority
                    </span>
                </div>
            </button>

            <button type="button"
                    onclick="filterProjects('active', this)"
                    class="project-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Active
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-blue-600">
                        {{ $activeProjects }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">
                        Ongoing
                    </span>
                </div>
            </button>

            <button type="button"
                    onclick="filterProjects('completed', this)"
                    class="project-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Completed
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">
                        {{ $completedProjects }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">
                        Done
                    </span>
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
                            Project Board
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Switch between table precision and card workspace view.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">

                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                                🔎
                            </span>

                            <input id="projectSearch"
                                   type="text"
                                   oninput="searchProjects()"
                                   placeholder="Search project, client, service..."
                                   class="w-full sm:w-80 pl-9 pr-3 py-2.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="flex items-center gap-1 rounded-2xl bg-slate-100 p-1">
                            <button id="tableViewBtn"
                                    type="button"
                                    onclick="setProjectView('table')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Table
                            </button>

                            <button id="cardViewBtn"
                                    type="button"
                                    onclick="setProjectView('card')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Cards
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <!-- TABLE VIEW -->
            <div id="projectTableView" class="overflow-x-auto">
                <table class="w-full min-w-[1050px]">
                    <thead class="bg-slate-50/90">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Project
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Client
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Service
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Priority
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Status
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Due Date
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide text-right">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody id="projectTableBody" class="divide-y divide-slate-100">
                        @forelse($projects as $project)
                            @php
                                $projectStatus = $project->status ?? 'pending';
                                $projectPriority = $project->priority ?? 'normal';

                                $statusLabel = ucfirst(str_replace('_', ' ', $projectStatus));
                                $priorityLabel = ucfirst(str_replace('_', ' ', $projectPriority));

                                $projectSearchText = strtolower(
                                    ($project->project_number ?? '') . ' ' .
                                    ($project->title ?? '') . ' ' .
                                    ($project->client->name ?? '') . ' ' .
                                    ($project->service_type ?? '') . ' ' .
                                    ($projectStatus ?? '') . ' ' .
                                    ($projectPriority ?? '')
                                );
                            @endphp

                            <tr class="project-row hover:bg-slate-50/90 transition"
                                data-status="{{ $projectStatus }}"
                                data-priority="{{ $projectPriority }}"
                                data-search="{{ $projectSearchText }}">

                                <td class="px-5 py-4">
                                    <div class="min-w-0">
                                        <p class="font-black text-slate-900 text-sm">
                                            {{ $project->project_number }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1 max-w-[320px] truncate">
                                            {{ $project->title }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-xs font-black shrink-0">
                                            {{ strtoupper(substr($project->client->name ?? 'C', 0, 1)) }}
                                        </div>

                                        <p class="text-sm font-bold text-slate-700 max-w-[180px] truncate">
                                            {{ $project->client->name ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex max-w-[180px] truncate px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black">
                                        {{ $project->service_type ?? 'General' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black
                                        @if($projectPriority === 'urgent')
                                            bg-orange-100 text-orange-700
                                        @elseif($projectPriority === 'high')
                                            bg-red-100 text-red-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ $priorityLabel }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black
                                        @if($projectStatus === 'completed')
                                            bg-emerald-100 text-emerald-700
                                        @elseif(in_array($projectStatus, ['active', 'in_progress', 'ongoing']))
                                            bg-amber-100 text-amber-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-600 font-bold">
                                    {{ $project->due_date
                                        ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y')
                                        : '-'
                                    }}
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('operations.projects.edit', $project->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-black transition">
                                            Edit
                                        </a>

                                        <a href="{{ route('operations.projects.show', $project->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 hover:bg-blue-600 text-white text-xs font-black transition">
                                            Open
                                            <span>→</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                            📁
                                        </div>

                                        <h3 class="mt-4 text-xl font-black text-slate-900">
                                            No projects found
                                        </h3>

                                        <p class="mt-2 text-sm text-slate-500">
                                            Create a project to start managing activities and tasks.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- CARD VIEW -->
            <div id="projectCardView" class="hidden p-4 sm:p-5">
                @if($projects->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
                        @foreach($projects as $project)
                            @php
                                $projectStatus = $project->status ?? 'pending';
                                $projectPriority = $project->priority ?? 'normal';

                                $statusLabel = ucfirst(str_replace('_', ' ', $projectStatus));
                                $priorityLabel = ucfirst(str_replace('_', ' ', $projectPriority));

                                $projectSearchText = strtolower(
                                    ($project->project_number ?? '') . ' ' .
                                    ($project->title ?? '') . ' ' .
                                    ($project->client->name ?? '') . ' ' .
                                    ($project->service_type ?? '') . ' ' .
                                    ($projectStatus ?? '') . ' ' .
                                    ($projectPriority ?? '')
                                );
                            @endphp

                            <div class="project-card rounded-[1.5rem] bg-white border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden"
                                 data-status="{{ $projectStatus }}"
                                 data-priority="{{ $projectPriority }}"
                                 data-search="{{ $projectSearchText }}">

                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black
                                                    @if($projectStatus === 'completed')
                                                        bg-emerald-100 text-emerald-700
                                                    @elseif(in_array($projectStatus, ['active', 'in_progress', 'ongoing']))
                                                        bg-amber-100 text-amber-700
                                                    @else
                                                        bg-slate-100 text-slate-700
                                                    @endif">
                                                    {{ $statusLabel }}
                                                </span>

                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black
                                                    @if($projectPriority === 'urgent')
                                                        bg-orange-100 text-orange-700
                                                    @elseif($projectPriority === 'high')
                                                        bg-red-100 text-red-700
                                                    @else
                                                        bg-slate-100 text-slate-600
                                                    @endif">
                                                    {{ $priorityLabel }}
                                                </span>
                                            </div>

                                            <h3 class="mt-3 text-lg font-black text-slate-900 leading-tight">
                                                {{ $project->project_number }}
                                            </h3>

                                            <p class="mt-1 text-sm text-slate-500 line-clamp-2">
                                                {{ $project->title }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-xs font-black shrink-0">
                                            {{ strtoupper(substr($project->client->name ?? 'C', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-slate-900 truncate">
                                                {{ $project->client->name ?? '-' }}
                                            </p>

                                            <p class="text-xs text-slate-500 truncate">
                                                {{ $project->service_type ?? 'General' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-2">
                                        <div class="rounded-2xl bg-slate-50 p-3">
                                            <p class="text-[10px] font-black text-slate-400 uppercase">
                                                Due Date
                                            </p>

                                            <p class="mt-1 text-xs font-black text-slate-700">
                                                {{ $project->due_date
                                                    ? \Carbon\Carbon::parse($project->due_date)->format('M d, Y')
                                                    : '-'
                                                }}
                                            </p>
                                        </div>

                                        <div class="rounded-2xl bg-slate-50 p-3">
                                            <p class="text-[10px] font-black text-slate-400 uppercase">
                                                Status
                                            </p>

                                            <p class="mt-1 text-xs font-black text-slate-700">
                                                {{ $statusLabel }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('operations.projects.show', $project->id) }}"
                                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-950 hover:bg-blue-600 text-white text-xs font-black transition">
                                            Open Workspace
                                            <span>→</span>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-16 text-center">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                            📁
                        </div>

                        <h3 class="mt-4 text-xl font-black text-slate-900">
                            No projects found
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            Create a project to start managing activities and tasks.
                        </p>
                    </div>
                @endif
            </div>

            <!-- NO RESULTS -->
            <div id="noProjectResults" class="hidden px-5 py-14 text-center border-t border-slate-100">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                    🔎
                </div>

                <h3 class="mt-4 text-xl font-black text-slate-900">
                    No matching projects
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Try changing the search text or filter.
                </p>
            </div>

        </div>

        @if(method_exists($projects, 'links'))
            <div class="pt-1">
                {{ $projects->links() }}
            </div>
        @endif

    </div>

    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-2xl bg-slate-950 text-white shadow-2xl hover:bg-blue-600 transition flex items-center justify-center text-xl">
        ↑
    </button>

    <script>
        let activeProjectFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('operationsProjectsView') || 'table';
            setProjectView(savedView);
            applyProjectVisibility();
        });

        function setProjectView(view) {
            const tableView = document.getElementById('projectTableView');
            const cardView = document.getElementById('projectCardView');
            const tableBtn = document.getElementById('tableViewBtn');
            const cardBtn = document.getElementById('cardViewBtn');

            localStorage.setItem('operationsProjectsView', view);

            if (view === 'card') {
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');

                cardBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
                tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';

                return;
            }

            cardView.classList.add('hidden');
            tableView.classList.remove('hidden');

            tableBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition bg-slate-950 text-white shadow-sm';
            cardBtn.className = 'view-toggle px-4 py-2 rounded-xl text-xs font-black transition text-slate-500 hover:text-slate-900';
        }

        function filterProjects(filter, button) {
            activeProjectFilter = filter;

            document.querySelectorAll('.project-filter').forEach(function (btn) {
                btn.classList.remove('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
                btn.classList.add('bg-white/80', 'border-slate-200');
            });

            if (button) {
                button.classList.remove('bg-white/80', 'border-slate-200');
                button.classList.add('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
            }

            applyProjectVisibility();
        }

        function searchProjects() {
            applyProjectVisibility();
        }

        function projectMatchesFilter(element) {
            if (activeProjectFilter === 'all') {
                return true;
            }

            if (activeProjectFilter === 'urgent') {
                return element.dataset.priority === 'urgent';
            }

            if (activeProjectFilter === 'active') {
                return ['active', 'in_progress', 'ongoing'].includes(element.dataset.status);
            }

            return element.dataset.status === activeProjectFilter;
        }

        function projectMatchesSearch(element) {
            const searchInput = document.getElementById('projectSearch');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            if (!query) {
                return true;
            }

            return (element.dataset.search || '').includes(query);
        }

        function applyProjectVisibility() {
            const rows = document.querySelectorAll('.project-row');
            const cards = document.querySelectorAll('.project-card');
            const noResults = document.getElementById('noProjectResults');

            let visibleCount = 0;

            rows.forEach(function (row) {
                const visible = projectMatchesFilter(row) && projectMatchesSearch(row);
                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            cards.forEach(function (card) {
                const visible = projectMatchesFilter(card) && projectMatchesSearch(card);
                card.classList.toggle('hidden', !visible);
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }
        }
    </script>
</x-app-layout>