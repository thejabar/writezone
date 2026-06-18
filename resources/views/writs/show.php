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
<?php if (
    \Core\Authentication\Auth::check()
    && \Core\Authentication\Auth::id() === (int) $writ->user_id
): ?>

    <hr>

    <p>

        <a href="/writs/<?= $writ->id ?>/edit">
            <i class="fa-regular fa-pen-to-square"></i>
            Edit
        </a>

    </p>

    <form
        method="POST"
        action="/writs/<?= $writ->id ?>/delete"
    >

        <button type="submit">

            <i class="fa-regular fa-trash-can"></i>

            Delete

        </button>

    </form>

<?php endif; ?>