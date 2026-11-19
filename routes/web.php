<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ValidacionController;
use App\Http\Controllers\ComprobacionesController;
use App\Http\Controllers\Viaticos\ViaticoController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\ReportesController;
use App\Models\Partida;
use PhpCfdi\CfdiExpresiones\Extractors\Comprobante32;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComisionCreada;

// ----------------------------------------------------------------------
// RUTAS DE INICIO DE SESIÓN, CIERRE DE SESIÓN Y RECUPERACIÓN (PÚBLICAS)
// ----------------------------------------------------------------------
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:3,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/RecuperarAcceso', [LoginController::class, 'RecuperarAcceso'])->name('RecuperarAcceso')->middleware('throttle:3,1');
Route::get('resetPassword', [ChangePasswordController::class, 'resetPassword'])->name('resetPassword');
Route::post('changePassword', [ChangePasswordController::class, 'changePassword'])->name('changePassword');
Route::middleware(['auth', 'must.change.password'])->group(function () {

    //FUNCIONES DE LOGIN Y VISTA DE ADMIN (idtype:1)
    Route::middleware(['idtype:1'])->group(function () {
        Route::get('/Dashboard-Admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        route::get('index', [LoginController::class, 'index'])->name('index');

Route::get('/beneficiarios/import-status/{id}', [DashboardAdminController::class, 'checkImportStatus'])->name('beneficiarios.import.status');
Route::post('/beneficiarios/import/process', [DashboardAdminController::class, 'import'])->name('beneficiarios.import.process');
        
        Route::get('/comprobacion/{idComision}/descargar-zip', [DashboardAdminController::class, 'downloadComisionFilesZip'])->name('downloadComisionFilesZip');
        /**********/
        Route::post('/reportes/zip', [DashboardAdminController::class, 'downloadComisionFilesZip'])->name('reportes.zip');
        // Route::get('/download-comisiones', [DashboardAdminController::class, 'downloadComisionFilesZip']);
        Route::get('comisiones/{idComision}/finalizar-comprobacion', [DashboardAdminController::class, 'comprobacionEnd'])->name('comprobacionEnd');
        /**********/
        // routes/web.php o routes/api.php
        // Route::post('/reportes/actualizar-status', [ReportesController::class, 'updateStatus'])->name('reportes.update_status');
        Route::get('/comisiones/status-por-unidad', [DashboardAdminController::class, 'getStatusPorUnidad'])->name('comisiones.statusPorUnidad');
        Route::get('/registros/export-status/{job}', [ReportesController::class, 'statusExport'])->name('registros.export_status');
        Route::get('/registros/export-download/{job}', [ReportesController::class, 'downloadExport'])->name('registros.export_download');
        Route::post('/registros/descargar', [ReportesController::class, 'descargarRegistros'])->name('registros.descargar');
        Route::post('/gastos', [ViaticoController::class, 'destroy'])->name('gastos.destroy');
        Route::get('/active/{id}', [ViaticoController::class, 'active'])->name('active');
        Route::get('/database', [ReportesController::class, 'index'])->name('database.index');
        Route::post('/database/store', [ReportesController::class, 'store'])->name('database.store');
        Route::post('/objetivos/{id}', [DashboardAdminController::class, 'update'])->name('database.update');
        Route::get('/update-factura/{id}', [ComprobacionesController::class, 'updateFactura'])->name('facturas.update');
        Route::get('objetives_list_json', [DashboardAdminController::class, 'objetives_list_json'])->name('objetives_list_json');
        Route::get('/comisiones/{idComision}/movimientos', [ViaticoController::class, 'obtenerMovimientos'])->name('comisiones.movimientos');
    });

    // TEST (idtype:1,2)
    Route::middleware(['idtype:1,2'])->group(function () {
        Route::get('/Formulario/Viatico', [ViaticoController::class, 'index'])->name('Form.index');
        Route::get('validateComition', [ViaticoController::class, 'validateComition'])->name('validateComition');
        Route::post('validateComission', [ViaticoController::class, 'validateComission'])->name('validateComission');
        Route::get('validacionForm/{idComision}', [ValidacionController::class, 'validacionForm'])->name('validacionForm');
        /**********/
        Route::get('editComision/{idComision}', [ViaticoController::class, 'editComision'])->name('editComision');
        Route::post('/download-report', [DashboardAdminController::class, 'downloadReport'])->name('downloadReport');
        Route::get('/reportes/status/{id}', [DashboardAdminController::class, 'checkReportStatus'])->name('reportes.status');
        /**********/
        Route::post('updateComision', [ViaticoController::class, 'updateComision'])->name('updateComision');
        Route::post('aprobarMasivo', [ViaticoController::class, 'aprobarMasivo'])->name('aprobarMasivo');
        Route::post('cancelarMasivo', [ViaticoController::class, 'cancelarMasivo'])->name('cancelarMasivo');
        Route::get('users', [DashboardAdminController::class, 'users'])->name('users');
        Route::post('saveUser', [DashboardAdminController::class, 'saveUser'])->name('saveUser');
        Route::get('users_list_json', [DashboardAdminController::class, 'users_list_json'])->name('users_list_json');
        Route::get('editUser/{idUser}', [DashboardAdminController::class, 'editUser'])->name('editUser');
        /**********/
        Route::post('updateUser', [DashboardAdminController::class, 'updateUser'])->name('updateUser');
        Route::get('datosEmpleado', [DashboardAdminController::class, 'datosEmpleado'])->name('datosEmpleado');
        Route::post('import-status', [FacturasController::class, 'importStatus'])->name('import_status');
        Route::get('comprobacionExcel', [ComprobacionesController::class, 'comprobacionExcel'])->name('comprobacionExcel');
        Route::post('anularFactura', [ComprobacionesController::class, 'anularFactura'])->name('anularFactura');
        Route::get('downloadComprobante/{comprobante}', [ViaticoController::class, 'downloadComprobante'])->name('downloadComprobante');
        Route::post('/reportes/sicop', [ReportesController::class, 'downloadSicopLayout'])->name('reportes.sicop');
        Route::get('layoutBancario', [ViaticoController::class, 'layoutBancario'])->name('layoutBancario');
        Route::get('procesarPago/{idComision}', [ViaticoController::class, 'procesarPago'])->name('procesarPago');
        /**********/
        Route::post('reportes', [ReportesController::class, 'reportes'])->name('reportes');
        Route::post('editarMasivo', [ViaticoController::class, 'editarMasivo'])->name('editarMasivo');
        Route::post('reporte/bancario/ampliacion', [ReportesController::class, 'reporteBancarioAmpliacion'])->name('reporteBancarioAmpliacion');
        Route::get('/descargar-layout-sicop/{idComision}', [ReportesController::class, 'downloadSicopLayout'])->name('downloadSicopLayout');
        /**********/
        Route::patch('/reintegros/{reintegro}/eliminar-comprobante', [ComprobacionesController::class, 'eliminarComprobante'])->name('reintegro.eliminarComprobante');
    });

    //COMISIONES (idtype:1,2,3)
    Route::middleware(['idtype:1,2,3'])->group(function () {
        Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('comisionView', [ViaticoController::class, 'comisionView'])->name('comisionView');
        Route::post('saveComision',  [ViaticoController::class, 'saveComision'])->name('saveComision');
        Route::get('autoEmpleados', [ViaticoController::class, 'autoEmpleados'])->name('autoEmpleados');
        Route::get('datosUnEmpleado', [ViaticoController::class, 'datosUnEmpleado'])->name('datosUnEmpleado');
        Route::get('solicitudPDF/{idComision}', [ViaticoController::class, 'solicitudPDF'])->name('solicitudPDF');
        /**********/
        Route::get('acusePDF/{idComision}', [ViaticoController::class, 'acusePDF'])->name('acusePDF');
        /**********/
        Route::get('comprobacionForm/{idComision}', [ComprobacionesController::class, 'comprobacionForm'])->name('comprobacionForm');
        /**********/
        Route::post('/xml-upload', [ComprobacionesController::class, 'processXml'])->name('xml.process');
        Route::post('/xml-process-multiple', [ComprobacionesController::class, 'extractNominaData'])->name('xml.process-multiple');
        Route::get('facturas_list_json', [ComprobacionesController::class, 'facturas_list_json'])->name('facturas_list_json');
        Route::get('facturas_reemplazo_list_json', [ComprobacionesController::class, 'facturas_reemplazo_list_json'])->name('facturas_reemplazo_list_json');

        Route::post('upload-invoice', [ComprobacionesController::class, 'upload-invoice'])->name('upload-invoice');
        Route::get('/generateSingleReasignacionPdf/{idComision}/ampliacion/{idReasignacion}/pdf', [ViaticoController::class, 'generateSingleReasignacionPdf'])->name('generateSingleReasignacionPdf');
        /**********/
        Route::get('reasignacion_list_json/{idComision}', [ViaticoController::class, 'reasignacion_list_json'])->name('reasignacion_list_json');
        /**********/
        Route::get('stream/{file}', [ComprobacionesController::class, 'stream'])->name('stream');
        Route::post('import-status', [ValidacionController::class, 'importStatus'])->name('import_status');
        Route::post('actualizarXml/{idfactura}', [ComprobacionesController::class, 'actualizarXml'])->name('actualizarXml');
        /**********/
        Route::get('downloadXml/{cfdi}', [ValidacionController::class, 'downloadXml'])->name('downloadXml');
        Route::get('comprobacionAdicional/{idFactura}', [ComprobacionesController::class, 'comprobacionAdicional'])->name('comprobacionAdicional');
        /**********/
        Route::post('saveTickets', [ComprobacionesController::class, 'saveTickets'])->name('saveTickets');
        Route::get('downloadAditional/{name_file}', [ComprobacionesController::class, 'downloadAditional'])->name('downloadAditional');
        Route::post('updateRevision/{idComision}', [ComprobacionesController::class, 'updateRevision'])->name('updateRevision');
        /**********/
        Route::get('detallesComision/{idComision}', [ViaticoController::class, 'detallesComision'])->name('detallesComision');
        /**********/
        Route::post('uploadComprobante', [ViaticoController::class, 'uploadComprobante'])->name('uploadComprobante');
        route::get('comision_list_json', [ViaticoController::class, 'comision_list_json'])->name('comision_list_json');
        route::get('formatoComprobacion/{idComision}', [ViaticoController::class, 'formatoComprobacion'])->name('formatoComprobacion');
        /**********/
        route::get('downloadConstancia', [DashboardController::class, 'downloadConstancia'])->name('downloadConstancia');
        Route::get('lineamientos', [DashboardController::class, 'lineamientos'])->name('lineamientos');
        Route::get('/api/partidas/{id}', function ($id) {
            $pasaje = Partida::where('idTypeComision', $id)
                ->where('tipoPartida', 'PASAJE')
                ->get(['partida']);

            $viatico = Partida::where('idTypeComision', $id)
                ->where('tipoPartida', 'VIATICO')
                ->get(['partida']);

            return response()->json([
                'pasaje' => $pasaje,
                'viatico' => $viatico
            ]);
        });
        Route::post('/facturas/{idfactura}/clasificar', [ComprobacionesController::class, 'clasificarFactura'])->name('facturas.clasificarFactura');
        /**********/
        Route::get('updateComprobacion/{idComision}', [ComprobacionesController::class, 'updateComprobacion'])->name('updateComprobacion');
        /**********/
        Route::get('gastosSinComprobar/{idComision}', [ViaticoController::class, 'gastosSinComprobar'])->name('gastosSinComprobar');
        /**********/
        Route::post('savePays', [ComprobacionesController::class, 'savePays'])->name('savePays');
        Route::get('generarGastosSinComprobantePDF/{idComision}', [ViaticoController::class, 'generarGastosSinComprobantePDF'])->name('generarGastosSinComprobantePDF');
        /**********/
        Route::get('/send-test-mail', function () {
            // ...
        });
        Route::get('/comprobacion/{idComision}/descargar-zip', [DashboardAdminController::class, 'downloadComisionFilesZip'])->name('downloadComisionFilesZip');
        /**********/
        Route::post('/reportes/zip', [DashboardAdminController::class, 'downloadComisionFilesZip'])->name('reportes.zip');
        Route::get('/descargar-ticket/{id}', [ViaticoController::class, 'downloadTicket'])->name('tickets.download');
        /**********/

        Route::get('/eliminar-factura/{id}', [ComprobacionesController::class, 'deleteFactura'])->name('facturas.delete');
        Route::post('calcularReintegro/{id}', [ComprobacionesController::class, 'calcularReintegro'])->name('calcularReintegro');
    });
});
