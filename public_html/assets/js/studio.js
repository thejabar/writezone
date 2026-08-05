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
    `<i class="fa-solid fa-circle"></i><span>${message}</span>`;
        }
        
        /*
|--------------------------------------------------------------------------
| Text Helpers
|--------------------------------------------------------------------------
*/

function pluralize(
    value,
    singular,
    plural,
    decimals = null
) {

    const number =
        Number(value);

    if (
        Number.isNaN(number)
    ) {
        return "--";
    }

    const display =
    decimals === null
        ? number
        : Number(number.toFixed(decimals));
    return `${display} ${
        number === 1
            ? singular
            : plural
    }`;

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
| Executive Summary
|--------------------------------------------------------------------------
*/

function renderExecutiveSummary(
    summary
) {

    summary = summary ?? {};

    const title =
        document.getElementById(
            "summary-title"
        );

    const text =
        document.getElementById(
            "summary-text"
        );

    const assessment =
        document.getElementById(
            "summary-assessment"
        );

    if (title) {

        title.textContent =
            summary.title ??
            "Waiting for analysis...";

    }

    if (text) {

        text.textContent =
            summary.summary ??
            "The Intelligence Engine will summarize your writing after analysis.";

    }

    if (assessment) {

        assessment.textContent =
            summary.overallAssessment ??
            "";

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
            ? pluralize(
                metrics.readingTime,
                "minute",
                "minutes"
            )
            : "--";

}

    const averageSentence =
        document.getElementById(
            "metric-average-sentence"
        );

    if (averageSentence) {

    averageSentence.textContent =
        metrics.averageSentenceLength !== undefined
            ? pluralize(
                metrics.averageSentenceLength,
                "word",
                "words",
                1
            )
            : "--";

}

    const averageParagraph =
        document.getElementById(
            "metric-average-paragraph"
        );

    if (averageParagraph) {

    averageParagraph.textContent =
        metrics.averageParagraphLength !== undefined
            ? pluralize(
                metrics.averageParagraphLength,
                "word",
                "words",
                1
            )
            : "--";

}

    const questions =
        document.getElementById(
            "metric-questions"
        );

    if (questions) {

        questions.textContent =
    metrics.questions !== undefined
        ? pluralize(
            metrics.questions,
            "question",
            "questions"
        )
        : "--";
    }

    const exclamations =
        document.getElementById(
            "metric-exclamations"
        );

    if (exclamations) {

        exclamations.textContent =
    metrics.exclamations !== undefined
        ? pluralize(
            metrics.exclamations,
            "exclamation",
            "exclamations"
        )
        : "--";
    }

}

/*
|--------------------------------------------------------------------------
| Vocabulary Intelligence
|--------------------------------------------------------------------------
*/

function renderVocabulary(vocabulary) {

    vocabulary = vocabulary ?? {};

    const map = {

        totalWords:
            "vocabulary-total",

        uniqueWords:
            "vocabulary-unique",

        repeatedWords:
            "vocabulary-repeated",

        lexicalDiversity:
            "vocabulary-diversity",

        fillerWords:
            "vocabulary-filler",

        transitionWords:
            "vocabulary-transition",

    };

    Object.entries(map).forEach(
        ([key, id]) => {

            const node =
                document.getElementById(id);

            if (!node) {
                return;
            }

            let value = vocabulary[key];

            if (
                key === "lexicalDiversity" &&
                value !== undefined
            ) {

                value =
                    Number(value).toFixed(1) + "%";

            }

            node.textContent =
                value ?? "--";

        }
    );

}

/*
|--------------------------------------------------------------------------
| Grammar Intelligence
|--------------------------------------------------------------------------
*/

function renderGrammar(
    grammar
) {

    grammar = grammar ?? {};

    const map = {

    score:
        "grammar-score",

    capitalizedSentences:
        "grammar-capitalized",

    sentenceEndings:
        "grammar-endings",

    doubleSpaces:
        "grammar-double-spaces",

    repeatedPunctuation:
        "grammar-repeated",

    commas:
        "grammar-commas",

    semicolons:
        "grammar-semicolons",

    colons:
        "grammar-colons",

    quotationMarks:
        "grammar-quotes",

    parentheses:
        "grammar-parentheses",

};

    Object.entries(map)
        .forEach(
            ([key, id]) => {

                const node =
                    document.getElementById(id);

                if (!node) {
                    return;
                }

                const value =
                    grammar[key];

                node.textContent =
                    value ?? "--";

            }
        );

}

/*
|--------------------------------------------------------------------------
| Readability Intelligence
|--------------------------------------------------------------------------
*/

function renderReadability(
    readability
) {

    readability = readability ?? {};

    const map = {

        score:
            "readability-score",

        shortSentences:
            "readability-short",

        longSentences:
            "readability-long",

        averageSentenceLength:
            "readability-average-sentence",

        averageParagraphLength:
            "readability-average-paragraph",

        readingFlow:
            "readability-flow",

        difficulty:
            "readability-difficulty",

        paragraphBalance:
            "readability-paragraphs",

    };

    Object.entries(map)
        .forEach(
            ([key, id]) => {

                const node =
                    document.getElementById(id);

                if (!node) {
                    return;
                }

                let value =
                    readability[key];

                switch (key) {

                    case "averageSentenceLength":

                    case "averageParagraphLength":

                        value =
                            value !== undefined
                                ? `${Number(value).toFixed(1)} words`
                                : "--";

                        break;

                    default:

                        value =
                            value ?? "--";

                }

                node.textContent = value;

            }
        );

}

/*
|--------------------------------------------------------------------------
| Grammar Feedback
|--------------------------------------------------------------------------
*/

function renderGrammarFeedback(
    feedback
) {

    feedback = feedback ?? {
        strengths: [],
        warnings: [],
        suggestions: [],
    };

    const container =
        document.getElementById(
            "grammar-feedback"
        );

    if (!container) {
        return;
    }

    let html = "";

    if (feedback.strengths.length > 0) {

        html += `
            <h5>
                <i class="fa-solid fa-circle-check"></i>
                Strengths
            </h5>

            <ul class="studio-suggestions">
        `;

        feedback.strengths.forEach(
            item => {

                html += `
                    <li>${item}</li>
                `;

            }
        );

        html += `
            </ul>
        `;
    }

    if (feedback.warnings.length > 0) {

        html += `
            <h5>
                <i class="fa-solid fa-triangle-exclamation"></i>
                Warnings
            </h5>

            <ul class="studio-suggestions">
        `;

        feedback.warnings.forEach(
            item => {

                html += `
                    <li>${item}</li>
                `;

            }
        );

        html += `
            </ul>
        `;
    }

    if (feedback.suggestions.length > 0) {

        html += `
            <h5>
                <i class="fa-solid fa-lightbulb"></i>
                Suggestions
            </h5>

            <ul class="studio-suggestions">
        `;

        feedback.suggestions.forEach(
            item => {

                html += `
                    <li>${item}</li>
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
                No grammar feedback available.
            </p>
        `;
    }

    container.innerHTML = html;
}

/*
|--------------------------------------------------------------------------
| Grammar Diagnostics
|--------------------------------------------------------------------------
*/

function renderGrammarDiagnostics(
    diagnostics
) {

    diagnostics = diagnostics ?? {};

    const container =
        document.getElementById(
            "grammar-diagnostics"
        );

    if (!container) {
        return;
    }

    const messages = [];

    messages.push(
        diagnostics.capitalizationConsistent
            ? "✓ Sentence capitalization is consistent."
            : "⚠ Sentence capitalization may be inconsistent."
    );

    messages.push(
        diagnostics.sentenceEndingsConsistent
            ? "✓ Sentence endings are properly detected."
            : "⚠ Sentence endings require attention."
    );

    messages.push(
        diagnostics.balancedQuotationMarks
            ? "✓ Quotation marks are balanced."
            : "⚠ Unbalanced quotation marks detected."
    );

    messages.push(
        diagnostics.balancedParentheses
            ? "✓ Parentheses are balanced."
            : "⚠ Unbalanced parentheses detected."
    );

    messages.push(
        diagnostics.doubleSpacesDetected
            ? "⚠ Double spaces detected."
            : "✓ No unnecessary double spaces detected."
    );

    messages.push(
        diagnostics.repeatedPunctuationDetected
            ? "⚠ Repeated punctuation detected."
            : "✓ No repeated punctuation detected."
    );

    messages.push(
        diagnostics.heavyCommaUsage
            ? "⚠ Heavy comma usage detected."
            : "✓ Comma usage appears balanced."
    );

    messages.push(
        diagnostics.longSentencesDetected
            ? "⚠ Long sentences detected."
            : "✓ Sentence length appears balanced."
    );

    container.innerHTML = `
        <ul class="studio-suggestions">
            ${messages
                .map(
                    message =>
                        `<li>${message}</li>`
                )
                .join("")}
        </ul>
    `;
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
<span>Strengths</span>
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
<span>Coach</span>
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
            pluralize(
                value,
                "word",
                "words"
            );

        break;

    case "average":

        node.textContent =
            pluralize(
                value,
                "word",
                "words",
                1
            );

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

renderVocabulary({});

renderGrammar({});

renderReadability({});

renderGrammarFeedback();

renderGrammarDiagnostics();

renderExecutiveSummary();

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

if (!data.success) {

    setStatus(

        data.message ??

        "Analysis failed."

    );

    return;

}

renderScore(data.score);

renderSignalDescription(data.score);

renderExecutiveSummary(data.executiveSummary);

renderMetrics(data.metrics);

renderInsights(data.metrics);

renderBreakdown(data.quality.breakdown);

renderSentences(data.sentences);

renderSuggestions(data.quality);

renderVocabulary(data.vocabulary);

renderGrammar(data.grammar);

renderReadability(data.readability);

renderGrammarFeedback(data.grammarFeedback);

renderGrammarDiagnostics(data.grammarDiagnostics);

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

renderVocabulary({});

renderGrammar({});

renderReadability({});

renderGrammarFeedback();

renderGrammarDiagnostics();

renderExecutiveSummary();

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