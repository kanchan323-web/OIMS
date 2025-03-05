@extends('layouts.frontend.layout')
@section('page-content')

    <div class="content-page">
     <div class="container-fluid">
        <div class="row">


            <div class="col-lg-12">
                <div class="row justify-content-between">

                    <div class="col-sm-6 col-md-9">
                       <div id="user_list_datatable_info" class="dataTables_filter">
                          <form class="mr-3 position-relative">
                            <div class="form-row">
                             <div class="col-md-2 mb-2">
                                <label for="">Category</label>
                                            <select class="form-control" id="" name="category" required>
                                               <option selected disabled value="">Select Category...</option>
                                               <option value="Spares">Spares</option>
                                               <option value="Stores">Stores</option>
                                               <option value="Capital items">Capital items</option>
                                            </select>

                             </div>
                             <div class="col-md-2 mb-2">
                                <label for="">Location Name</label>
                                <input type="text" class="form-control" placeholder=" Location Name" name="location_name" id="" required>
                             </div>
                             <div class="col-md-2 mb-2">
                                <label for="">From Date</label>
                                <input type="date" class="form-control" placeholder=" Location Name" name="location_name" id="" required>
                             </div>
                             <div class="col-md-2 mb-2">
                                <label for="">To Date</label>
                                <input type="date" class="form-control" placeholder=" Location Name" name="location_name" id="" required>
                             </div>
                             <div class="col-md-2 mb-2">
                             <button class="btn btn-primary" type="submit">Search</button>

                             <button type="reset" class="btn btn-danger">Reset</button>
                             </div>
                            </div>

                          </form>
                       </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                       <div class="user-list-files d-flex">
                         <a href="{{ route('add_stock') }}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Add Stock</a>
                         <a href="{{ route('add_stock') }}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Bulk Stocks </a>
                       </div>
                    </div>
                 </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                <table class="data-tables table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox1">
                                    <label for="checkbox1" class="mb-0"></label>
                                </div>
                            </th>
                            <th>Sr.No</th>
                            <th>Location Name</th>
                            <th>EDP</th>
                            <th>Section</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        <tr>
                            <td>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox2">
                                    <label for="checkbox2" class="mb-0"></label>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        Organic Cream
                                    </div>
                                </div>
                            </td>
                            <td>CREM01</td>
                            <td>Beauty</td>
                            <td>$25.00</td>
                            <td>Lakme</td>
                            <td>10.0</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"
                                        href="#"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                        href="#"><i class="ri-pencil-line mr-0"></i></a>
                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"
                                        href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox d-inline-block">
                                    <input type="checkbox" class="checkbox-input" id="checkbox3">
                                    <label for="checkbox3" class="mb-0"></label>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        Rain Umbrella
                                    </div>
                                </div>
                            </td>
                            <td>UM01</td>
                            <td>Grocery</td>
                            <td>$30.00</td>
                            <td>Sun</td>
                            <td>15.0</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"
                                        href="#"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                        href="#"><i class="ri-pencil-line mr-0"></i></a>
                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"
                                        href="#"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>


        </div>
    </div>
</div>
