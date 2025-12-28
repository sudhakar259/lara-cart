<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Low Stock Notification</title>
</head>
<body>
    <h2>Low Stock Alert</h2>
    <p>The following product is running low on stock:</p>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Product</th>
            <th>Current Stock</th>
            <th>Price</th>
        </tr>
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->stock_quantity }}</td>
            <td>${{ number_format($product->price, 2) }}</td>
        </tr>
    </table>
    
    <p>Please consider restocking this item.</p>
    
    <p>Best regards,<br>E-Commerce System</p>
</body>
</html>
