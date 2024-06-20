<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <h1>Items</h1>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <button type="button" class="btn btn-primary btn-sm add-pic-btn" data-bs-toggle="modal" data-bs-target="#itemModal">
        New Item
    </button><p></p>

    <table id="supplier-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Item ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Unit Of Measurement</th>
                <th>Description</th>
                <th>Suppliers</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->item_id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $categories[$item->category] }}</td>
                <td>{{ $unit_of_measurement[$item->unit_of_measurement] }}</td>
                <td>{{ $item->description }}</td>
                <td><span class="badge text-bg-info">{{ $item->supplier_total }}</span></td>
                <td>
                    <a href="">Edit</a> &nbsp;
                    <button type="button" class="btn btn-primary btn-sm add-pic-btn" data-bs-toggle="modal" data-bs-target="#itemModal">
                        Add Pic
                    </button>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="itemModalLabel">New Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk menambah PIC -->
                    <form action="{{ route('item.add') }}" method="POST" id="add-pic-form" onsubmit="return confirmSubmit()">
                        @csrf
                        <label for="item_id">Item ID:</label>
                        <input type="text" id="item_id" name="item_id" class="form-control" required autofocus><br>

                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required><br>

                        <label for="category">Category:</label>
                        <select id="category" name="category" class="form-select" aria-label="Default select example">
                            @foreach ($categories as $index => $category)
                            <option value="{{ $index }}">{{ $category }}</option>
                            @endforeach
                        </select><br>

                        <label for="unit_of_measurement">Unit of Measurement</label>
                        <select id="unit_of_measurementSelect" name="unit_of_measurement" class="form-select" aria-label="Default select example">
                            @foreach ($unit_of_measurement as $index => $unit)
                            <option value="{{ $index }}">{{ $unit }}</option>
                            @endforeach
                        </select><br>

                        <label for="descriptionTextarea" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter your text here..." required></textarea>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Item</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {

            // Tampilkan modal untuk menambah PIC saat tombol ditekan
            $('#supplier-table').on('click', '.add-pic-btn', function() {
                var supplierId = $(this).closest('tr').find('td:eq(1)').text();
                var supplierName = $(this).closest('tr').find('td:eq(2)').text();
                $('#supplierId').text(supplierId);
                $('#supplierName').text(supplierName);

                $('#supplier_id').val(supplierId);
            });

        });
    </script>

    <script>
        function confirmSubmit() {
            return confirm("Apakah Anda yakin ingin mengirim form?");
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = document.getElementById('itemModal');
            myModal.addEventListener('shown.bs.modal', function() {
                var input = document.getElementById('item_id');
                input.focus();
            });
        });
    </script>

</body>

</html>