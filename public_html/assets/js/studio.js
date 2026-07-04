document.addEventListener(
    "DOMContentLoaded",
    () => {

        console.log(
            "🧠 WriteZone Studio Ready"
        );

        const editor =
            document.getElementById(
                "studio-editor"
            );

        if (!editor) {
            return;
        }

        editor.focus();

    }
);