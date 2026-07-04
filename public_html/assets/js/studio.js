document.addEventListener(
    "DOMContentLoaded",
    () => {

        console.log(
            "🧠 WriteZone Studio Ready"
        );

        const editor = document.getElementById(
            "studio-editor"
        );

        if (!editor) {
            return;
        }

        editor.focus();

        const clear = document.getElementById(
            "studio-clear"
        );

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

                window.location.href = "/lab";

            }
        );

    }
);