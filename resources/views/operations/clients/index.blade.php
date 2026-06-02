<x-app-layout>
    <x-slot name="header">
        Clients
    </x-slot>

    @php
        $clientItems = method_exists($clients, 'items') ? collect($clients->items()) : collect($clients);

        $totalClients = method_exists($clients, 'total') ? $clients->total() : $clientItems->count();
        $shownClients = $clients->count();
        $currentPage = method_exists($clients, 'currentPage') ? $clients->currentPage() : 1;
        $lastPage = method_exists($clients, 'lastPage') ? $clients->lastPage() : 1;

        $withEmail = $clientItems->filter(fn($client) => filled($client->email))->count();
        $withPhone = $clientItems->filter(fn($client) => filled($client->phone))->count();
        $withContactPerson = $clientItems->filter(fn($client) => filled($client->contact_person))->count();
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
                                Clients
                            </h1>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                Page {{ $currentPage }} / {{ $lastPage }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                {{ $shownClients }} shown
                            </span>
                        </div>

                        <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Manage client records, contact information, business details, and project-ready customer data.
                        </p>
                    </div>

                    <a href="{{ route('operations.clients.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                        <span>＋</span>
                        <span>Add Client</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
            <button type="button"
                    onclick="filterClients('all', this)"
                    class="client-filter active-filter rounded-[1.4rem] bg-slate-950 text-white border border-slate-900 p-3 sm:p-4 text-left shadow-sm transition">
                <p class="text-[11px] sm:text-xs font-black opacity-70 uppercase tracking-wide">
                    Total Clients
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black">
                        {{ $totalClients }}
                    </h3>
                    <span class="text-xs font-bold opacity-70">All</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterClients('email', this)"
                    class="client-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    With Email
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-blue-600">
                        {{ $withEmail }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Reachable</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterClients('phone', this)"
                    class="client-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    With Phone
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">
                        {{ $withPhone }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Contact</span>
                </div>
            </button>

            <button type="button"
                    onclick="filterClients('contact_person', this)"
                    class="client-filter rounded-[1.4rem] bg-white/80 border border-slate-200 p-3 sm:p-4 text-left shadow-sm hover:shadow-md transition">
                <p class="text-[11px] sm:text-xs font-black text-slate-500 uppercase tracking-wide">
                    Contact Person
                </p>
                <div class="mt-2 flex items-end justify-between gap-3">
                    <h3 class="text-2xl sm:text-3xl font-black text-violet-600">
                        {{ $withContactPerson }}
                    </h3>
                    <span class="text-xs font-bold text-slate-400">Named</span>
                </div>
            </button>
        </div>

        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Client Directory
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Table is default for fast scanning. Cards are better for mobile and visual review.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                                🔎
                            </span>

                            <input id="clientSearch"
                                   type="text"
                                   oninput="searchClients()"
                                   placeholder="Search client, contact, email, phone..."
                                   class="w-full sm:w-96 pl-9 pr-3 py-2.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="flex items-center gap-1 rounded-2xl bg-slate-100 p-1">
                            <button id="tableViewBtn"
                                    type="button"
                                    onclick="setClientView('table')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Table
                            </button>

                            <button id="cardViewBtn"
                                    type="button"
                                    onclick="setClientView('cards')"
                                    class="view-toggle px-4 py-2 rounded-xl text-xs font-black transition">
                                Cards
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="clientTableView" class="overflow-x-auto">
                <table class="w-full min-w-[1180px]">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Business
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Contact Person
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Email
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Phone
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Business Type
                            </th>
                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wide">
                                Address
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($clients as $client)
                            @php
                                $searchText = strtolower(
                                    ($client->name ?? '') . ' ' .
                                    ($client->contact_person ?? '') . ' ' .
                                    ($client->email ?? '') . ' ' .
                                    ($client->phone ?? '') . ' ' .
                                    ($client->business_type ?? '') . ' ' .
                                    ($client->address ?? '')
                                );
                            @endphp

                            <tr class="client-row hover:bg-slate-50 transition"
                                data-email="{{ filled($client->email) ? 'yes' : 'no' }}"
                                data-phone="{{ filled($client->phone) ? 'yes' : 'no' }}"
                                data-contact-person="{{ filled($client->contact_person) ? 'yes' : 'no' }}"
                                data-search="{{ $searchText }}">

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-sm font-black shadow-sm shrink-0">
                                            {{ strtoupper(substr($client->name ?? 'C', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-slate-900 max-w-[240px] truncate">
                                                {{ $client->name }}
                                            </p>

                                            <p class="text-xs text-slate-400 font-bold mt-1">
                                                Client #{{ $client->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-bold text-slate-700 max-w-[190px] truncate">
                                        {{ $client->contact_person ?: '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}"
                                           class="text-sm font-bold text-blue-600 hover:text-blue-700 max-w-[220px] truncate block">
                                            {{ $client->email }}
                                        </a>
                                    @else
                                        <span class="text-sm font-bold text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if($client->phone)
                                        <a href="tel:{{ $client->phone }}"
                                           class="text-sm font-bold text-slate-700 hover:text-blue-600">
                                            {{ $client->phone }}
                                        </a>
                                    @else
                                        <span class="text-sm font-bold text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black max-w-[180px] truncate">
                                        {{ $client->business_type ?: 'General' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-600 max-w-[320px] line-clamp-2 leading-relaxed">
                                        {{ $client->address ?: '-' }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                        🏢
                                    </div>

                                    <h3 class="mt-4 text-xl font-black text-slate-900">
                                        No clients yet
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Add your first client so projects can be connected to a customer record.
                                    </p>

                                    <a href="{{ route('operations.clients.create') }}"
                                       class="mt-5 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black transition">
                                        ＋ Add Client
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="clientCardView" class="hidden p-4 sm:p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
                    @forelse($clients as $client)
                        @php
                            $searchText = strtolower(
                                ($client->name ?? '') . ' ' .
                                ($client->contact_person ?? '') . ' ' .
                                ($client->email ?? '') . ' ' .
                                ($client->phone ?? '') . ' ' .
                                ($client->business_type ?? '') . ' ' .
                                ($client->address ?? '')
                            );
                        @endphp

                        <div class="client-card rounded-[1.5rem] bg-white border border-slate-200 p-4 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition"
                             data-email="{{ filled($client->email) ? 'yes' : 'no' }}"
                             data-phone="{{ filled($client->phone) ? 'yes' : 'no' }}"
                             data-contact-person="{{ filled($client->contact_person) ? 'yes' : 'no' }}"
                             data-search="{{ $searchText }}">

                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 text-white flex items-center justify-center text-lg font-black shadow-sm shrink-0">
                                        {{ strtoupper(substr($client->name ?? 'C', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="text-lg font-black text-slate-900 truncate">
                                            {{ $client->name }}
                                        </h3>

                                        <p class="text-xs text-slate-500 font-bold truncate">
                                            {{ $client->business_type ?: 'General Business' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-2">
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                        Contact Person
                                    </p>

                                    <p class="mt-1 text-sm font-black text-slate-800 truncate">
                                        {{ $client->contact_person ?: '-' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div class="rounded-2xl bg-slate-50 p-3">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                            Email
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-blue-600 truncate">
                                            {{ $client->email ?: '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50 p-3">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                            Phone
                                        </p>

                                        <p class="mt-1 text-xs font-bold text-slate-700 truncate">
                                            {{ $client->phone ?: '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                        Address
                                    </p>

                                    <p class="mt-1 text-sm text-slate-600 line-clamp-2">
                                        {{ $client->address ?: '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 2xl:col-span-3 px-5 py-16 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                                🏢
                            </div>

                            <h3 class="mt-4 text-xl font-black text-slate-900">
                                No clients yet
                            </h3>

                            <p class="mt-2 text-sm text-slate-500">
                                Add your first client so projects can be connected to a customer record.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div id="noClientResults" class="hidden px-5 py-14 text-center border-t border-slate-100">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">
                    🔎
                </div>

                <h3 class="mt-4 text-xl font-black text-slate-900">
                    No matching clients
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Try changing the search text or filter.
                </p>
            </div>

            @if(method_exists($clients, 'links'))
                <div class="p-4 sm:p-5 border-t border-slate-100">
                    {{ $clients->links() }}
                </div>
            @endif

        </div>

    </div>

    <script>
        let activeClientFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('operationsClientsView') || 'table';
            setClientView(savedView);
            applyClientVisibility();
        });

        function setClientView(view) {
            const tableView = document.getElementById('clientTableView');
            const cardView = document.getElementById('clientCardView');
            const tableBtn = document.getElementById('tableViewBtn');
            const cardBtn = document.getElementById('cardViewBtn');

            localStorage.setItem('operationsClientsView', view);

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

        function filterClients(filter, button) {
            activeClientFilter = filter;

            document.querySelectorAll('.client-filter').forEach(function (btn) {
                btn.classList.remove('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
                btn.classList.add('bg-white/80', 'border-slate-200');
            });

            if (button) {
                button.classList.remove('bg-white/80', 'border-slate-200');
                button.classList.add('active-filter', 'bg-slate-950', 'text-white', 'border-slate-900');
            }

            applyClientVisibility();
        }

        function searchClients() {
            applyClientVisibility();
        }

        function clientMatchesFilter(element) {
            if (activeClientFilter === 'all') {
                return true;
            }

            if (activeClientFilter === 'email') {
                return element.dataset.email === 'yes';
            }

            if (activeClientFilter === 'phone') {
                return element.dataset.phone === 'yes';
            }

            if (activeClientFilter === 'contact_person') {
                return element.dataset.contactPerson === 'yes';
            }

            return true;
        }

        function clientMatchesSearch(element) {
            const searchInput = document.getElementById('clientSearch');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            if (!query) {
                return true;
            }

            return (element.dataset.search || '').includes(query);
        }

        function applyClientVisibility() {
            const rows = document.querySelectorAll('.client-row');
            const cards = document.querySelectorAll('.client-card');
            const noResults = document.getElementById('noClientResults');

            let visibleCount = 0;

            rows.forEach(function (row) {
                const visible = clientMatchesFilter(row) && clientMatchesSearch(row);
                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            cards.forEach(function (card) {
                const visible = clientMatchesFilter(card) && clientMatchesSearch(card);
                card.classList.toggle('hidden', !visible);
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }
        }
    </script>
</x-app-layout>