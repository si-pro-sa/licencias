<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\FhirAddressType;
use App\Enums\FhirAddressUse;
use App\Enums\FhirGender;
use App\Enums\FhirResourceStatus;
use App\Enums\FhirEncounterStatus;
use App\Enums\FhirConditionStatus;
use App\Enums\FhirObservationStatus;
use App\Enums\FhirDocumentStatus;
use App\Enums\FhirNoteType;
use App\Enums\FhirNoteStatus;
use Illuminate\Support\Facades\DB;

class CreateFhirResourcesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar si la tabla agentes existe, sino crearla
        if (!Schema::hasTable('agentes')) {
            Schema::create('agentes', function (Blueprint $table) {
                $table->id('idagente');
                $table->string('nombre');
                $table->string('apellido');
                $table->string('documento')->nullable();
                $table->string('tipo_documento')->nullable();
                $table->string('email')->nullable();
                $table->string('telefono')->nullable();
                $table->date('fecha_nacimiento')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });

            // Crear algunos agentes de prueba
            DB::table('agentes')->insert([
                [
                    'idagente' => 1,
                    'nombre' => 'Juan',
                    'apellido' => 'Pérez',
                    'documento' => '12345678',
                    'tipo_documento' => 'DNI',
                    'email' => 'juan.perez@example.com',
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'idagente' => 2,
                    'nombre' => 'María',
                    'apellido' => 'González',
                    'documento' => '87654321',
                    'tipo_documento' => 'DNI',
                    'email' => 'maria.gonzalez@example.com',
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'idagente' => 3,
                    'nombre' => 'Carlos',
                    'apellido' => 'Rodríguez',
                    'documento' => '23456789',
                    'tipo_documento' => 'DNI',
                    'email' => 'carlos.rodriguez@example.com',
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // FHIR Addresses table
        Schema::create('fhir_addresses', function (Blueprint $table) {
            $table->id('fhir_address_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('use', FhirAddressUse::values());
            $table->enum('type', FhirAddressType::values());
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // FHIR Locations table
        Schema::create('fhir_locations', function (Blueprint $table) {
            $table->id('fhir_location_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('status', FhirResourceStatus::values());
            $table->json('name');
            $table->json('alias')->nullable();
            $table->text('description')->nullable();
            $table->string('mode')->default('instance');
            $table->json('type')->nullable();
            $table->json('telecom')->nullable();
            $table->foreignId('fhir_address_id')->nullable()->constrained('fhir_addresses', 'fhir_address_id')->onDelete('set null');
            $table->json('physicalType')->nullable();
            $table->json('position')->nullable();
            $table->unsignedBigInteger('idlocalidad')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // FHIR Facilities table
        Schema::create('fhir_facilities', function (Blueprint $table) {
            $table->id('fhir_facility_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('status', FhirResourceStatus::values());
            $table->json('name');
            $table->json('alias')->nullable();
            $table->text('description')->nullable();
            $table->json('type')->nullable();
            $table->json('telecom')->nullable();
            $table->foreignId('fhir_address_id')->nullable()->constrained('fhir_addresses', 'fhir_address_id')->onDelete('set null');
            $table->foreignId('fhir_location_id')->nullable()->constrained('fhir_locations', 'fhir_location_id')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Licencias de Salud Ocupacional table (Occupational Health Licenses)
        Schema::create('licencias_salud_ocupacional', function (Blueprint $table) {
            $table->id('idlicencia_salud_ocupacional');
            $table->string('numero')->unique();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('estado');
            $table->text('motivo');
            $table->string('tipo');
            $table->unsignedBigInteger('idagente');
            $table->timestamps();
            $table->softDeletes();
        });

        // Juntas Médicas table (Medical Board)
        Schema::create('juntas_medicas', function (Blueprint $table) {
            $table->id('idjuntamedica');
            $table->string('numero')->unique();
            $table->string('tipo');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->string('estado');
            $table->foreignId('fhir_facility_id')->nullable()->constrained('fhir_facilities', 'fhir_facility_id')->onDelete('set null');
            $table->unsignedBigInteger('idlicencia_salud_ocupacional')->nullable();
            // Añadir fechas adicionales
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_deliberacion')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();
            // Añadir campos adicionales
            $table->text('observaciones')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->string('resolucion')->nullable();
            $table->integer('quorum_minimo')->default(5);
            $table->unsignedBigInteger('idagente_presidente')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla pivot para los miembros de la junta médica
        Schema::create('junta_medica_miembros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idjuntamedica');
            $table->unsignedBigInteger('idagente');
            $table->string('rol'); // presidente, secretario, vocal, asesor, etc.
            $table->string('voto')->nullable(); // aprobado, rechazado, abstencion
            $table->text('comentario')->nullable();
            $table->boolean('asistio')->default(false);
            $table->timestamp('fecha_voto')->nullable();
            $table->timestamps();

            $table->foreign('idjuntamedica')->references('idjuntamedica')->on('juntas_medicas')->onDelete('cascade');
            if (Schema::hasTable('agentes')) {
                $table->foreign('idagente')->references('idagente')->on('agentes')->onDelete('cascade');
            }

            // Un agente solo puede tener un rol por junta médica
            $table->unique(['idjuntamedica', 'idagente']);
        });

        // Add foreign key constraint to juntas_medicas
        Schema::table('juntas_medicas', function (Blueprint $table) {
            $table->foreign('idlicencia_salud_ocupacional')
                ->references('idlicencia_salud_ocupacional')
                ->on('licencias_salud_ocupacional')
                ->onDelete('set null');

            if (Schema::hasTable('agentes')) {
                $table->foreign('idagente_presidente')
                    ->references('idagente')
                    ->on('agentes')
                    ->onDelete('set null');
            }
        });

        // Add foreign key to licencias_salud_ocupacional for patient (agente)
        Schema::table('licencias_salud_ocupacional', function (Blueprint $table) {
            if (Schema::hasTable('agentes')) {
                $table->foreign('idagente')->references('idagente')->on('agentes')->onDelete('cascade');
            }
        });

        // FHIR Providers table
        Schema::create('fhir_providers', function (Blueprint $table) {
            $table->id('fhir_provider_id');
            $table->uuid('fhir_id')->unique();
            $table->boolean('active')->default(true);
            $table->json('name');
            $table->enum('gender', FhirGender::values())->nullable();
            $table->date('birth_date')->nullable();
            $table->json('qualification')->nullable();
            $table->json('telecom')->nullable();
            $table->foreignId('fhir_address_id')->nullable()->constrained('fhir_addresses', 'fhir_address_id')->onDelete('set null');
            $table->foreignId('fhir_facility_id')->nullable()->constrained('fhir_facilities', 'fhir_facility_id')->onDelete('set null');
            $table->json('communication')->nullable();
            $table->unsignedBigInteger('idagente')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key to reference agentes table
            if (Schema::hasTable('agentes')) {
                $table->foreign('idagente')->references('idagente')->on('agentes')->onDelete('set null');
            }
        });

        // FHIR Patients table
        Schema::create('fhir_patients', function (Blueprint $table) {
            $table->id('fhir_patient_id');
            $table->uuid('fhir_id')->unique();
            $table->boolean('active')->default(true);
            $table->json('name');
            $table->enum('gender', FhirGender::values())->nullable();
            $table->date('birth_date')->nullable();
            $table->timestamp('deceased')->nullable();
            $table->foreignId('fhir_address_id')->nullable()->constrained('fhir_addresses', 'fhir_address_id')->onDelete('set null');
            $table->json('telecom')->nullable();
            $table->json('communication')->nullable();
            $table->json('generalPractitioner')->nullable();
            $table->json('managingOrganization')->nullable();
            $table->json('link')->nullable();
            $table->unsignedBigInteger('idagente')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key to reference agentes table
            if (Schema::hasTable('agentes')) {
                $table->foreign('idagente')->references('idagente')->on('agentes')->onDelete('set null');
            }
        });

        // FHIR Encounters table
        Schema::create('fhir_encounters', function (Blueprint $table) {
            $table->id('fhir_encounter_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('status', FhirEncounterStatus::values());
            $table->string('class');
            $table->foreignId('fhir_patient_id')->nullable()->constrained('fhir_patients', 'fhir_patient_id')->onDelete('cascade');
            $table->foreignId('fhir_provider_id')->nullable()->constrained('fhir_providers', 'fhir_provider_id')->onDelete('set null');
            $table->foreignId('fhir_facility_id')->nullable()->constrained('fhir_facilities', 'fhir_facility_id')->onDelete('set null');
            $table->foreignId('fhir_location_id')->nullable()->constrained('fhir_locations', 'fhir_location_id')->onDelete('set null');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->text('reason')->nullable();
            $table->json('diagnosis')->nullable();
            $table->unsignedBigInteger('idjuntamedica')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key for junta medica
            $table->foreign('idjuntamedica')->references('idjuntamedica')->on('juntas_medicas')->onDelete('set null');
        });

        // FHIR Conditions table
        Schema::create('fhir_conditions', function (Blueprint $table) {
            $table->id('fhir_condition_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('clinical_status', FhirConditionStatus::values())->nullable();
            $table->foreignId('fhir_patient_id')->constrained('fhir_patients', 'fhir_patient_id')->onDelete('cascade');
            $table->foreignId('fhir_encounter_id')->nullable()->constrained('fhir_encounters', 'fhir_encounter_id')->onDelete('set null');
            $table->date('recorded_date')->nullable();
            $table->json('code')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // FHIR Observations table
        Schema::create('fhir_observations', function (Blueprint $table) {
            $table->id('fhir_observation_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('status', FhirObservationStatus::values());
            $table->json('category')->nullable();
            $table->json('code');
            $table->foreignId('fhir_patient_id')->constrained('fhir_patients', 'fhir_patient_id')->onDelete('cascade');
            $table->foreignId('fhir_encounter_id')->nullable()->constrained('fhir_encounters', 'fhir_encounter_id')->onDelete('set null');
            $table->timestamp('effective_datetime')->nullable();
            $table->timestamp('issued')->nullable();
            $table->json('value')->nullable();
            $table->string('data_type')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // FHIR Document References table
        Schema::create('fhir_document_references', function (Blueprint $table) {
            $table->id('fhir_document_reference_id');
            $table->uuid('fhir_id')->unique();
            $table->enum('status', FhirDocumentStatus::values());
            $table->json('type');
            $table->json('category')->nullable();
            $table->foreignId('fhir_patient_id')->nullable()->constrained('fhir_patients', 'fhir_patient_id')->onDelete('cascade');
            $table->string('author_type')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamp('created')->nullable();
            $table->json('content');
            $table->text('description')->nullable();
            $table->string('path')->nullable();
            $table->string('mimetype')->nullable();
            $table->string('language')->default('es-AR');
            $table->timestamps();
            $table->softDeletes();
        });

        // FHIR Clinical Notes table
        Schema::create('fhir_clinical_notes', function (Blueprint $table) {
            $table->id('fhir_clinical_note_id');
            $table->uuid('fhir_id')->unique();
            $table->foreignId('fhir_patient_id')->constrained('fhir_patients', 'fhir_patient_id')->onDelete('cascade');
            $table->foreignId('fhir_encounter_id')->nullable()->constrained('fhir_encounters', 'fhir_encounter_id')->onDelete('set null');
            $table->string('author_type')->comment('Provider, System, etc.');
            $table->unsignedBigInteger('author_id');
            $table->enum('note_type', FhirNoteType::values());
            $table->text('content');
            $table->timestamp('recorded_date');
            $table->enum('status', FhirNoteStatus::values());
            $table->timestamps();
            $table->softDeletes();
        });

        // Crear tabla pivot para la relación entre proveedores y juntas médicas
        if (!Schema::hasTable('fhir_provider_junta_medica')) {
            Schema::create('fhir_provider_junta_medica', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fhir_provider_id')->constrained('fhir_providers', 'fhir_provider_id')->onDelete('cascade');
                $table->unsignedBigInteger('idjuntamedica');
                $table->foreign('idjuntamedica')->references('idjuntamedica')->on('juntas_medicas')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Función genérica para eliminar claves foráneas de forma segura
        $dropForeignKeysForTable = function ($tableName, $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($columns, $tableName) {
                    foreach ($columns as $column) {
                        // Obtenemos la lista de claves foráneas que existen en la tabla
                        try {
                            $foreignKeys = Schema::getConnection()
                                ->getDoctrineSchemaManager()
                                ->listTableForeignKeys($tableName);

                            // Buscamos si alguna clave foránea hace referencia a nuestra columna
                            foreach ($foreignKeys as $foreignKey) {
                                $foreignKeyColumns = $foreignKey->getLocalColumns();

                                if (in_array($column, $foreignKeyColumns)) {
                                    $table->dropForeign($foreignKey->getName());
                                    break;
                                }
                            }
                        } catch (\Exception $e) {
                            // Si hay un error, intentamos eliminar usando el formato convencional
                            try {
                                $table->dropForeign([$column]);
                            } catch (\Exception $e) {
                                // Si también falla, ignoramos y continuamos
                            }
                        }
                    }
                });
            }
        };

        // Primero eliminamos las relaciones de claves foráneas que hacen referencia a nuestras tablas
        // Tablas que podrían tener referencias a las tablas FHIR
        $tableReferences = [
            'informes' => [
                'fhir_document_reference_id',
                'fhir_encounter_id',
                'fhir_patient_id',
                'fhir_provider_id',
                'fhir_observation_id',
                'fhir_condition_id'
            ],
            'historias_clinicas' => [
                'fhir_document_reference_id',
                'fhir_encounter_id',
                'fhir_patient_id',
                'fhir_provider_id',
                'fhir_observation_id',
                'fhir_condition_id'
            ],
            'expedientes' => [
                'fhir_document_reference_id',
                'fhir_encounter_id',
                'fhir_patient_id',
                'fhir_provider_id'
            ],
            'consultas' => [
                'fhir_encounter_id',
                'fhir_patient_id',
                'fhir_provider_id'
            ],
            'diagnosticos' => [
                'fhir_condition_id',
                'fhir_patient_id',
                'idjuntamedica'
            ],
            'observaciones' => [
                'fhir_observation_id',
                'fhir_patient_id',
                'idjuntamedica'
            ]
        ];

        // Eliminar las claves foráneas en tablas externas
        foreach ($tableReferences as $tableName => $columns) {
            $dropForeignKeysForTable($tableName, $columns);
        }

        // Eliminar tablas pivot antes de las tablas principales
        Schema::dropIfExists('fhir_provider_junta_medica');

        // Eliminar claves foráneas en las tablas FHIR
        if (Schema::hasTable('fhir_clinical_notes')) {
            Schema::table('fhir_clinical_notes', function (Blueprint $table) {
                $table->dropForeign(['fhir_patient_id']);
                $table->dropForeign(['fhir_encounter_id']);
            });
        }

        if (Schema::hasTable('fhir_document_references')) {
            Schema::table('fhir_document_references', function (Blueprint $table) {
                $table->dropForeign(['fhir_patient_id']);
            });
        }

        if (Schema::hasTable('fhir_observations')) {
            Schema::table('fhir_observations', function (Blueprint $table) {
                $table->dropForeign(['fhir_patient_id']);
                $table->dropForeign(['fhir_encounter_id']);
            });
        }

        if (Schema::hasTable('fhir_conditions')) {
            Schema::table('fhir_conditions', function (Blueprint $table) {
                $table->dropForeign(['fhir_patient_id']);
                $table->dropForeign(['fhir_encounter_id']);
            });
        }

        if (Schema::hasTable('fhir_encounters')) {
            Schema::table('fhir_encounters', function (Blueprint $table) {
                $table->dropForeign(['fhir_patient_id']);
                $table->dropForeign(['fhir_provider_id']);
                $table->dropForeign(['fhir_facility_id']);
                $table->dropForeign(['fhir_location_id']);
                $table->dropForeign(['idjuntamedica']);
            });
        }

        if (Schema::hasTable('fhir_patients')) {
            Schema::table('fhir_patients', function (Blueprint $table) {
                $table->dropForeign(['fhir_address_id']);
                if (Schema::hasColumn('fhir_patients', 'idagente')) {
                    try {
                        $table->dropForeign(['idagente']);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                }
            });
        }

        if (Schema::hasTable('fhir_providers')) {
            Schema::table('fhir_providers', function (Blueprint $table) {
                $table->dropForeign(['fhir_address_id']);
                $table->dropForeign(['fhir_facility_id']);
                if (Schema::hasColumn('fhir_providers', 'idagente')) {
                    try {
                        $table->dropForeign(['idagente']);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                }
            });
        }

        if (Schema::hasTable('junta_medica_miembros')) {
            Schema::table('junta_medica_miembros', function (Blueprint $table) {
                $table->dropForeign(['idjuntamedica']);
                if (Schema::hasColumn('junta_medica_miembros', 'idagente')) {
                    try {
                        $table->dropForeign(['idagente']);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                }
            });
        }

        if (Schema::hasTable('juntas_medicas')) {
            Schema::table('juntas_medicas', function (Blueprint $table) {
                $table->dropForeign(['fhir_facility_id']);
                $table->dropForeign(['idlicencia_salud_ocupacional']);
                if (Schema::hasColumn('juntas_medicas', 'idagente_presidente')) {
                    try {
                        $table->dropForeign(['idagente_presidente']);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                }
            });
        }

        if (Schema::hasTable('licencias_salud_ocupacional')) {
            Schema::table('licencias_salud_ocupacional', function (Blueprint $table) {
                if (Schema::hasColumn('licencias_salud_ocupacional', 'idagente')) {
                    try {
                        $table->dropForeign(['idagente']);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                }
            });
        }

        if (Schema::hasTable('fhir_facilities')) {
            Schema::table('fhir_facilities', function (Blueprint $table) {
                $table->dropForeign(['fhir_address_id']);
                $table->dropForeign(['fhir_location_id']);
            });
        }

        if (Schema::hasTable('fhir_locations')) {
            Schema::table('fhir_locations', function (Blueprint $table) {
                $table->dropForeign(['fhir_address_id']);
            });
        }

        // Ahora eliminamos las tablas
        Schema::dropIfExists('fhir_clinical_notes');
        Schema::dropIfExists('fhir_document_references');
        Schema::dropIfExists('fhir_observations');
        Schema::dropIfExists('fhir_conditions');
        Schema::dropIfExists('fhir_encounters');
        Schema::dropIfExists('fhir_patients');
        Schema::dropIfExists('fhir_providers');
        Schema::dropIfExists('junta_medica_miembros');
        Schema::dropIfExists('juntas_medicas');
        Schema::dropIfExists('licencias_salud_ocupacional');
        Schema::dropIfExists('fhir_facilities');
        Schema::dropIfExists('fhir_locations');
        Schema::dropIfExists('fhir_addresses');

        // No eliminar la tabla agentes porque podría ser utilizada por otras partes del sistema
    }
}
