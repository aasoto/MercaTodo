<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Código</th>
        <th>Fecha de compra</th>
        <th>Fecha de pago</th>
        <th>Estado del pago</th>
        <th>Total de la compra</th>
        <th>Link de pago</th>
        <th>Ultima vez actualizado</th>
        <th>Número de documento</th>
        <th>Primer nombre</th>
        <th>Segundo nombre</th>
        <th>Primer apellido</th>
        <th>Segundo apellido</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->code }}</td>
            <td>{{ $order->purchase_date }}</td>
            <td>{{ $order->payment_date }}</td>
            <td>{{ $order->payment_status }}</td>
            <td>{{ $order->purchase_total }}</td>
            <td>{{ $order->url }}</td>
            <td>{{ $order->updated_at }}</td>
            <td>{{ $order->number_document }}</td>
            <td>{{ $order->first_name }}</td>
            <td>{{ $order->second_name }}</td>
            <td>{{ $order->surname }}</td>
            <td>{{ $order->second_surname }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
