<h1>
    <i class="fa-regular fa-comments"></i>
    Latest Writs
</h1>

<p>
    <a href="/writs/create">
        <i class="fa-regular fa-pen-to-square"></i>
        Create Writ
    </a>
</p>

<?php foreach ($writs as $writ): ?>

    <article>

        <p>

            <a href="/@<?= htmlspecialchars($writ->handle) ?>">
                <i class="fa-regular fa-user"></i>
                @<?= htmlspecialchars($writ->handle) ?>
            </a>

        </p>

        <p>

            <a href="/writs/<?= $writ->id ?>">

                <?= nl2br(
                    htmlspecialchars($writ->content)
                ) ?>

            </a>

        </p>

        <small>

            <i class="fa-regular fa-hashtag"></i>

            <?= $writ->id ?>

        </small>

        <hr>

    </article>

<?php endforeach; ?>