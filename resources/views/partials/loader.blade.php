<div class="loader-container" id="loader">
    <div class="circle-spinner"></div>
</div>

<style>
    /* Loader cubre toda la pantalla */
    .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.7); /* Fondo oscuro semi-transparente */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none; /* Oculto por defecto */
    }

    /* Spinner circular */
    .circle-spinner {
        width: 80px;
        height: 80px;
        border: 8px solid #f3e6d3; /* Color de fondo */
        border-top: 8px solid #c8a47e; /* Color café clarito */
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
