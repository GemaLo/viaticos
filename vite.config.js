import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import html from '@rollup/plugin-html';
import { glob } from 'glob';


const isProduction = process.env.NODE_ENV === 'production';
const devHost = 'localhost';

// Helper para buscar archivos
function GetFilesArray(query) {
  return glob.sync(query);
}

// Archivos JS
const pageJsFiles = GetFilesArray('resources/assets/js/*.js');
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js');
const libsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

// Archivos SCSS y CSS
const coreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');
const libsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const libsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');
const fontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/!(_)*.scss');

export default defineConfig({
  server: {
    host: '0.0.0.0',
    https: false
  },
  base: '/build',
  plugins: [
    vue(),
    laravel({
      input: [
        // CSS/JS base
        'resources/assets/vendor/scss/core.scss',
        'resources/assets/vendor/scss/theme-default.scss',
        'resources/css/app.css',
        'resources/css/seePassword.css',
        'resources/css/admin.css',
        'resources/css/partial-create-update.css',
        'resources/js/app.js',
        'resources/js/seePassword.js',
        'resources/js/loader.js',
        'resources/js/inputDate.js',
        'resources/js/admin_toggleButton.js',
        'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
        'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
        'resources/assets/vendor/libs/apex-charts/apexcharts.js',
        'resources/assets/js/dashboards-analytics.js',
        'resources/assets/css/demo.css',
        'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss',
        // Incluidos automáticamente
        ...pageJsFiles,
        ...vendorJsFiles,
        ...libsJsFiles,
        ...coreScssFiles,
        ...libsScssFiles,
        ...libsCssFiles,
        ...fontsScssFiles
      ],
      refresh: true,
    }),
    html(),
  ],
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: 'manifest.json',
    minify: 'esbuild',
    sourcemap: false,
    rollupOptions: {
      input: {
        app: 'resources/js/app.js',
        seePassword: 'resources/js/seePassword.js',
        loader: 'resources/js/loader.js',
        inputDate: 'resources/js/inputDate.js',
        admin_toggleButtonjs: 'resources/js/admin_toggleButton.js',
        appStyle: 'resources/css/app.css',
        adminStyle: 'resources/css/admin.css',
        seePasswordStyle: 'resources/css/seePassword.css',
        partialsStyle: 'resources/css/partial-create-update.css',
        apexCharts: 'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
        apexlib: 'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
        apexchatsJs: 'resources/assets/vendor/libs/apex-charts/apexcharts.js',
        analityz: 'resources/assets/js/dashboards-analytics.js',
        // validateComission: 'resources/assets/js/validateComission.js',
        core: 'resources/assets/vendor/scss/core.scss',
        theme: 'resources/assets/vendor/scss/theme-default.scss',
        demo: 'resources/assets/css/demo.css',
        scroll: 'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss',
      },
      output: {
        entryFileNames: 'assets/[name].[hash].js',
        chunkFileNames: 'assets/[name].[hash].js',
        assetFileNames: 'assets/[name].[hash].[ext]',
      },
    },
  },
});
