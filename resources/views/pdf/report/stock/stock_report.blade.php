<!DOCTYPE html>
<html>

<head>
    <title>Stock List PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        h1, h2 {
            margin: 0;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center; /* Center table content */
        }

        th {
            background-color: #f2f2f2;
        }

        .no-data {
            font-weight: bold;
            color: red;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>

<body>

    <h1>ONGC</h1>
    <h2>Stock Overview Report</h2>

    <table>
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>EDP Code</th>
                <th>Section</th>
                <th>Description</th>
                <th>Total Qty</th>
                <th>Available Qty</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @if($stockData->isEmpty())
                <tr>
                    <td colspan="6" class="no-data">No data found</td>
                </tr>
            @else
                @foreach($stockData as $index => $stock)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $stock->EDP_Code }}</td>
                        <td>{{ $stock->section }}</td>
                        <td>{{ $stock->description }}</td>
                        <td>{{ $stock->initial_qty }}</td>
                        <td>{{ $stock->qty }}</td>
                        <td>{{ $stock->created_at }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</body>

</html>
