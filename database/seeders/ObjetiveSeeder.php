<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjetiveSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('objetives')->insert([
            ['objetive' => 'Seguimiento al primer Eje de la Estrategia Nacional de Seguridad “Atención a las Causas”.', 'ur' => json_encode(['150', '151', '152'])],
            ['objetive' => 'Seguimiento en la implementación de la Estrategia “Reconecta con la Paz”.', 'ur' => json_encode(['150', '151', '152'])],
            ['objetive' => 'Reunión de seguimiento con los familiares de los mineros atrapados en la mina “Pasta de Conchos”.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Reunión de coordinación de acciones en materia de prevención y preparación ante los diferentes riesgos a los que se encuentra sujeto el territorio nacional.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Asistir a la Misión de Enlace y Coordinación Operativa (ECO), para brindar apoyo a la población en situación de emergencia o desastre.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Reunión con gobernadores de estado, para presentar las estrategias del Gobierno de México para la reducción de los principales riesgos, generando acciones conjuntas en beneficio de su población.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Impartición de curso-taller sobre la elaboración del Plan de Continuidad de Operaciones (PCO), garantizando seguridad, inclusión y respuesta efectiva ante emergencias o desastres.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Capacitación y evaluación sobre las Normas Oficiales Mexicanas y Evaluación de la Conformidad correspondiente.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Realizar vuelos aéreos no tripulados (DRON) para diagnosticar los riesgos previsibles en el marco de gestión integral de riesgo.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Talleres los integrantes del SINAPROC para desarrollar conocimientos y capacidades en temas de Protección Civil.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Realizar evaluación de daños y análisis de necesidades derivado de un fenómeno perturbador (Natural y Antropogénico).', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Acciones de prevención y preparación ante incidentes derivado del uso, elaboración, transporte y comercialización de artificios pirotécnicos.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Impartición de cursos en materia de Protección Civil e Instrumentos Financieros de Gestión de Riesgos.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Visitas para la detección de necesidades y presentación de protocolo de Refugios Temporales.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Talleres para la promoción de fondos estatales para la Gestión de Riesgos.', 'ur' => json_encode(['160', '161', '162', '163'])],
            ['objetive' => 'Acciones de apoyo en entidades federativas, en coordinación con la Fiscalía General de la República.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Verificar empresas de seguridad privada, en términos del artículo 37 de la Ley Federal de Seguridad Privada.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Acciones de apoyo en entidades federativas, en coordinación con la Fiscalía Especializada en Investigación de Terrorismo, Acopio y Tráfico de Armas.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Auxiliar a la Fiscalía Especializada en la Investigación del Delito de Delincuencia Organizada mediante investigación de campo, para corroborar hechos constitutivos de delitos señalados en la carpeta de investigación en entidades federativas.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Acciones de apoyo en las entidades federativas en coordinación con la Fiscalía General de la República, así como labores de inspección e investigación de campo, vigilancia y seguimiento en atención a mandamientos.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Acciones de apoyo en entidades federativas, en atención a carpetas de investigación.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Coadyuvar con las autoridades competentes para la realización de diversos actos de seguridad, cobertura táctica-operativa y tareas de investigación de campo.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Coadyuvar con las autoridades competentes para realizar la actividad de diversos actos de investigación y actividades de atribuciones de esta Secretaría de carácter confidencial.', 'ur' => json_encode(['200', '212', '220', '221', '231'])],
            ['objetive' => 'Apoyo a programas humanitarios del Gobierno Federal.', 'ur' => json_encode(['Otros'])],
            ['objetive' => 'Comisión para la atención de asuntos jurídicos y/o administrativos de la Dependencia.', 'ur' => json_encode(['Otros'])],
            ['objetive' => 'Otras comisiones oficiales.', 'ur' => json_encode(['Otros'])]
        ]);
    }
}
