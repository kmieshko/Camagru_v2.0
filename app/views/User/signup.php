<center>
    <div class="login-page">
        <h1>Registartion</h1>
        <div class="form">
            <form method="post" action="/user/signup">
                <input class="field" type="text" name="login" placeholder="Login"
                       value="<?= isset($_SESSION['form_data']['login']) ? h($_SESSION['form_data']['login']) : ''; ?>">
                <input class="field" type="password" name="password" placeholder="Password">
                <input class="field" type="email" name="email" placeholder="Email"
                       value="<?= isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : ''; ?>">
                <p class="radio">Would you like to receive notifications?</p>
                <div class="radio">
                    <div>
                        <input type="radio" name="notifications" value="no" checked>
                        <label>No</label>
                    </div>
                    <div>
                        <input type="radio" name="notifications" value="yes">
                        <label>Yes</label>
                    </div>
                </div>
                <button style="margin-top: 10px" type="submit">Sign Up</button>
            </form>
        </div>
    </div>
</center>
<?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']) ?>