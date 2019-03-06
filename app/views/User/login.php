<center>
    <h2>Log In</h2>
    <form method="post" action="/user/login">
        <div>
            <label for="login">Login</label>
            <input type="text" name="login" id="login" placeholder="Login">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password">
        </div>
        <div class="g-recaptcha" data-sitekey="6LfK5pUUAAAAAGyqPYMLTb3RoL21vqhPsBxgazKZ"></div>
        <button type="submit">Sign In</button>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </form>
    <a href="/user/reset-password">Forgot your password?</a>
</center>

