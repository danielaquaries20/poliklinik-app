<x-layouts.app title="Laporan Pemeriksaan">

    {{-- Header & Tombol Export Excel --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Laporan Rekapitulasi Pemeriksaan</h2>
            <p class="text-xs text-slate-500 mt-1">Rekap seluruh data pelayanan medis dan transaksi pembayaran
                poliklinik.</p>
        </div>

        <a href="{{ route('admin.laporan.export') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-md transition">
            <i class="fas fa-file-excel text-sm"></i>
            <span>Export Excel</span>
        </a>
    </div>

    {{-- Tabel Rekapitulasi --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4 text-center">Status Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse ($laporans as $item)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tgl_periksa)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $item->daftarPoli->pasien->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-bold text-slate-800">
                                        {{ $item->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                    <div class="text-slate-500">Dr.
                                        {{ $item->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-blue-600">Rp
                                    {{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if (($item->pembayaran->status_pembayaran ?? '') === 'lunas')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Lunas</span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Belum
                                            Bayar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400">Belum ada data laporan pemeriksaan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>