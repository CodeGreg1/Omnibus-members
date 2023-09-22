<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #123</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 10px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        *, ::after, ::before {
            box-sizing: border-box;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: 11px;
            border-collapse: collapse;
        }
        table thead th {
            padding: 8px;
            font-size: 11px;
            text-align: left;
            background-color: rgba(0, 0, 0, 0.04);
            color: #666;
            border: 0;
        }
        tbody tr td {
            padding: 8px;
            font-size: 11px;
            text-align: left;
            border: 0;
        }
        tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .title {
            width: 100%;
            text-align: center;
        }
    </style>

</head>
<body>
    <div class="transactions">
        <div class="title">
            <h3>@lang('Transactions Report')</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>@lang('Transaction ID')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('User')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Description')</th>
                    <th>@lang('Currency')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Charge')</th>
                    <th>@lang('Total')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row['trx'] }}</td>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['user'] }}</td>
                        <td>{{ $row['type'] }}</td>
                        <td>{{ $row['description'] }}</td>
                        <td>{{ $row['currency'] }}</td>
                        <td>{{ $row['total'] }}</td>
                        <td>{{ $row['total_charge'] }}</td>
                        <td>{{ $row['grand_total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
