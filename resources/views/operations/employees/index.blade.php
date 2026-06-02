<x-app-layout>
    <x-slot name="header">
        Employees
    </x-slot>

    @php
        $employeeItems = method_exists($employees, 'items') ? collect($employees->items()) : collect($employees);

        $totalEmployees = method_exists($employees, 'total') ? $employees->total() : $employeeItems->count();
        $shownEmployees = $employees->count();
        $currentPage = method_exists($employees, 'currentPage') ? $employees->currentPage() : 1;
        $lastPage = method_exists($employees, 'lastPage') ? $employees->lastPage() : 1;

        $admins = $employeeItems->where('role', 'admin')->count();
        $managers = $employeeItems->where('role', 'manager')->count();
        $technicians = $employeeItems->where('role', 'technician')->count();
        $staff = $employeeItems->filter(fn($employee) => !in_array($employee->role, ['admin', 'manager', 'technician']))->count();
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
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

            <div class="relative p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                                Employees
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                Page {{ $currentPage }} / {{ $lastPage }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ $shownEmployees }} shown
                            </span>
                        </div>

                        <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Manage employee accounts, roles, system access, and operational team assignments.
                        </p>
                    </div>

                    <a href="{{ route('operations.employees.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                        <span>＋</span>
                        <span>Add Employee</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">
            <button type="button"
                    onclick="filterEmployees('all', this)"
                    class="employee-filter active-filter rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-3 sm:p-4 text-left shadow-sm transition">
                <p class="text-[11px] sm:text-xs font-black opacity-70 uppercase tracking-wide">
                    Total
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black">
                        {{ $totalEmployees }}
                    </h3>
                    <span class="text-xs font-bold opacity-70">All</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterEmployees('admin', this)"
                    class="employee-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Admins
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-blue-600">
                        {{ $admins }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Access</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterEmployees('manager', this)"
                    class="employee-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Managers
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-violet-600">
                        {{ $managers }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Lead</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterEmployees('technician', this)"
                    class="employee-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Technicians
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">
                        {{ $technicians }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Field</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterEmployees('staff', this)"
                    class="employee-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition col-span-2 xl:col-span-1">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Staff
                </p>

                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-700">
                        {{ $staff }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Team</span>
                </div>
            </button>
        </div>

        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Employee Directory
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Table is default for fast scanning. Cards are better for mobile review.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                                🔎
                            </span>

                            <input id="employeeSearch"
                                   type="text"
                                   oninput="searchEmployees()"
                                   placeholder="Search employee, email, role..."
                                   class="w-full sm:w-96 pl-9 pr-3 py-2.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="flex items-center gap-1 rounded-2xl bg-slate-100 p-1">
                            <button id="tableViewBtn"
                                    type="button"
                                    onclick="setEmployeeView('table')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Table
                            </button>

                            <button id="cardViewBtn"
                                    type="button"
                                    onclick="setEmployeeView('cards')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Cards
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="employeeTableView" class="overflow-x-auto">
                <table class="w-full min-w-[980px]">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Employee
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Email
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Role
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Created
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Access
                            </th>
                            <th class="px-5 py-3 text-right text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $employee)
                            @php
                                $employeeRole = $employee->role ?? 'employee';

                                $roleGroup = in_array($employeeRole, ['admin', 'manager', 'technician'])
                                    ? $employeeRole
                                    : 'staff';

                                $searchText = strtolower(
                                    ($employee->name ?? '') . ' ' .
                                    ($employee->email ?? '') . ' ' .
                                    ($employeeRole ?? '')
                                );
                            @endphp

                            <tr class="employee-row hover:bg-slate-50 transition"
                                data-role="{{ $roleGroup }}"
                                data-search="{{ $searchText }}">

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-sm font-black shadow-sm shrink-0">
                                            {{ strtoupper(substr($employee->name ?? 'U', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-slate-900 max-w-[240px] truncate">
                                                {{ $employee->name }}
                                            </p>

                                            <p class="text-xs text-slate-400 font-bold mt-1">
                                                Employee #{{ $employee->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <a href="mailto:{{ $employee->email }}"
                                       class="text-sm font-bold text-blue-600 hover:text-blue-700 max-w-[260px] truncate block">
                                        {{ $employee->email }}
                                    </a>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-black
                                        @if($employeeRole === 'admin')
                                            bg-blue-100 text-blue-700
                                        @elseif($employeeRole === 'manager')
                                            bg-violet-100 text-violet-700
                                        @elseif($employeeRole === 'technician')
                                            bg-emerald-100 text-emerald-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif">
                                        {{ ucwords(str_replace('_', ' ', $employeeRole)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-bold text-slate-700">
                                        {{ $employee->created_at ? $employee->created_at->format('M d, Y') : '-' }}
                                    </p>

                                    <p class="text-xs text-slate-400 font-bold mt-1">
                                        {{ $employee->created_at ? $employee->created_at->diffForHumans() : '' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black">
                                        Active
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        <a href="{{ route('operations.employees.edit', $employee->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-black transition">
                                            Edit
                                        </a>

                                        <a href="{{ route('operations.employees.show', $employee->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 hover:bg-blue-600 text-white text-xs font-black transition">
                                            Open
                                            <span>→</span>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                        👥
                                    </div>

                                    <h3 class="mt-4 text-xl font-black text-slate-900">
                                        No employees yet
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Add your first employee so tasks and activity logs can be assigned.
                                    </p>

                                    <a href="{{ route('operations.employees.create') }}"
                                       class="mt-5 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black transition">
                                        ＋ Add Employee
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="employeeCardView" class="hidden p-4 sm:p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
                    @forelse($employees as $employee)
                        @php
                            $employeeRole = $employee->role ?? 'employee';

                            $roleGroup = in_array($employeeRole, ['admin', 'manager', 'technician'])
                                ? $employeeRole
                                : 'staff';

                            $searchText = strtolower(
                                ($employee->name ?? '') . ' ' .
                                ($employee->email ?? '') . ' ' .
                                ($employeeRole ?? '')
                            );
                        @endphp

                        <div class="employee-card rounded-[1.5rem] bg-white border border-slate-200 p-4 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition"
                             data-role="{{ $roleGroup }}"
                             data-search="{{ $searchText }}">

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-lg font-black shadow-sm shrink-0">
                                        {{ strtoupper(substr($employee->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="text-lg font-black text-slate-900 truncate">
                                            {{ $employee->name }}
                                        </h3>

                                        <p class="text-xs text-blue-600 font-bold truncate">
                                            {{ $employee->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-2">
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                        Role
                                    </p>

                                    <p class="mt-1 text-sm font-black text-slate-800">
                                        {{ ucwords(str_replace('_', ' ', $employeeRole)) }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div class="rounded-2xl bg-slate-50 p-3">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                            Created
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-slate-700 truncate">
                                            {{ $employee->created_at ? $employee->created_at->format('M d, Y') : '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50 p-3">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                            Access
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-emerald-600">
                                            Active
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 2xl:col-span-3 px-5 py-16 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                👥
                            </div>

                            <h3 class="mt-4 text-xl font-black text-slate-900">
                                No employees yet
                            </h3>

                            <p class="mt-2 text-sm text-slate-500">
                                Add your first employee so tasks and activity logs can be assigned.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div id="noEmployeeResults" class="hidden px-5 py-14 text-center border-t border-slate-100">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                    🔎
                </div>

                <h3 class="mt-4 text-xl font-black text-slate-900">
                    No matching employees
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Try changing the search text or filter.
                </p>
            </div>

            @if(method_exists($employees, 'links'))
                <div class="p-4 sm:p-5 border-t border-slate-100">
                    {{ $employees->links() }}
                </div>
            @endif

        </div>

    </div>

    <script>
        let activeEmployeeFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('operationsEmployeesView') || 'table';
            setEmployeeView(savedView);
            applyEmployeeVisibility();
        });

        function setEmployeeView(view) {
            const tableView = document.getElementById('employeeTableView');
            const cardView = document.getElementById('employeeCardView');
            const tableBtn = document.getElementById('tableViewBtn');
            const cardBtn = document.getElementById('cardViewBtn');

            localStorage.setItem('operationsEmployeesView', view);

            if (view === 'cards') {
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

        function filterEmployees(filter, button) {
            activeEmployeeFilter = filter;

            document.querySelectorAll('.employee-filter').forEach(function (btn) {
                btn.classList.remove('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
                btn.classList.add('bg-white/80', 'border-slate-200');
            });

            if (button) {
                button.classList.remove('bg-white/80', 'border-slate-200');
                button.classList.add('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
            }

            applyEmployeeVisibility();
        }

        function searchEmployees() {
            applyEmployeeVisibility();
        }

        function employeeMatchesFilter(element) {
            if (activeEmployeeFilter === 'all') {
                return true;
            }

            return element.dataset.role === activeEmployeeFilter;
        }

        function employeeMatchesSearch(element) {
            const searchInput = document.getElementById('employeeSearch');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            if (!query) {
                return true;
            }

            return (element.dataset.search || '').includes(query);
        }

        function applyEmployeeVisibility() {
            const rows = document.querySelectorAll('.employee-row');
            const cards = document.querySelectorAll('.employee-card');
            const noResults = document.getElementById('noEmployeeResults');

            let visibleCount = 0;

            rows.forEach(function (row) {
                const visible = employeeMatchesFilter(row) && employeeMatchesSearch(row);
                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            cards.forEach(function (card) {
                const visible = employeeMatchesFilter(card) && employeeMatchesSearch(card);
                card.classList.toggle('hidden', !visible);
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }
        }
    </script>
</x-app-layout>