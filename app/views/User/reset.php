<link rel="stylesheet" type="text/css" href="/public/css/reset-password.css">
<?php if (empty($_SESSION['success'])) : ?>
    <form class="reset" method="post" action="">
        <div>
            <label for="reset">Please write your email: </label>
            <input type="text" name="reset" id="reset" placeholder="Email">
        </div>
        <button class="btn btn-primary" type="submit">Reset Password</button>
    </form>
<?php endif; ?>

