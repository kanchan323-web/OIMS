<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Two-Part PDF Form Layout</title>
  <style>
    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 14px;
      margin: 20px;
    }

    table.form-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .form-table th,
    .form-table td {
      border: 1px solid #000;
      padding: 8px 10px;
      text-align: left;
      vertical-align: top;
    }

    .form-table th {
      background-color: #f2f2f2;
      font-weight: bold;
      width: 25%;
    }

    .form-table td {
      width: 25%;
    }
  </style>
</head>
<body>
<h2 style="text-align:center;">Issuer Invoice Details</h2>
<table class="form-table">
  <tr>
    <th>Request ID</th>
    <td>{{$requestStock->RID}}</td>
    <th>DN Number</th>
    <td>{{ $requestStock->dn_no ? $requestStock->dn_no : '-' }}</td>
  </tr>
  <tr>
    <th>Receiver Location</th>
    <td>{{$requestStock->requesters_rig}}</td>
    <th>Supplier Location</th>
    <td>{{$requestStock->suppliers_rig}}</td>
  </tr>
  <tr>
    <th>Request Date</th>
    <td>{{$requestStock->formatted_created_at}}</td>
    <th>Issue Date</th>
    <td>{{ $issue_details->supplier_qty !== null ? $issue_details->issue_date : '-' }}</td>
  </tr>
  <tr>
    <th>EDP</th>
    <td>{{$requestStock->edp_code}}</td>
    <th>Description</th>
    <td>{{$requestStock->description}}</td>
  </tr>
   <tr>
    <th>Category</th>
    <td>{{$requestStock->category}}</td>
    <th>Section</th>
    <td>{{$requestStock->section}}</td>
  </tr>
   <tr>
    <th>Requested quantity(unit)  </th>
    <td>{{$requestStock->requested_qty}}({{$requestStock->measurement}})</td>
    <th>Issued quantity(unit)</th>
    <td>{{ $issue_details->supplier_qty ? $issue_details->supplier_qty . ' (' . $requestStock->measurement . ')' : '-' }}</td>
  </tr>
  <tr>
    <th>Signature of the Issuer</th>
    <td></td>
    <th>Status</th>
    <td>{{$requestStock->status_name}}</td>   
  </tr>
</table>

</body>
</html>
