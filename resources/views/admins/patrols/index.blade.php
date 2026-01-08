@extends('admins.layouts.index')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center"
                style="border-bottom: 1px solid #f0e6ea;">
                <h4 class="m-0 font-weight-bold" style="color: #e91e63;">Data Safety Patrol</h4>
                <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addPatrolModal">
                    <i class="fas fa-plus me-1"></i> Tambah Patrol
                </button>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered datatable mb-0" style="font-size: 0.95rem; width: 100%;">
                        <thead class="bg-light-pink text-pink">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Nama Patrol</th>
                                <th class="py-3 px-4">Tanggal Patrol</th>
                                <th class="py-3 px-4">Aksi</th>
                                <th class="py-3 px-4">Temuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patrols as $patrol)
                                <tr class="border-bottom" style="border-color: #f8f9fa;">
                                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4">{{ $patrol->Name_Patrol }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($patrol->Time_Patrol)->format('Y-m-d') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <button type="button" class="btn btn-sm btn-outline-success me-1"
                                            data-bs-toggle="modal" data-bs-target="#editPatrolModal"
                                            data-id="{{ $patrol->Id_Patrol }}" data-name="{{ $patrol->Name_Patrol }}"
                                            data-time="{{ \Carbon\Carbon::parse($patrol->Time_Patrol)->format('Y-m-d\TH:i') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('patrol.destroy', $patrol->Id_Patrol) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('temuan.index', ['id' => $patrol->Id_Patrol]) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Tambah Patrol -->
    <div class="modal fade" id="addPatrolModal" tabindex="-1" aria-labelledby="addPatrolLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0"
                style="border-radius: 12px; box-shadow: 0 4px 20px rgba(233, 30, 99, 0.15);">
                <form action="{{ route('patrol.create') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="addPatrolLabel" style="color: #e91e63;">Tambah Patrol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Patrol</label>
                            <input type="text" class="form-control" name="Name_Patrol" placeholder="Masukkan nama patrol"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tanggal Patrol</label>
                            <input type="date" class="form-control" name="Time_Patrol" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pink">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Patrol -->
    <div class="modal fade" id="editPatrolModal" tabindex="-1" aria-labelledby="editPatrolLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0"
                style="border-radius: 12px; box-shadow: 0 4px 20px rgba(233, 30, 99, 0.15);">
                <form id="editPatrolForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_patrol_id" name="id">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="editPatrolLabel" style="color: #e91e63;">Edit Patrol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Patrol</label>
                            <input type="text" class="form-control" id="edit_name_patrol" name="Name_Patrol"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tanggal Patrol</label>
                            <input type="date" class="form-control" id="edit_time_patrol" name="Time_Patrol"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-pink">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        .btn-pink {
            background: #e91e63;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-pink:hover {
            background: #d81b60;
            color: white;
        }

        .text-pink {
            color: #e91e63 !important;
        }

        .bg-light-pink {
            background-color: #fdf2f8 !important;
        }

        .card {
            border-radius: 12px;
        }
    </style>

    <script>
        const editModal = document.getElementById('editPatrolModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const time = button.getAttribute('data-time');

            document.getElementById('edit_patrol_id').value = id;
            document.getElementById('edit_name_patrol').value = name;
            document.getElementById('edit_time_patrol').value = time.split('T')[0];

            const form = document.getElementById('editPatrolForm');
            form.action = `./patrol/${id}`;
        });
    </script>
@endsection
