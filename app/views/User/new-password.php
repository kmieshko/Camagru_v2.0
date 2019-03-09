<link rel="stylesheet" type="text/css" href="/public/css/reset-password.css">
<form class="reset" method="post" action="/user/update-password">
    <div>
        <label for="new_password">New Password</label>
        <input type="text" name="new-password" id="new_password" placeholder="New Password">
    </div>
    <div>
        <label for="repeat">Repeat New Password</label>
        <input type="text" name="repeat" id="repeat" placeholder="Repeat New Password">
    </div>
    <button class="btn" type="submit">Update Password</button>
</form>
