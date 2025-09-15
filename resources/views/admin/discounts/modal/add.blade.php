{{-- resources/views/admin/discounts/modal/add.blade.php --}}

<div class="modal fade" id="modal_add" tabindex="-1" aria-labelledby="modal_add_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_add" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_add_label">
                        <i class="fas fa-plus me-2"></i>Tambah Diskon Menu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="menu_id" class="form-label">Pilih Menu <span class="text-danger">*</span></label>
                            <select class="form-select" name="menu_id" required>
                                <option value="">-- Pilih Menu --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                            <div class="menu-price-display mt-2" style="display: none;"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Nilai Diskon <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="value" placeholder="Masukkan nilai diskon" required min="0" step="0.01">
                        <div class="form-text">Masukkan nilai diskon sesuai tipe yang dipilih</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date">
                            <div class="form-text">Kosongkan jika berlaku selamanya</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" name="end_date">
                            <div class="form-text">Kosongkan jika tidak ada batas waktu</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="start_time">
                            <div class="form-text">Opsional - untuk happy hour</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">Jam Berakhir</label>
                            <input type="time" class="form-control" name="end_time">
                            <div class="form-text">Opsional - untuk happy hour</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi diskon (opsional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_add" checked>
                            <label class="form-check-label" for="is_active_add">
                                <strong>Aktifkan Diskon</strong>
                            </label>
                        </div>
                    </div>

                    {{-- Preview Section --}}
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-1"></i> Tips:</h6>
                        <ul class="mb-0">
                            <li>Diskon persentase maksimal 100%</li>
                            <li>Diskon nominal tidak boleh melebihi harga menu</li>
                            <li>Jika tidak ada batasan tanggal/jam, diskon berlaku selamanya</li>
                            <li>Hanya bisa ada 1 diskon aktif per menu dalam periode yang sama</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
