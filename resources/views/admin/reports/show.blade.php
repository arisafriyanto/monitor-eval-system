@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-light">
                                <i class="fa fa-arrow-left-long"></i>
                            </a>

                            Laporan Penyaluran Bantuan
                        </h5>
                        <p class="text-muted mb-4">Berikut adalah rincian laporan penyaluran bantuan yang telah dibuat.</p>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Nama Program</th>
                                    <td>{{ $report->program_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Jumlah Penerima</th>
                                    <td>{{ $report->recipient_count }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Provinsi</th>
                                    <td>{{ $report->province->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Kabupaten/Kota</th>
                                    <td>{{ $report->city->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Kecamatan</th>
                                    <td>{{ $report->district->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Tanggal Penyaluran</th>
                                    <td>{{ \Carbon\Carbon::parse($report->distribution_date)->format('d F, Y') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Bukti Penyaluran</th>
                                    <td>
                                        @if ($report->evidence_file)
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#evidenceModal">
                                                Lihat Bukti Penyaluran
                                            </button>
                                        @else
                                            <p>Tidak ada bukti penyaluran yang tersedia.</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Catatan Tambahan</th>
                                    <td>{{ $report->notes ? $report->notes : 'Tidak ada catatan tambahan.' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        @if ($report->status === 'pending')
                                            <span class="badge text-bg-warning py-1 text-dark">Pending</span>
                                        @elseif ($report->status === 'rejected')
                                            <span class="badge text-bg-danger py-1">Ditolak</span>
                                        @else
                                            <span class="badge text-bg-success py-1">Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($report->rejection_reason)
                                    <tr>
                                        <th scope="row">Alasan Penolakan</th>
                                        <td>{{ $report->rejection_reason }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @can('approved', $report)
                            <div class="d-flex justify-content-center pb-3 pt-4">
                                <form action="{{ route('admin.reports.approve', $report) }}" method="POST"
                                    class="d-inline me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Setujui Laporan</button>
                                </form>
                            @endcan

                            @can('rejected', $report)
                                <button type="button" class="btn btn-danger ms-1" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                    Tolak Laporan
                                </button>
                            </div>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="evidenceModal" tabindex="-1" aria-labelledby="evidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="evidenceModalLabel">Bukti Penyaluran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if ($report->evidence_file)
                        @php
                            $fileExtension = pathinfo($report->evidence_file, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array(strtolower($fileExtension), ['jpg', 'png']))
                            <img src="{{ asset('storage/' . $report->evidence_file) }}" alt="Bukti Penyaluran"
                                class="img-fluid" style="max-width: 700px; height: auto;">
                        @elseif(strtolower($fileExtension) == 'pdf')
                            <embed src="{{ asset('storage/' . $report->evidence_file) }}" type="application/pdf"
                                width="100%" height="600px">
                        @else
                            <p>Format file tidak didukung.</p>
                        @endif
                    @else
                        <p>Tidak ada bukti penyaluran yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.reports.reject', $report) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan</label>
                            <textarea name="reason" id="reason" cols="10" rows="6" class="form-control"
                                placeholder="Masukan alasan anda..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
