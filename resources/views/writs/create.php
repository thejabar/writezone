<!DOCTYPE html>
<html>
<head>
    <title>New Writ</title>
</head>
<body>

<h1>New Writ</h1>

<form method="POST" action="/writs">

    <textarea
        name="content"
        rows="6"
        cols="60"
        maxlength="1000"
        placeholder="What's happening?"
        required
    ></textarea>

    <br><br>

    <button type="submit">
        Publish Writ
    </button>

</form>

</body>
</html>