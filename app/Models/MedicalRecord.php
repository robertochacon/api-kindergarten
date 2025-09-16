<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'kid_id',
        // ANTECEDENTES PRENATALES
        'bajo_riesgo',
        'alto_riesgo',
        'preeclampsia',
        'sangrados',
        'infecciones',
        'madre_adolescente',
        'rn_a_termino',
        'rn_prematuro',
        'semanas_al_nacer',
        
        // NACIMIENTO
        'parto',
        'cesarea',
        'lloro_al_nacer',
        'peso_al_nacer',
        'talla',
        'perimetro_cefalico',
        'complicacion_al_nacer',
        'descripcion_complicacion',
        'tiempo_hospitalizado',
        'incubadora',
        'oxigeno',
        'ventilacion_mecanica',
        
        // DESARROLLO Y NUTRICION
        'lactancia_materna_al_nacer',
        'duracion_lactancia',
        'inicio_alimentacion_complementaria',
        'suplemento_hierro',
        'suplemento_vitamina_a',
        'suplemento_multivitaminas',
        'estimulantes_apetito',
        'come_solo',
        'cantidad_veces_come',
        'buen_apetito',
        'alergia_alimento',
        
        // ASPECTOS PSICOMOTORES
        'controla_esfinteres',
        'utiliza_panal',
        'discapacidad_diagnosticada',
        'descripcion_discapacidad',
        'antecedentes_patologicos',
        'alergico_medicamento',
        'medicamento_permanente',
        'enfermedades_previas',
        'tipo_enfermedad',
        'estado_ingresado',
        'veces_ingresado',
        'anemia',
        'fiebre',
        'infecciones_previas',
        'deshidratacion',
        'luce_desnutrido',
        'accidentes',
        'cirugias',
        
        // EXAMEN FISICO
        'cabeza',
        'fontanelas',
        'cara',
        'ojos_oidos_nariz',
        'boca',
        'cuello',
        'torax',
        'abdomen',
        'extremidades_superiores_inferiores',
        'genitourinario',
        'piel',
        'peso_lbs',
        'talla_cms',
        'pliegue_brazo',
        'imc',
        'estado_nutricional',
        
        // DATOS DEL PEDIATRA O MEDICO EVALUADOR
        'firma_medico',
        'exequatur',
        'cmd',
        'tabla_crecimiento_desarrollo'
    ];

    protected $casts = [
        'bajo_riesgo' => 'boolean',
        'alto_riesgo' => 'boolean',
        'preeclampsia' => 'boolean',
        'sangrados' => 'boolean',
        'infecciones' => 'boolean',
        'madre_adolescente' => 'boolean',
        'rn_a_termino' => 'boolean',
        'rn_prematuro' => 'boolean',
        'parto' => 'boolean',
        'cesarea' => 'boolean',
        'lloro_al_nacer' => 'boolean',
        'complicacion_al_nacer' => 'boolean',
        'incubadora' => 'boolean',
        'oxigeno' => 'boolean',
        'ventilacion_mecanica' => 'boolean',
        'lactancia_materna_al_nacer' => 'boolean',
        'suplemento_hierro' => 'boolean',
        'suplemento_vitamina_a' => 'boolean',
        'suplemento_multivitaminas' => 'boolean',
        'estimulantes_apetito' => 'boolean',
        'come_solo' => 'boolean',
        'buen_apetito' => 'boolean',
        'alergia_alimento' => 'boolean',
        'controla_esfinteres' => 'boolean',
        'utiliza_panal' => 'boolean',
        'discapacidad_diagnosticada' => 'boolean',
        'alergico_medicamento' => 'boolean',
        'enfermedades_previas' => 'boolean',
        'estado_ingresado' => 'boolean',
        'anemia' => 'boolean',
        'fiebre' => 'boolean',
        'infecciones_previas' => 'boolean',
        'deshidratacion' => 'boolean',
        'luce_desnutrido' => 'boolean',
        'accidentes' => 'boolean',
        'cirugias' => 'boolean',
    ];

    public function kid()
    {
        return $this->belongsTo(Kids::class, 'kid_id', 'id');
    }
}