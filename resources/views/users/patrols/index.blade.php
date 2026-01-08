@extends('users.layouts.index')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header py-3 bg-white d-flex flex-column align-items-start"
                style="border-bottom: 1px solid #f0e6ea;">
                <h4 class="m-0 font-weight-bold" style="color: #e91e63;">Data Safety Patrol</h4>
                <p class="m-0 text-muted">
                    Member: <strong class="text-pink">{{ session('login_name') ?? '-' }}</strong>
                </p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered datatable" style="font-size: 0.95rem; width: 100%;">
                        <thead class="bg-light-pink text-pink">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Nama Patrol</th>
                                <th class="py-3 px-4">Waktu Patrol</th>
                                <th class="py-3 px-4">Temuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patrols as $patrol)
                                <tr>
                                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4">{{ $patrol->Name_Patrol }}</td>
                                    <td class="py-3 px-4">
                                        {{ \Carbon\Carbon::parse($patrol->Time_Patrol)->format('d-m-Y H:i') }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('user_temuan.index', ['id' => $patrol->Id_Patrol]) }}"
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
    <!-- Modal Tambah & Edit tetap seperti aslinya -->
    <!-- Tidak diubah karena tidak digunakan di halaman user ini -->
@endsection

@section('script')
    <style>
        .text-pink {
            color: #e91e63 !important;
        }

        .bg-light-pink {
            background-color: #fdf2f8 !important;
        }

        .card {
            border-radius: 12px;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.9rem;
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
            $('#patrolTable').DataTable({
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
                        "targets": [3]
                    } // Kolom "Temuan" & "Nilai" tidak bisa di-sort
                ]
            });
        });
    </script>
@endsection
