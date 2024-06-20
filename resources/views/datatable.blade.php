<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Purchase Order Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

    <style>
        /* CSS untuk mengatur ukuran input text dengan class form-control */
        .form-control.small-input {
            width: 100px;
            /* Ganti dengan lebar yang diinginkan */
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <h1 class="text-left">Purchase Order</h1>

        <!-- TODO check po number if exists, when user refresh button-->
        <div class="form-group">
            <label for="po_number" id="po_number_label">PO Number: {{ $po_number }}</label><br>
            <label for="supplier_id" id="supplier_id_label">ID Supplier: {{ $supplier_id }}</label><br>
            <label for="name" id="name_label">Nama Supplier: {{ $name }}</label><br>
        </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal" id="addItem">
            Add Item
        </button>
        <p></p>

        <!-- Modal -->
        <!-- TODO validasi jika input tidak diisi -->
        <form id="po_form">
            <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="addItemModalLabel">New Item</h1>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="itemSearch">Search</label>
                                <input type="text" id="itemSearch" class="form-control" placeholder="Type item ID or name" autocomplete="off">
                                <span id="itemSearchError" class="error"></span>

                                <select id="itemResults" class="form-control mt-2" size="5" style="display: none;"></select>
                                <span id="itemResultsError" class="error"></span>
                            </div>
                            <div class="form-group">

                                <label for="price">Price</label>
                                <input type="text" id="price" name="price" class="form-control" placeholder="Rp." required autocomplete="off">
                                <span id="priceError" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="text" id="quantity" name="quantity" class="form-control small-input" value="1" autocomplete="off" required><span id="uom"></span>
                                <span id="quantityError" class="error"></span>

                            </div>
                            <label for="total">Total</label>
                            <span id="total">0.0</span>
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
                        <th>Item ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan ditambahkan di sini menggunakan JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">Grand Total</th>
                        <th id="grandTotal">0.0</th>
                    </tr>
                </tfoot>
            </table>
            <!-- <button type="submit" class="btn btn-primary btn-sm align-right">Create PO</button> -->
            <div class="row">
                <div class="col-md-6"></div> <!-- Untuk membuat ruang di sisi kiri tombol -->
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-primary btn-sm" id="save_po">Create PO</button>
                </div>
            </div>
    </div>
    </form>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "emptyTable": "No data available in table",
                    "infoEmpty": "",
                    "info": ""
                },
                "paging": false
            });
        });

        function extractText(input) {
            // Cari posisi terakhir dari tanda ')' dan '('
            let endIndex = input.lastIndexOf(')');
            let startIndex = input.lastIndexOf('(', endIndex);

            // Jika kedua tanda ditemukan, ekstrak teks di antaranya
            if (startIndex !== -1 && endIndex !== -1 && startIndex < endIndex) {
                return input.substring(startIndex + 1, endIndex);
            } else {
                return null; // Atau bisa mengembalikan pesan kesalahan
            }
        }

        function extractTextAfterDash(inputString) {
            // Memisahkan string berdasarkan tanda "-"
            const parts = inputString.split('-');
            // Menghapus spasi di sekitar hasil pemisahan
            if (parts.length > 1) {
                return parts[1].trim();
            }
            return '';
        }

        function calculateTotal() {
            // Ambil nilai dari input price dan quantity
            var price = parseFloat(document.getElementById('price').value);
            var quantity = parseFloat(document.getElementById('quantity').value);

            if (isNaN(quantity)) {
                quantity = 0.0;
            }

            // Lakukan perhitungan
            var total = price * quantity;

            var formattedTotal = total.toLocaleString('id-ID');

            // Tampilkan hasilnya di dalam span dengan id "jumlah"
            document.getElementById('total').innerText = formattedTotal; // Menggunakan toFixed(2) untuk menampilkan 2 digit desimal
        }

        function updateGrandTotal() {
            let grandTotal = 0;
            $('#example tbody tr').each(function() {
                let total = $(this).find('td:eq(6)').text().replace(/\./g, '').replace(/,/g, '.'); // Ubah format dari 1.000 menjadi 1000
                if (!isNaN(total) && total.length !== 0) {
                    grandTotal += parseFloat(total);
                }
            });

            $('#grandTotal').text(grandTotal.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        //TODO add delete button, editable price & quantity
        $(document).on('click', '#addItemToTable', function() {
            var selectedItem = $('#itemSearch').val();
            var supplierId = '{{ $supplier_id }}';
            var quantity = $('#quantity').val();
            var total = $('#total').text();
            var unitPrice = $('#price').val();

            let text = selectedItem;
            let itemId = extractText(text);

            $.ajax({
                url: '/items/' + itemId,
                method: "GET",
                data: {
                    supplier_id: supplierId
                },
                success: function(data) {
                    indexUoM = data['item'].unit_of_measurement;
                    indexCategory = data['item'].category;

                    var price = parseFloat($('#price').val());
                    var total = price * parseFloat(quantity); // Menghitung total berdasarkan harga dan kuantitas
                    var formattedTotal = total.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }); // Memformat total ke dalam format mata uang lokal

                    var newRow = '<tr>' +
                        '<td>' + data['item'].item_id + '</td>' +
                        '<td>' + data['item'].name + '</td>' +
                        '<td>' + data['categories'][indexCategory] + '</td>' +
                        '<td>' + data['unit_of_measurements'][indexUoM] + '</td>' +
                        '<td>' + unitPrice + '</td>' +
                        '<td>' + quantity + '</td>' +
                        '<td>' + formattedTotal + '</td>' +
                        '</tr>';

                    var table = $('#example').DataTable();
                    table.row.add($(newRow)).draw(false);

                    $('#addItemModal').modal('hide');

                    // Update grand total setelah menambahkan baris baru
                    updateGrandTotal();
                }
            });
        });

        $('#itemSearch').on('input', function() {
            let query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: "{{ route('search.items') }}",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        let results = $('#itemResults');
                        results.empty();
                        if (data['item'].length > 0) {
                            results.show();

                            $.each(data['item'], function(index, item) {
                                results.append('<option value="' + item.item_id + '">' + item.name + ' (' + item.item_id + ') ' + ' - ' + data['unit_of_measurements'][item.unit_of_measurement] + '</option>');
                            });
                        } else {
                            results.hide();
                        }
                    }
                });
            } else {
                $('#itemResults').hide();
            }
        });

        $('#itemResults').on('click', 'option', function() {
            let selectedItem = $(this).text();
            $('#itemSearch').val(selectedItem);
            $('#itemResults').hide();

            const uomString = extractTextAfterDash(selectedItem);
            $('#uom').text(uomString);

        });

        $(document).click(function(event) {
            if (!$(event.target).closest('#itemSearch').length && !$(event.target).closest('#itemResults').length) {
                $('#itemResults').hide();
            }
        });

        document.getElementById('price').addEventListener('input', calculateTotal);
        document.getElementById('quantity').addEventListener('input', calculateTotal);
    </script>

    <script>
        $(document).ready(function() {
            $('#po_form').on('submit', function(event) {
                event.preventDefault();

                var table = $('#example').DataTable();
                var data = table.rows().data().toArray();
                var poNumber = $('#po_number_label').text().split(':')[1].trim();
                var supplierId = $('#supplier_id_label').text().split(':')[1].trim();

                let token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/purchase_order/save',
                    method: 'POST',
                    data: {
                        _token: token,
                        item: data,
                        po_number: poNumber,
                        supplier_id: supplierId,
                        total: 0
                    },
                    success: function(response) {
                        table.clear().draw();
                        window.location.href = "/purchase_orders?success=Purchase+Order+berhasil+ditambahkan!";
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Event listener for modal show
            $('#addItemModal').on('show.bs.modal', function(e) {
                // Reset the form
                $('#po_form')[0].reset();
            });
        });
    </script>

    <script>
        $('#addItemModal').on('shown.bs.modal', function() {
            $('#itemSearch').focus();
        });
    </script>

</body>

</html>