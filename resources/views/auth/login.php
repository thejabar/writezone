<h1>
    <i class="fa-solid fa-right-to-bracket"></i>
    Sign In
</h1>
<p>
    Welcome back to WriteZone.
</p>
<form method="POST" action="/login">
    <p>
        <label for="email">Email</label>
    </p>
    <input
        id="email"
        type="email"
        name="email"
        placeholder="admin@writezone.org"
        required
    >
    <br><br>
    <p>
        <label for="password">Password</label>
    </p>
    <input
        id="password"
        type="password"
        name="password"
        placeholder="Password"
        required
    >
    <br><br>
    <button type="submit">
        <i class="fa-solid fa-right-to-bracket"></i>
        Login
    </button>
</form>
<p>
    Don't have an account?
    <a href="/register">
        Create one
    </a>
</p>