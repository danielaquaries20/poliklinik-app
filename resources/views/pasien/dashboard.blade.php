<x-layouts.app title="Dashboard Pasien">

    {{-- Alert --}}
    @if (session('error'))
        <div class="alert alert-error mb-6 rounded-xl shadow-sm border border-slate-200">
            <i class="fas fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Banner Antrian Aktif Pasien --}}
    <div id="container-antrian-aktif" class="{{ $antrianAktif ? '' : 'hidden' }}">
        @if ($antrianAktif)
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl p-6 text-white shadow-lg mb-8">
                <div class="flex items-center justify-between mb-4 border-b border-blue-500/40 pb-3">
                    <span class="text-xs uppercase tracking-wider font-bold text-blue-200">
                        📢 ANTRIAN AKTIF ANDA
                    </span>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/30 text-white border border-blue-300/30">
                        Status: {{ ucfirst($antrianAktif->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
                    <div>
                        <p class="text-xs text-blue-200">Poliklinik</p>
                        <p class="text-lg font-bold">{{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                        <p class="text-sm text-blue-100">Dr. {{ $antrianAktif->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-blue-200">Jadwal Periksa</p>
                        <p class="text-sm font-semibold">
                            {{ $antrianAktif->jadwalPeriksa->hari }},
                            {{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($antrianAktif->jadwalPeriksa->jam_selesai)->format('H:i') }}
                        </p>
                    </div>

                    <div class="text-center bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                        <p class="text-xs text-blue-200">Nomor Antrian Anda</p>
                        <p class="text-3xl font-extrabold text-amber-300">{{ $antrianAktif->no_antrian }}</p>
                    </div>

                    <div class="text-center bg-white/10 rounded-xl p-3 backdrop-blur-sm">
                        <p class="text-xs text-blue-200">Sedang Dipanggil</p>
                        <p id="no-sedang-dilayani" class="text-3xl font-extrabold text-emerald-300">
                            {{ $sedangDilayani > 0 ? $sedangDilayani : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Banner Antrian Kosong (Bila Tidak Ada Antrian Aktif / Setelah Selesai) --}}
    <div id="container-antrian-kosong" class="{{ !$antrianAktif ? '' : 'hidden' }}">
        <div class="bg-slate-100 border border-slate-200 rounded-2xl p-6 text-center text-slate-500 mb-8">
            <i class="fas fa-info-circle text-2xl mb-2 text-slate-400"></i>
            <p class="text-sm">Anda belum memiliki antrian aktif saat ini. Silakan mendaftar di menu <strong>Pendaftaran
                    Periksa</strong>.</p>
        </div>
    </div>

    {{-- Tabel Daftar Jadwal Poliklinik & Nomor Dipanggil --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Daftar Jadwal Poliklinik</h3>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Poli</th>
                            <th class="px-4 py-3">Dokter</th>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3">Jam Periksa</th>
                            <th class="px-4 py-3 text-center">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse ($jadwals as $jadwal)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $jadwal->dokter->poli->nama_poli ?? '-' }}</td>
                                <td class="px-4 py-3">Dr. {{ $jadwal->dokter->nama ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $jadwal->hari }}</td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span id="jadwal-dilayani-{{ $jadwal->id }}"
                                        class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                                        {{ $jadwal->no_sedang_dilayani > 0 ? 'No. ' . $jadwal->no_sedang_dilayani : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">Belum ada jadwal poliklinik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script WebSocket Listener (Laravel Echo) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Echo !== 'undefined') {
                const channel = window.Echo.channel('antrian-channel');

                // 1. Listener Pembaruan Nomor Antrian Berjalan
                channel.listen('.antrian.updated', (e) => {
                    const elBanner = document.getElementById('no-sedang-dilayani');
                    if (elBanner && e.noAntrianSekarang !== undefined) {
                        elBanner.textContent = e.noAntrianSekarang > 0 ? e.noAntrianSekarang : '-';
                    }

                    const elTabel = document.getElementById('jadwal-dilayani-' + e.idJadwal);
                    if (elTabel && e.noAntrianSekarang !== undefined) {
                        elTabel.textContent = e.noAntrianSekarang > 0 ? 'No. ' + e.noAntrianSekarang : '-';
                    }
                });

                // 2. Listener Antrian Selesai (Sembunyikan Banner Aktif Pasien)
                channel.listen('.AntrianSelesai', (e) => {
                    const currentUserId = {{ auth()->id() ?? 'null' }};
                    if (currentUserId && e.pasienId == currentUserId) {
                        document.getElementById('container-antrian-aktif')?.classList.add('hidden');
                        document.getElementById('container-antrian-kosong')?.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
</x-layouts.app>