<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirNoteType;
use App\Enums\FhirNoteStatus;

class FhirClinicalNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Asegúrese de que ya existen pacientes, encuentros y proveedores
        $patientId = DB::table('fhir_patients')->first()->fhir_patient_id ?? null;
        $encounterId = DB::table('fhir_encounters')->first()->fhir_encounter_id ?? null;
        $providerId = DB::table('fhir_providers')->first()->fhir_provider_id ?? null;

        if (!$patientId || !$encounterId || !$providerId) {
            $this->command->error('Se necesitan pacientes, encuentros y proveedores antes de crear notas clínicas');
            return;
        }

        $clinicalNotes = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'fhir_patient_id' => $patientId,
                'fhir_encounter_id' => $encounterId,
                'author_type' => 'Provider',
                'author_id' => $providerId,
                'note_type' => FhirNoteType::PHYSICIAN->value,
                'content' => "SUBJETIVO:\nPaciente refiere dolor lumbar de intensidad 7/10 que empeora con los movimientos. Relata inicio hace 3 días luego de levantar objetos pesados. Sin irradiación a miembros inferiores.\n\nOBJETIVO:\nTA: 120/80 mmHg, FC: 72 lpm, Temp: 36.5°C\nMovilidad lumbar limitada, test de Lasegue positivo a 45° en miembro inferior derecho.\n\nEVALUACIÓN:\nLumbalgia aguda con componente radicular. Posible hernia discal L4-L5.\n\nPLAN:\n1. Reposo relativo 48h\n2. Diclofenac 75mg/12h por 5 días\n3. Solicitar RMN lumbar\n4. Control en 7 días",
                'recorded_date' => now()->subDays(5),
                'status' => FhirNoteStatus::FINAL->value,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'fhir_patient_id' => $patientId,
                'fhir_encounter_id' => $encounterId,
                'author_type' => 'Provider',
                'author_id' => $providerId,
                'note_type' => FhirNoteType::PROGRESS->value,
                'content' => "SUBJETIVO:\nPaciente refiere mejoría del dolor lumbar (3/10). Aún presenta molestias con actividades que requieren flexión prolongada. Completó tratamiento con AINES.\n\nOBJETIVO:\nMovilidad lumbar mejorada. Test de Lasegue negativo bilateralmente.\n\nEVALUACIÓN:\nLumbalgia en resolución.\n\nPLAN:\n1. Alta laboral con recomendación de evitar esfuerzos durante 7 días adicionales\n2. Control según evolución",
                'recorded_date' => now()->subDays(1),
                'status' => FhirNoteStatus::FINAL->value,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'fhir_patient_id' => $patientId,
                'fhir_encounter_id' => $encounterId,
                'author_type' => 'Provider',
                'author_id' => $providerId,
                'note_type' => FhirNoteType::NURSING->value,
                'content' => "Signos vitales: TA 125/85, FC 70, FR 16, T 36.6°C\nPaciente sin dolor en reposo. Se realizaron ejercicios de movilización pasiva con buena tolerancia.\nSe entregaron indicaciones para cuidados en domicilio y técnicas de movilización adecuadas.",
                'recorded_date' => now()->subDays(3),
                'status' => FhirNoteStatus::FINAL->value,
            ],
        ];

        foreach ($clinicalNotes as $note) {
            DB::table('fhir_clinical_notes')->insert(array_merge($note, [
                'created_at' => $note['recorded_date'],
                'updated_at' => $note['recorded_date'],
            ]));
        }
    }
}
