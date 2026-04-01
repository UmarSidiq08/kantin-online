<div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form_edit" class="modal-content" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id">
            {{-- Simpan nama file gambar lama, dikirim ke controller jika tidak ada gambar baru --}}
            <input type="hidden" name="existing_image" id="edit_existing_image">

            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="category" required>
                        @foreach (\App\Constant::MENU_CATEGORIES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" name="price" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" name="stok" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label>

                    {{-- Preview gambar lama --}}
                    <div id="edit_image_preview_container" class="mb-2">
                        <p class="text-sm text-gray-500 mb-1">Gambar saat ini:</p>
                        <img id="edit_image_preview" src="" alt="Preview" class="rounded"
                            style="height: 80px; object-fit: cover;">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan input di bawah jika tidak ingin mengganti
                            gambar.</p>
                    </div>

                    <input type="file" class="form-control" name="image" id="edit_image_input" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
