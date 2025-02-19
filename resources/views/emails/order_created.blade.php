<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <p>We appreciate your business!</p>

    <h3>Order Details</h3>
    <p>Order ID: {{ $order->id }}</p>
    <p>Total: {{ $order->total }}</p>
    <p>Status: {{ $order->status }}</p>
    <!-- Aqui você pode exibir qualquer outro dado da Order -->
</body>
</html>
