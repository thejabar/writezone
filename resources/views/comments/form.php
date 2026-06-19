<?php if (\Core\Authentication\Auth::check()): ?>
    <form
        method="POST"
        action="/writs/<?= htmlspecialchars($writ->public_id) ?>/comments"
        >
        <textarea
            name="content"
            placeholder="Join the discussion..."
            required
        ></textarea>
        <br><br>
        <button type="submit">
            <i class="fa-solid fa-comment"></i>
            Comment
        </button>
    </form>
<?php else: ?>
    <div class="card">
        <p>
            <a href="/login">
                Sign in
            </a>
            to join the discussion.
        </p>
    </div>
<?php endif; ?>