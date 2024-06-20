<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Good Receipt Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    Good Receipt Note<br>
    {{ $master_po->po_number }}<br>
    {{ $master_po->supplier_id }}<br>
    {{ $master_po->created_at }}<br>

    @php
    $total = 0
    @endphp

    <table id="grn-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Item ID</th>
                <th>Name</th>
                <th>Ordered Qty</th>
                <th>Receipt Qty</th>
                <th>Unit</th>
                <th>Condition</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase_orders as $po)
            <tr>
                <td>{{ $po->id }}</td>
                <td>{{ $po->item_id }}</td>
                <td>{{ $po->item_name }}</td>
                <td class="quantity">{{ $po->quantity }}</td>
                <td class="statusValue">0</td>
                <td>{{ $po->uom }}</td>
                <td>
                    <select class="form-select condition-select" aria-label="Default select example" data-item-id="{{ $po->item_id }}" data-item-name="{{ $po->item_name }}" data-item-orderedQty="{{ $po->quantity }}">
                        <option value="1" selected>Unverified</option>
                        <option value="2">Completed</option>
                        <option value="3" id="incompleteSelected">Incompleted</option>
                    </select>
                </td>
                <td>
                    <a href="">Edit</a> &nbsp;
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-6"></div> <!-- Untuk membuat ruang di sisi kiri tombol -->
        <div class="col-md-6 text-right">
            <button type="submit" class="btn btn-primary btn-sm" id="save_grn">Create GRN</button>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="incompleteModal" tabindex="-1" aria-labelledby="incompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="incompleteModalLabel">Incomplete Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Form untuk menambah PIC -->
                    <form action="{{ route('purchase_order_receipt_incomplete_item') }}" id="add-pic-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <fieldset disabled>
                            <label for="item_id">Item ID</label>
                            <input type="text" name="disabledItemID" class="form-control" id="disabledItemID"><br>

                            <label for="name">Name</label>
                            <input type="text" id="disabledItemName" name="disabledItemName" class="form-control"><br>

                            <label for="name">Ordered Qty</label>
                            <input type="text" id="disabledOrderedQty" name="disabledOrderedQty" class="form-control"><br>
                        </fieldset>

                        <label for="incompleteType">Type</label>
                        <select id="incompleteType" name="incompleteType" class="form-select" aria-label="Default select example">
                            <option value="defect">Defect</option>
                            <option value="unknown">Unknown</option>
                        </select><br>

                        <label for="quantity">Quantity</label>
                        <input type="text" id="incompleteQuantity" name="incompleteQuantity" class="form-control" required><br>

                        <div class="input-group mb-3">
                            <input type="file" name="incompleteImgs[]" class="form-control" id="incompleteImgs" multiple>
                            <label class="input-group-text" for="incompleteImg">Upload Image</label>
                        </div>

                        <label for="descriptionTextarea" class="form-label">Notes</label>
                        <textarea class="form-control" id="Notes" name="Notes" rows="3" placeholder="Enter your text here..." required></textarea>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="addItemSubmit">Save</button>
                            <button type="button" class="btn btn-primary" id="addItemBtn">Add</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        let selectedRow;

        function checkReceiptQty() {
            let allCompleted = true;
            document.querySelectorAll('.statusValue').forEach(function(statusValueElement) {
                if (parseInt(statusValueElement.textContent) === 0) {
                    allCompleted = false;
                }
            });
            document.getElementById('save_grn').disabled = !allCompleted;
        }

        function validateIncompleteQuantity() {
            const incompleteQuantity = document.getElementById('incompleteQuantity');
            const orderedQty = document.getElementById('disabledOrderedQty').value;
            const addItemBtn = document.getElementById('addItemBtn');

            const quantity = parseInt(incompleteQuantity.value);
            const maxQty = parseInt(orderedQty);

            if (quantity > 0 && quantity <= maxQty) {
                addItemBtn.disabled = false;
            } else {
                addItemBtn.disabled = true;
            }
        }

        document.getElementById('incompleteQuantity').addEventListener('input', validateIncompleteQuantity);

        document.querySelectorAll('.condition-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                // Get the closest tr element
                selectedRow = this.closest('tr');

                // Get the quantity and statusValue elements within the same tr
                var quantityElement = selectedRow.querySelector('.quantity');
                var statusValueElement = selectedRow.querySelector('.statusValue');

                // Update the statusValue element if 'Completed' is selected
                if (this.value == '2') {
                    statusValueElement.textContent = quantityElement.textContent;
                } else if (this.value == '3') {
                    var itemID = this.getAttribute('data-item-id');
                    var itemName = this.getAttribute('data-item-name');
                    var orderedQty = this.getAttribute('data-item-orderedQty');

                    document.getElementById('disabledItemID').value = itemID;
                    document.getElementById('disabledItemName').value = itemName;
                    document.getElementById('disabledOrderedQty').value = orderedQty;

                    var conditionModal = new bootstrap.Modal(document.getElementById('incompleteModal'));
                    conditionModal.show();
                } else {
                    statusValueElement.textContent = '0'; // Reset to 0 or any other value you prefer
                }

                checkReceiptQty(); // Call the function to check the statusValue after change
            });
        });

        document.getElementById('incompleteModal').addEventListener('shown.bs.modal', function() {
            validateIncompleteQuantity(); // Validate quantity when the modal is shown
        });

        document.getElementById('addItemBtn').addEventListener('click', function() {
            var quantityInput = document.getElementById('incompleteQuantity').value;
            var orderedQty = selectedRow.querySelector('.quantity').textContent;

            var newReceiptQty = orderedQty - quantityInput;

            selectedRow.querySelector('.statusValue').textContent = newReceiptQty;

            var conditionModal = bootstrap.Modal.getInstance(document.getElementById('incompleteModal'));
            conditionModal.hide();

            checkReceiptQty(); // Call the function to check the statusValue after change
        });

        $(document).ready(function() {
            // Inisialisasi DataTables
            // $('#grn-table').DataTable();grn-table

            // Tampilkan modal untuk menambah PIC saat tombol ditekan
            $('#grn-table').on('change', '.incompleteSelected', function() {
                alert('tes');
                // var supplierId = $(this).closest('tr').find('td:eq(1)').text();
                // var supplierName = $(this).closest('tr').find('td:eq(2)').text();
                // $('#supplierId').text(supplierId);
                // $('#supplierName').text(supplierName);

                // $('#supplier_id').val(supplierId);
            });
        });

        document.getElementById("save_grn").disabled = true;
    </script>
</body>

</html>