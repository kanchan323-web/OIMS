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
@php $report_type = $request->report_type; @endphp

    <h1>ONGC</h1>
    @switch($report_type)
    @case('summary')
        <h2>Request Stock Summary Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sr.No</th><th>Total Requests</th><th>Approved</th><th>Declined</th><th>Pending</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($data))
                        <tr>
                            <td colspan="6" class="no-data">No data found</td>
                        </tr>
                    @else
                            <tr>
                                <td>1</td>
                                <td class="text-purple"><strong>{{$data['total_requests']}}</strong></td>
                                <td class="text-success"><strong>{{$data['approved']}}</strong></td>
                                <td class="text-danger"><strong>{{$data['declined']}}</strong></td>
                                <td class="text-warning"><strong>{{$data['pending'] }}</strong></td>
                            </tr>
                    @endif
                </tbody>
            </table>
        @break
    @case('approval_rates')
        <h2>Request Stock Approval Rates Report</h2>
        <table>
            <thead>
                <tr><th>Sr.No</th><th>Approval Rate (%)</th><th>Decline Rate (%)</th><th>Pending (%)</th></tr>
            </thead>
            <tbody>
                @if(empty($data))
                    <tr>
                        <td colspan="6" class="no-data">No data found</td>
                    </tr>
                @else
                    <td>1</td>
                    <td class="text-success"><strong>{{$data['approval_rate']}}</strong></td>
                    <td class="text-danger"><strong>{{$data['decline_rate']}}</strong></td>
                    <td class="text-warning"><strong>{{$data['pending_rate'] }}</strong></td>
                @endif
            </tbody>
        </table>
     @break

    @case('transaction_history')
        <h2>Request Stock Transaction History Report</h2>
        <table>
            <thead>
                <tr><th>Sr.No</th><th>Quantity</th><th>Status</th><th>Processed By</th><th>Location Name</th><th>Created At</th></tr>
            </thead>
            <tbody>
                @if(empty($data))
                    <tr>
                        <td colspan="6" class="no-data">No data found</td>
                    </tr>
                @else
                    @foreach($data as $index => $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->supplier_qty }}</td>
                            <td>{{ $stock->status_id }}</td>
                            <td>{{ $stock->processed_by_name }}</td>
                            <td>{{ $stock->rig_name }}</td>
                            <td>{{ $stock->created_at }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
     @break
     @case('fulfillment_status')
     <h2>Request Stock Fulfillment Status Report</h2>
         <table>
             <thead>
                 <tr><th>Sr.No</th><th>Request ID</th><th>Requested Stock</th><th>Requesters Stock</th><th>Status</th><th>Expected Delivery</th><th>Actual Delivery</th></tr>
             </thead>
             <tbody>
                @if($data->isEmpty())
                     <tr>
                         <td colspan="6" class="no-data">No data found</td>
                     </tr>
                 @else
                     @foreach($data as $index => $stock)
                         <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock['request_id'] }}</td>
                            <td>{{ $stock['requested_stock_item'] }}</td>
                            <td>{{ $stock['requester_stock_item'] }}</td>
                            <td>{{ $stock['status'] }}</td>
                            <td>{{ $stock['expected_delivery'] }}</td>
                            <td>{{ $stock['actual_delivery'] }}</td>
                         </tr>
                     @endforeach
                 @endif
             </tbody>
         </table>
     @break
    @case('consumption_details')
        <h2>Request Stock Consumptions Report</h2>
        <table>
            <thead>
                <tr><th>Sr.No</th><th>Request ID</th><th>Requested Stock Item</th><th>Requester Stock Item</th><th>Initial Stock</th><th>Received Stock</th><th>Used Stock</th><th>Remaining Stock</th></tr>
            </thead>
            <tbody>
                @if($data->isEmpty())
                    <tr>
                        <td colspan="6" class="no-data">No data found</td>
                    </tr>
                @else
                    @foreach($data as $index => $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock['request_id'] }}</td>
                            <td>{{ $stock['requested_stock_item'] }}</td>
                            <td>{{ $stock['requester_stock_item'] }}</td>
                            <td>{{ $stock['initial_stock'] }}</td>
                            <td>{{ $stock['received_stock'] }}</td>
                            <td>{{ $stock['used_stock'] }}</td>
                            <td>{{ $stock['remaining_stock'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    @break
    @default
         <table>
            <thead><tr><td>No Data Found</td></tr></thead>
        </table></p>
    @endswitch

</body>

</html>
