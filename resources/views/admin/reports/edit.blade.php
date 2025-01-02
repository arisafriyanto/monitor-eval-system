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
                            @method('PUT') <!-- Karena ini untuk update, gunakan method PUT -->

                            <div class="mb-4">
                                <label for="program_name" class="form-label">Nama Program</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="program_name" name="program_name">
                                    <option value=""
                                        {{ old('program_name', $report->program_name) == '' ? 'selected' : '' }}>-- Pilih
                                        Program --</option>
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
                                <label for="province" class="form-label">Provinsi</label><span class="text-danger">*</span>
                                <select class="form-select" id="province" name="province">
                                    <option value="" {{ old('province', $report->province) == '' ? 'selected' : '' }}>
                                        -- Pilih Provinsi --</option>
                                    <option value="Jawa Barat"
                                        {{ old('province', $report->province) == 'Jawa Barat' ? 'selected' : '' }}>Jawa
                                        Barat</option>
                                    <option value="Jawa Tengah"
                                        {{ old('province', $report->province) == 'Jawa Tengah' ? 'selected' : '' }}>Jawa
                                        Tengah</option>
                                    <option value="Jawa Timur"
                                        {{ old('province', $report->province) == 'Jawa Timur' ? 'selected' : '' }}>Jawa
                                        Timur</option>
                                </select>
                                <div id="error_province" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="district" class="form-label">Kota/Kabupaten</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="district" name="district">
                                    <option value=""
                                        {{ old('district', $report->district) == '' ? 'selected' : '' }}>-- Pilih
                                        Kota/Kabupaten --</option>
                                    <option value="Bandung"
                                        {{ old('district', $report->district) == 'Bandung' ? 'selected' : '' }}>Bandung
                                    </option>
                                    <option value="Brebes"
                                        {{ old('district', $report->district) == 'Brebes' ? 'selected' : '' }}>Brebes
                                    </option>
                                    <option value="Malang"
                                        {{ old('district', $report->district) == 'Malang' ? 'selected' : '' }}>Malang
                                    </option>
                                </select>
                                <div id="error_district" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="sub_district" class="form-label">Kecamatan</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="sub_district" name="sub_district">
                                    <option value=""
                                        {{ old('sub_district', $report->sub_district) == '' ? 'selected' : '' }}>-- Pilih
                                        Kecamatan --</option>
                                    <option value="Ciwidey"
                                        {{ old('sub_district', $report->sub_district) == 'Ciwidey' ? 'selected' : '' }}>
                                        Ciwidey</option>
                                    <option value="Bulakamba"
                                        {{ old('sub_district', $report->sub_district) == 'Bulakamba' ? 'selected' : '' }}>
                                        Bulakamba</option>
                                    <option value="Klojen"
                                        {{ old('sub_district', $report->sub_district) == 'Klojen' ? 'selected' : '' }}>
                                        Klojen</option>
                                </select>
                                <div id="error_sub_district" class="invalid-feedback"></div>
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
                                    <div>
                                        <a href="{{ asset('storage/' . $report->evidence_file) }}" target="_blank">Lihat
                                            File</a>
                                    </div>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#reportForm').on('submit', function(e) {
                var isValid = true;
                var fields = [
                    'program_name',
                    'recipient_count',
                    'province',
                    'district',
                    'sub_district',
                    'distribution_date'
                ];

                // Clear previous validation errors
                fields.forEach(function(field) {
                    $('#' + field).removeClass('is-invalid');
                    $('#error_' + field).text('');
                });

                // Loop through fields to check if they are empty or invalid
                fields.forEach(function(field) {
                    var element = $('#' + field);
                    var label = $("label[for='" + field + "']").text(); // Get the label text

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

                // Validate file upload for evidence
                var fileInput = $('#evidence_file')[0];
                var file = fileInput.files[0];
                var hasExistingFile = "{{ $report->evidence_file }}" !== "";

                $('#evidence_file').removeClass('is-invalid');
                $('#error_evidence_file').text('');

                if (!file && !hasExistingFile) {
                    isValid = false;
                    $('#evidence_file').addClass('is-invalid');
                    $('#error_evidence_file').text('Bukti Penyaluran wajib diunggah.');
                } else if (file) {
                    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
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

                // If the form is invalid, prevent submission
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
