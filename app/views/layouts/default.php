<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title><?=$title ?></title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <link rel="stylesheet" type="text/css" href="/public/css/gallery.css">
    <link rel="stylesheet" type="text/css" href="/public/css/pagination.css">
    <link rel="stylesheet" type="text/css" href="/public/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/public/css/menu.css">
    <link rel="stylesheet" type="text/css" href="/public/css/login.css">
    <link rel="stylesheet" type="text/css" href="/public/css/add-image.css">
    <script src="/public/js/functions.js"></script>
</head>
<body class="site">

<div class="site-content">
    <?php require_once 'header.php'; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?=$_SESSION['error'];
            unset($_SESSION['error']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'];
            unset($_SESSION['success']) ?>
        </div>
    <?php endif; ?>

<?=$content ?>
</div>
<!--        --><?//= debug(vendor\core\Db::$countSql)?>
<!--        --><?//= debug(vendor\core\Db::$queries)?>

<?php require_once 'footer.php'; ?>
</body>
</html>