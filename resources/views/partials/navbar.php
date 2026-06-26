<?php

use App\Models\Notification;
use Core\Authentication\Auth;

$unreadCount = 0;

if (Auth::check()) {
    $unreadCount = Notification::unreadCount(
        (int) Auth::id()
    );
}

?>

<nav class="navbar">

    <a href="/" class="brand">
        <i class="fa-solid fa-feather"></i>
        WriteZone
    </a>

    <div class="nav-links">

        <a href="/">
            <i class="fa-solid fa-house"></i>
            Home
        </a>

        <a href="/writs">
            <i class="fa-solid fa-pen"></i>
            Writs
        </a>

        <?php if (Auth::check()): ?>
    <a href="/writs/create">
        <i class="fa-solid fa-square-plus"></i>
        New Writ
    </a>
    <a href="/dashboard">
        <i class="fa-solid fa-chart-line"></i>
        Dashboard
    </a>
    <a href="/search">
        <i class="fa-solid fa-magnifying-glass"></i>
        Search
    </a>
    <a href="/notifications">
        <i class="fa-solid fa-bell"></i>
        Notifications
        <?php if ($unreadCount > 0): ?>
            <span class="notification-badge">
                <?= $unreadCount ?>
            </span>
        <?php endif; ?>
    </a>
    <a href="/bookmarks">
        <i class="fa-solid fa-bookmark"></i>
        Bookmarks
    </a>
    <a href="/logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout
    </a>
<?php else: ?>
    <a href="/login">
        <i class="fa-solid fa-right-to-bracket"></i>
        Login
    </a>
    <a href="/register">
        <i class="fa-solid fa-user-plus"></i>
        Register
    </a>
<?php endif; ?>

    </div>

</nav>