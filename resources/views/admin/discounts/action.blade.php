<div class="btn-group" role="group">
    <button class="btn btn-sm btn-outline-warning"
            onclick="onEdit({{ $row->id }}, {{ $row->menu_id }}, '{{ $row->type }}', {{ $row->value }}, '{{ $row->start_date?->format('Y-m-d') }}', '{{ $row->end_date?->format('Y-m-d') }}', '{{ $row->start_time ? \Carbon\Carbon::parse($row->start_time)->format('H:i') : '' }}', '{{ $row->end_time ? \Carbon\Carbon::parse($row->end_time)->format('H:i') : '' }}', '{{ $row->description }}', {{ $row->is_active ? 1 : 0 }})"
            title="Edit">
        <i class="fas fa-edit me-1"></i> Edit
    </button>

    <button class="btn btn-sm btn-outline-{{ $row->is_active ? 'secondary' : 'success' }}"
            onclick="onToggleStatus({{ $row->id }})"
            title="{{ $row->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
        <i class="fas fa-{{ $row->is_active ? 'pause' : 'play' }} me-1"></i>
        {{ $row->is_active ? 'Nonaktif' : 'Aktifkan' }}
    </button>

    <button class="btn btn-sm btn-outline-danger"
            onclick="onDelete({{ $row->id }})"
            title="Hapus">
        <i class="fas fa-trash me-1"></i> Hapus 
    </button>
</div>
