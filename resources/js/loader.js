import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// resources/js/loader.js

document.addEventListener('DOMContentLoaded', function () {
    function showLoader() {
        const loader = document.getElementById("loader");
        if (loader) loader.style.display = "flex";
    }

    function hideLoader() {
        const loader = document.getElementById("loader");
        if (loader) loader.style.display = "none";
    }

    // Aplica a todos los botones con clase "btn-load"
    document.querySelectorAll(".btn-load").forEach(button => {
        button.addEventListener("click", function () {
            showLoader();
            // Puedes quitar este timeout si la carga es redirección real
            setTimeout(hideLoader, 30000);
        });
    });

    // Opcional: exponerlo globalmente si quieres usarlo manualmente
    window.showLoader = showLoader;
});
