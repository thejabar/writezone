document.addEventListener(
    "DOMContentLoaded",
    () => {

        console.log(
            "🧠 WriteZone Studio Ready"
        );

        const form =
            document.getElementById(
                "studio-form"
            );

        const editor =
            document.getElementById(
                "studio-editor"
            );

        const clear =
            document.getElementById(
                "studio-clear"
            );

        const status =
            document.getElementById(
                "studio-status"
            );

        if (
            !form ||
            !editor
        ) {
            return;
        }

        editor.focus();

        let timer = null;

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        function setStatus(
            message
        ) {

            if (!status) {
                return;
            }

            status.innerHTML =
                `<i class="fa-solid fa-circle"></i> ${message}`;

        }

        /*
        |--------------------------------------------------------------------------
        | Score
        |--------------------------------------------------------------------------
        */

        function renderScore(score) {

    const value =
        typeof score === "number"
            ? Math.round(score)
            : "--";

    const element =
        document.getElementById(
            "studio-score"
        );

    const quality =
        document.getElementById(
            "signal-quality"
        );

    if (element) {
        element.textContent = value;
    }

    if (quality) {
        quality.textContent = value;
    }
}

/*
|--------------------------------------------------------------------------
| Signal Description
|--------------------------------------------------------------------------
*/

function renderSignalDescription(score) {

    const element =
        document.getElementById(
            "signal-description"
        );

    if (!element) {
        return;
    }

    if (score >= 80) {

        element.textContent =
            "Excellent writing quality detected.";

    } else if (score >= 60) {

        element.textContent =
            "Good writing with room for improvement.";

    } else {

        element.textContent =
            "Improve structure and clarity for a stronger score.";

    }
}
/*

|--------------------------------------------------------------------------

| Reset Signal Description

|--------------------------------------------------------------------------

*/

function resetSignalDescription() {

    const element =

        document.getElementById(

            "signal-description"

        );

    if (!element) {

        return;

    }

    element.textContent =

        "Signal breakdown will appear after analysis.";
}

        /*
        |--------------------------------------------------------------------------
        | Metrics
        |--------------------------------------------------------------------------
        */
        function renderMetrics(
    metrics
) {

    const map = {

        characters:
            "metric-characters",

        words:
            "metric-words",

        sentences:
            "metric-sentences",

        paragraphs:
            "metric-paragraphs",

        mentions:
            "metric-mentions",

        hashtags:
            "metric-hashtags",

        links:
            "metric-links",

        emojis:
            "metric-emojis",

    };

    Object.entries(map)
        .forEach(
            ([key, id]) => {

                const node =
                    document.getElementById(
                        id
                    );

                if (!node) {
                    return;
                }

                node.textContent =
                    metrics[key] ?? "--";

            }
        );

}

/*
|--------------------------------------------------------------------------
| Writing Insights
|--------------------------------------------------------------------------
*/

function renderInsights(
    metrics
) {

    const readingTime =
        document.getElementById(
            "metric-reading-time"
        );

    if (readingTime) {

        readingTime.textContent =
            metrics.readingTime !== undefined
                ? metrics.readingTime + " min"
                : "--";

    }

    const averageSentence =
        document.getElementById(
            "metric-average-sentence"
        );

    if (averageSentence) {

        averageSentence.textContent =
            metrics.averageSentenceLength !== undefined
                ? Number(
                    metrics.averageSentenceLength
                ).toFixed(1) + " words"
                : "--";

    }

    const averageParagraph =
        document.getElementById(
            "metric-average-paragraph"
        );

    if (averageParagraph) {

        averageParagraph.textContent =
            metrics.averageParagraphLength !== undefined
                ? Number(
                    metrics.averageParagraphLength
                ).toFixed(1) + " words"
                : "--";

    }

    const questions =
        document.getElementById(
            "metric-questions"
        );

    if (questions) {

        questions.textContent =
            metrics.questions ?? "--";

    }

    const exclamations =
        document.getElementById(
            "metric-exclamations"
        );

    if (exclamations) {

        exclamations.textContent =
            metrics.exclamations ?? "--";

    }

}

/*
|--------------------------------------------------------------------------
| Suggestions
|--------------------------------------------------------------------------
*/

function renderSuggestions(
    quality
) {

    quality = quality ?? {
        strengths: [],
        suggestions: [],
    };

    const container =
        document.getElementById(
            "studio-suggestions"
        );

    if (!container) {
        return;
    }

    let html = "";

    if (
        quality.strengths.length > 0
    ) {

        html += `
            <h4 class="studio-section-title">
                <i class="fa-solid fa-circle-check"></i>
                Strengths
            </h4>

            <ul class="studio-suggestions">
        `;

        quality.strengths.forEach(
            strength => {

                html += `
                    <li>${strength}</li>
                `;

            }
        );

        html += `
            </ul>
        `;

    }

    if (
        quality.suggestions.length > 0
    ) {

        html += `
            <h4 class="studio-section-title studio-section-spacing">
                <i class="fa-solid fa-lightbulb"></i>
                Coach
            </h4>

            <ul class="studio-suggestions">
        `;

        quality.suggestions.forEach(
            suggestion => {

                html += `
                    <li>${suggestion}</li>
                `;

            }
        );

        html += `
            </ul>
        `;

    }

    if (html === "") {

        html = `
            <p class="muted">
                No coaching advice available.
            </p>
        `;

    }

    container.innerHTML = html;

}

/*
|--------------------------------------------------------------------------
| Writing Dimensions
|--------------------------------------------------------------------------
*/

function renderBreakdown(
    breakdown
) {

    breakdown = breakdown ?? {};

    const map = {

        structure:
            "dimension-structure",

        readability:
            "dimension-readability",

        vocabulary:
            "dimension-vocabulary",

    };

    Object.entries(map)
        .forEach(
            ([key, id]) => {

                const node =
                    document.getElementById(
                        id
                    );

                if (!node) {
                    return;
                }

                node.textContent =
                    breakdown[key] !== undefined
                        ? Math.round(
                            breakdown[key]
                        )
                        : "--";

            }
        );

}

/*
|--------------------------------------------------------------------------
| Sentence Intelligence
|--------------------------------------------------------------------------
*/

function renderSentences(
    sentences
) {

    sentences = sentences ?? {};

    const map = {

        total:
            "sentence-total",

        shortest:
            "sentence-shortest",

        longest:
            "sentence-longest",

        average:
            "sentence-average",

        variety:
            "sentence-variety",

    };

    Object.entries(map)
        .forEach(
            ([key, id]) => {

                const node =
                    document.getElementById(
                        id
                    );

                if (!node) {
                    return;
                }

                const value =
                    sentences[key];

                if (
                    value === undefined
                ) {

                    node.textContent =
                        "--";

                    return;

                }

                switch (key) {

                    case "shortest":

                    case "longest":

                        node.textContent =
                            value + " words";

                        break;

                    case "average":

                        node.textContent =
                            Number(value)
                                .toFixed(1)
                            + " words";

                        break;

                    default:

                        node.textContent =
                            value;

                }

            }
        );

}

/*
|--------------------------------------------------------------------------
| Analyze
|--------------------------------------------------------------------------
*/

async function analyze() {

    const content =
        editor.value.trim();

    if (
        content.length === 0
    ) {

   renderScore("--");

renderMetrics({});

renderInsights({});

renderBreakdown({});

renderSentences({});

renderSuggestions();

resetSignalDescription();

setStatus(
    "Intelligence Ready"
);
        return;

    }

    setStatus(
        "Analyzing with Intelligence..."
    );

    try {

        const response =
            await fetch(
                "/lab/analyze",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded",
                    },

                    body:
                        new URLSearchParams({
                            content,
                        }),

                }
            );

        const data = await response.json();

renderScore(data.score);

renderSignalDescription(data.score);

renderMetrics(data.metrics);

renderInsights(data.metrics);

renderBreakdown(data.quality.breakdown);

renderSentences(data.sentences);

renderSuggestions(data.quality);

setStatus("Analysis Complete");
    }

    catch (error) {

        console.error(
            error
        );

        setStatus(
            "Connection error"
        );

    }

}

/*
|--------------------------------------------------------------------------
| Debounce
|--------------------------------------------------------------------------
*/

editor.addEventListener(
    "input",
    () => {

        clearTimeout(
            timer
        );

        timer = setTimeout(
            analyze,
            400
        );

    }
);

/*
|--------------------------------------------------------------------------
| Prevent Form Submit
|--------------------------------------------------------------------------
*/

form.addEventListener(
    "submit",
    event => {

        event.preventDefault();

        analyze();

    }
);

/*
|--------------------------------------------------------------------------
| New Analysis
|--------------------------------------------------------------------------
*/

clear?.addEventListener(
    "click",
    () => {

        if (
            !confirm(
                "Start a new analysis?"
            )
        ) {
            return;
        }

        editor.value = "";

renderScore("--");

renderMetrics({});

renderInsights({});

renderBreakdown({});

renderSentences({});

renderSuggestions();

resetSignalDescription();

setStatus(
    "Intelligence Ready"
);

        editor.focus();

    }
);

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/

setStatus(
    "Intelligence Ready"
);

if (
    editor.value.trim().length > 0
) {

    analyze();

}

    }
);