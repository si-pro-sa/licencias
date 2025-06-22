# Diagrama de Relaciones entre Recursos FHIR

Este documento presenta una visualización detallada de las relaciones entre los diferentes recursos FHIR implementados en nuestro sistema.

## Relaciones entre Recursos

```
+-------------+       +-------------+       +-------------+
|   Address   |<------+   Location  +------>|  Facility   |
+-------------+       +-------------+       +-------------+
       ^                     ^                    ^
       |                     |                    |
       v                     |                    v
+-------------+              |             +-------------+
|   Patient   |<-------------+------------>|  Provider   |
+-------------+              |             +-------------+
       ^                     v                    ^
       |              +-------------+             |
       +------------->|  Encounter  |<------------+
       |              +-------------+             |
       |                     ^                    |
       v                     |                    v
+-------------+       +-------------+      +-------------+
|  Condition  |------>| Observation |      | ClinicalNote|
+-------------+       +-------------+      +-------------+
                            ^
                            |
                     +-------------+
                     |  Document   |
                     | Reference   |
                     +-------------+
```

## Mapa de Campos Principales

### Address
- `fhir_address_id`: PK
- `fhir_id`: Identificador FHIR
- `use`: home, work, temp, old, billing
- `type`: postal, physical, both
- `line1`, `line2`: Dirección
- `city`, `state`, `postal_code`, `country`: Ubicación

### Location
- `fhir_location_id`: PK
- `fhir_id`: Identificador FHIR
- `status`: active, suspended, inactive
- `name`: Nombre de la ubicación
- `fhir_address_id`: FK a Address

### Facility
- `fhir_facility_id`: PK
- `fhir_id`: Identificador FHIR
- `status`: active, suspended, inactive
- `name`: Nombre del establecimiento
- `fhir_address_id`: FK a Address
- `fhir_location_id`: FK a Location

### Patient
- `fhir_patient_id`: PK
- `fhir_id`: Identificador FHIR
- `identifier`: Identificadores externos (JSON)
- `active`: Estado del paciente
- `name`: Nombre completo (JSON)
- `gender`: male, female, other, unknown
- `birth_date`: Fecha de nacimiento
- `fhir_address_id`: FK a Address
- `idagente`: FK a la tabla agente

### Provider
- `fhir_provider_id`: PK
- `fhir_id`: Identificador FHIR
- `npi`: Número de Proveedor Nacional
- `active`: Estado del proveedor
- `name`: Nombre completo (JSON)
- `qualification`: Calificaciones (JSON)
- `fhir_address_id`: FK a Address
- `fhir_facility_id`: FK a Facility
- `idagente`: FK a la tabla agente

### Encounter
- `fhir_encounter_id`: PK
- `fhir_id`: Identificador FHIR
- `status`: planned, arrived, triaged, in-progress, onleave, finished, cancelled
- `class`: Clasificación (AMB, EMER, IMP, etc.)
- `type`: Tipo de encuentro (JSON)
- `subject`: Paciente involucrado (JSON)
- `participant`: Proveedores participantes (JSON)
- `period`: Período del encuentro (JSON)
- `fhir_facility_id`: FK a Facility
- `fhir_location_id`: FK a Location
- `idlicencia`: FK a la tabla licencias

### Condition
- `fhir_condition_id`: PK
- `fhir_id`: Identificador FHIR
- `status`: active, recurrence, relapse, inactive, remission, resolved
- `category`: Categoría del diagnóstico (JSON)
- `code`: Código del diagnóstico (JSON)
- `fhir_patient_id`: FK a Patient
- `fhir_encounter_id`: FK a Encounter
- `idDiagnostico`: FK a la tabla diagnósticos

### Observation
- `fhir_observation_id`: PK
- `fhir_id`: Identificador FHIR
- `status`: registered, preliminary, final, amended, corrected, cancelled, entered-in-error, unknown
- `category`: Categoría (JSON)
- `code`: Código LOINC/SNOMED (JSON)
- `value`: Valor de la observación (JSON)
- `fhir_patient_id`: FK a Patient
- `fhir_encounter_id`: FK a Encounter
- `idObservacion`: FK a la tabla observaciones

### DocumentReference
- `fhir_document_reference_id`: PK
- `fhir_id`: Identificador FHIR
- `status`: current, superseded, entered-in-error
- `type`: Tipo de documento (JSON)
- `subject_type`: Tipo de entidad relacionada
- `subject_id`: ID de la entidad relacionada
- `content_url`: URL al documento físico

### ClinicalNote
- `fhir_clinical_note_id`: PK
- `fhir_id`: Identificador FHIR
- `fhir_patient_id`: FK a Patient
- `fhir_encounter_id`: FK a Encounter
- `author_type`: Tipo de autor
- `author_id`: ID del autor
- `note_type`: Códigos LOINC (34839-1, 34746-8, etc.)
- `content`: Contenido de la nota
- `status`: preliminary, final, amended, entered-in-error

## Relaciones con Tablas Específicas del Dominio

```
+-------------+       +-------------+       +-------------+
|  Patient    |<----->|   Agente    |<----->|  Provider   |
+-------------+       +-------------+       +-------------+
       ^                     ^                    ^
       |                     |                    |
       v                     v                    v
+-------------+       +-------------+       +-------------+
|  Condition  |<----->| Diagnostico |       |  Junta      |
+-------------+       +-------------+       |  Medica     |
       ^                     ^              +-------------+
       |                     |                    ^
       v                     v                    |
+-------------+       +-------------+       +-------------+
| Observation |<----->| Observacion |<----->|  Licencia   |
+-------------+       +-------------+       +-------------+
                            ^
                            |
                     +-------------+
                     |  Informe    |
                     +-------------+
```

## Campos de Relación con Sistema Existente

### Conexión con Sistema de Recursos Humanos
- Patient -> idagente -> Agente
- Provider -> idagente -> Agente
- Encounter -> idlicencia -> Licencias
- Condition -> idDiagnostico -> Diagnosticos
- Observation -> idObservacion -> Observaciones

### Flujo de Juntas Médicas
1. Se crea un `Encounter` para la junta médica
2. Se asocian `Provider` participantes mediante tabla pivote
3. Se registran `Condition` (diagnósticos) durante la junta
4. Se registran `Observation` (hallazgos) durante la junta
5. Se crean `ClinicalNote` para notas médicas y observaciones
6. Se generan `DocumentReference` para informes y documentos adicionales
7. Se actualiza la `Licencia` según el resultado de la junta

## Estados y Transiciones

### Estados de un Paciente (Patient)
- `active`: Paciente activo en el sistema
- `inactive`: Paciente inactivo
  
### Estados de un Encuentro (Encounter)
- `planned`: Junta médica programada
- `in-progress`: Junta médica en curso
- `finished`: Junta médica finalizada
- `cancelled`: Junta médica cancelada

### Estados de un Diagnóstico (Condition)
- `active`: Diagnóstico actual 
- `resolved`: Diagnóstico resuelto
- `recurrence`: Recurrencia de un problema previo

### Estados de una Nota Clínica (ClinicalNote)
- `preliminary`: Borrador
- `final`: Nota finalizada
- `amended`: Nota modificada después de finalizada

## Codificación y Terminologías

### Códigos de Diagnóstico (Condition.code)
Utilizamos primariamente:
- SNOMED CT para diagnósticos clínicos
- ICD-10 como sistema secundario de codificación

### Códigos de Observación (Observation.code)
Utilizamos:
- LOINC para observaciones y mediciones clínicas estandarizadas

### Tipos de Documentos (DocumentReference.type)
Utilizamos:
- LOINC Document Ontology para clasificar documentos clínicos 
