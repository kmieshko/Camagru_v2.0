<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title><?=$title ?></title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <link rel="stylesheet" type="text/css" href="/public/css/gallery.css">
    <link rel="stylesheet" type="text/css" href="/public/css/pagination.css">
    <link rel="stylesheet" type="text/css" href="/public/css/modal.css">
    <script src="/public/js/functions.js"></script>
</head>
<body>

<?php require_once 'header.php'; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div>
        <?= $_SESSION['error'];
        unset($_SESSION['error']) ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div>
        <?= $_SESSION['success'];
        unset($_SESSION['success']) ?>
    </div>
<?php endif; ?>

<?php debug($_SESSION); ?>
<?=$content ?>
<!--        --><?//= debug(vendor\core\Db::$countSql)?>
<!--        --><?//= debug(vendor\core\Db::$queries)?>

<?php require_once 'footer.php'; ?>
</body>
</html>