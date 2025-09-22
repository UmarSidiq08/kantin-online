<div class="modal fade" id="modal_add" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="form_add" class="modal-content bg-white rounded-xl shadow-xl border-0" enctype="multipart/form-data">
            @csrf
            <!-- Header -->
            <div class="modal-header bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-t-xl border-0 px-6 py-4">
                <h5 class="modal-title font-semibold text-lg">Tambah Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-6 space-y-4">
                <!-- Nama Menu -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nama Menu</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200" name="name" placeholder="Masukkan nama menu" required>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Deskripsi</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200 resize-none" name="description" rows="3" placeholder="Masukkan deskripsi menu" required></textarea>
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 text-sm font-medium mb-2">Kategori</label>
                    <select name="category" id="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200">
                        @foreach (\App\Constant::MENU_CATEGORIES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Harga</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                        <input type="number" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200" name="price" placeholder="0" required>
                    </div>
                </div>

                <!-- Gambar -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Gambar</label>
                    <div class="relative">
                        <input type="file" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" name="image" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-gray-50 rounded-b-xl px-6 py-4 border-0">
                <button type="button" class="px-6 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 mr-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
