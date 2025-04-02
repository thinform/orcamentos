<?php
require_once 'config.php';
$page_title = $page_title ?? 'THINFORMA';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <style>
    .table > :not(caption) > * > * {
        padding: 0.5rem;
    }
    .btn-group-sm > .btn, .btn-sm {
        padding: 0.25rem 0.5rem;
    }
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }
    .form-control, .form-select {
        padding: 0.375rem 0.75rem;
    }
    .navbar {
        padding: 0.5rem 1rem;
        margin-bottom: 1rem;
    }
    .card {
        margin-bottom: 1rem;
    }
    .alert {
        margin-bottom: 1rem;
    }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container py-4">
    <?php
    if (isset($_GET['success'])) {
        echo alert('success', $_GET['success']);
    }
    if (isset($_GET['error'])) {
        echo alert('danger', $_GET['error']);
    }
    ?>
</div>
</body>
</html> 