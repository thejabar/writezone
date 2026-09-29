<?php
declare(strict_types=1);

require dirname(__DIR__) . "/vendor/autoload.php";

use App\Models\Comment;
use App\Models\Writ;
use App\Policies\CommentPolicy;
use App\Policies\WritPolicy;
use Core\Authorization\Authorization;

function assertAuthorization(bool $condition, string $message): void
{
    if (! $condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

$writ = new Writ();
$writ->user_id = 10;

$comment = new Comment();
$comment->user_id = 10;

assertAuthorization(
    Authorization::allows(WritPolicy::class, "update", 10, $writ),
    "Writ owner can update."
);

assertAuthorization(
    ! Authorization::allows(WritPolicy::class, "update", 20, $writ),
    "Non-owner cannot update Writ."
);

assertAuthorization(
    Authorization::allows(WritPolicy::class, "delete", 10, $writ),
    "Writ owner can delete."
);

assertAuthorization(
    ! Authorization::allows(WritPolicy::class, "delete", 20, $writ),
    "Non-owner cannot delete Writ."
);

assertAuthorization(
    Authorization::allows(CommentPolicy::class, "update", 10, $comment),
    "Comment owner can update."
);

assertAuthorization(
    ! Authorization::allows(CommentPolicy::class, "update", 20, $comment),
    "Non-owner cannot update Comment."
);

assertAuthorization(
    Authorization::allows(CommentPolicy::class, "delete", 10, $comment),
    "Comment owner can delete."
);

assertAuthorization(
    ! Authorization::allows(CommentPolicy::class, "delete", 20, $comment),
    "Non-owner cannot delete Comment."
);

assertAuthorization(
    ! Authorization::allows(WritPolicy::class, "unknown", 10, $writ),
    "Unknown Writ ability fails closed."
);

assertAuthorization(
    ! Authorization::allows(CommentPolicy::class, "unknown", 10, $comment),
    "Unknown Comment ability fails closed."
);

printf("Authorization regression: PASS (10/10)\n");
