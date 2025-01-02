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

                            Tambah Laporan
                        </h5>
                        <p class="text-muted mb-4">Isi formulir untuk menambahkan Laporan Penyaluran Bantuan.</p>

                        <form id="reportForm" action="{{ route('reports.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="program_name" class="form-label">Nama Program</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="program_name" name="program_name">
                                    <option value="" selected>-- Pilih Program --</option>
                                    <option value="PKH">PKH</option>
                                    <option value="BLT">BLT</option>
                                    <option value="Bansos">Bansos</option>
                                </select>
                                <div id="error_program_name" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="recipient_count" class="form-label">Jumlah Penerima</label><span
                                    class="text-danger">*</span>
                                <input type="number" class="form-control" id="recipient_count" name="recipient_count"
                                    value="{{ old('recipient_count') }}">
                                <div id="error_recipient_count" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="province" class="form-label">Provinsi</label><span class="text-danger">*</span>
                                <select class="form-select" id="province" name="province">
                                    <option value="" selected>-- Pilih Provinsi --</option>
                                    <option value="Jawa Barat">Jawa Barat</option>
                                    <option value="Jawa Tengah">Jawa Tengah</option>
                                    <option value="Jawa Timur">Jawa Timur</option>
                                </select>
                                <div id="error_province" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="district" class="form-label">Kota/Kabupaten</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="district" name="district">
                                    <option value="" selected>-- Pilih Kota/Kabupaten --</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Brebes">Brebes</option>
                                    <option value="Malang">Malang</option>
                                </select>
                                <div id="error_district" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="sub_district" class="form-label">Kecamatan</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="sub_district" name="sub_district">
                                    <option value="" selected>-- Pilih Kecamatan --</option>
                                    <option value="Ciwidey">Ciwidey</option>
                                    <option value="Bulakamba">Bulakamba</option>
                                    <option value="Klojen">Klojen</option>
                                </select>
                                <div id="error_sub_district" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="distribution_date" class="form-label">Tanggal Penyaluran</label><span
                                    class="text-danger">*</span>
                                <input type="date" class="form-control" id="distribution_date" name="distribution_date"
                                    value="{{ old('distribution_date') }}">
                                <div id="error_distribution_date" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="evidence_file" class="form-label">Bukti Penyaluran</label><span
                                    class="text-danger">*</span>
                                <input type="file" class="form-control" id="evidence_file" name="evidence_file"
                                    value="{{ old('evidence_file') }}" onchange="previewEvidence_file(event)">
                                <div id="error_evidence_file" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea name="notes" id="notes" cols="30" rows="10" class="form-control">{{ old('notes') }}</textarea>
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
                    'distribution_date',
                    'evidence_file'
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

                    if (field === 'recipient_count' && element.val() <= 0) {
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
                var label = $("label[for='evidence_file']").text(); // Get the label text for file upload
                if (!file) {
                    isValid = false;
                    $('#evidence_file').addClass('is-invalid');
                    $('#error_evidence_file').text(label + ' wajib upload.');
                } else {
                    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
                    if (!allowedExtensions.exec(file.name)) {
                        isValid = false;
                        $('#evidence_file').addClass('is-invalid');
                        $('#error_evidence_file').text(
                            'Hanya file JPG, PNG, atau PDF yang diperbolehkan untuk ' + label + '.');
                    } else if (file.size > 2 * 1024 * 1024) {
                        isValid = false;
                        $('#evidence_file').addClass('is-invalid');
                        $('#error_evidence_file').text('File ' + label + ' tidak boleh lebih dari 2MB.');
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
