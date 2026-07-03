<?php

declare(strict_types=1);

use App\Intelligence\Inspectors\FeedInspector;

/** @var App\Ranking\Results\RankedCandidate $candidate */

$inspection = (new FeedInspector())
    ->inspect($candidate);

?>

<div class="intelligence-panel">

    <div class="intelligence-title">

        🧠 Intelligence

    </div>

    <?php foreach ($inspection as $item): ?>

        <div class="intelligence-row">

            <strong>

                <?= htmlspecialchars($item->label()) ?>

            </strong>

            <span>

                <?php

                $value = $item->value();

                if (is_array($value)) {

                    echo htmlspecialchars(
                        (string) ($value['value'] ?? '')
                    );

                    echo '<br>';

                    echo '<small>';

                    echo htmlspecialchars(
                        (string) ($value['reason'] ?? '')
                    );

                    echo '</small>';

                } else {

                    echo htmlspecialchars(
                        (string) $value
                    );

                }

                ?>

            </span>

        </div>

    <?php endforeach; ?>

</div>
