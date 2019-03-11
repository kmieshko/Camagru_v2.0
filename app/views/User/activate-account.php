<?php if (!empty($_SESSION['success'])) : ?>
    <?php $_SESSION['success'] = 'Check your inbox for an account activate email'; ?>
<?php elseif (!empty($_SESSION['user']) && $_SESSION['user']['activate'] == '1') : ?>
    <?php $_SESSION['success'] = 'Account activated successfully!'; ?>
    <?php redirect('/') ?>
<?php else : ?>
<div class="alert alert-success">
    <p>Account activated successfully! Now you can try to <a href='/user/login'>Login</a></p>
</div>
<?php endif; ?>