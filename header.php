
<header>

    <nav id="navbar">
        <ul id="nav">
            <h1>OnlyCars</h1><br>
        <div id="list">
            <a href="index.php">Etusivu</a>
            <?php if (!empty($user)): ?>
                <a href="create_post.php">Luo postaus</a>
            <?php else: ?>
                <a href="login.php">Kirjaudu sisään julkaistaksesi</a>
            <?php endif; ?>
        </div>
        </ul>
    </nav>

</header>