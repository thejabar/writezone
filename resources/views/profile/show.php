<!-- resources/views/profile/show.php -->
<section class="max-w-2xl mx-auto">
    <div class="border rounded-xl p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                <i class="fa-solid fa-user text-2xl text-gray-500"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold">
                    <?= htmlspecialchars($user->display_name ?: $user->username) ?>
                </h1>
                <p class="text-gray-500">
                    @<?= htmlspecialchars($user->handle ?? $user->username) ?>
                </p>
            </div>
        </div>
        <?php if (!empty($user->bio)): ?>
            <p class="mb-8 whitespace-pre-line">
                <?= htmlspecialchars($user->bio) ?>
            </p>
        <?php endif; ?>
        <div class="space-y-4">
            <?php foreach ($writs as $writ): ?>
                <article class="border rounded-lg p-4">
                    <a href="/writs/<?= $writ->id ?>">
                        <p class="whitespace-pre-line">
                            <?= htmlspecialchars($writ->content) ?>
                        </p>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>