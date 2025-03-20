<!DOCTYPE html>
<html>

<head>
    <title>Stock Request from ONGC</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    h1 {
        background-color: #004085;
        color: #fff;
        padding: 10px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
    }

    p {
        margin-top: 10px;
    }

    .footer {
        margin-top: 20px;
        font-size: 12px;
        color: #666;
        text-align: center;
    }
    </style>
</head>

<body>

    <div class="container">
        <h1>{{ $mailDataRequester['title'] }}</h1>

        <p style="margin-left:15px">
            You, have successfully submitted a stock  request.
            <br>
            <strong>Stock EDP Code:</strong> {{ $mailDataRequester['stock_id'] }}
            Please find the details below.
        </p>

        <table>
            <tr>
                <th>Requester Name</th>
                <td>{{ $mailDataRequester['requester_name'] }}</td>
            </tr>
            <tr>
                <th>Supplier Name</th>
                <td>{{ $mailDataRequester['supplier_name'] }}</td>
            </tr>
            <tr>
                <th>Stock EDP Code</th>
                <td>{{ $mailDataRequester['stock_id'] }}</td>
            </tr>
            <tr>
                <th>Available Quantity</th>
                <td>{{ $mailDataRequester['available_qty'] }}</td>
            </tr>
            <tr>
                <th>Requested Quantity</th>
                <td>{{ $mailDataRequester['requested_qty'] }}</td>
            </tr>
            <tr>
                <th>Requester Rig Name</th>
                <td>{{ $mailDataRequester['requester_rig_id'] }}</td>
            </tr>
            <tr>
                <th>Supplier Rig Name</th>
                <td>{{ $mailDataRequester['supplier_rig_id'] }}</td>
            </tr>
            <tr>
                <th>Request Date</th>
                <td>{{ $mailDataRequester['created_at'] }}</td>
            </tr>
        </table>

        <p>If this request was not raised by you, please contact the support team immediately.</p>

        <div class="footer" style="text-align: left; margin-top: 20px;">
            <p>Thank you,</p>

        </div>

        <div class="footer" style="color: gray; font-size: 12px; margin-top: 10px;">
            <p>This is a system-generated email. Please do not reply.</p>
        </div>


</body>

</html>
