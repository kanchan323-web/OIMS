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

<h2 style="text-align:center;">Request Details</h2>

<table class="form-table">
  <tr>
    <th>Request ID</th>
    <td>INV20250610</td>
    <th>Request Date</th>
    <td>10 June 2025</td>
  </tr>
  <tr>
    <th>Customer Name</th>
    <td>John Doe</td>
    <th>Email</th>
    <td>john@example.com</td>
  </tr>
  <tr>
    <th>Location</th>
    <td>New Delhi</td>
    <th>Department</th>
    <td>IT</td>
  </tr>
  <tr>
    <th>Item Name</th>
    <td>Wireless Mouse</td>
    <th>Quantity</th>
    <td>5</td>
  </tr>
</table>

</body>
</html>
