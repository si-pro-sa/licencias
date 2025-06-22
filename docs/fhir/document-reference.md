# Recurso DocumentReference

El recurso `DocumentReference` en FHIR se utiliza para referirse y describir documentos que contienen información de salud. En nuestra implementación, este recurso permite gestionar todo tipo de documentos clínicos, incluyendo informes médicos, resultados de laboratorio, imágenes médicas y documentos administrativos.

## Estructura de la tabla

La tabla `fhir_document_references` implementa el recurso DocumentReference con la siguiente estructura:

| Campo | Tipo | Descripción | Valores Posibles |
|-------|------|-------------|------------------|
| fhir_document_reference_id | bigint | Identificador primario interno | Auto-incrementable |
| fhir_id | string | Identificador único FHIR | UUID generado |
| status | string | Estado del documento | 'current', 'superseded', 'entered-in-error' |
| type | json | Tipo de documento (codificado) | Código LOINC o SNOMED CT |
| category | json | Categoría del documento | Clasificación clínica |
| subject_type | string | Tipo de recurso al que pertenece el documento | 'Patient', 'Encounter', etc. |
| subject_id | bigint | ID del recurso relacionado | ID del recurso correspondiente |
| content_type | string | Tipo MIME del contenido | 'application/pdf', 'image/jpeg', etc. |
| content_url | string | URL o ruta al contenido | Ruta al documento |
| content_size | string | Tamaño del documento | En bytes |
| content_hash | string | Hash del contenido para verificación | SHA-256 típicamente |
| context_period_start | datetime | Inicio del período de relevancia | Fecha y hora |
| context_period_end | datetime | Fin del período de relevancia | Fecha y hora |
| author | json | Autor del documento | Referencias a Practitioners/Providers |
| description | text | Descripción textual del documento | Texto libre |
| created_at | timestamp | Fecha de creación del registro | Auto-generado |
| updated_at | timestamp | Fecha de última actualización | Auto-generado |
| deleted_at | timestamp | Fecha de eliminación lógica | Null si está activo |

## Estados Válidos (FHIR)

El campo `status` debe usar uno de los siguientes valores FHIR:

- **current**: El documento está actualmente en uso
- **superseded**: El documento ha sido reemplazado por otra versión
- **entered-in-error**: El documento fue creado o identificado por error

## Tipos de Documentos

El campo `type` debe contener un objeto JSON con un sistema de codificación y código. Se recomienda usar códigos LOINC para la categorización de documentos clínicos. Ejemplos comunes:

```json
{
  "coding": [
    {
      "system": "http://loinc.org",
      "code": "34839-1",
      "display": "Physician note"
    }
  ]
}
```

Códigos LOINC comunes para documentos:
- **34839-1**: Physician note (Nota médica)
- **34746-8**: Nursing note (Nota de enfermería)
- **11506-3**: Progress note (Nota de evolución)
- **18842-5**: Discharge summary (Resumen de alta)
- **11488-4**: Consultation note (Nota de consulta)
- **28570-0**: Procedure note (Nota de procedimiento)

## Categorías

El campo `category` permite clasificar el documento según su uso o departamento. Ejemplos:

```json
{
  "coding": [
    {
      "system": "http://loinc.org",
      "code": "LP173421-1",
      "display": "Report"
    }
  ]
}
```

## Uso del Recurso DocumentReference

El recurso DocumentReference se utiliza para:

1. **Informes médicos**: Referencias a informes de juntas médicas, evaluaciones o dictámenes
2. **Documentos subidos por pacientes**: Certificados externos, exámenes previos
3. **Documentos administrativos**: Formularios de licencias, autorizaciones
4. **Resultados de estudios**: Imágenes, laboratorios, estudios complementarios

## Vinculación con otros recursos

DocumentReference se puede vincular con varios recursos a través de `subject_type` y `subject_id`:

- **Patient**: Documentos pertenecientes al historial médico de un paciente
- **Encounter**: Documentos generados durante un encuentro médico o junta médica
- **Condition**: Documentos relacionados con un diagnóstico específico
- **Observation**: Documentos que respaldan o amplían una observación

## Ejemplos de Uso

### Guardar una nota médica como DocumentReference

```php
$documentReference = [
    'fhir_id' => Str::uuid()->toString(),
    'status' => 'current',
    'type' => json_encode([
        'coding' => [
            [
                'system' => 'http://loinc.org',
                'code' => '34839-1',
                'display' => 'Physician note'
            ]
        ]
    ]),
    'category' => json_encode([
        'coding' => [
            [
                'system' => 'http://loinc.org',
                'code' => 'LP173421-1',
                'display' => 'Report'
            ]
        ]
    ]),
    'subject_type' => 'Patient',
    'subject_id' => $patientId,
    'content_type' => 'application/pdf',
    'content_url' => $pdfFilePath,
    'content_size' => $fileSize,
    'content_hash' => hash_file('sha256', $pdfFilePath),
    'context_period_start' => now(),
    'context_period_end' => null,
    'author' => json_encode([
        'reference' => "Provider/$providerId",
        'display' => $providerName
    ]),
    'description' => 'Nota médica de evaluación de junta médica'
];

DB::table('fhir_document_references')->insert($documentReference);
```

### Consulta de documentos de un paciente por tipo

```php
$physicianNotes = DB::table('fhir_document_references')
    ->where('subject_type', 'Patient')
    ->where('subject_id', $patientId)
    ->whereJsonContains('type->coding', [
        ['code' => '34839-1'] // Código LOINC para Physician note
    ])
    ->where('status', 'current')
    ->orderBy('context_period_start', 'desc')
    ->get();
```

## Consideraciones para cumplimiento HIPAA

- Los documentos referenciados deben almacenarse en un sistema de almacenamiento seguro
- Se debe implementar auditoría de acceso a los documentos
- La información sensible en el documento debe estar encriptada
- Los hashes de los documentos deben verificarse periódicamente para garantizar integridad
- Establecer políticas claras de retención y eliminación de documentos antiguos

## Relación con ClinicalNotes

Mientras que DocumentReference se utiliza para referenciar documentos externos o archivos como PDFs, imágenes o documentos escaneados, el recurso ClinicalNote es una implementación específica para notas textuales que se crean y gestionan directamente en el sistema.

- **DocumentReference**: Para referenciar documentos externos, con metadatos y ubicación
- **ClinicalNote**: Para notas textuales estructuradas creadas en el sistema

## Referencias

- [Documentación oficial del recurso DocumentReference en FHIR](https://www.hl7.org/fhir/documentreference.html)
- [Guía de implementación de DocumentReference](https://build.fhir.org/ig/HL7/US-Core/StructureDefinition-us-core-documentreference.html)
- [Códigos LOINC para documentos clínicos](https://loinc.org/document-ontology/) 
