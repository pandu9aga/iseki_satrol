@extends('admins.layouts.index')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Cards -->
        <div class="row">
            <!-- Total Patrol -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-4 border-pink shadow-sm h-100 py-2"
                    style="border-left-color: #e91e63 !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-pink text-uppercase mb-1">Total Patrol</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPatrol }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-search fa-2x text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Patrol -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold" style="color: #e91e63;">Daftar Patrol</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead class="bg-light-pink text-pink">
                                    <tr>
                                        <th class="py-3 px-4" style="width: 5%;">No</th>
                                        <th class="py-3 px-4">Nama Patrol</th>
                                        <th class="py-3 px-4">Waktu Patrol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patrols as $patrol)
                                        <tr class="border-bottom" style="border-color: #f8f9fa !important;">
                                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                            <td class="py-3 px-4">{{ $patrol->Name_Patrol }}</td>
                                            <td class="py-3 px-4">{{ $patrol->Time_Patrol }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <style>
        .text-pink {
            color: #e91e63 !important;
        }

        .bg-light-pink {
            background-color: #fdf2f8 !important;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #f1f1f1;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table tbody tr:last-child {
            border-bottom: none !important;
        }
    </style>

    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script>
        // Opsional: Jika kamu ingin tambahkan chart nanti, aktifkan ini
        // const ctx = document.getElementById('perbaikanChart').getContext('2d');
        // const perbaikanChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: ['Total Patrol', 'Done', 'Pending'],
        //         datasets: [{
        //             label: 'Jumlah',
        //             data: [{{ $totalPatrol }}, {{ $totalPerbaikan }}, {{ $pendingTemuan }}],
        //             backgroundColor: ['#e91e63', '#4caf50', '#ff9800'],
        //         }]
        //     },
        //     options: {
        //         responsive: true,
        //         scales: {
        //             y: {
        //                 beginAtZero: true,
        //                 ticks: {
        //                     precision: 0
        //                 }
        //             }
        //         }
        //     }
        // });
    </script>
@endsection
