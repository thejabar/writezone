<h1>Edit Comment</h1>
<form
    method="POST"
    action="/comments/<?= $comment->id ?>/update"
>
    <textarea
        name="content"
        rows="5"
        required
    ><?= htmlspecialchars(
        $comment->content
    ) ?></textarea>
    <br><br>
    <button type="submit">
        Save Changes
    </button>
</form>