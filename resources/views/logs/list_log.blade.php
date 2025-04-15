@extends('layouts.frontend.admin_layout')
@section('page-content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-between">
                        <div class="col-sm-6 col-md-9">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form id="filterForm" class="mr-3 position-relative">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <label for="edp_code">Logs Type</label>
                                            <select class="form-control" name="report_type" id="report_type">
                                                <option disabled selected>Select Logs Type...</option>
                                                
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="form_date">From Date</label>
                                            <input type="date" class="form-control" name="form_date" id="form_date">
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="to_date">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date">
                                        </div>

                                        <div class="col-md-2 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary mr-2"
                                                id="filterButton">Search</button>
                                            <a href="{{ route('get.logs') }}"
                                                class="btn btn-secondary ml-2">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                  
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data" id="tableHeaders">
                                    <!-- Headers will be set dynamically -->
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="reportTable">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
