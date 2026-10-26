<x-layouts.app title="Detail Pemeriksaan">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.riwayat-pendaftaran.index') }}"
           class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Detail Hasil Pemeriksaan
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kartu Info Pemeriksaan & Dokter --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
            <div class="card-body p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Informasi Poliklinik</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-semibold">Poliklinik</p>
                        <p class="font-bold text-slate-800 text-base">{{ $daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase font-semibold">Dokter Pemeriksa</p>
                        <p class="font-semibold text-slate-700">Dr. {{ $daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase font-semibold">Tanggal Pemeriksaan</p>
                        <p class="text-slate-700">
                            {{ $periksa ? \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d F Y (H:i)') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase font-semibold">Keluhan Awal Pasien</p>
                        <p class="text-slate-700 italic bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            "{{ $daftarPoli->keluhan }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Catatan Dokter & Obat-Obatan --}}
        <div class="lg:col-span-2 card bg-base-100 shadow-md rounded-2xl border border-slate-200">
            <div class="card-body p-6">

                {{-- Catatan Dokter --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan / Diagnosa Dokter</h3>
                    <div class="p-4 bg-blue-50/60 border border-blue-200 rounded-xl text-slate-800 text-sm">
                        {{ $periksa->catatan ?? 'Tidak ada catatan khusus dari dokter.' }}
                    </div>
                </div>

                {{-- Resep Obat --}}
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Resep Obat Diberikan</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="table w-full border border-slate-200 rounded-xl">
                            <thead class="bg-slate-50 text-slate-500 text-xs">
                                <tr>
                                    <th class="px-4 py-2">Nama Obat</th>
                                    <th class="px-4 py-2">Kemasan</th>
                                    <th class="px-4 py-2 text-right">Harga Satuan</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @if ($periksa && $periksa->detailPeriksas->isNotEmpty())
                                    @foreach ($periksa->detailPeriksas as $detail)
                                        <tr class="border-t border-slate-100">
                                            <td class="px-4 py-2.5 font-semibold text-slate-800">{{ $detail->obat->nama_obat }}</td>
                                            <td class="px-4 py-2.5 text-slate-600">{{ $detail->obat->kemasan ?? '-' }}</td>
                                            <td class="px-4 py-2.5 text-right font-medium">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-slate-400">Tidak ada resep obat.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Rincian Biaya --}}
                <div class="border-t border-slate-200 pt-4 flex flex-col items-end">
                    <div class="w-full md:w-1/2 space-y-2 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Jasa Pemeriksaan Dokter:</span>
                            <span>Rp 150.000</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Harga Obat:</span>
                            <span>
                                Rp {{ number_format(($periksa->biaya_periksa ?? 150000) - 150000, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between font-bold text-base text-slate-800 border-t pt-2">
                            <span>Total Biaya Berobat:</span>
                            <span class="text-blue-700">Rp {{ number_format($periksa->biaya_periksa ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-layouts.app>