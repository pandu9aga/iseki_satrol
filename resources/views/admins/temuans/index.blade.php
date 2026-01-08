@extends('admins.layouts.index')
@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white d-flex flex-column align-items-start"
                style="border-bottom: 1px solid #f0e6ea;">
                <h4 class="m-0 font-weight-bold" style="color: #e91e63;">Temuan Safety Patrol</h4>
                <div class="d-flex align-items-center justify-content-between w-100 mt-3">
                    <div>
                        <p class="m-0 font-weight-bold text-dark">
                            Name Patrol 5S: <span class="text-pink">{{ $patrol->Name_Patrol ?? '-' }}</span>
                        </p>
                        <p class="m-0 text-muted">
                            Time Patrol Safety:
                            {{ $patrol->Time_Patrol ? \Carbon\Carbon::parse($patrol->Time_Patrol)->format('d-m-Y H:i') : '-' }}
                        </p>
                    </div>
                    {{-- <button class="btn btn-pink">
                        <i class="fas fa-plus me-1"></i> Tambah Temuan
                    </button> --}}
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('temuan.exportPPT', $patrol->Id_Patrol) }}" class="btn btn-success">
                        <i class="fas fa-file-powerpoint me-1"></i> Export ke PPT
                    </a>
                    {{-- <button class="btn btn-pink">Tambah Temuan</button> --}}
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered datatable" style="font-size: 0.95rem; width: 100%;">
                        <thead class="bg-light-pink text-pink">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Penemu</th>
                                <th class="py-3 px-4">Foto Temuan</th>
                                <th class="py-3 px-4">Hasil Temuan</th>
                                <th class="py-3 px-4">Foto Perbaikan</th>
                                <th class="py-3 px-4">Hasil Perbaikan</th>
                                <th class="py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($temuans as $index => $temuan)
                                <tr class="{{ $temuan->Status_Temuan == 'Done' ? 'done-row' : '' }}">
                                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4 text-pink font-weight-bold">
                                        {{ $temuan->user && $temuan->user->Id_Type_User == 1
                                            ? $temuan->user->Name_User ?? '-'
                                            : $temuan->member->nama ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($temuan->Path_Temuan)
                                            <img src="{{ asset('uploads/' . $temuan->Path_Temuan) }}" class="img-thumbnail"
                                                style="max-height:80px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">{{ $temuan->Desc_Temuan }}</td>
                                    <td class="py-3 px-4">
                                        @if ($temuan->Path_Update_Temuan)
                                            <img src="{{ asset('uploads/' . $temuan->Path_Update_Temuan) }}"
                                                class="img-thumbnail" style="max-height:80px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">{{ $temuan->Desc_Update_Temuan }}</td>
                                    <td class="py-3 px-4">
                                        <button type="button" class="btn btn-sm btn-outline-success me-1 view-temuan"
                                            data-bs-toggle="modal" data-bs-target="#viewTemuanModal"
                                            data-index="{{ $index }}" data-id="{{ $temuan->Id_Temuan }}"
                                            data-nama-penemu="{{ $temuan->user && $temuan->user->Id_Type_User == 1
                                                ? $temuan->user->Name_User ?? '-'
                                                : $temuan->member->nama ?? '-' }}"
                                            data-foto-temuan="{{ $temuan->Path_Temuan }}"
                                            data-desc-temuan="{{ $temuan->Desc_Temuan }}"
                                            data-foto-update="{{ $temuan->Path_Update_Temuan }}"
                                            data-desc-update="{{ $temuan->Desc_Update_Temuan }}"
                                            data-status="{{ $temuan->Status_Temuan }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('temuan.destroy', $temuan->Id_Temuan) }}" method="POST"
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
                <div class="card-footer bg-white border-0 text-end">
                    <a href="{{ route('patrol') }}" class="btn btn-outline-primary">Back to patrol</a>
                </div>
            </div>
        </div>
    </div>

    <!-- View Temuan Modal -->
    <div class="modal fade" id="viewTemuanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header px-5 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Detail Temuan</h5>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm me-2" id="prevTemuan">&laquo;
                            Prev</button>
                        <button type="button" class="btn btn-outline-primary btn-sm me-5" id="nextTemuan">Next
                            &raquo;</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-0">
                                <label class="form-label">Penemu:</label>
                                <span id="modalNamaPenemu" class="fw-bold text-primary"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form id="statusForm" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="Id_Temuan" id="statusTemuanId">
                                <div class="d-flex align-items-center">
                                    <label class="form-label me-3 mb-0">Status:</label>
                                    <div class="form-check form-switch mb-0 ms-2">
                                        <input class="form-check-input" type="checkbox" id="statusSwitchInput"
                                            name="Status_Temuan" value="Done">
                                        <label class="form-check-label" for="statusSwitchInput">Selesai</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Foto Temuan</label>
                            <img id="modalFotoTemuan" src="" alt="Foto Temuan" class="img-fluid rounded"
                                style="max-height:500px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Perbaikan</label>
                            <img id="modalFotoUpdate" src="" alt="Foto Perbaikan" class="img-fluid rounded"
                                style="max-height:500px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Temuan:</label>
                            <p id="modalDescTemuan"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perbaikan:</label>
                            <p id="modalDescUpdate"></p>
                        </div>
                    </div>

                    <hr>
                    <form id="updateTemuanForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="Path_Update_Temuan" class="form-label">Foto Perbaikan</label>
                            <input type="file" name="Path_Update_Temuan" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="Desc_Update_Temuan" class="form-label">Deskripsi Perbaikan</label>
                            <textarea name="Desc_Update_Temuan" rows="3" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Perbaikan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
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

        .done-row td {
            background-color: #e3f2fd !important;
            color: #1976d2 !important;
        }

        .card {
            border-radius: 12px;
        }

        .img-thumbnail {
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            var table = $('#example').DataTable({
                "pageLength": -1,
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
                "order": [],
                "columnDefs": [{
                    "orderable": false,
                    "targets": '_all'
                }]
            });

            function applyRowHighlight() {
                $('#example tbody tr').each(function() {
                    const btn = this.querySelector('button.view-temuan');
                    if (btn && (btn.dataset.status || '').toLowerCase() === 'done') {
                        this.classList.add('done-row');
                    } else {
                        this.classList.remove('done-row');
                    }
                });
            }

            applyRowHighlight();
            table.on('draw', applyRowHighlight);
        });

        document.addEventListener("DOMContentLoaded", () => {
            const viewModal = document.getElementById("viewTemuanModal");
            const statusTemuanId = document.getElementById("statusTemuanId");
            const statusSwitchInput = document.getElementById("statusSwitchInput");
            const statusForm = document.getElementById("statusForm");
            const updateTemuanForm = document.getElementById("updateTemuanForm");

            let temuanButtons = Array.from(document.querySelectorAll(".view-temuan"));
            let currentIndex = -1;

            viewModal.addEventListener("show.bs.modal", function(event) {
                const button = event.relatedTarget;
                if (!button) return;
                currentIndex = temuanButtons.indexOf(button);
                loadTemuanData(button);
            });

            statusSwitchInput.addEventListener("change", () => {
                const formData = new FormData(statusForm);
                fetch(statusForm.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        const row = document.querySelector(
                            `button[data-id="${formData.get('Id_Temuan')}"]`).closest("tr");
                        if (statusSwitchInput.checked) {
                            row.classList.add('done-row');
                        } else {
                            row.classList.remove('done-row');
                        }
                        alert("Status berhasil diperbarui!");
                    } else {
                        alert("Gagal update status");
                    }
                }).catch(err => {
                    console.error(err);
                    alert("Error saat update status");
                });
            });

            document.getElementById("nextTemuan").addEventListener("click", () => {
                if (currentIndex < temuanButtons.length - 1) {
                    currentIndex++;
                    loadTemuanData(temuanButtons[currentIndex]);
                }
            });

            document.getElementById("prevTemuan").addEventListener("click", () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    loadTemuanData(temuanButtons[currentIndex]);
                }
            });

            function loadTemuanData(btn) {
                const id = btn.dataset.id;
                statusTemuanId.value = id;
                document.getElementById("modalNamaPenemu").textContent = btn.dataset.namaPenemu || "-";
                document.getElementById("modalDescTemuan").textContent = btn.dataset.descTemuan || "-";
                document.getElementById("modalDescUpdate").textContent = btn.dataset.descUpdate || "-";

                document.getElementById("modalFotoTemuan").src = btn.dataset.fotoTemuan ?
                    `{{ asset('uploads/') }}/${btn.dataset.fotoTemuan}` : "{{ asset('storage/no-img.jpeg') }}";
                document.getElementById("modalFotoUpdate").src = btn.dataset.fotoUpdate ?
                    `{{ asset('uploads/') }}/${btn.dataset.fotoUpdate}` : "{{ asset('storage/no-img.jpeg') }}";

                statusSwitchInput.checked = (btn.dataset.status === "Done");

                updateTemuanForm.action = `{{ url('temuan') }}/${id}`;
                statusForm.action = `{{ url('temuan') }}/${id}/status`;
            }
        });
    </script>
@endsection
