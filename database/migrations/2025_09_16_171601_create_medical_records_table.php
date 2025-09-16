<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kid_id')->constrained('kids')->onDelete('cascade');
            
            // ANTECEDENTES PRENATALES
            $table->boolean('bajo_riesgo')->nullable();
            $table->boolean('alto_riesgo')->nullable();
            $table->boolean('preeclampsia')->nullable();
            $table->boolean('sangrados')->nullable();
            $table->boolean('infecciones')->nullable();
            $table->boolean('madre_adolescente')->nullable();
            $table->boolean('rn_a_termino')->nullable();
            $table->boolean('rn_prematuro')->nullable();
            $table->string('semanas_al_nacer')->nullable();
            
            // NACIMIENTO
            $table->boolean('parto')->nullable();
            $table->boolean('cesarea')->nullable();
            $table->boolean('lloro_al_nacer')->nullable();
            $table->string('peso_al_nacer')->nullable();
            $table->string('talla')->nullable();
            $table->string('perimetro_cefalico')->nullable();
            $table->boolean('complicacion_al_nacer')->nullable();
            $table->text('descripcion_complicacion')->nullable();
            $table->string('tiempo_hospitalizado')->nullable();
            $table->boolean('incubadora')->nullable();
            $table->boolean('oxigeno')->nullable();
            $table->boolean('ventilacion_mecanica')->nullable();
            
            // DESARROLLO Y NUTRICION
            $table->boolean('lactancia_materna_al_nacer')->nullable();
            $table->string('duracion_lactancia')->nullable();
            $table->string('inicio_alimentacion_complementaria')->nullable();
            $table->boolean('suplemento_hierro')->nullable();
            $table->boolean('suplemento_vitamina_a')->nullable();
            $table->boolean('suplemento_multivitaminas')->nullable();
            $table->boolean('estimulantes_apetito')->nullable();
            $table->boolean('come_solo')->nullable();
            $table->string('cantidad_veces_come')->nullable();
            $table->boolean('buen_apetito')->nullable();
            $table->boolean('alergia_alimento')->nullable();
            
            // ASPECTOS PSICOMOTORES
            $table->boolean('controla_esfinteres')->nullable();
            $table->boolean('utiliza_panal')->nullable();
            $table->boolean('discapacidad_diagnosticada')->nullable();
            $table->text('descripcion_discapacidad')->nullable();
            $table->text('antecedentes_patologicos')->nullable();
            $table->boolean('alergico_medicamento')->nullable();
            $table->text('medicamento_permanente')->nullable();
            $table->boolean('enfermedades_previas')->nullable();
            $table->text('tipo_enfermedad')->nullable();
            $table->boolean('estado_ingresado')->nullable();
            $table->string('veces_ingresado')->nullable();
            $table->boolean('anemia')->nullable();
            $table->boolean('fiebre')->nullable();
            $table->boolean('infecciones_previas')->nullable();
            $table->boolean('deshidratacion')->nullable();
            $table->boolean('luce_desnutrido')->nullable();
            $table->boolean('accidentes')->nullable();
            $table->boolean('cirugias')->nullable();
            
            // EXAMEN FISICO
            $table->text('cabeza')->nullable();
            $table->text('fontanelas')->nullable();
            $table->text('cara')->nullable();
            $table->text('ojos_oidos_nariz')->nullable();
            $table->text('boca')->nullable();
            $table->text('cuello')->nullable();
            $table->text('torax')->nullable();
            $table->text('abdomen')->nullable();
            $table->text('extremidades_superiores_inferiores')->nullable();
            $table->text('genitourinario')->nullable();
            $table->text('piel')->nullable();
            $table->string('peso_lbs')->nullable();
            $table->string('talla_cms')->nullable();
            $table->string('pliegue_brazo')->nullable();
            $table->string('imc')->nullable();
            $table->string('estado_nutricional')->nullable();
            
            // DATOS DEL PEDIATRA O MEDICO EVALUADOR
            $table->string('firma_medico')->nullable();
            $table->string('exequatur')->nullable();
            $table->string('cmd')->nullable();
            $table->text('tabla_crecimiento_desarrollo')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
