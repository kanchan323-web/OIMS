<!DOCTYPE html>
<html>

<head>
    <title>Stock Request Accepted - ONGC</title>
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

        table, th, td {
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
        <h1>{{ $mailData['title'] }}</h1>

        <p style="margin-left:15px">
            Your stock request has been accepted.
            <br>
            <strong>Stock ID:</strong> {{ $mailData['stock_id'] }}
            <br>
            Please find the details below.
        </p>

        <table>
            <tr>
                <th>Requester Name</th>
                <td>{{ $mailData['requester_name'] }}</td>
            </tr>
            <tr>
                <th>Supplier Name</th>
                <td>{{ $mailData['supplier_name'] }}</td>
            </tr>
            <tr>
                <th>Request ID</th>
                <td>{{ $mailData['stock_id'] }}</td>
            </tr>
            <tr>
                <th>Requested Quantity</th>
                <td>{{ $mailData['requested_qty'] }}</td>
            </tr>
            <tr>
                <th>Supplier Provided Quantity</th>
                <td>{{ $mailData['supplier_qty'] }}</td>
            </tr>
            <tr>
                <th>New Spareable</th>
                <td>{{ $mailData['supplier_new_spareable'] }}</td>
            </tr>
            <tr>
                <th>Used Spareable</th>
                <td>{{ $mailData['supplier_used_spareable'] }}</td>
            </tr>
            <tr>
                <th>Request Date</th>
                <td>{{ $mailData['created_at'] }}</td>
            </tr>
        </table>

        <p>If you have any questions or concerns, please contact the support team.</p>

        <div class="footer" style="text-align: left; margin-top: 20px;">
            <p>Thank you,</p>
        </div>

        <div class="footer" style="color: gray; font-size: 12px; margin-top: 10px;">
            <p>This is a system-generated email. Please do not reply.</p>
        </div>

    </div>

</body>

</html>
