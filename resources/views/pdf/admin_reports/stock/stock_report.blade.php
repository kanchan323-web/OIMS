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
    @php $report_type = $data['report_type']; @endphp

    <h1>ONGC</h1>
    @if ($data['report_type'] == 1)
    @endif


    @switch($report_type)
        @case('1')
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
                    @if ($stockData->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($stockData as $index => $stock)
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
        @break

        @case('2')
            <h2>Stock Additions Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <!--  <th>Total </th> -->
                        <th>Add</th>
                        <th>Supplier Rig</th>
                        <th> Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($stockData->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($stockData as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <!--      <td>{{ $stock->qty }}</td> -->
                                <td>{{ $stock->requested_qty }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->created_at }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @case('3')
            <h2>Stock Removals Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <!-- <th>Total </th> -->
                        <th>Remove</th>
                        <th>Rig Name</th>
                        <th> Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($stockData->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($stockData as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <!--  <td>{{ $stock->qty }}</td> -->
                                <td>{{ $stock->requested_qty }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->created_at }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @case('4')
            <h2>Stock Adjustments Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <th>Adjustments</th>
                        <th>Rig Name</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($stockData->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($stockData as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <td>{{ $stock->qty }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->created_at }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @case('5')
            <h2>Stock Consumptions Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <th>Total </th>
                        <th>Consumed</th>
                        <th>Consumed Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($stockData->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($stockData as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <td>{{ $stock->avl_qty }}</td>
                                <td>{{ $stock->consume }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->created_at }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @break

        @case('6')
            <h2>Stock Replenishment Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>EDP Code</th>
                        <th>Description</th>
                        <!--  <th>Available </th> -->
                        <th>Replenishment</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($stockData->isEmpty())
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                        @foreach ($stockData as $index => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->EDP_Code }}</td>
                                <td>{{ $stock->description }}</td>
                                <!--    <td>{{ $stock->avl_qty }}</td> -->
                                <td>{{ $stock->replinish }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->created_at }}</td>
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
