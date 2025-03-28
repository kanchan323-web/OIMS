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
    <h2>Stock List Report</h2>
    
    <table>
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Location Name</th>
                <th>EDP</th>
                <th>Section</th>
                <th>Description</th>
                <th>Quantity</th>
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
                        <td>{{ $stock->location_name }}</td>
                        <td>{{ $stock->edp_code }}</td>
                        <td>{{ $stock->section }}</td>
                        <td>{{ $stock->description }}</td>
                        <td>{{ $stock->qty }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</body>

</html>
