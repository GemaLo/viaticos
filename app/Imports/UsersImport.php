<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Jobs\SendWelcomeEmail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

// use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToModel, WithHeadingRow
{
    public function __construct()
    {
        // dd("UsersImport.php se ejecutó correctamente. si llegue ");
        config(['database.default' => 'oracle_primary']);
    }


    public function model(array $row)
    {

        //  Log::info("Filas detectadas en UsersImport.php:", $rows->toArray());
        // dd("Filas detectadas en UsersImport.php:", $row);
        // dd("Importando usuario: ", $rows);



        // Obtener el número de empleado desde el Excel
        $numEmpleado = $row['num_empleado'];
        $email = $row['email'];
        // dd($email->all());


        // Buscar datos en la base de datos `oracle_secondary`
        $empleado = DB::connection(env('DB_ORACLE_CONNECTION', 'oracle_secondary'))->selectOne("
            SELECT NOMBRE_COMPLETO AS nombre, CURP, CLAVE_UR AS seccion, NUM_EMPLEADO
            FROM SERTEC.SSPC_EMP_ACTIVOS_RBOS
            WHERE NUM_EMPLEADO = ?
        ", [$numEmpleado]);


        // Guardar el archivo JSON en la misma carpeta del controlador


        // Log::info("dondeme quedo:", $empleado);
        // return "holamundo";


        // Si no se encuentra, se omite el usuario
        if (!$empleado) {
            return null;
        }

        return DB::transaction(function () use ($empleado, $email) {
            // Generar nueva contraseña aleatoria
            $nuevaContrasena = Str::random(10);

            // Insertar usuario en la base de datos
            // $user =  DB::connection('oracle_primary')->table('users')->insert([
                User::create([
                'nombre' => $empleado->nombre,
                'email' => $email,
                'idtype' => 'user', // Todos tendrán el rol "user"
                'seccion' => $empleado->seccion,
                'curp' => $empleado->curp,
                'num_empleado' => $empleado->num_empleado,
                'password' => Hash::make($nuevaContrasena),
                'resetPass' => 1,
            ]);

            // Enviar correo en background
            dispatch(new SendWelcomeEmail($email, $nuevaContrasena));


// >onConnection('oracle_primary'))
        });
    }
}
