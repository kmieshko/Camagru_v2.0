<div class="login-page">
    <h1>Log In</h1>
    <div class="form">
        <form method="post" action="/user/login">
            <input class="field" type="text" name="login" id="login" placeholder="Login">
            <input class="field" type="password" name="password" id="password" placeholder="Password">
            <div class="g-recaptcha" data-sitekey="6LfK5pUUAAAAAGyqPYMLTb3RoL21vqhPsBxgazKZ"></div>
            <button class="btn btn-primary" type="submit">Sign In</button>
            <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
            <p class="message"><a href="/user/reset-password">Forgot your password?</a></p>
        </form>
    </div>
</div>
