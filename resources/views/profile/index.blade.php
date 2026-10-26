<x-layouts.app title="Profil Saya">

    <div class="max-w-2xl mx-auto">

        {{-- Header & Tombol Kembali --}}
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ url()->previous() }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 shadow-sm transition">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Profil Saya</h2>
        </div>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success mb-6 rounded-xl shadow-sm border border-slate-200">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Card Utama --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
            <div class="card-body p-8">

                {{-- Informasi Singkat Pengguna --}}
                <div class="flex items-center gap-4 pb-6 mb-6 border-b border-slate-100">
                    <div
                        class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xl shadow-sm">
                        {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">{{ $user->nama ?? $user->name }}</h2>
                        <p class="text-sm text-slate-500">{{ $user->email }} • <span
                                class="capitalize font-medium text-blue-600">{{ $user->role }}</span></p>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-slate-800 mb-4">Ganti Password</h3>

                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 1. Password Lama --}}
                    <div class="form-control mb-4">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">Password Lama <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <input type="password" name="current_password" placeholder="Masukkan password lama saat ini"
                            class="input input-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none @error('current_password') input-error @enderror"
                            required>
                        @error('current_password')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 2. Password Baru --}}
                    <div class="form-control mb-4">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">Password Baru <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                            class="input input-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none @error('password') input-error @enderror"
                            required>
                        @error('password')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 3. Konfirmasi Password Baru --}}
                    <div class="form-control mb-6">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">Konfirmasi Password Baru <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                            class="input input-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none"
                            required>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition shadow-md hover:shadow-lg">
                            Simpan Password
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</x-layouts.app>