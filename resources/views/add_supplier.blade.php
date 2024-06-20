<!DOCTYPE html>
<html lang="en">

<!-- TODO update bootstrap -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Supplier</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Langkah 2: CSS untuk menyembunyikan grup input PIC secara default */
        #pic-group {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Form Tambah Supplier</h2>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <form action="{{ route('supplier.add') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Supplier</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="address">Alamat</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="telephone">Telepon</label>
                <input type="text" class="form-control" id="telephone" name="telephone" required>
            </div>
            <h3>Person In Charge (PIC)</h3>
            <button type="button" class="btn btn-info" id="add-pic-btn">Tambah PIC</button>
            <div id="pic-group">
                <div class="form-group">
                    <label for="pic_name">Nama PIC</label>
                    <input type="text" class="form-control" id="pic_name" name="pic_name">
                </div>
                <div class="form-group">
                    <label for="pic_telephone">Telepon PIC</label>
                    <input type="text" class="form-control" id="pic_telephone" name="pic_telephone">
                </div>
                <div class="form-group">
                    <label for="pic_email">Email PIC</label>
                    <input type="email" class="form-control" id="pic_email" name="pic_email">
                </div>
                <div class="form-group">
                    <label for="pic_assignment_date">Tanggal Penugasan PIC</label>
                    <input type="date" class="form-control" id="pic_assignment_date" name="pic_assignment_date">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Supplier</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // Langkah 3: JavaScript untuk mengubah visibilitas grup input PIC ketika tombol ditekan
        document.getElementById('add-pic-btn').addEventListener('click', function() {
            var picGroup = document.getElementById('pic-group');
            if (picGroup.style.display === 'none' || picGroup.style.display === '') {
                picGroup.style.display = 'block';
            } else {
                picGroup.style.display = 'none';
            }
        });
    </script>
</body>

</html>