<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EMTECH Activity System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-900 overflow-x-hidden">

<div x-data="{ sidebarOpen: false }"
     class="min-h-screen bg-[radial-gradient(circle_at_top_left,_#eef2ff,_transparent_35%),radial-gradient(circle_at_top_right,_#ecfeff,_transparent_30%),#f8fafc]">
    <!-- SIDEBAR -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed left-0 top-0 bottom-0 z-50 w-[82vw] max-w-72 transform transition duration-300 ease-in-out lg:left-3 lg:top-3 lg:bottom-7 lg:w-72">

        <div class="h-full bg-slate-950 text-white shadow-2xl border-r border-white/10 overflow-hidden flex flex-col lg:rounded-3xl lg:border lg:border-white/10">

            <!-- LOGO -->
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center font-extrabold shadow-lg">
                        E
                    </div>

                    <div>
                        <h1 class="text-xl font-extrabold tracking-wide">EMTECH</h1>
                        <p class="text-xs text-slate-400">Activity Management</p>
                    </div>
                </div>
            </div>

            <!-- NAVIGATION -->
            <nav class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6">

                <div>
                    <p class="px-3 mb-2 text-[11px] uppercase tracking-widest text-slate-500 font-bold">Main</p>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                       {{ request()->routeIs('dashboard') || request()->routeIs('operations.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </a>
                </div>

                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <div>
                        <p class="px-3 mb-2 text-[11px] uppercase tracking-widest text-slate-500 font-bold">Management</p>

                        <div class="space-y-1">
                            <a href="{{ route('operations.projects') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.projects*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>📁</span>
                                <span>Projects</span>
                            </a>

                            <a href="{{ route('operations.clients') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.clients*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>🏢</span>
                                <span>Clients</span>
                            </a>

                            <a href="{{ route('operations.employees') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.employees*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>👥</span>
                                <span>Employees</span>
                            </a>

                            <a href="{{ route('operations.activities') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.activities*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>🛠️</span>
                                <span>Activities</span>
                            </a>
                        </div>
                    </div>
                @endif

                @if(in_array(Auth::user()->role, ['admin', 'manager', 'technician', 'employee']))
                    <div>
                        <p class="px-3 mb-2 text-[11px] uppercase tracking-widest text-slate-500 font-bold">Work</p>

                        <div class="space-y-1">
                            <a href="{{ route('operations.workspace') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.workspace*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>🚀</span>
                                <span>Workspace</span>
                            </a>

                            <a href="{{ route('operations.my_tasks') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.my_tasks') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>✅</span>
                                <span>My Tasks</span>
                            </a>

                            <a href="{{ route('operations.my_activities') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-semibold
                               {{ request()->routeIs('operations.my_activities') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                                <span>📝</span>
                                <span>My Activities</span>
                            </a>
                        </div>
                    </div>
                @endif
            </nav>

            <!-- USER -->
            <div class="p-4 border-t border-white/10">
                <div class="bg-white/5 rounded-2xl p-3 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <p class="font-bold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ ucfirst(Auth::user()->role ?? 'User') }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white transition">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 rounded-xl text-slate-300 hover:bg-red-600 hover:text-white transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MOBILE OVERLAY -->
    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col min-w-0 lg:ml-[19.5rem]">

        <!-- TOPBAR -->
        <header class="px-4 sm:px-6 lg:px-8 pt-4">
        <div class="h-16 rounded-3xl bg-white/90 backdrop-blur-xl border border-slate-200 shadow-sm px-5 flex items-center justify-between gap-5">

            <div class="flex items-center gap-3 min-w-0">
                <button
                    type="button"
                    @click="sidebarOpen = true"
                    class="lg:hidden w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center font-black">
                    ☰
                </button>

                <div class="min-w-0">
                    <h2 class="text-lg font-black text-slate-900 truncate">
                        {{ $header ?? 'Dashboard' }}
                    </h2>
                    <p class="text-xs text-slate-500 truncate">
                        EMTECH Activity Management System
                    </p>
                </div>
            </div>

            <div class="hidden md:flex flex-1 max-w-xl">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
                    <input type="text"
                        placeholder="Search projects, clients, tasks..."
                        class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 transition relative">
                    🔔
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="hidden md:block text-right">
                    <p class="font-black text-slate-900 leading-tight">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-slate-500">
                        {{ Auth::user()->email }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 text-white flex items-center justify-center font-black shadow-lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>

        </div>
    </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="p-3 sm:p-4 lg:p-5 xl:p-6 max-w-[1450px] mx-auto w-full">
                {{ $slot }}
            </div>
        </main>

    </div>
</div>

<button
    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-2xl bg-slate-950 text-white shadow-2xl hover:bg-blue-600 transition flex items-center justify-center text-2xl">
    ↑
</button>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.12);
        border-radius: 999px;
        transition: 0.2s ease;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.22);
    }

    /* FIREFOX */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.12) transparent;
    }
</style>

</body>
</html>