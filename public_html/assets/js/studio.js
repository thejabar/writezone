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

        function renderScore(
            score
        ) {

            const element =
                document.getElementById(
                    "studio-score"
                );

            const quality =
                document.getElementById(
                    "signal-quality"
                );

            if (element) {
                element.textContent = score;
            }

            if (quality) {
                quality.textContent = score;
            }

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

                        if (
                            node &&
                            metrics[key] !== undefined
                        ) {
                            node.textContent =
                                metrics[key];
                        }

                    }
                );

        }

        /*
        |--------------------------------------------------------------------------
        | Suggestions
        |--------------------------------------------------------------------------
        */

        function renderSuggestions(quality) {

    const container =
        document.getElementById(
            "studio-suggestions"
        );

    if (!container) {
        return;
    }

    let html = "";

    if (
        quality.strengths.length
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

        html += "</ul>";

    }

    if (
        quality.suggestions.length
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

        html += "</ul>";

    }

    if (html === "") {

        html =
            '<p class="muted">No coaching advice available.</p>';

    }

    container.innerHTML = html;

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

                return;

            }

            setStatus(
                "Analyzing..."
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

                const data =
                    await response.json();

                if (
                    !data.success
                ) {

                    setStatus(
                        "Analysis failed"
                    );

                    return;

                }

                renderScore(
                    data.score
                );

                renderMetrics(
                    data.metrics
                );

                renderSuggestions(
    data.quality ?? {
        strengths: [],
        suggestions: []
    }
);

                setStatus(
                    "Intelligence Ready"
                );

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

                timer =
                    setTimeout(
                        analyze,
                        400
                    );

            }
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent form submit
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            "submit",
            function (
                event
            ) {

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

                window.location.href =
                    "/lab";

            }
        );

    }
);