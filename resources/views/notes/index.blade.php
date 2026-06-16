@extends('layout.main')

@section('title', 'Manage Notes - '  . Auth::user()->name)
@section('namepage', 'Notes Management')
@section('route', route('notes.index'))
@section('namemenu', 'Notes Data')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-sticky-note me-2"></i>Streamer Notes</h6>
        <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createNoteModal">
            <i class="fas fa-plus me-1"></i>Add Note
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="datatablesSimple" class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="px-4" style="width: 80px;">No</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th class="text-center" style="width: 180px;">Action</th> </tr>
                </thead>
                <tbody>
                    @forelse($notes as $index => $note)
                    <tr>
                        <td class="px-4 fw-bold text-muted">{{ $index + 1 }}</td>
                        <td><span class="fw-bold text-dark">{{ $note->title }}</span></td>
                        <td>
                            <span class="text-muted text-wrap d-block" style="max-width: 400px;">
                                {{ Str::limit($note->description, 60, '...') }}
                            </span>
                        </td>
                        <td class="text-center px-4">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('notes.overlay', $note->id) }}" target="_blank" class="btn btn-sm btn-soft-info" title="Buka Overlay OBS">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-soft-warning" data-bs-toggle="modal" data-bs-target="#editNoteModal{{ $note->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" id="delete" class="btn btn-sm btn-soft-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editNoteModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light border-0 py-3">
                                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i>Edit Note</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('notes.update', $note->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Title</label>
                                            <input type="text" name="title" class="form-control" value="{{ $note->title }}" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Description / Text Content</label>
                                            <textarea name="description" class="form-control" rows="3" required>{{ $note->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0 py-3">
                                        <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning text-white">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Not found any note.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-sticky-note me-2 text-primary"></i>Add New Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('notes.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-600">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Info Promo" required />
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-600">Description / Text Content</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="e.g. Buy 5 Booster Packs Get 1 Free Single Card! Promo only for today live stream!" required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection