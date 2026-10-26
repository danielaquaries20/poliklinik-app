<x-layouts.app title="Riwayat Pendaftaran Poli">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Riwayat Pendaftaran Poli
        </h2>
    </div>

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Jadwal Periksa</th>
                            <th class="px-6 py-4 text-center">No Antrian</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse ($riwayats as $riwayat)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                                <td class="px-6 py-4 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    Dr. {{ $riwayat->jadwalPeriksa->dokter->nama ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $riwayat->jadwalPeriksa->hari ?? '-' }},
                                    {{ \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-center font-bold text-blue-600">
                                    {{ $riwayat->no_antrian }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($riwayat->status === 'selesai')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <i class="fas fa-check-circle text-xs"></i> Selesai
                                        </span>
                                    @elseif ($riwayat->status === 'diperiksa')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <i class="fas fa-stethoscope text-xs"></i> Diperiksa
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            <i class="fas fa-clock text-xs"></i> Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if ($riwayat->periksas->isNotEmpty())
                                        <a href="{{ route('pasien.riwayat-pendaftaran.show', $riwayat->id) }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                            <i class="fas fa-eye text-xs"></i> Detail
                                        </a>
                                    @else
                                        <button disabled
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-200 text-slate-400 text-xs font-semibold rounded-lg cursor-not-allowed">
                                            <i class="fas fa-eye text-xs"></i> Detail
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                    Belum ada riwayat pendaftaran poli.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>