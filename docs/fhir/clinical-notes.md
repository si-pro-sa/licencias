# Recurso ClinicalNotes

El recurso `ClinicalNotes` es una implementación personalizada basada en los recursos FHIR para manejar notas médicas y de enfermería en nuestro sistema. Este recurso permite la creación, almacenamiento y consulta de notas clínicas textuales creadas directamente en la aplicación.

## Estructura de la tabla

La tabla `fhir_clinical_notes` implementa las notas clínicas con la siguiente estructura:

| Campo | Tipo | Descripción | Valores Posibles |
|-------|------|-------------|------------------|
| fhir_clinical_note_id | bigint | Identificador primario interno | Auto-incrementable |
| fhir_id | string | Identificador único FHIR | UUID generado |
| fhir_patient_id | bigint | ID del paciente relacionado | FK a fhir_patients |
| fhir_encounter_id | bigint | ID del encuentro relacionado (opcional) | FK a fhir_encounters |
| author_type | string | Tipo de autor | 'Provider', 'Practitioner' |
| author_id | bigint | ID del autor | ID del proveedor/practicante |
| note_type | string | Tipo de nota clínica | Códigos LOINC (ver abajo) |
| content | text | Contenido textual de la nota | Texto libre o estructurado |
| recorded_date | datetime | Fecha y hora de registro | Timestamp |
| status | string | Estado de la nota | 'preliminary', 'final', 'amended' |
| created_at | timestamp | Fecha de creación del registro | Auto-generado |
| updated_at | timestamp | Fecha de última actualización | Auto-generado |
| deleted_at | timestamp | Fecha de eliminación lógica | Null si está activo |

## Tipos de Notas Clínicas

El campo `note_type` debe contener uno de los códigos LOINC estándar para notas clínicas:

- **34839-1**: Physician note (Nota médica)
- **34746-8**: Nursing note (Nota de enfermería)
- **11506-3**: Progress note (Nota de evolución)
- **18842-5**: Discharge summary (Resumen de alta)
- **11488-4**: Consultation note (Nota de consulta)
- **28570-0**: Procedure note (Nota de procedimiento)

## Estados Válidos

El campo `status` debe usar uno de los siguientes valores FHIR:

- **preliminary**: La nota está en borrador o no ha sido finalizada
- **final**: La nota ha sido finalizada y validada
- **amended**: La nota ha sido modificada después de finalizada
- **entered-in-error**: La nota fue creada por error

## Implementación FHIR

Esta tabla es una implementación personalizada basada en una combinación de los recursos FHIR:
- **DiagnosticReport**: Para informes estructurados
- **Composition**: Para documentos clínicos
- **QuestionnaireResponse**: Para notas estructuradas

Se optó por esta implementación personalizada para simplificar y optimizar el manejo de notas clínicas textuales en el contexto de juntas médicas y salud ocupacional.

## Uso del Recurso ClinicalNotes

El recurso ClinicalNotes se utiliza principalmente para:

1. **Notas médicas**: Registros de consultas, evaluaciones o seguimientos realizados por médicos
2. **Notas de enfermería**: Observaciones y cuidados realizados por personal de enfermería
3. **Notas de evolución**: Registros periódicos del progreso de un paciente
4. **Notas de procedimientos**: Descripciones de procedimientos realizados
5. **Notas de junta médica**: Evaluaciones y conclusiones de juntas médicas

## Diferencias con DocumentReference

A diferencia del recurso DocumentReference, que se utiliza para referenciar documentos externos:

- **ClinicalNotes**: Almacena directamente el contenido textual de la nota en la base de datos
- **DocumentReference**: Almacena metadatos y una referencia a un documento externo (PDF, imagen, etc.)

## Ejemplos de Uso

### Creación de una nueva nota médica

```php
$clinicalNote = [
    'fhir_id' => Str::uuid()->toString(),
    'fhir_patient_id' => $patientId,
    'fhir_encounter_id' => $encounterId,
    'author_type' => 'Provider',
    'author_id' => $providerId,
    'note_type' => '34839-1', // Physician note
    'content' => $noteContent,
    'recorded_date' => now(),
    'status' => 'final'
];

DB::table('fhir_clinical_notes')->insert($clinicalNote);
```

### Consulta de notas médicas de un paciente

```php
$patientNotes = DB::table('fhir_clinical_notes')
    ->where('fhir_patient_id', $patientId)
    ->where('note_type', '34839-1') // Physician note
    ->where('status', 'final')
    ->orderBy('recorded_date', 'desc')
    ->get();
```

### Actualización de una nota clínica

```php
DB::table('fhir_clinical_notes')
    ->where('fhir_clinical_note_id', $noteId)
    ->update([
        'content' => $updatedContent,
        'status' => 'amended',
        'updated_at' => now()
    ]);
```

## Consideraciones para cumplimiento HIPAA

Para garantizar el cumplimiento con HIPAA, se deben implementar las siguientes medidas:

1. **Control de acceso**:
   - Restringir el acceso a las notas según rol y necesidad
   - Implementar registro de auditoría para cada acceso y modificación

2. **Privacidad de datos**:
   - Evitar incluir información de identificación personal innecesaria
   - Considerar encriptar el campo `content` para notas sensibles

3. **Integridad de datos**:
   - Utilizar el campo `status` para controlar el ciclo de vida de la nota
   - Nunca eliminar permanentemente notas (usar soft deletes)
   - Mantener historial de cambios para notas modificadas

4. **Retención de datos**:
   - Implementar políticas de retención según regulaciones aplicables
   - Documentar el motivo de cada acceso a notas antiguas

## Estructura recomendada para el contenido

Para facilitar la búsqueda y el análisis, se recomienda estructurar el contenido de las notas clínicas. Ejemplo:

```
SUBJETIVO:
Paciente refiere dolor lumbar de intensidad 7/10 que empeora con los movimientos.

OBJETIVO:
TA: 120/80 mmHg, FC: 72 lpm, Temp: 36.5°C
Movilidad lumbar limitada, test de Lasegue positivo a 45° en miembro inferior derecho.

EVALUACIÓN:
Lumbalgia aguda con componente radicular. Posible hernia discal L4-L5.

PLAN:
1. Reposo relativo 48h
2. Diclofenac 75mg/12h por 5 días
3. Solicitar RMN lumbar
4. Control en 7 días
```

## Referencias

- [LOINC Clinical Document Ontology](https://loinc.org/document-ontology/)
- [Estándares FHIR para notas clínicas](https://www.hl7.org/fhir/clinicalnotes.html)
- [Guía HL7 para documentación clínica](https://build.fhir.org/ig/HL7/US-Core/clinical-notes-guidance.html)
- [Mejores prácticas para documentación médica HIPAA](https://www.hhs.gov/hipaa/for-professionals/privacy/guidance/index.html) 
