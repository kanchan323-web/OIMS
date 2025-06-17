<!DOCTYPE html>
<html>

<head>
    <title>Stock Report PDF</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            /* Center table content */
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
    @php $report_type = $request->report_type; @endphp

    <h1>ONGC</h1>
    @switch($report_type)
        @case('overview')
            <h2>Stock Overview Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>EDP Code</th>
                        <th>Section</th>
                        <th>Description</th>
                        <th>Total Quantity</th>
                        <th>Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($data as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->section }}</td>
                                <td>{{ $stock->description }}</td>
                                <td>{{ $stock->qty }}</td>
                                <td>{{ $stock->date }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @case('stock_receiver')
            <h2>Stock Received Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Request ID</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <th>Received QTY</th>
                        <th>Supplier Location</th>
                        <th>Receipt Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($data as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->RID }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <td>{{ $stock->requested_qty }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->receipt_date }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @case('stock_issuer')
            <h2>Stock Issued Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Request ID</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <th>Issued QTY</th>
                        <th>Receiver Location </th>
                        <th>Issued Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($data as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->RID }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <td>{{ $stock->requested_qty }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->issued_date }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @default
            <table>
                <thead>
                    <tr>
                        <td>No Data Found</td>
                    </tr>
                </thead>
            </table>
            </p>
    @endswitch

</body>

</html>
