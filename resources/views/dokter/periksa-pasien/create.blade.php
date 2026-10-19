<x-layouts.app title="Periksa Pasien">

    {{-- Header dengan tombol kembali --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Card Form --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 max-w-4xl">
        <div class="card-body p-8">

            <form action="{{ route('periksa-pasien.store') }}" method="POST" id="form-periksa">
                @csrf

                {{-- Hidden Inputs untuk Controller --}}
                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">
                <input type="hidden" name="obat_json" id="obat_json" value="[]">
                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">

                {{-- 1. Pilih Obat --}}
                <div class="form-control mb-6">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-slate-700">
                            Pilih Obat <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <select id="select_obat"
                        class="select select-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="" selected>-- Pilih Obat --</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                data-harga="{{ $obat->harga }}">
                                {{ $obat->nama_obat }} - {{ $obat->kemasan ?? 'Biasa' }} (Rp
                                {{ number_format($obat->harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('obat_json')
                        <span class="text-xs text-red-500 mt-1">Pilih minimal 1 obat untuk pasien.</span>
                    @enderror
                </div>

                {{-- 2. Daftar Obat Terpilih --}}
                <div class="form-control mb-6">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-slate-700">Obat Terpilih</span>
                    </label>
                    <div id="container_obat_terpilih"
                        class="flex flex-wrap gap-2 p-4 min-h-[50px] bg-slate-50 border border-slate-200 rounded-xl">
                        <p class="text-sm text-slate-400 italic">Belum ada obat yang dipilih</p>
                    </div>
                </div>

                {{-- 3. Total Harga --}}
                <div class="form-control mb-6">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-slate-700">Total Harga</span>
                    </label>
                    <div class="p-3 bg-slate-100 border border-slate-200 rounded-xl">
                        <span id="display_total_harga" class="text-lg font-bold text-slate-800">
                            Rp 0
                        </span>
                    </div>
                </div>

                {{-- 4. Catatan (Opsional) --}}
                <div class="form-control mb-8">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-slate-700">Catatan (Opsional)</span>
                    </label>
                    <textarea name="catatan" rows="4" placeholder="Masukkan catatan..."
                        class="textarea textarea-bordered w-full rounded-xl text-slate-700 focus:ring-2 focus:ring-primary focus:outline-none resize-none">{{ old('catatan') }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl transition shadow-md">
                        Simpan
                    </button>

                    <a href="{{ route('periksa-pasien.index') }}"
                        class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    {{-- Script Tambah & Hapus Obat Dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectObat = document.getElementById('select_obat');
            const containerObatTerpilih = document.getElementById('container_obat_terpilih');
            const displayTotalHarga = document.getElementById('display_total_harga');
            const inputObatJson = document.getElementById('obat_json');
            const inputBiayaPeriksa = document.getElementById('biaya_periksa');

            let selectedObat = [];

            // Event handler saat obat dipilih dari dropdown
            selectObat.addEventListener('change', function () {
                const option = selectObat.options[selectObat.selectedIndex];
                if (!option.value) return;

                const id = option.value;
                const nama = option.getAttribute('data-nama');
                const harga = parseInt(option.getAttribute('data-harga')) || 0;

                // Cegah obat ganda
                if (selectedObat.some(item => String(item.id) === String(id))) {
                    alert('Obat ini sudah ditambahkan.');
                    selectObat.value = '';
                    return;
                }

                selectedObat.push({ id, nama, harga });
                selectObat.value = '';

                updateUI();
            });

            // Fungsi Hapus Obat Terpilih
            window.hapusObat = function (id) {
                selectedObat = selectedObat.filter(item => String(item.id) !== String(id));
                updateUI();
            };

            // Re-render UI & Hitung Ulang Total Harga
            function updateUI() {
                if (selectedObat.length === 0) {
                    containerObatTerpilih.innerHTML = '<p class="text-sm text-slate-400 italic">Belum ada obat yang dipilih</p>';
                } else {
                    containerObatTerpilih.innerHTML = selectedObat.map(item => `
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 shadow-sm">
                            <span>${item.nama} (Rp ${new Intl.NumberFormat('id-ID').format(item.harga)})</span>
                            <button type="button" onclick="hapusObat('${item.id}')" class="text-red-500 hover:text-red-700 transition ml-1">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    `).join('');
                }

                // Kalkulasi Total Obat
                const totalObat = selectedObat.reduce((sum, item) => sum + item.harga, 0);

                // Update tampilan dan hidden input
                displayTotalHarga.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalObat);
                inputObatJson.value = JSON.stringify(selectedObat.map(item => item.id));
                inputBiayaPeriksa.value = totalObat;
            }
        });
    </script>

</x-layouts.app>