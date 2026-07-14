<h1 class="page-title">
    <i class="fa-solid fa-brain"></i>
    <span>WriteZone Studio</span>
</h1>

<p class="page-subtitle">
    Analyze writing exactly as the Intelligence Engine sees it.
</p>

<?php

$metrics = $result?->metrics();
$quality = $result?->quality();
$sentenceReport = $result
    ? $result->sentences()
    : null;
?>


<div class="studio-layout">

    <section class="studio-editor card">

        <h2>
            <i class="fa-solid fa-pen-nib"></i>
            <span>Writing Workspace</span>
        </h2>

        <form
            id="studio-form"
            method="post"
        >

            <textarea
                id="studio-editor"
                class="studio-textarea"
                name="content"
                rows="16"
                placeholder="Start writing...

The Intelligence Engine will analyze your writing, measure its structure, evaluate its quality, and explain every decision."
            ><?= htmlspecialchars($content ?? '') ?></textarea>

            <div class="studio-toolbar">

                <div class="studio-actions">

                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        <i class="fa-solid fa-brain"></i>
                       <span>Analyze</span>
                    </button>

                    <button
                        id="studio-clear"
                        class="btn"
                        type="button"
                    >
                        <i class="fa-solid fa-file-circle-plus"></i>
                        <span>New Analysis</span>
                    </button>

                </div>

                <span
                    id="studio-status"
                    class="studio-status"
                >
                    <i class="fa-solid fa-circle"></i>
                    <span>Intelligence Ready</span>
                </span>

            </div>

        </form>

    </section>
<aside class="studio-sidebar">

    <div class="card">

        <h3>
            <i class="fa-solid fa-gauge-high"></i>
            <span>Overall Score</span>
        </h3>

        <div
            id="studio-score"
            class="studio-score"
        >
            <?= $result
                ? number_format(
                    $result->score(),
                    0
                )
                : '--' ?>
        </div>

    </div>

    <div class="card">

    <h3>
        <i class="fa-solid fa-layer-group"></i>
        <span>Writing Dimensions</span>
    </h3>

    <div class="studio-metric">

        <span>Structure</span>

        <strong id="dimension-structure">
    <?= $quality
        ? number_format(
            $quality->scoreFor('structure') ?? 0,
            0
        )
        : '--' ?>
</strong>

    </div>

    <div class="studio-metric">

        <span>Readability</span>

        <strong id="dimension-readability">
    <?= $quality
        ? number_format(
            $quality->scoreFor('readability') ?? 0,
            0
        )
        : '--' ?>
</strong>

    </div>

    <div class="studio-metric">

        <span>Vocabulary</span>

        <strong id="dimension-vocabulary">
    <?= $quality
        ? number_format(
            $quality->scoreFor('vocabulary') ?? 0,
            0
        )
        : '--' ?>
</strong>

    </div>

</div>

<div class="card">

    <h3>
        <i class="fa-solid fa-paragraph"></i>
        <span>Sentence Intelligence</span>
    </h3>

    <div class="studio-metric">
        <span>Total Sentences</span>
        <strong id="sentence-total">
            <?= $sentenceReport
                ? $sentenceReport->total
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Shortest Sentence</span>
        <strong id="sentence-shortest">
            <?= $sentenceReport
                ? $sentenceReport->shortest . ' words'
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Longest Sentence</span>
        <strong id="sentence-longest">
            <?= $sentenceReport
                ? $sentenceReport->longest . ' words'
                : '--' ?>
        </strong>
    </div>
    
    

    <div class="studio-metric">
        <span>Average Sentence Length</span>
        <strong id="sentence-average">
            <?= $sentenceReport
                ? number_format(
                    $sentenceReport->average,
                    1
                ) . ' words'
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Sentence Variety</span>
        <strong id="sentence-variety">
            <?= $sentenceReport
                ? $sentenceReport->variety
                : '--' ?>
        </strong>
    </div>

</div>

<div class="card">

    <h3>
        <i class="fa-solid fa-book"></i>
        <span>Vocabulary Intelligence</span>
    </h3>

    <div class="studio-metric">
        <span>Total Words</span>
        <strong id="vocabulary-total">
            <?= $result
                ? $result->vocabulary()->totalWords
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Unique Words</span>
        <strong id="vocabulary-unique">
            <?= $result
                ? $result->vocabulary()->uniqueWords
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Repeated Words</span>
        <strong id="vocabulary-repeated">
            <?= $result
                ? $result->vocabulary()->repeatedWords
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Lexical Diversity</span>
        <strong id="vocabulary-diversity">
            <?= $result
                ? number_format(
                    $result->vocabulary()->lexicalDiversity,
                    1
                ) . '%'
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Filler Words</span>
        <strong id="vocabulary-filler">
            <?= $result
                ? $result->vocabulary()->fillerWords
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Transition Words</span>
        <strong id="vocabulary-transition">
            <?= $result
                ? $result->vocabulary()->transitionWords
                : '--' ?>
        </strong>
    </div>

</div>
    <div class="card">

        <h3>
            <i class="fa-solid fa-chart-column"></i>
            <span>Metrics</span>
        </h3>

        <div id="studio-metrics">

            <div class="studio-metric">
                <span>Characters</span>
                <strong id="metric-characters">
                    <?= $result ? $metrics->characters : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Words</span>
                <strong id="metric-words">
                    <?= $result ? $metrics->words : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Sentences</span>
                <strong id="metric-sentences">
                    <?= $result ? $metrics->sentences : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Paragraphs</span>
                <strong id="metric-paragraphs">
                    <?= $result ? $metrics->paragraphs : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Mentions</span>
                <strong id="metric-mentions">
                    <?= $result ? $metrics->mentions : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Hashtags</span>
                <strong id="metric-hashtags">
                    <?= $result ? $metrics->hashtags : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Links</span>
                <strong id="metric-links">
                    <?= $result ? $metrics->links : '--' ?>
                </strong>
            </div>

            <div class="studio-metric">
                <span>Emojis</span>
                <strong id="metric-emojis">
                    <?= $result ? $metrics->emojis : '--' ?>
                </strong>
            </div>

        </div>

    </div>

    <div class="card">

    <h3>
        <i class="fa-solid fa-chart-line"></i>
        <span>Writing Insights</span>
    </h3>

    <div class="studio-metric">
        <span>Reading Time</span>
        <strong id="metric-reading-time">
            <?= $result ? $metrics->readingTime . ' min' : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Average Sentence Length</span>
        <strong id="metric-average-sentence">
            <?= $result
                ? number_format($metrics->averageSentenceLength, 1) . ' words'
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Average Paragraph Length</span>
        <strong id="metric-average-paragraph">
            <?= $result
                ? number_format($metrics->averageParagraphLength, 1) . ' words'
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Questions</span>
        <strong id="metric-questions">
            <?= $result ? $metrics->questions : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Exclamations</span>
        <strong id="metric-exclamations">
            <?= $result ? $metrics->exclamations : '--' ?>
        </strong>
    </div>

</div>

<div class="card">

    <h3>
        <i class="fa-solid fa-bolt"></i>
        <span>Signals</span>
    </h3>

    <div id="studio-signals">

        <div class="studio-metric">

            <span>Quality</span>

            <strong id="signal-quality">
                <?= $result
                    ? number_format(
                        $result->score(),
                        0
                    )
                    : '--' ?>
            </strong>

        </div>

        <p
            id="signal-description"
            class="muted"
        >
            <?php if ($result): ?>

                More signal processors will appear here as the Intelligence Engine evolves.

            <?php else: ?>

                Signal breakdown will appear after analysis.

            <?php endif; ?>
        </p>

    </div>

</div>
        <div class="card">

    <h3>
        <i class="fa-solid fa-lightbulb"></i>
        <span>Writing Coach</span>
    </h3>

    <div id="studio-suggestions">

        <?php if ($result && $quality): ?>

            <?php if (!empty($quality->strengths())): ?>

                <h4 class="studio-section-title">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Strengths</span>
                </h4>

                <ul class="studio-suggestions">

                    <?php foreach ($quality->strengths() as $strength): ?>

                        <li>
                            <?= htmlspecialchars($strength) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            <?php endif; ?>

            <?php if (!empty($quality->suggestions())): ?>

                <h4 class="studio-section-title studio-section-spacing">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span>Coach</span>
                </h4>

                <ul class="studio-suggestions">

                    <?php foreach ($quality->suggestions() as $suggestion): ?>

                        <li>
                            <?= htmlspecialchars($suggestion) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            <?php endif; ?>

            <?php if (
                empty($quality->strengths())
                && empty($quality->suggestions())
            ): ?>

                <p class="muted">
                    No coaching advice is available yet.
                </p>

            <?php endif; ?>

        <?php else: ?>

            <p
                id="suggestion-empty"
                class="muted"
            >
                Analyze your writing to receive personalised coaching.
            </p>

        <?php endif; ?>

    </div>

</div>

        <div class="card">

    <h3>
        <i class="fa-solid fa-flask"></i>
        <span>Benchmarks</span>
    </h3>

    <div id="studio-benchmarks">

        <p class="muted">
            Benchmark comparison coming in the next generation of the Intelligence Lab.
        </p>

    </div>

</div>
    </aside>

</div>