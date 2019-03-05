<?php if (empty($_SESSION['success'])) : ?>
<center>
    <form method="post" action="">
        <div>
            <label for="reset">Please write your email: </label>
            <input type="text" name="reset" id="reset" placeholder="Email">
        </div>
        <button type="submit">Reset Password</button>
    </form>
</center>
<?php endif; ?>