<x-layouts.app title="Pendaftaran Periksa">

    {{-- Alert Notifikasi --}}
    @if (session('message'))
        <div
            class="alert alert-{{ session('type', 'success') === 'success' ? 'success' : 'error' }} mb-6 rounded-xl shadow-sm border border-slate-200">
            <i class="fas fa-{{ session('type', 'success') === 'success' ? 'circle-check' : 'circle-xmark' }}"></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success mb-6 rounded-xl shadow-sm border border-slate-200">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error mb-6 rounded-xl shadow-sm border border-slate-200">
            <i class="fas fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Container Form Pendaftaran --}}
    <div class="max-w-3xl mx-auto">
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
            <div class="card-body p-8">

                {{-- Judul Form --}}
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-800 flex items-center justify-center gap-2">
                        <span>📑</span> Pendaftaran Poli
                    </h2>
                </div>

                <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                    @csrf

                    {{-- 1. Nomor Rekam Medis (Readonly) --}}
                    <div class="form-control mb-5">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">Nomor Rekam Medis</span>
                        </label>
                        <input type="text" value="{{ $user->no_rm ?? 'Belum ada No. RM' }}"
                            class="input input-bordered w-full rounded-xl bg-slate-100 text-slate-600 font-medium cursor-not-allowed"
                            readonly disabled>
                    </div>

                    {{-- 2. Pilih Poli --}}
                    <div class="form-control mb-5">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">
                                Pilih Poli <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="id_poli" id="id_poli"
                            class="select select-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none @error('id_poli') select-error @enderror"
                            required>
                            <option value="" disabled {{ old('id_poli') ? '' : 'selected' }}>-- Pilih Poli --</option>
                            @foreach ($polis as $poli)
                                <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_poli')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 3. Pilih Jadwal Periksa --}}
                    <div class="form-control mb-5">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">
                                Pilih Jadwal Periksa <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="id_jadwal" id="id_jadwal"
                            class="select select-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none @error('id_jadwal') select-error @enderror"
                            required>
                            <option value="" disabled {{ old('id_jadwal') ? '' : 'selected' }}>-- Pilih Jadwal --
                            </option>
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" data-poli="{{ $jadwal->dokter->id_poli ?? '' }}"
                                    @selected(old('id_jadwal') == $jadwal->id)>
                                    Dokter {{ $jadwal->dokter->nama ?? 'Tidak Diketahui' }} | {{ $jadwal->hari }},
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jadwal')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 4. Keluhan (Wajib diisi agar sesuai skema DB SQL NOT NULL) --}}
                    <div class="form-control mb-8">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-slate-700">
                                Keluhan <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <textarea name="keluhan" rows="4" placeholder="Tulis keluhan anda..."
                            class="textarea textarea-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none resize-none @error('keluhan') textarea-error @enderror"
                            required>{{ old('keluhan') }}</textarea>
                        @error('keluhan')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition shadow-md hover:shadow-lg">
                            Daftar Poli
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Script JavaScript untuk Filter Jadwal Berdasarkan Poli --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const poliSelect = document.getElementById('id_poli');
            const jadwalSelect = document.getElementById('id_jadwal');
            const jadwalOptions = Array.from(jadwalSelect.options);

            function filterJadwal() {
                const selectedPoliId = poliSelect.value;

                // Reset pilihan jadwal ke placeholder jika poli berganti
                jadwalSelect.value = '';

                jadwalOptions.forEach(option => {
                    if (option.value === '') return;

                    const poliIdOption = option.getAttribute('data-poli');

                    if (selectedPoliId === '' || poliIdOption === selectedPoliId) {
                        option.style.display = 'block';
                        option.disabled = false;
                    } else {
                        option.style.display = 'none';
                        option.disabled = true;
                    }
                });
            }

            poliSelect.addEventListener('change', filterJadwal);

            // Jalankan filter otomatis jika ada old value setelah validasi error
            if (poliSelect.value) {
                filterJadwal();
            }
        });
    </script>

</x-layouts.app>