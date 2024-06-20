<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Data</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Gaya tambahan */
        .container {
            margin-top: 50px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Supplier Data</h2>

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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->supplier_id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->telephone }}</td>
                    <td>
                        <a href="">Edit</a>
                        <!-- <button type="button" class="btn btn-primary mb-3 add-pic-btn" data-toggle="modal" data-target="#addPicModal" data-supplier-id="{{ $supplier->supplier_id }}">Add PIC</button> -->

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1">
                            Add Contact Person
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menambah PIC -->
    <div id="addPicModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Person in Charge - <span id="supplierName"></span> (<span id="supplierId"></span>)</h3>

            <!-- Form untuk menambah PIC -->
            <form action="{{ route('supplier.pic.add') }}" method="POST" id="add-pic-form">
                @csrf
                <label for="pic_name">Name:</label>
                <input type="text" id="pic_name" name="pic_name" class="form-control" required><br>

                <label for="pic_telephone">Telephone:</label>
                <input type="text" id="pic_telephone" name="pic_telephone" class="form-control" required><br>

                <label for="pic_email">Email:</label>
                <input type="email" id="pic_email" name="pic_email" class="form-control"><br>

                <label for="pic_assignment_date">Tanggal Penugasan PIC</label>
                <input type="date" class="form-control" id="pic_assignment_date" name="pic_assignment_date"><br>

                <button type="submit" class="btn btn-primary">Add PIC</button>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            var table = $('#supplier-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('supplier.index') }}", // Ganti dengan rute yang sesuai
                "columns": [{
                        "data": "DT_RowIndex",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": "supplier_id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "address"
                    },
                    {
                        "data": "telephone"
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-primary add-pic-btn" data-supplier-id="' + row.supplier_id + '">Add PIC</button>';
                        }
                    }
                ]
            });

            // Tampilkan modal untuk menambah PIC saat tombol ditekan
            $('#supplier-table').on('click', '.add-pic-btn', function() {
                // var supplierId = $(this).data('supplier_id');
                var supplierId = $(this).closest('tr').find('td:eq(1)').text();
                var supplierName = $(this).closest('tr').find('td:eq(2)').text();
                $('#addPicModal').data('supplier_id', supplierId).show();
                $('#supplierId').text(supplierId);
                $('#supplierName').text(supplierName);
            });

            // Tutup modal saat tombol close ditekan
            $('.close').on('click', function() {
                $('#addPicModal').hide();
            });

            // Kirim data PIC saat form disubmit
            $('#add-pic-form').on('submit', function(e) {
                e.preventDefault();
                // Ambil data PIC dari form dan ID supplier dari modal
                // var supplierId = $('#addPicModal').data('supplier_id');
                // var picData = {
                //     supplier_id: supplierId,
                //     pic_name: $('#pic_name').val(),
                //     pic_telephone: $('#pic_telephone').val(),
                //     pic_email: $('#pic_email').val(),
                //     pic_assignment_date: $('#pic_assignment_date').val()
                // };
                // $.ajax({
                //     method: "POST",
                //     url: "{{ route('supplier.pic.add') }}",
                //     data: {
                //         'picData': picData,
                //     },
                //     success: function(response) {
                //         console.log(picData);
                //     }
                // });
                // Lakukan sesuatu dengan data PIC, misalnya kirimkan melalui AJAX
                // console.log(picData);
                // Setelah selesai, tutup modal
                $('#addPicModal').hide();
            });
        });
    </script> -->

</body>

</html>