<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daily Sales Report</title>
</head>
<body>
    <h2>Daily Sales Report - {{ now()->toDateString() }}</h2>
    
    <p>Summary of sales for today:</p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <th>Metric</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Total Orders</td>
            <td>{{ $reportData['total_orders'] }}</td>
        </tr>
        <tr>
            <td>Total Revenue</td>
            <td>${{ number_format($reportData['total_revenue'], 2) }}</td>
        </tr>
        <tr>
            <td>Items Sold</td>
            <td>{{ $reportData['items_sold'] }}</td>
        </tr>
    </table>
    
    <h3>Top Products Sold</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>Product</th>
            <th>Quantity Sold</th>
            <th>Revenue</th>
        </tr>
        @foreach($reportData['top_products'] as $item)
        <tr>
            <td>{{ $item['product_name'] }}</td>
            <td>{{ $item['quantity_sold'] }}</td>
            <td>${{ number_format($item['revenue'], 2) }}</td>
        </tr>
        @endforeach
    </table>
    
    <p style="margin-top: 20px;">Best regards,<br>E-Commerce System</p>
</body>
</html>
