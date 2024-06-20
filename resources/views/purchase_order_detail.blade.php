<html>

<body>
    Purchase Order Detail<br>
    {{ $master_po->po_number }}<br>
    {{ $master_po->supplier_id }}<br>
    {{ $master_po->created_at }}<br>

    @php
    $total = 0
    @endphp

    @foreach($purchase_orders as $po) {
    {{ $po->item_id }}
    {{ $po->item_name }}
    {{ $po->category }}
    {{ $po->price }}
    {{ $po->quantity }}
    {{ $po->uom }}
    @php
    $subTotal = $po->quantity * $po->price
    @endphp
    {{ $subTotal }}
    @php
    $total += $subTotal
    @endphp
    <br>
    }
    @endforeach
    {{ $total }}
</body>

</html>