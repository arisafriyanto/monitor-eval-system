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
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="reportsTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Program</th>
                                        <th>Wilayah</th>
                                        <th>Jumlah Penerima</th>
                                        <th>Tanggal Penyaluran</th>
                                        <th>Bukti Penyaluran</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $index => $report)
                                        <tr>
                                            <td>{{ $index + 1 }}.</td>
                                            <td>{{ $report->program_name }}</td>
                                            <td>
                                                {{ ucwords(strtolower($report->district->name)) }},
                                                {{ ucwords(strtolower($report->city->name)) }},
                                                {{ ucwords(strtolower($report->province->name)) }}
                                            </td>
                                            <td>{{ $report->recipient_count }}</td>
                                            <td>{{ \Carbon\Carbon::parse($report->distribution_date)->format('d F, Y') }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info btn-view-evidence"
                                                    data-bs-toggle="modal" data-bs-target="#evidenceModal"
                                                    data-file="{{ Storage::url($report->evidence_file) }}">
                                                    Lihat File
                                                </button>
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
                                            <td>
                                                <a href="{{ route('admin.reports.show', $report->id) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
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
    </div>

    <div class="modal fade" id="evidenceModal" tabindex="-1" aria-labelledby="evidenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="evidenceModalLabel">Bukti Penyaluran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="evidenceContent">
                        <p>Tidak ada bukti penyaluran yang tersedia.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="downloadEvidence" href="#" class="btn btn-success d-none" download>Unduh File</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#reportsTable').DataTable();

            $('.btn-view-evidence').on('click', function() {
                const fileUrl = $(this).data('file');
                const fileExtension = fileUrl.split('.').pop().toLowerCase();

                let content = '';
                let allowDownload = ['jpg', 'png', 'pdf'].includes(fileExtension);

                if (['jpg', 'png', 'gif'].includes(fileExtension)) {
                    content =
                        `<img src="${fileUrl}" alt="Evidence" class="img-fluid" style="max-width: 700px; height: auto;">`;
                } else if (fileExtension === 'pdf') {
                    content =
                        `<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" />`;
                } else {
                    content =
                        `<p>File tidak dapat ditampilkan. <a href="${fileUrl}" target="_blank">Unduh file di sini</a>.</p>`;
                }

                $('#evidenceContent').html(content);

                if (allowDownload) {
                    $('#downloadEvidence').removeClass('d-none').attr('href', fileUrl);
                } else {
                    $('#downloadEvidence').addClass('d-none').attr('href', '#');
                }
            });
        });
    </script>
@endsection
