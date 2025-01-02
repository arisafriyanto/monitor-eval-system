@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('reports.index') }}" class="btn btn-light">
                                <i class="fa fa-arrow-left-long"></i>
                            </a>

                            Edit Laporan
                        </h5>
                        <p class="text-muted mb-4">Isi formulir untuk mengedit Laporan Penyaluran Bantuan.</p>

                        <form id="reportForm" action="{{ route('reports.update', $report->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="program_name" class="form-label">Nama Program</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="program_name" name="program_name">
                                    <option value="">-- Pilih Program --</option>
                                    <option value="PKH"
                                        {{ old('program_name', $report->program_name) == 'PKH' ? 'selected' : '' }}>PKH
                                    </option>
                                    <option value="BLT"
                                        {{ old('program_name', $report->program_name) == 'BLT' ? 'selected' : '' }}>BLT
                                    </option>
                                    <option value="Bansos"
                                        {{ old('program_name', $report->program_name) == 'Bansos' ? 'selected' : '' }}>
                                        Bansos</option>
                                </select>
                                <div id="error_program_name" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="recipient_count" class="form-label">Jumlah Penerima</label><span
                                    class="text-danger">*</span>
                                <input type="number" class="form-control" id="recipient_count" name="recipient_count"
                                    value="{{ old('recipient_count', $report->recipient_count) }}">
                                <div id="error_recipient_count" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="province_code" class="form-label">Provinsi</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="province_code" name="province_code">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinces as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('province_code', $report->province_code) == $code ? 'selected' : '' }}>
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                                <div id="error_province_code" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="city_code" class="form-label">Kabupaten/Kota</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="city_code" name="city_code">
                                    <option value="">-- Pilih Kabupaten/Kotas --</option>
                                    @foreach ($cities as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('city_code', $report->city_code) == $code ? 'selected' : '' }}>
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                                <div id="error_city_code" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="district_code" class="form-label">Kecamatan</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="district_code" name="district_code">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($districts as $code => $name)
                                        <option value="{{ $code }}"
                                            {{ old('district_code', $report->district_code) == $code ? 'selected' : '' }}>
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                                <div id="error_district_code" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="distribution_date" class="form-label">Tanggal Penyaluran</label><span
                                    class="text-danger">*</span>
                                <input type="date" class="form-control" id="distribution_date" name="distribution_date"
                                    value="{{ old('distribution_date', $report->distribution_date) }}">
                                <div id="error_distribution_date" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="evidence_file" class="form-label">Bukti Penyaluran</label><span
                                    class="text-danger">*</span>
                                <input type="file" class="form-control" id="evidence_file" name="evidence_file">

                                @if ($report->evidence_file)
                                    <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                        data-bs-target="#previewEvidenceModal">
                                        Lihat File
                                    </button>
                                @else
                                    <p>Tidak ada bukti penyaluran yang tersedia.</p>
                                @endif

                                <div id="error_evidence_file" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea name="notes" id="notes" cols="30" rows="10" class="form-control">{{ old('notes', $report->notes) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewEvidenceModal" tabindex="-1" aria-labelledby="previewEvidenceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewEvidenceModalLabel">Bukti Penyaluran</h5>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#reportForm').on('submit', function(e) {
                let isValid = true;
                let fields = [
                    'program_name',
                    'recipient_count',
                    'province_code',
                    'city_code',
                    'district_code',
                    'distribution_date'
                ];

                fields.forEach(function(field) {
                    $('#' + field).removeClass('is-invalid');
                    $('#error_' + field).text('');
                });

                fields.forEach(function(field) {
                    let element = $('#' + field);
                    let label = $("label[for='" + field + "']").text();

                    if (field === 'recipient_count' && (element.val() <= 0 || element.val() ===
                            '')) {
                        isValid = false;
                        element.addClass('is-invalid');
                        $('#error_' + field).text(label + ' harus lebih dari 0.');
                    } else if (element.val() === '') {
                        isValid = false;
                        element.addClass('is-invalid');
                        $('#error_' + field).text(label + ' tidak boleh kosong.');
                    }
                });

                let fileInput = $('#evidence_file')[0];
                let file = fileInput.files[0];
                let hasExistingFile = "{{ $report->evidence_file }}" !== "";

                $('#evidence_file').removeClass('is-invalid');
                $('#error_evidence_file').text('');

                if (!file && !hasExistingFile) {
                    isValid = false;
                    $('#evidence_file').addClass('is-invalid');
                    $('#error_evidence_file').text('Bukti Penyaluran wajib diunggah.');
                } else if (file) {
                    let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
                    if (!allowedExtensions.exec(file.name)) {
                        isValid = false;
                        $('#evidence_file').addClass('is-invalid');
                        $('#error_evidence_file').text('Hanya file JPG, PNG, atau PDF yang diperbolehkan.');
                    } else if (file.size > 2 * 1024 * 1024) {
                        isValid = false;
                        $('#evidence_file').addClass('is-invalid');
                        $('#error_evidence_file').text('File tidak boleh lebih dari 2MB.');
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            $('#province_code').on('change', function() {
                let provinceCode = $(this).val();
                if (provinceCode) {
                    $.ajax({
                        url: "{{ route('indonesia.cities', '') }}/" + provinceCode,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#city_code').empty().append(
                                '<option value="">-- Pilih Kabupaten/Kota --</option>'
                            );
                            $('#district_code').empty().append(
                                '<option value="">-- Pilih Kecamatan --</option>'
                            );
                            $.each(data, function(code, name) {
                                $('#city_code').append(new Option(name, code));
                            });
                        }
                    });
                }
            });

            $('#city_code').on('change', function() {
                let cityCode = $(this).val();
                if (cityCode) {
                    $.ajax({
                        url: "{{ route('indonesia.districts', '') }}/" + cityCode,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#district_code').empty().append(
                                '<option value="">-- Pilih Kecamatan --</option>'
                            );
                            $.each(data, function(code, name) {
                                $('#district_code').append(new Option(name,
                                    code));
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
