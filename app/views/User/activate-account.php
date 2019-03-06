<?php if (isset($_SESSION) && $_SESSION['user']['activate'] === '1') : ?>
    <p>Account activated successfully! Now you can try to <a href='/user/login'>Login</a></p>
<?php else : ?>
    <?php$_SESSION['success'] = 'Check your inbox for an account activate email'; ?>
<?php endif; ?>
