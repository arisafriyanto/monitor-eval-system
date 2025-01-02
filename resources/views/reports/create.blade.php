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
                                <label for="province_code" class="form-label">Provinsi</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="province_code" name="province_code">
                                    <option value="" selected>-- Pilih Provinsi --</option>
                                    @foreach ($provinces as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <div id="error_province_code" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="city_code" class="form-label">Kabupaten/Kota</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="city_code" name="city_code">
                                    <option value="" selected>-- Pilih Kabupaten/Kota --</option>
                                </select>
                                <div id="error_city_code" class="invalid-feedback"></div>
                            </div>

                            <div class="mb-4">
                                <label for="district_code" class="form-label">Kecamatan</label><span
                                    class="text-danger">*</span>
                                <select class="form-select" id="district_code" name="district_code">
                                    <option value="" selected>-- Pilih Kecamatan --</option>
                                </select>
                                <div id="error_district_code" class="invalid-feedback"></div>
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

                let isValid = true;
                let fields = [
                    'program_name',
                    'recipient_count',
                    'province_code',
                    'city_code',
                    'district_code',
                    'distribution_date',
                    'evidence_file'
                ];

                fields.forEach(function(field) {
                    $('#' + field).removeClass('is-invalid');
                    $('#error_' + field).text('');
                });

                fields.forEach(function(field) {
                    let element = $('#' + field);
                    let label = $("label[for='" + field + "']").text();

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

                let fileInput = $('#evidence_file')[0];
                let file = fileInput.files[0];
                let label = $("label[for='evidence_file']").text();

                if (!file) {
                    isValid = false;
                    $('#evidence_file').addClass('is-invalid');
                    $('#error_evidence_file').text(label + ' wajib upload.');
                } else {
                    let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
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
