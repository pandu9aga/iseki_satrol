@extends('admins.layouts.index')
@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center"
                style="border-bottom: 1px solid #f0e6ea;">
                <h4 class="m-0 font-weight-bold" style="color: #e91e63;">Data User</h4>
                <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus me-1"></i> Tambah User
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="userTable" class="table table-borderless mb-0" style="font-size: 0.95rem; width: 100%;">
                        <thead class="bg-light-pink text-pink">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Username</th>
                                <th class="py-3 px-4">Nama</th>
                                <th class="py-3 px-4">Password</th>
                                <th class="py-3 px-4">Tipe User</th>
                                <th class="py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4">{{ $user->Username_User }}</td>
                                    <td class="py-3 px-4">{{ $user->Name_User }}</td>
                                    <td class="py-3 px-4">{{ $user->Password_User }}</td>
                                    <td class="py-3 px-4">{{ $user->type_user->Name_Type_User ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <button type="button" class="btn btn-sm btn-outline-success me-1"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                                            data-id="{{ $user->Id_User }}" data-username="{{ $user->Username_User }}"
                                            data-name="{{ $user->Name_User }}" data-password="{{ $user->Password_User }}"
                                            data-type_user="{{ $user->Id_Type_User }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('user.destroy', $user->Id_User) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
    <!-- Modal Tambah User -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0"
                style="border-radius: 12px; box-shadow: 0 4px 20px rgba(233, 30, 99, 0.15);">
                <form action="{{ route('user.create') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="addModalLabel" style="color: #e91e63;">Tambah Data User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Username</label>
                            <input type="text" class="form-control" name="Username_User" placeholder="Masukkan username"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <input type="text" class="form-control" name="Name_User" placeholder="Masukkan nama"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Password</label>
                            <input type="text" class="form-control" name="Password_User" placeholder="Minimal 6 karakter"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tipe User</label>
                            <select class="form-select" name="Id_Type_User" required>
                                <option value="" disabled selected>Pilih tipe user</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
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

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0"
                style="border-radius: 12px; box-shadow: 0 4px 20px rgba(233, 30, 99, 0.15);">
                <form id="editUserForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_user_id" name="id">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="editUserModalLabel" style="color: #e91e63;">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="Username_User" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <input type="text" class="form-control" id="edit_name_user" name="Name_User" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Password</label>
                            <input type="text" class="form-control" id="edit_password" name="Password_User">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tipe User</label>
                            <select class="form-select" id="edit_type_user" name="Id_Type_User" required>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
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

        div.dataTables_wrapper div.dataTables_length select,
        div.dataTables_wrapper div.dataTables_filter input {
            border: 1px solid #f0e6ea;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        div.dataTables_wrapper div.dataTables_paginate .paginate_button {
            padding: 6px 12px !important;
            margin: 0 2px !important;
            border-radius: 6px !important;
            border: 1px solid #eee !important;
        }

        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
            background: #e91e63 !important;
            color: white !important;
            border-color: #e91e63 !important;
        }

        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_paginate {
            padding: 10px 15px;
        }
    </style>

    <!-- DataTables JS -->
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "»",
                        "previous": "«"
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 5]
                }]
            });
        });

        // Modal Edit - tetap seperti versi working
        const editModal = document.getElementById('editUserModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const username = button.getAttribute('data-username');
            const name = button.getAttribute('data-name');
            const password = button.getAttribute('data-password');
            const typeUser = button.getAttribute('data-type_user');

            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_name_user').value = name;
            document.getElementById('edit_password').value = password;
            document.getElementById('edit_type_user').value = typeUser;

            const form = document.getElementById('editUserForm');
            form.action = `./data_user/${id}`;
        });
    </script>
@endsection
