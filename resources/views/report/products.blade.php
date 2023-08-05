<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría del producto</th>
            <th>Precio</th>
            <th>Unidad</th>
            <th>Stock</th>
            <th>Disponilidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->unit }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->availability }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
