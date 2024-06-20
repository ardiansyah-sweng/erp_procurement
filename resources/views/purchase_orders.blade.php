<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purhase Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Purchase Orders</h1>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif(isset($_GET['success']))
        <div class="alert alert-success" id="success-message">
            {{ $_GET['success'] }}
        </div>
        @endif
        <script>
            setTimeout(function() {
                document.getElementById('success-message').style.display = 'none';
                // Menghapus query string setelah 5 detik
                window.history.replaceState({}, document.title, "/purchase_orders");
            }, 3000);
        </script>
    </div>

    <body>
        <div class="container mt-5">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <table id="supplier-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Supplier</th>
                        <th>PO Number</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase_orders as $index => $po)
                    <tr>
                        <td> {{$po->id }}</td>
                        <td> {{$po->supplier_id }}</td>
                        <td> {{$po->po_number }}</td>
                        <td> <a href="{{ route('purchase_order_detail', ['po_number' => $po->po_number ]) }}">{{$po->total_po }}</a></td>
                        <td> {{$po->total }}</td>
                        <td> {{$po->created_at }}</td>
                        <td> On Progress</td>
                        <td>
                            <form action="/purchase_order/receipt" method="GET" class="d-inline">
                                <input type="hidden" name="po_number" value="{{ $po->po_number }}">
                                <button type="submit" class="btn btn-primary btn-sm">Receipt</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addPicModal" tabindex="-1" aria-labelledby="addPicModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addPicModalLabel"><span id="supplierName"></span> (<span id="supplierId"></span>)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form untuk menambah PIC -->
                        <form action="{{ route('supplier.pic.add') }}" method="POST" id="add-pic-form" onsubmit="return confirmSubmit()">
                            @csrf
                            <label for="pic_name">Name:</label>
                            <input type="text" id="pic_name" name="pic_name" class="form-control" required><br>

                            <label for="pic_telephone">Telephone:</label>
                            <input type="text" id="pic_telephone" name="pic_telephone" class="form-control" required><br>

                            <label for="pic_email">Email:</label>
                            <input type="email" id="pic_email" name="pic_email" class="form-control"><br>

                            <label for="pic_assignment_date">Tanggal Penugasan PIC</label>
                            <input type="date" class="form-control" id="pic_assignment_date" name="pic_assignment_date"><br>

                            <input type="hidden" id="supplier_id" name="supplier_id" value="">

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Contact Person</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>


    </body>

</html>