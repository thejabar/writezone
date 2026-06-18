<!-- resources/views/auth/login.php -->
<section class="max-w-md mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Sign In</h1>
        <p class="text-gray-500 mt-2">
            Welcome back to WriteZone.
        </p>
    </div>
    <?php if (flash('error')): ?>
        <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
            <?= htmlspecialchars(flash('error')) ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="/login" class="space-y-5">
        <?= csrf_field() ?>
        <div>
            <label class="block mb-2 font-medium">
                Email
            </label>
            <input
                type="email"
                name="email"
                required
                class="w-full rounded-lg border px-4 py-3"
                placeholder="name@example.com"
            >
        </div>
        <div>
            <label class="block mb-2 font-medium">
                Password
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full rounded-lg border px-4 py-3"
                placeholder="••••••••"
            >
        </div>
        <button
            type="submit"
            class="w-full rounded-lg bg-black text-white py-3"
        >
            <i class="fa-solid fa-right-to-bracket mr-2"></i>
            Sign In
        </button>
    </form>
    <p class="text-center mt-6 text-gray-600">
        Don't have an account?
        <a href="/register" class="font-medium">
            Create one
        </a>
    </p>
</section>
<!-- resources/views/auth/register.php -->
<section class="max-w-md mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Create Account</h1>
        <p class="text-gray-500 mt-2">
            Join the WriteZone community.
        </p>
    </div>
    <form method="POST" action="/register" class="space-y-5">
        <?= csrf_field() ?>
        <div>
            <label class="block mb-2 font-medium">
                Username
            </label>
            <input
                type="text"
                name="username"
                required
                class="w-full rounded-lg border px-4 py-3"
            >
        </div>
        <div>
            <label class="block mb-2 font-medium">
                Handle
            </label>
            <input
                type="text"
                name="handle"
                class="w-full rounded-lg border px-4 py-3"
                placeholder="mrsjabar"
            >
        </div>
        <div>
            <label class="block mb-2 font-medium">
                Email
            </label>
            <input
                type="email"
                name="email"
                required
                class="w-full rounded-lg border px-4 py-3"
            >
        </div>
        <div>
            <label class="block mb-2 font-medium">
                Password
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full rounded-lg border px-4 py-3"
            >
        </div>
        <button
            type="submit"
            class="w-full rounded-lg bg-black text-white py-3"
        >
            <i class="fa-solid fa-user-plus mr-2"></i>
            Create Account
        </button>
    </form>
</section>