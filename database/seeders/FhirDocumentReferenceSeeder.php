<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirDocumentStatus;

class FhirDocumentReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure patients and providers exist
        $patients = DB::table('fhir_patients')->get();
        $providers = DB::table('fhir_providers')->get();

        if ($patients->isEmpty() || $providers->isEmpty()) {
            $this->command->error('Please run required seeders first (FhirPatientSeeder, FhirProviderSeeder)');
            return;
        }

        $documents = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirDocumentStatus::CURRENT->value,
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '34805-0',
                            'display' => 'Imaging Study.total'
                        ]
                    ],
                    'text' => 'Resonancia Magnética'
                ]),
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code' => 'RADIOLOGY',
                                'display' => 'Radiology'
                            ]
                        ],
                        'text' => 'Radiología'
                    ]
                ]),
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'author_type' => 'Provider',
                'author_id' => $providers[2]->fhir_provider_id,
                'created' => now()->subDays(25),
                'content' => json_encode([
                    [
                        'attachment' => [
                            'contentType' => 'application/pdf',
                            'language' => 'es-AR',
                            'title' => 'RM_LUMBAR_20230501.pdf',
                            'creation' => now()->subDays(25)->toIso8601String()
                        ]
                    ]
                ]),
                'description' => 'Resonancia magnética de columna lumbar que muestra protrusión discal L4-L5 con compromiso de raíz nerviosa. Cambios degenerativos leves.',
                'path' => 'documentos/imagenes/RM_LUMBAR_20230501.pdf',
                'mimetype' => 'application/pdf',
                'language' => 'es-AR'
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirDocumentStatus::CURRENT->value,
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '11502-2',
                            'display' => 'Laboratory report'
                        ]
                    ],
                    'text' => 'Informe de Laboratorio'
                ]),
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code' => 'LAB',
                                'display' => 'Laboratory'
                            ]
                        ],
                        'text' => 'Laboratorio'
                    ]
                ]),
                'fhir_patient_id' => $patients[1]->fhir_patient_id,
                'author_type' => 'Provider',
                'author_id' => $providers[1]->fhir_provider_id,
                'created' => now()->subDays(10),
                'content' => json_encode([
                    [
                        'attachment' => [
                            'contentType' => 'application/pdf',
                            'language' => 'es-AR',
                            'title' => 'LAB_TOXICOLOGIA_20230515.pdf',
                            'creation' => now()->subDays(10)->toIso8601String()
                        ]
                    ]
                ]),
                'description' => 'Informe de análisis toxicológico completo. Resultados negativos para todas las sustancias evaluadas.',
                'path' => 'documentos/laboratorio/LAB_TOXICOLOGIA_20230515.pdf',
                'mimetype' => 'application/pdf',
                'language' => 'es-AR'
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirDocumentStatus::CURRENT->value,
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '68604-8',
                            'display' => 'Physical therapy Evaluation and management Report'
                        ]
                    ],
                    'text' => 'Informe de Fisioterapia'
                ]),
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code' => 'REHAB',
                                'display' => 'Rehabilitation'
                            ]
                        ],
                        'text' => 'Rehabilitación'
                    ]
                ]),
                'fhir_patient_id' => $patients[2]->fhir_patient_id,
                'author_type' => 'Provider',
                'author_id' => $providers[0]->fhir_provider_id,
                'created' => now()->subDays(35),
                'content' => json_encode([
                    [
                        'attachment' => [
                            'contentType' => 'application/pdf',
                            'language' => 'es-AR',
                            'title' => 'FISIO_RODILLA_20230420.pdf',
                            'creation' => now()->subDays(35)->toIso8601String()
                        ]
                    ]
                ]),
                'description' => 'Informe de evaluación fisioterapéutica de lesión meniscal en rodilla derecha. Se detalla plan de tratamiento y pronóstico de recuperación.',
                'path' => 'documentos/rehabilitacion/FISIO_RODILLA_20230420.pdf',
                'mimetype' => 'application/pdf',
                'language' => 'es-AR'
            ]
        ];

        foreach ($documents as $document) {
            DB::table('fhir_document_references')->insert(array_merge($document, [
                'created_at' => $document['created'],
                'updated_at' => $document['created'],
            ]));
        }
    }
}
