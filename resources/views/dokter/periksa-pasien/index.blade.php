<x-layouts.app title="Periksa Pasien">

    {{-- Alert Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success mb-6 rounded-xl shadow-sm border border-slate-200">
            <i class="fas fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>

        {{-- Tombol Tambah Periksa Pasien --}}
        @php
            // Mengambil data antrian pasien pertama yang belum diperiksa
            $pasienBelumDiperiksa = $daftarPasien->first(function ($item) {
                return $item->periksas->isEmpty();
            });
        @endphp

        <!-- @if ($pasienBelumDiperiksa)
            <a href="{{ route('periksa-pasien.create', $pasienBelumDiperiksa->id) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-plus text-xs"></i>
                Tambah Periksa Pasien
            </a>
        @else
            <button disabled
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-300 text-slate-500 text-sm font-semibold rounded-xl cursor-not-allowed">
                <i class="fas fa-plus text-xs"></i>
                Tambah Periksa Pasien
            </button>
        @endif -->
    </div>

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Keluhan</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse ($daftarPasien as $item)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                                <td class="px-6 py-4 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $item->pasien->nama ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $item->keluhan ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $item->no_antrian }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if ($item->periksas && $item->periksas->isNotEmpty())
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <i class="fas fa-check-circle text-xs"></i>
                                            Sudah Diperiksa
                                        </span>
                                    @else
                                        <a href="{{ route('periksa-pasien.create', $item->id) }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                            <i class="fas fa-stethoscope text-xs"></i>
                                            Periksa
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400">
                                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                    Belum ada antrian pasien.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>