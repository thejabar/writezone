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
$grammar = $result?->grammar();
$sentenceReport = $result
    ? $result->sentences()
    : null;
$executiveSummary = $result
    ? $result->executiveSummary()
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
        <i class="fa-solid fa-brain"></i>
        <span>Executive Summary</span>
    </h3>

    <div id="executive-summary">

    <h4
    id="summary-title"
    class="studio-summary-title">

            <?= $executiveSummary
                ? htmlspecialchars(
                    $executiveSummary->title
                )
                : 'Waiting for analysis...' ?>

    </h4>

    <p
    id="summary-text"
    class="studio-summary-text">

            <?= $executiveSummary
                ? htmlspecialchars(
                    $executiveSummary->summary
                )
                : 'The Intelligence Engine will summarize your writing after analysis.' ?>

    </p>

    <p
    id="summary-assessment"
    class="studio-summary-assessment muted">

            <?= $executiveSummary
                ? htmlspecialchars(
                    $executiveSummary->overallAssessment
                )
                : '' ?>

        </p>

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
        <i class="fa-solid fa-spell-check"></i>
        <span>Grammar Intelligence</span>
    </h3>

    <div class="studio-metric">
        <span>Grammar Score</span>
        <strong id="grammar-score">
            <?= isset($grammar)
                ? number_format($grammar->score, 0)
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Capitalized Sentences</span>
        <strong id="grammar-capitalized">
            <?= isset($grammar)
                ? $grammar->capitalizedSentences
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Sentence Endings</span>
        <strong id="grammar-endings">
            <?= isset($grammar)
                ? $grammar->sentenceEndings
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Double Spaces</span>
        <strong id="grammar-double-spaces">
            <?= isset($grammar)
                ? $grammar->doubleSpaces
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Repeated Punctuation</span>
        <strong id="grammar-repeated">
            <?= isset($grammar)
                ? $grammar->repeatedPunctuation
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Commas</span>
        <strong id="grammar-commas">
            <?= isset($grammar)
                ? $grammar->commas
                : '--' ?>
        </strong>
    </div>
    
    <div class="studio-metric">
    <span>Semicolons</span>

    <strong id="grammar-semicolons">
        <?= isset($grammar)
            ? $grammar->semicolons
            : '--' ?>
    </strong>
</div>

<div class="studio-metric">
    <span>Colons</span>

    <strong id="grammar-colons">
        <?= isset($grammar)
            ? $grammar->colons
            : '--' ?>
    </strong>
</div>

<div class="studio-metric">
    <span>Quotation Marks</span>

    <strong id="grammar-quotes">
        <?= isset($grammar)
            ? $grammar->quotationMarks
            : '--' ?>
    </strong>
</div>

<div class="studio-metric">
    <span>Parentheses</span>

    <strong id="grammar-parentheses">
        <?= isset($grammar)
            ? $grammar->parentheses
            : '--' ?>
    </strong>
</div>

</div>

<div class="studio-section">

    <h4 class="studio-section-title">
        <i class="fa-solid fa-comments"></i>
        <span>Grammar Coach</span>
    </h4>

    <div id="grammar-feedback">

        <p class="muted">
            Grammar feedback will appear after analysis.
        </p>

    </div>
    
    <div class="studio-section">

    <h4 class="studio-section-title">
        <i class="fa-solid fa-stethoscope"></i>
        <span>Grammar Diagnostics</span>
    </h4>

    <div id="grammar-diagnostics">

        <p class="muted">
            Grammar diagnostics will appear after analysis.
        </p>

    </div>

</div>



</div>

<div class="card">

    <h3>
        <i class="fa-solid fa-book-open-reader"></i>
        <span>Readability Intelligence</span>
    </h3>

    <div class="studio-metric">
        <span>Readability Score</span>

        <strong id="readability-score">
            <?= $result
                ? number_format(
                    $result->readability()->score,
                    0
                )
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Reading Flow</span>

        <strong id="readability-flow">
            <?= $result
                ? $result->readability()->readingFlow
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Reading Difficulty</span>

        <strong id="readability-difficulty">
            <?= $result
                ? $result->readability()->difficulty
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Paragraph Balance</span>

        <strong id="readability-paragraphs">
            <?= $result
                ? $result->readability()->paragraphBalance
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Short Sentences</span>

        <strong id="readability-short">
            <?= $result
                ? $result->readability()->shortSentences
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Long Sentences</span>

        <strong id="readability-long">
            <?= $result
                ? $result->readability()->longSentences
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Average Sentence Length</span>

        <strong id="readability-average-sentence">
            <?= $result
                ? number_format(
                    $result->readability()->averageSentenceLength,
                    1
                ) . ' words'
                : '--' ?>
        </strong>
    </div>

    <div class="studio-metric">
        <span>Average Paragraph Length</span>

        <strong id="readability-average-paragraph">
            <?= $result
                ? number_format(
                    $result->readability()->averageParagraphLength,
                    1
                ) . ' words'
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