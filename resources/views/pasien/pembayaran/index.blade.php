<x-layouts.app title="Pembayaran Tagihan">
    
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Pembayaran Tagihan Berobat</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Tgl Periksa</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4 text-center">Status Pembayaran</th>
                            <th class="px-6 py-4 text-right">Aksi Upload</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse ($tagihans as $item)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">
                                        {{ $item->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">Dr.
                                        {{ $item->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tgl_periksa)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-bold text-blue-600">Rp
                                    {{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if (!$item->pembayaran)
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Belum
                                            Bayar</span>
                                    @elseif ($item->pembayaran->status_pembayaran === 'pending')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu
                                            Verifikasi</span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Lunas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if (!$item->pembayaran || $item->pembayaran->status_pembayaran === 'pending')
                                        <form action="{{ route('pasien.pembayaran.store') }}" method="POST"
                                            enctype="multipart/form-data" class="flex items-center justify-end gap-2">
                                            @csrf
                                            <input type="hidden" name="id_periksa" value="{{ $item->id }}">
                                            <input type="file" name="bukti_pembayaran" accept="image/*"
                                                class="file-input file-input-bordered file-input-xs w-full max-w-xs rounded-lg"
                                                required>
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">Upload</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-emerald-600 font-semibold"><i class="fas fa-check-double"></i>
                                            Verified</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400">Belum ada tagihan pemeriksaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>