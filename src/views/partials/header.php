<header>
    <nav>
        <ul>
            <li><a href="/gallery?page=1">Gallery</a></li>
            <li><a href="/gallery/add">Add an image</a></li>
            <?php if ($this->isUserLoggedIn): ?>
                <li><a href='/gallery/favourites'>Favourites</a></li>
                <li><a href="/gallery/my_images">My Images</a></li>
                <li><a href='/user/logout'>Logout</a></li>
            <?php else: ?>
                <li><a href='/user/login'>Login</a></li>
                <li><a href='/user/register'>Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>