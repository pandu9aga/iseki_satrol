@extends('admins.layouts.index')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="m-0">Data Member</h4>
                <!-- Tombol tambah bisa ditambahkan di sini nanti jika perlu -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="memberTable" class="table table-borderless align-middle"
                        style="width:100%; font-size: 0.95rem;">
                        <thead class="text-pink bg-light-pink">
                            <tr>
                                <th class="py-3 px-4 text-center" style="width: 5%;">No</th>
                                <th class="py-3 px-4">NIK</th>
                                <th class="py-3 px-4">Nama</th>
                                <th class="py-3 px-4">Divisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($members as $member)
                                <tr>
                                    <td class="text-center py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4">{{ $member->nik }}</td>
                                    <td class="py-3 px-4">{{ $member->nama }}</td>
                                    <td class="py-3 px-4">{{ $member->division->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada data member.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        .text-pink {
            color: #d63384 !important;
        }

        .bg-light-pink {
            background-color: #fdf2f8 !important;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(90deg, #fff 0%, #ffe6ef 100%);
            border-bottom: none;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 1.2rem 1.5rem;
        }

        .card-header h4 {
            color: #d63384;
            font-weight: 600;
            margin: 0;
        }

        /* Styling DataTables dengan tema pink */
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
            background: #d63384 !important;
            color: white !important;
            border-color: #d63384 !important;
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
            $('#memberTable').DataTable({
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
                        "targets": [0]
                    } // Kolom No tidak bisa di-sort
                ]
            });
        });
    </script>
@endsection
