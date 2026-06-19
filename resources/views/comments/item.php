<article class="card">
    <div class="writ-header">
        <a
            class="writ-author"
            href="/@<?= htmlspecialchars($comment->handle) ?>"
        >
            <i class="fa-solid fa-user"></i>
            @<?= htmlspecialchars($comment->handle) ?>
        </a>
        <span class="writ-meta">
            <?= htmlspecialchars($comment->created_at) ?>
        </span>
    </div>
    <div class="writ-content">
        <?= nl2br(
            htmlspecialchars($comment->content)
        ) ?>
    </div>
    <?php if ( \Core\Authentication\Auth::check() ): ?> <form method="POST" action="/comments/<?= $comment->id ?>/reply" > <textarea name="content" placeholder="Write a reply..." required ></textarea> <br><br> <button type="submit"> <i class="fa-solid fa-reply"></i> Reply </button> </form> <?php endif; ?>
    
    <?php foreach ( \App\Models\Comment::replies( (int) $comment->id ) as $reply ): ?> <div style=" margin-left: 2rem; margin-top: 1rem; padding-left: 1rem; border-left: 2px solid var(--border); " > <strong> @<?= htmlspecialchars( $reply->handle ) ?> </strong> <p> <?= nl2br( htmlspecialchars( $reply->content ) ) ?> </p> </div> <?php endforeach; ?>
</article>