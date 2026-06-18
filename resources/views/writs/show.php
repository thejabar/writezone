<p>

    <a href="/@<?= htmlspecialchars($writ->handle) ?>">
        <i class="fa-regular fa-user"></i>
        @<?= htmlspecialchars($writ->handle) ?>
    </a>

</p>

<p>

    <?= nl2br(
        htmlspecialchars($writ->content)
    ) ?>

</p>

<hr>

<small>

    <i class="fa-regular fa-hashtag"></i>

    <?= $writ->id ?>

</small>