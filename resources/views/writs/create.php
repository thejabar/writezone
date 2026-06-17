<!DOCTYPE html>
<html>
<head>
    <title>Create Writ</title>
</head>
<body>

<h1>Create Writ</h1>

<form method="POST" action="/writs">

    <input
        type="text"
        name="title"
        placeholder="Title"
    >

    <br><br>

    <textarea
        name="content"
        rows="8"
        cols="50"
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