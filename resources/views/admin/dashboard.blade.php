@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-3 my-2 mx-1">
            <div class="d-flex">
                <h4 class="text-bold">Dashboard</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0 illustration">
                    <div class="card-body p-0 d-flex flex-fill">
                        <div class="p-3 m-1">
                            <h4>Total Laporan yang Masuk</h4>
                            <h2 id="totalTransactions" class="mb-0">{{ $totalReports }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-0">
                    <div class="card-body py-4">
                        <h4 class="mb-2">
                            Jumlah Penerima Bantuan per Program
                        </h4>
                        <ul class="list-group">
                            @foreach ($recipientCountPerProgram as $report)
                                <li class="list-group-item">
                                    <strong>{{ $report->program_name }}</strong>: {{ $report->total_recipients }} penerima
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header" style="background-color: #fff">
                        <h5>Grafik Penyaluran Bantuan per Wilayah</h5>
                    </div>
                    <div class="p-2">
                        <canvas id="distributionChart"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            const distributionData = @json($distributionPerRegion);

            const labels = distributionData.map(data => `${data.province.name} - ${data.city.name}`);
            const data = distributionData.map(data => data.total_reports);

            const ctx = $('#distributionChart')[0].getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Laporan per Wilayah',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1,
                        borderRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
