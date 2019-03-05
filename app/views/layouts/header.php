<ul>
    <li><a href="/">Home</a></li>
    <?php if (isset($_SESSION['user'])) : ?>
        <li><a href="/gallery/index">Add image</a></li>
        <li><a href="/user/logout">Logout</a></li>
        <li><a href="/user/profile">Profile</a></li>
    <?php else : ?>
        <li><a href="/user/signup">Signup</a></li>
        <li><a href="/user/login">Login</a></li>
    <?php endif ?>
</ul>
