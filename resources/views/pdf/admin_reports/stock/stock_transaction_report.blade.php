<!DOCTYPE html>
<html>

<head>
    <title>Stock Transfer PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        h1,
        h2 {
            margin: 0;
            padding: 5px;
        }

   /*     table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            /* Center table content 
        }

        th {
            background-color: #f2f2f2;
        }
        */
        table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid black;
        padding: 8px;
        font-size: 12px;
        word-wrap: break-word;
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
    <h2>Stock Transfer Report</h2>
    
    <table>
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>EDP</th>
                <th>Description</th>
                <th>Change in New</th>
                <th>Change in Used</th>
                <th>Qty</th>
                <th>Transaction</th>
                <th>Reference ID</th>
                <th>Transaction Date</th>
                <th>Receiver</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @if($stockData->isEmpty())
                <tr>
                    <td colspan="8" class="no-data">No data found</td>
                </tr>
            @else
                @foreach($stockData as $index => $stock)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $stock->EDP_Code}}</td>
                        <td>{{ $stock->description }}</td>
                        <td>{{ IND_money_format($stock->formatted_new_value ?? '-') }}</td>
                        <td>{{ IND_money_format($stock->formatted_used_value ?? '-') }}</td>
                        <td>{{ IND_money_format($stock->qty ?? '-') }}</td>
                        <td>{{ $stock->action ?? '-' }}</td>
                        <td>{{ $stock->reference_id ?? '-' }}</td>
                        <td>{{ $stock->updated_at_formatted ?? '-' }}</td>
                        <td>{{ $stock->receiver ?? '-' }}</td>
                        <td>{{ $stock->supplier ?? '-' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</body>

</html>
