<ul class="menu">
    <li><a class="navig-link" href="/">Home</a></li>
    <?php if (isset($_SESSION['user'])) : ?>
        <li><a class="navig-link" href="/gallery/index">Add image</a></li>
        <li><a class="navig-link" href="/user/logout">Logout</a></li>
        <li><a class="navig-link" href="/user/profile">Profile</a></li>
    <?php else : ?>
        <li><a class="navig-link" href="/user/signup">Sign Up</a></li>
        <li><a class="navig-link" href="/user/login">Log In</a></li>
    <?php endif ?>
</ul>
