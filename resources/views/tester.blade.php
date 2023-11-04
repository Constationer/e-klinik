<!DOCTYPE html>
<html>

<head>
    <title>Select2 Example</title>
    <!-- Include Select2 CSS via CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <h1>Select2 Example</h1>
        <select class="select2" style="width: 100%;">
            <option value="option1">Option 1</option>
            <option value="option2">Option 2</option>
            <option value="option3">Option 3</option>
            <!-- Add more options as needed -->
        </select>
    </div>

    <!-- Include jQuery and Select2 JavaScript via CDNs -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2(); // Initialize Select2 on your select element
        });
    </script>
</body>

</html>
