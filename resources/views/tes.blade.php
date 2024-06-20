<!DOCTYPE html>
<html>

<head>
    <title>Input Text Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Input Text Form</h1>
    <form id="textForm">
        <label for="text">Masukkan Teks:</label>
        <input type="text" id="text" name="text">
        <button type="submit">Kirim</button>
    </form>
    <div id="result"></div>

    <script>
        $(document).ready(function() {
            $('#textForm').on('submit', function(event) {
                event.preventDefault();

                let text = $('#text').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/submit-text',
                    method: 'POST',
                    data: {
                        _token: token,
                        text: text
                    },
                    success: function(response) {
                        $('#result').html('<p>Anda telah menginput: ' + response.text + '</p>');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>