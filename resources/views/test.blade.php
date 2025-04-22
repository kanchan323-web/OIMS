@extends('layouts.frontend.admin_layout')
@section('page-content')
<div class="container">
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>
<script type="text/javascript">

    $(function () {
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('admin.edp.test') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'edp_code', name: 'edp_code'},
              {data: 'category', name: 'category'},
          ]
      });
    });
  </script>
    
@endsection