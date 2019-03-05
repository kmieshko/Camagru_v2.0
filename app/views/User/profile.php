<center>
    <form method="post" action="/user/profile">
        <div>
            Login: <label id="label-login"><?=$login ?></label>
            <img id="edit-login" width="16" src="/public/icons/edit.png">
        </div>
        <div>
            Email: <label id="label-email"><?=$email ?></label>
            <img id="edit-email" width="16" src="/public/icons/edit.png">
        </div>
        <div>
            <label for="notification">Would you like to receive notifications?</label>
            <?php if ($notifications === 'no') : ?>
                <input type="radio" name="notifications" value="no" checked>No
                <input type="radio" name="notifications" value="yes">Yes
            <?php else : ?>
                <input type="radio" name="notifications" value="no">No
                <input type="radio" name="notifications" value="yes" checked>Yes
            <?php endif; ?>
        </div>
        <div>
            Change Password: <label id="label-password"></label>
            <img id="edit-password" width="16" src="/public/icons/edit.png">
        </div>
        <button type="submit">Submit</button>
    </form>
</center>

<script src="/public/js/profile.js"></script>