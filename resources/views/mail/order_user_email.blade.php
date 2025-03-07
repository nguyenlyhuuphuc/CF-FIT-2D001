
@php $orderItems = $order->orderItems @endphp

<table border="1">
    <tr>
        <th>STT</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    @php $total = 0; @endphp
    @foreach ($orderItems as $item)
        @php $total += $item->price * $item->qty @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ number_format($item->price, 2) }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->price * $item->qty, 2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td>Total : </td>
        <td colspan="4">{{ number_format($total, 2) }}</td>
    </tr>
</table>