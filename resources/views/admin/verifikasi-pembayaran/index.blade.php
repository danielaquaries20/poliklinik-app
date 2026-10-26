<x-layouts.app title="Verifikasi Pembayaran">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Verifikasi Pembayaran Pasien</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4 text-center">Bukti Transfer</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse ($pembayarans as $pembayaran)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $pembayaran->periksa->daftarPoli->pasien->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-bold text-slate-800">
                                        {{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                                    </div>
                                    <div class="text-slate-500">Dr.
                                        {{ $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-blue-600">
                                    Rp {{ number_format($pembayaran->periksa->biaya_periksa, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($pembayaran->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline font-semibold">
                                            <i class="fas fa-image"></i> Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($pembayaran->status_pembayaran === 'lunas')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Lunas</span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($pembayaran->status_pembayaran === 'pending')
                                        <form action="{{ route('admin.verifikasi.konfirmasi', $pembayaran->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                Konfirmasi Lunas
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">Sudah Diverifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-slate-400">Belum ada transaksi pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>