// app.js
// =========================================
// IMPORTAR LIBRERÍAS EXTERNAS
// =========================================
import $ from 'jquery';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Masonry from 'masonry-layout';
import hljs from 'highlight.js';
import moment from 'moment';
import 'select2';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import JSZip from 'jszip';
import Dropzone from 'dropzone';

// IMPORTAR ESTILOS
import 'bootstrap/dist/css/bootstrap.min.css';
import 'highlight.js/styles/atom-one-light.css';
import 'select2/dist/css/select2.min.css';
import 'boxicons/css/boxicons.min.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';
import 'dropzone/dist/dropzone.css';
import '../css/seePassword.css';
import '../css/admin.css';
import '../css/partial-create-update.css';
import '../css/app.css';

// =========================================
// EXPORTAR LIBRERÍAS AL OBJETO GLOBAL (window)
// =========================================
window.$ = $;
window.jQuery = $;
window.Masonry = Masonry;
window.hljs = hljs;
window.moment = moment;
window.DataTable = DataTable;
window.JSZip = JSZip;
window.Dropzone = Dropzone;

// =========================================
// FONDO CON TEXTURA
// =========================================
const body = document.body;
body.style.setProperty('--background-texture', 'url(/images/textura-roja2.png)');
body.style.setProperty('--background-opacity', '0.15');
body.style.setProperty('--background-zindex', '-10');

body.insertAdjacentHTML('afterbegin', `
  <style>
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-image: var(--background-texture);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      opacity: var(--background-opacity);
      z-index: var(--background-zindex);
    }
  </style>
`);

// =========================================
// INICIALIZAR HIGHLIGHT.JS
// =========================================
document.querySelectorAll('pre code').forEach((block) => {
  hljs.highlightBlock(block);
});

// =========================================
// EJEMPLO DE USO DE MASONRY
// =========================================
document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('.grid');
  if (grid) {
    new Masonry(grid, {
      itemSelector: '.grid-item',
      columnWidth: '.grid-sizer',
      percentPosition: true
    });
  }
});
