@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mb-3 mb-sm-0">
                <div class="d-flex justify-content-between align-items-center mx-1 my-2">
                    <div>
                        <h4 class="text-bold">Laporan</h4>
                        <p class="text-muted">Daftar Laporan Penyaluran Bantuan.</p>
                    </div>
                    <div>
                        <a href="{{ route('reports.create') }}" class="btn btn-outline-primary">Tambah Laporan</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="reportsTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Program</th>
                                    <th>Jumlah Penerima</th>
                                    <th>Wilayah</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $index => $report)
                                    <tr>
                                        <td>{{ $index + 1 }}.</td>
                                        <td>{{ $report->program_name }}</td>
                                        <td>{{ $report->recipient_count }}</td>
                                        <td>
                                            {{ ucwords(strtolower($report->district->name)) }},
                                            {{ ucwords(strtolower($report->city->name)) }},
                                            {{ ucwords(strtolower($report->province->name)) }}
                                        </td>
                                        <td>
                                            @if ($report->status === 'pending')
                                                <span class="badge text-bg-warning py-1 text-dark">Pending</span>
                                            @elseif ($report->status === 'rejected')
                                                <span class="badge text-bg-danger py-1">Ditolak</span>
                                            @else
                                                <span class="badge text-bg-success py-1">Disetujui</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('reports.show', $report->id) }}"
                                                class="btn btn-sm btn-info">Lihat</a>

                                            @can('update', $report)
                                                <a href="{{ route('reports.edit', $report->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                            @endcan

                                            @can('delete', $report)
                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#reportsTable').DataTable();
        });
    </script>
@endsection
