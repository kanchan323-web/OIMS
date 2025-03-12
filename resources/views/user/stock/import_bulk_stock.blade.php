@extends('layouts.frontend.layout')
@section('page-content')


<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Import Stock Data</h4>
                    </div>
                    <div class="card-body">
                        @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form class="needs-validation" novalidate method="POST" action="{{ route('stock.import') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-md-6 mb-3">
                                    <label for="file">Upload Excel File</label>
                                    <div class="custom-file-upload">
                                        <input type="file" name="file" id="file"
                                            class="file-input @error('file') is-invalid @enderror" required>
                                        <label for="file" class="file-label">📁 Choose File</label>
                                        <span class="file-name ext-primary small" style="color:#a943d6">No file chosen</span>
                                    </div>
                                  
                                    @error('file')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                        <a href="{{ route('stock.downloadSample') }}" class="ml-1 text-primary">
                                            Download Sample File
                                        </a>
                                </div>
                            
                            </div>
                            <button class="btn btn-primary" type="submit">Import</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light">Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        $(document).ready(function () {
            $('#file').on('change', function () {
                let fileName = $(this).val().split('\\').pop(); 
                if (fileName) {
                    $('.file-name').text(fileName);
                } else {
                    $('.file-name').text("No file chosen");
                }
            });
        });
    </script>
@endsection