<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DireccionesSeeder extends Seeder
{
    public function run(): void
    {
        $direcciones = [
            ['no_direccion' => '100', 'nombre_ur' => 'Oficina de la C. Secretaria de Seguridad y Protección Ciudadana'],
            ['no_direccion' => '111', 'nombre_ur' => 'Dirección General de Comunicación Social'],
            ['no_direccion' => '120', 'nombre_ur' => 'Unidad de Análisis Estratégicos y Vinculación Interinstitucional'],
            ['no_direccion' => '121', 'nombre_ur' => 'Dirección General de Análisis Criminal'],
            ['no_direccion' => '122', 'nombre_ur' => 'Dirección General de Política Criminal'],
            ['no_direccion' => '123', 'nombre_ur' => 'Dirección General de Vinculación Interinstitucional'],
            ['no_direccion' => '124', 'nombre_ur' => 'Dirección General de Coordinación Estratégica'],
            ['no_direccion' => '130', 'nombre_ur' => 'Unidad General de Asuntos Jurídicos y Transparencia'],
            ['no_direccion' => '131', 'nombre_ur' => 'Dirección General de lo Consultivo y Derechos Humanos'],
            ['no_direccion' => '132', 'nombre_ur' => 'Dirección General de lo Contencioso y Procedimientos Constitucionales'],
            ['no_direccion' => '133', 'nombre_ur' => 'Dirección General de Transparencia y Archivos'],
            ['no_direccion' => '140', 'nombre_ur' => 'Unidad de Administración y Finanzas'],
            ['no_direccion' => '141', 'nombre_ur' => 'Dirección General de Recursos Humanos'],
            ['no_direccion' => '142', 'nombre_ur' => 'Dirección General de Recursos Materiales, Servicios y Obra Pública'],
            ['no_direccion' => '143', 'nombre_ur' => 'Dirección General de Programación y Presupuesto'],
            ['no_direccion' => '200', 'nombre_ur' => 'Subsecretaría de Seguridad Pública'],
            ['no_direccion' => '210', 'nombre_ur' => 'Unidad de Información, Infraestructura Informática y Vinculación Tecnológica'],
            ['no_direccion' => '211', 'nombre_ur' => 'Centro Nacional de Información Plataforma México'],
            ['no_direccion' => '212', 'nombre_ur' => 'Dirección General de Gestión de Servicios, Ciberseguridad y Desarrollo Tecnológico'],
            ['no_direccion' => '220', 'nombre_ur' => 'Unidad de Política Policial, Penitenciaria y Seguridad Privada'],
            ['no_direccion' => '221', 'nombre_ur' => 'Dirección General de Política y Desarrollo Penitenciario'],
            ['no_direccion' => '222', 'nombre_ur' => 'Dirección General de Política y Desarrollo Policial'],
            ['no_direccion' => '223', 'nombre_ur' => 'Dirección General de Seguridad Privada'],
            ['no_direccion' => '231', 'nombre_ur' => 'Secretaría Técnica de las Conferencias Nacionales de Secretarios de Seguridad Pública y del Sistema Penitenciario'],
            ['no_direccion' => '150', 'nombre_ur' => 'Unidad de Prevención de la Violencia y el Delito'],
            ['no_direccion' => '151', 'nombre_ur' => 'Dirección General de Planeación Estratégica para la Prevención'],
            ['no_direccion' => '152', 'nombre_ur' => 'Dirección General de Implementación y Evaluación de Políticas para la Prevención'],
            ['no_direccion' => '160', 'nombre_ur' => 'Coordinación Nacional de Protección Civil'],
            ['no_direccion' => '161', 'nombre_ur' => 'Dirección General de Protección Civil'],
            ['no_direccion' => '162', 'nombre_ur' => 'Dirección General para la Gestión de Riesgos'],
            ['no_direccion' => '163', 'nombre_ur' => 'Dirección General de Vinculación, Innovación y Normativa en Protección Civil'],
            ['no_direccion' => '170', 'nombre_ur' => 'Unidad de Planeación y Evaluación Institucional'],
            ['no_direccion' => '171', 'nombre_ur' => 'Dirección General de Planeación y Prospectiva'],
            ['no_direccion' => '172', 'nombre_ur' => 'Dirección General de Integración y Evaluación Institucional'],
            ['no_direccion' => '180', 'nombre_ur' => 'Unidad de Políticas y Estrategias para la Construcción de Paz con Entidades Federativas y Regiones'],
            ['no_direccion' => '181', 'nombre_ur' => 'Dirección General de Coordinación con Entidades Federativas Zona 1'],
            ['no_direccion' => '182', 'nombre_ur' => 'Dirección General de Coordinación con Entidades Federativas Zona 2'],
            ['no_direccion' => 'C00', 'nombre_ur' => 'Coordinación Nacional Antisecuestro y Delitos de Alto Impacto (CONASE)'],
            ['no_direccion' => 'E00', 'nombre_ur' => 'CENAPRED (Centro Nacional de Prevención de Desastres)'],
        ];

        DB::table('direcciones')->insert($direcciones);
    }
}
