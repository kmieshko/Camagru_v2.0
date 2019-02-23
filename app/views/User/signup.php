<h2>Registartion</h2>

<form method="post" action="/user/signup">
    <div>
        <label for="login">Login</label>
        <input type="text" name="login" placeholder="Login"
               value="<?= isset($_SESSION['form_data']['login']) ? h($_SESSION['form_data']['login']) : ''; ?>">
    </div>
    <div>
        <label for="login">Password</label>
        <input type="text" name="password" placeholder="Password">
    </div>
    <div>
        <label for="email">Email</label>
        <input type="text" name="email" placeholder="Email"
               value="<?= isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : ''; ?>">
    </div>
    <div>
        <label for="notification">Would you like to receive notifications?</label>
        <input type="radio" name="notifications" value="no" checked>No
        <input type="radio" name="notifications" value="yes">Yes
    </div>
    <button type="submit">Sign Up</button>
</form>
<?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']) ?>

