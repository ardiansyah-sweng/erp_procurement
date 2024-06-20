<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables with Bootstrap Example</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-left">Purchase Order</h1>

        <div class="form-group">
            <label for="supplier_id">ID Supplier: {{ $supplier_id  }}</label><br>
            <label for="name">Nama Supplier: {{ $name  }}</label><br>
        </div>

        <button type="button" class="btn btn-primary btn-sm" id="save_po">Create PO</button>
        <!-- </form> -->


        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal" id="addItem">
            Add Item
        </button>
        <p></p>

        <!-- Modal -->
        <div class=" modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addItemModalLabel">New Item</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="pic_name">Search</label>
                        <input type="text" id="itemSearch" class="form-control" placeholder="Type item ID or name">
                        <select id="itemResults" class="form-control mt-2" size="5" style="display: none;"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addItemToTable">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan ditambahkan di sini menggunakan JavaScript -->
            </tbody>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        $(document).on('click', '#addItemToTable', function() {
            var selectedItem = $('#itemSearch').val();
            var supplierId = '{{ $supplier_id }}'; // Ambil supplier_id yang terpilih dari blade template
            // Lakukan permintaan AJAX untuk mendapatkan data terkait dari server
            var itemId = selectedItem.split(' ')[1];
            itemId = itemId.substring(1, itemId.length - 1);

            $.ajax({
                url: "/items/" + itemId,
                method: "GET",
                data: {
                    supplier_id: supplierId
                },
                success: function(data) {
                    var newRow = '<tr>' +
                        '<td>' + selectedItem + '</td>' +
                        '<td>' + data.name + '</td>' + // Isi dengan data yang diterima dari server
                        '<td>' + data.category + '</td>' + // Isi dengan data yang diterima dari server
                        '<td>' + data.unit_of_measurement + '</td>' + // Isi dengan data yang diterima dari server
                        '<td>' + data.start_date + '</td>' + // Isi dengan data yang diterima dari server
                        '<td>' + data.salary + '</td>' + // Isi dengan data yang diterima dari server
                        '</tr>';
                    $('#example tbody').append(newRow);
                    $('#addItemModal').modal('hide');
                }
            });
        });

        // Sisanya tetap sama
    </script>

    <script>
        $(document).on('click', '#save_po', function() {
            var table = $('#example').DataTable();
            var data = table.rows().data().toArray(); // Ambil semua data dari DataTable
            alert(data);
            $.ajax({
                url: "{{ route('save.purchase_order') }}", // Rute Laravel untuk menyimpan data
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF Laravel
                    items: data
                },
                success: function(response) {
                    alert('Data saved successfully!');
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while saving data.');
                }
            });
        });
    </script>
</body>

</html>