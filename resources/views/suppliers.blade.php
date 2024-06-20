<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Suppliers</h1><br>
        <a href="{{ route('supplier.form') }}">New Supplier</a>
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
                    <th>Name</th>
                    <th>Address</th>
                    <th>Telephone</th>
                    <th>PiC</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $index => $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->supplier_id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->telephone }}</td>
                    <td><span class="badge text-bg-info">{{ $supplier->total_pic }}</span></td>
                    <td>
                        <!-- TODO membuat nama field susah ditebak. jangan plain seperti sekarang -->
                        <a href="">Edit</a> &nbsp; <a href="{{ route('purchase_order.form', 
                            ['supplier_id' => $encryptedSupplierIds[$index], 
                            'name' => $encryptedNames[$index], 'po_number' =>$nextPONumbers[$index] ])
                        }}">Create PO</a>
                        <button type="button" class="btn btn-primary btn-sm add-pic-btn" data-bs-toggle="modal" data-bs-target="#addPicModal">
                            Add Pic
                        </button>
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

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $('#supplier-table').DataTable();

            // Tampilkan modal untuk menambah PIC saat tombol ditekan
            $('#supplier-table').on('click', '.add-pic-btn', function() {
                var supplierId = $(this).closest('tr').find('td:eq(1)').text();
                var supplierName = $(this).closest('tr').find('td:eq(2)').text();
                $('#supplierId').text(supplierId);
                $('#supplierName').text(supplierName);

                $('#supplier_id').val(supplierId);
            });
        });

        function confirmSubmit() {
            return confirm("Apakah Anda yakin ingin mengirim form?");
        }
    </script>
</body>

</html>