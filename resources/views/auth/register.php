<h1>
    <i class="fa-solid fa-user-plus"></i>
    Register
</h1>
<p>
    Create your WriteZone account.
</p>
<form method="POST" action="/register">
    <p>
        <label for="username">Username</label>
    </p>
    <input
        id="username"
        type="text"
        name="username"
        placeholder="Username"
        required
    >
    <br><br>
    <p>
        <label for="email">Email</label>
    </p>
    <input
        id="email"
        type="email"
        name="email"
        placeholder="Email"
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
        <i class="fa-solid fa-user-plus"></i>
        Register
    </button>
</form>
<p>
    Already have an account?
    <a href="/login">
        Sign in
    </a>
</p>