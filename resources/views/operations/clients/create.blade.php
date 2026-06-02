<x-app-layout>
    <x-slot name="header">
        Add Client
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
              action="{{ route('operations.clients.store') }}"
              class="space-y-4">
            @csrf

            <div class="relative overflow-hidden rounded-[1.7rem] bg-slate-950 text-white shadow-xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.32),_transparent_35%)]"></div>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('operations.clients') }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 text-[11px] font-black transition">
                                    ← Clients
                                </a>

                                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-[11px] font-black">
                                    New Client Record
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                                Add Client
                            </h1>

                            <p class="mt-2 text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                                Create a clean customer record that can be connected to future projects and operational workspaces.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ route('operations.clients') }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm font-black transition">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/30 transition">
                                Save Client →
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
                                Business Identity
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Main information used to identify the client.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Business Name <span class="text-red-500">*</span>
                                </label>

                                <input name="name"
                                       id="clientNameInput"
                                       required
                                       value="{{ old('name') }}"
                                       oninput="updateClientPreview()"
                                       placeholder="Example: ABC Trading"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Contact Person
                                    </label>

                                    <input name="contact_person"
                                           id="contactPersonInput"
                                           value="{{ old('contact_person') }}"
                                           oninput="updateClientPreview()"
                                           placeholder="Example: Juan Dela Cruz"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Business Type
                                    </label>

                                    <input name="business_type"
                                           id="businessTypeInput"
                                           value="{{ old('business_type') }}"
                                           oninput="updateClientPreview()"
                                           placeholder="Restaurant, Office, Retail, Resort, etc."
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                            <h2 class="text-xl font-black text-slate-900">
                                Contact Details
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Add the main communication channels for this client.
                            </p>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Email
                                    </label>

                                    <input name="email"
                                           id="emailInput"
                                           type="email"
                                           value="{{ old('email') }}"
                                           oninput="updateClientPreview()"
                                           placeholder="client@email.com"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                        Phone
                                    </label>

                                    <input name="phone"
                                           id="phoneInput"
                                           value="{{ old('phone') }}"
                                           oninput="updateClientPreview()"
                                           placeholder="09xx-xxx-xxxx"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm font-bold text-slate-800">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wide mb-2">
                                    Address
                                </label>

                                <textarea name="address"
                                          id="addressInput"
                                          rows="4"
                                          oninput="updateClientPreview()"
                                          placeholder="Client address"
                                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 text-sm text-slate-700 resize-none">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="2xl:hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('operations.clients') }}"
                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                            Save Client
                        </button>
                    </div>
                </div>

                <div class="2xl:col-span-4">
                    <div class="2xl:sticky 2xl:top-5 space-y-4">

                        <div class="rounded-[1.7rem] bg-white/85 backdrop-blur-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-5 py-4 border-b border-slate-100">
                                <h3 class="text-lg font-black text-slate-900">
                                    Client Preview
                                </h3>

                                <p class="text-xs text-slate-500 mt-1">
                                    Live preview of this client profile.
                                </p>
                            </div>

                            <div class="p-4 sm:p-5">
                                <div class="rounded-[1.5rem] bg-slate-950 text-white p-5 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,.35),_transparent_40%)]"></div>

                                    <div class="relative">
                                        <div id="previewInitial"
                                             class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl font-black shadow-lg">
                                            C
                                        </div>

                                        <h4 id="previewName"
                                            class="mt-4 text-2xl font-black leading-tight">
                                            Client Name
                                        </h4>

                                        <p id="previewBusinessType"
                                           class="mt-1 text-sm text-slate-300">
                                            Business Type
                                        </p>

                                        <div class="mt-5 space-y-2 text-xs">
                                            <div class="rounded-2xl bg-white/10 p-3">
                                                <p class="text-slate-400 font-black uppercase text-[10px]">
                                                    Contact Person
                                                </p>

                                                <p id="previewContact" class="mt-1 font-black">
                                                    -
                                                </p>
                                            </div>

                                            <div class="rounded-2xl bg-white/10 p-3">
                                                <p class="text-slate-400 font-black uppercase text-[10px]">
                                                    Email
                                                </p>

                                                <p id="previewEmail" class="mt-1 font-black truncate">
                                                    -
                                                </p>
                                            </div>

                                            <div class="rounded-2xl bg-white/10 p-3">
                                                <p class="text-slate-400 font-black uppercase text-[10px]">
                                                    Phone
                                                </p>

                                                <p id="previewPhone" class="mt-1 font-black">
                                                    -
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-[1.4rem] bg-slate-50 border border-slate-100 p-4">
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-wide">
                                        Address Preview
                                    </p>

                                    <p id="previewAddress"
                                       class="mt-2 text-sm text-slate-600 leading-relaxed line-clamp-4">
                                        No address yet.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="hidden 2xl:grid grid-cols-1 gap-3">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black shadow-lg shadow-blue-900/20 transition">
                                Save Client →
                            </button>

                            <a href="{{ route('operations.clients') }}"
                               class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-black shadow-sm transition">
                                Back to Clients
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateClientPreview();
        });

        function updateClientPreview() {
            const name = document.getElementById('clientNameInput').value.trim();
            const contact = document.getElementById('contactPersonInput').value.trim();
            const businessType = document.getElementById('businessTypeInput').value.trim();
            const email = document.getElementById('emailInput').value.trim();
            const phone = document.getElementById('phoneInput').value.trim();
            const address = document.getElementById('addressInput').value.trim();

            document.getElementById('previewInitial').textContent = name ? name.charAt(0).toUpperCase() : 'C';
            document.getElementById('previewName').textContent = name || 'Client Name';
            document.getElementById('previewBusinessType').textContent = businessType || 'Business Type';
            document.getElementById('previewContact').textContent = contact || '-';
            document.getElementById('previewEmail').textContent = email || '-';
            document.getElementById('previewPhone').textContent = phone || '-';
            document.getElementById('previewAddress').textContent = address || 'No address yet.';
        }
    </script>
</x-app-layout>