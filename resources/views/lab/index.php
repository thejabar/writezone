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
                        Analyze
                    </button>

                    <button
                        id="studio-clear"
                        class="btn"
                        type="button"
                    >
                        <i class="fa-solid fa-file-circle-plus"></i>
                        New Analysis
                    </button>

                </div>

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

            <?php if ($result): ?>

                <?php $metrics = $result->metrics(); ?>

            <?php endif; ?>

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
                <i class="fa-solid fa-bolt"></i>
                Signals
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
                Suggestions
            </h3>

            <div id="studio-suggestions">

                <?php if ($result): ?>

                    <ul
                        id="suggestion-list"
                        class="studio-suggestions"
                    >

                        <li>
                            The Intelligence Engine successfully analyzed your writing.
                        </li>

                        <li>
                            Additional suggestions will appear as new evaluators are introduced.
                        </li>

                    </ul>

                <?php else: ?>

                    <p
                        id="suggestion-empty"
                        class="muted"
                    >
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