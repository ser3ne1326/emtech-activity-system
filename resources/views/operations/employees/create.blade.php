<x-app-layout>
    <x-slot name="header">
        Add Employee
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
              action="{{ route('operations.employees.store') }}"
              class="space-y-4">
            @csrf

            <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('operations.employees') }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 text-[11px] font-black transition">
                                    ← Employees
                                </a>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    New Account
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                                Add Employee
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Create a staff account, assign system access, and prepare the user for task and activity tracking.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ route('operations.employees') }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                                Save Employee →
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
                                Employee Identity
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Basic login identity and account ownership.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>

                                <input name="name"
                                       id="employeeNameInput"
                                       required
                                       value="{{ old('name') }}"
                                       oninput="updateEmployeePreview()"
                                       placeholder="Example: Juan Dela Cruz"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>

                                <input name="email"
                                       id="employeeEmailInput"
                                       type="email"
                                       required
                                       value="{{ old('email') }}"
                                       oninput="updateEmployeePreview()"
                                       placeholder="employee@email.com"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Account Access
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Assign system role and initial password.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>

                                    <input name="password"
                                           type="password"
                                           required
                                           placeholder="Minimum 6 characters"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Role <span class="text-red-500">*</span>
                                    </label>

                                    <select name="role"
                                            id="employeeRoleInput"
                                            required
                                            onchange="updateEmployeePreview()"
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                        <option value="">Select Role</option>
                                        <option value="admin" @selected(old('role') === 'admin')>Admin / CEO</option>
                                        <option value="manager" @selected(old('role') === 'manager')>Manager</option>
                                        <option value="technician" @selected(old('role') === 'technician')>Technician</option>
                                        <option value="employee" @selected(old('role') === 'employee')>Employee</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rounded-[1.4rem] bg-blue-50 border border-blue-100 p-4">
                                <p class="text-sm font-black text-blue-800">
                                    Role guide
                                </p>

                                <p class="mt-1 text-xs text-blue-700 leading-relaxed">
                                    Admin can manage the system, Manager can oversee projects and teams, Technician handles field work, and Employee can track assigned tasks and submit activity logs.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="2xl:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('operations.employees') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                            Save Employee
                        </button>
                    </div>
                </div>

                <div class="2xl:col-span-4">
                    <div class="2xl:sticky 2xl:top-5 space-y-4">

                        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                                <h3 class="text-lg font-black text-slate-900">
                                    Employee Preview
                                </h3>

                                <p class="text-xs text-slate-500 mt-1">
                                    Live preview of this account.
                                </p>
                            </div>

                            <div class="p-4 sm:p-5">
                                <div class="rounded-[1.5rem] bg-slate-950 text-white p-5 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.35),_transparent_40%)]"></div>

                                    <div class="relative">
                                        <div id="previewInitial"
                                             class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl font-black shadow-lg">
                                            U
                                        </div>

                                        <h4 id="previewName"
                                            class="mt-4 text-2xl font-black leading-tight">
                                            Employee Name
                                        </h4>

                                        <p id="previewEmail"
                                           class="mt-1 text-sm text-slate-300 truncate">
                                            employee@email.com
                                        </p>

                                        <div class="mt-5 rounded-2xl bg-white/10 p-3">
                                            <p class="text-slate-400 font-black uppercase text-[10px]">
                                                Assigned Role
                                            </p>

                                            <p id="previewRole" class="mt-1 font-black">
                                                Select Role
                                            </p>
                                        </div>

                                        <div class="mt-3 rounded-2xl bg-white/10 p-3">
                                            <p class="text-slate-400 font-black uppercase text-[10px]">
                                                Access Status
                                            </p>

                                            <p class="mt-1 font-black text-emerald-300">
                                                Active after creation
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden 2xl:grid grid-cols-1 gap-3">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                                Save Employee →
                            </button>

                            <a href="{{ route('operations.employees') }}"
                               class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                                Back to Employees
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateEmployeePreview();
        });

        function updateEmployeePreview() {
            const nameInput = document.getElementById('employeeNameInput');
            const emailInput = document.getElementById('employeeEmailInput');
            const roleInput = document.getElementById('employeeRoleInput');

            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const role = roleInput.options[roleInput.selectedIndex]?.text || '';

            document.getElementById('previewInitial').textContent = name ? name.charAt(0).toUpperCase() : 'U';
            document.getElementById('previewName').textContent = name || 'Employee Name';
            document.getElementById('previewEmail').textContent = email || 'employee@email.com';
            document.getElementById('previewRole').textContent = role && role !== 'Select Role' ? role : 'Select Role';
        }
    </script>
</x-app-layout>