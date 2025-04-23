@extends('layouts.frontend.admin_layout')
@section('page-content')


    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">
                    {{Breadcrumbs::render('import_bulk_edp')}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Import Bulk EDP </h4>
                        </div>
                        <div class="card-body">
                            @if (Session::get('success'))
                                <div class="alert bg-success text-white alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ Session::get('success') }}
                                    <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (Session::has('error'))
                                <div class="alert bg-danger text-white alert-dismissible fade show" role="alert">
                                    <strong>Error:</strong>
                                    @if (is_array(Session::get('error')))
                                        <ul>
                                            @foreach (Session::get('error') as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ Session::get('error') }}
                                    @endif
                                    <button type="button" class="close close-dark" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form class="needs-validation" novalidate method="POST" id="AddBulkEDP"
                                action="{{ route('admin.edp.import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row align-items-center">
                                    <div class="col-md-6 mb-3">
                                        <label for="file">Upload Excel File</label>
                                        <div class="custom-file-upload">
                                            <input type="file" name="file" id="file"
                                                class="file-input @error('file') is-invalid @enderror" required>
                                            <label for="file" class="file-label">📁 Choose File</label>
                                            <span class="file-name ext-primary small" style="color:#a943d6">No file
                                                chosen</span>
                                        </div>

                                        @error('file')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <a href="{{ route('admin.edp.downloadSample') }}" data-toggle="modal"
                                            data-target="#customModal" class="ml-1 text-primary">
                                            Download Sample File
                                        </a>

                                    </div>

                                </div>
                                <button class="btn btn-primary" id="AddBulkEDP" type="submit">Import</button>
                                <a href="{{ url()->previous() }}" class="btn btn-light">Go Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Allowed Units of Measurement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please note that only the following units of measurement are permitted:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><strong>EA</strong> - Each</li>
                                <li><strong>FT</strong> - Foot</li>
                                <li><strong>GAL</strong> - Gallon</li>
                                <li><strong>KG</strong> - Kilogram</li>
                                <li><strong>KIT</strong> - Kit</li>
                                <li><strong>KL</strong> - Kiloliter</li>
                                <li><strong>L</strong> - Liter</li>
                                <li><strong>LB</strong> - Pound</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><strong>M</strong> - Meter</li>
                                <li><strong>M3</strong> - Cubic Meter</li>
                                <li><strong>MT</strong> - Metric Ton</li>
                                <li><strong>NO</strong> - Number</li>
                                <li><strong>PAA</strong> - Pack/Packet</li>
                                <li><strong>PAC</strong> - Pack</li>
                                <li><strong>ROL</strong> - Roll</li>
                                <li><strong>ST</strong> - Set</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.edp.downloadSample') }}" data-dismiss="modal" 
                       class="btn btn-dark download-link">
                        Confirm & Download Sample
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    

    <script>
        $(document).ready(function () {
            $(".download-link").click(function () {
                $("#customModal").modal("hide");
            });

            $("#AddBulkEDP").on("submit", function () {
                // Show loader
                $("#loading").show();

                // Disable submit button to prevent multiple clicks
                $("#AddRequestStock button[type=submit]").prop("disabled", true);
            });

            $('#file').on('change', function () {
                let fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    $('.file-name').text(fileName);
                } else {
                    $('.file-name').text("No file chosen");
                }
            });
        });

        $(document).ready(function () {
            // Automatically fade out alerts after 3 seconds
            setTimeout(function () {
                $(".alert").fadeOut("slow");
            }, 7000);
        });


    </script>
@endsection