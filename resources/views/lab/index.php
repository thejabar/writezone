<h1 class="page-title">
    <i class="fa-solid fa-brain"></i>
    WriteZone Studio
</h1>

<p class="page-subtitle">
    Analyze writing exactly as the Intelligence Engine sees it.
</p>

<div class="studio-layout">

    <section class="studio-editor card">

        <h2>
            <i class="fa-solid fa-pen-nib"></i>
            Writing Workspace
        </h2>

        <form method="post">

            <textarea
                id="studio-editor"
                class="studio-textarea"
                name="content"
                rows="16"
                placeholder="Start writing...

The Intelligence Engine will analyze your writing, measure its structure, evaluate its quality, and explain every decision."
            ><?= htmlspecialchars($content ?? '') ?></textarea>

            <div class="studio-toolbar">

                <button
                    class="btn btn-primary"
                    type="submit"
                >
                    <i class="fa-solid fa-brain"></i>
                    Analyze
                </button>

                <span
                    id="studio-status"
                    class="studio-status"
                >
                    <i class="fa-solid fa-circle"></i>
                    Intelligence Ready
                </span>

            </div>

        </form>

    </section>

    <aside class="studio-sidebar">

        <div class="card">

            <h3>
                <i class="fa-solid fa-gauge-high"></i>
                Overall Score
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
                <i class="fa-solid fa-chart-column"></i>
                Metrics
            </h3>

            <div id="studio-metrics">

                <?php if ($result): ?>

                    <?php $metrics = $result->metrics(); ?>

                    <div class="studio-metric">
                        <span>Characters</span>
                        <strong><?= $metrics->characters ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Words</span>
                        <strong><?= $metrics->words ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Sentences</span>
                        <strong><?= $metrics->sentences ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Paragraphs</span>
                        <strong><?= $metrics->paragraphs ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Mentions</span>
                        <strong><?= $metrics->mentions ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Hashtags</span>
                        <strong><?= $metrics->hashtags ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Links</span>
                        <strong><?= $metrics->links ?></strong>
                    </div>

                    <div class="studio-metric">
                        <span>Emojis</span>
                        <strong><?= $metrics->emojis ?></strong>
                    </div>

                <?php else: ?>

                    <p class="muted">
                        Analyze some writing to see live metrics.
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <div class="card">

            <h3>
                <i class="fa-solid fa-bolt"></i>
                Signals
            </h3>

            <div id="studio-signals">

                <?php if ($result): ?>

                    <div class="studio-metric">
                        <span>Quality</span>
                        <strong><?= number_format($result->score(), 0) ?></strong>
                    </div>

                    <p class="muted">
                        More signal processors will appear here as the Intelligence Engine evolves.
                    </p>

                <?php else: ?>

                    <p class="muted">
                        Signal breakdown will appear after analysis.
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <div class="card">

            <h3>
                <i class="fa-solid fa-lightbulb"></i>
                Suggestions
            </h3>

            <div id="studio-suggestions">

                <?php if ($result): ?>

                    <ul class="studio-suggestions">

                        <li>
                            The Intelligence Engine successfully analyzed your writing.
                        </li>

                        <li>
                            Additional suggestions will appear as new evaluators are introduced.
                        </li>

                    </ul>

                <?php else: ?>

                    <p class="muted">
                        Suggestions will appear after analysis.
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <div class="card">

            <h3>
                <i class="fa-solid fa-flask"></i>
                Benchmarks
            </h3>

            <div id="studio-benchmarks">

                <p class="muted">
                    Benchmark comparison coming in the next generation of the Intelligence Lab.
                </p>

            </div>

        </div>

    </aside>

</div>