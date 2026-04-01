<div class="d-flex justify-content-end">
    <button class="btn btn-warning btn-sm me-1"
        onclick="onEdit(
            '{{ $row->id }}',
            '{{ $row->name }}',
            '{{ $row->description }}',
            '{{ $row->category }}',
            '{{ $row->price }}',
            '{{ $row->stok }}',
            '{{ $row->image }}'
        )">
        Edit
    </button>
    <button class="btn btn-danger btn-sm" onclick="onDelete('{{ $row->id }}')">Hapus</button>
</div>
