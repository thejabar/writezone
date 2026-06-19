<h1>
    <i class="fa-regular fa-pen-to-square"></i>
    Edit Writ
</h1>

<form method="POST" action="/writs/<?= htmlspecialchars($writ->public_id) ?>/update">

    <textarea
        name="content"
        rows="6"
        cols="60"
        maxlength="1000"
        required
    ><?= htmlspecialchars($writ->content) ?></textarea>

    <br><br>

    <button type="submit">
        <i class="fa-regular fa-floppy-disk"></i>
        Save Changes
    </button>

</form>