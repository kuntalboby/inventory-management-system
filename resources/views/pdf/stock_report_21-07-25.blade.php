<h2>{{ env('APP_NAME')}} : Product Stock Report</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Serial Number</th>
        <th>Product Name</th>
        <th>Category Name</th>
        <th>Packet Quantity</th>
        <th>No of Packets</th>
        <th>Buying Price</th>
        <th>GST</th>
        <th>Selling Price</th>
    </tr>
</thead>
<tbody>
    @if ($products->isEmpty())
        <tr>
            <td colspan="8">Sorry, no product found!</td>
        </tr>
    @else
        @foreach($products as $k => $product)
            <tr>
                <td>{{ $k + 1 }}</td>
                <td>{{ ucwords(strtolower($product->name) ) }}</td>
                <td>{{ ucwords(strtolower($goDownProducts->category->name) ) ?? '' }}</td>
                <td>
                    {{ $product->packet_size ?? '' }}
                    {{ $product->unit->name ?? '' }}
                </td>
                <td> {{$product->quantity}}  </td>
                <td style="font-family: DejaVu Sans, sans-serif;"> ₹ {{$product->buying_price}}  </td>
                <td> {{$product->gst}} % </td>
                <td style="font-family: DejaVu Sans, sans-serif;"> ₹ {{$product->selling_price}}  </td>
            </tr>
        @endforeach
    @endif
</tbody>

</table>
