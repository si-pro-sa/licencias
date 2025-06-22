# Valores Estandarizados para Recursos FHIR

Este documento proporciona una referencia completa de los valores estandarizados que se deben utilizar en los diferentes recursos FHIR implementados en el sistema.

## Estados de Recursos

### Estados Generales
Estos estados se aplican a muchos tipos de recursos:

| Valor | Descripción | Se aplica a |
|-------|-------------|-------------|
| `active` | Recurso activo y en uso | Patient, Provider, Location, Facility |
| `inactive` | Recurso inactivo pero puede reactivarse | Patient, Provider, Location, Facility |
| `entered-in-error` | Recurso creado por error | Todos los recursos |

### Estados de Notas Clínicas (ClinicalNote)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `preliminary` | `FHIR_NOTE_STATUS_PRELIMINARY` | Borrador, no verificado o finalizado |
| `final` | `FHIR_NOTE_STATUS_FINAL` | Nota completada y verificada |
| `amended` | `FHIR_NOTE_STATUS_AMENDED` | Nota modificada después de finalizada |
| `entered-in-error` | `FHIR_NOTE_STATUS_ENTERED_IN_ERROR` | Nota creada por error |

### Estados de Condiciones/Diagnósticos (Condition)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `active` | `FHIR_CONDITION_STATUS_ACTIVE` | Problema o diagnóstico activo actualmente |
| `recurrence` | `FHIR_CONDITION_STATUS_RECURRENCE` | Problema que ha vuelto a aparecer |
| `relapse` | `FHIR_CONDITION_STATUS_RELAPSE` | Recaída o empeoramiento después de mejoría |
| `inactive` | `FHIR_CONDITION_STATUS_INACTIVE` | Problema existe pero no activo actualmente |
| `remission` | `FHIR_CONDITION_STATUS_REMISSION` | Problema disminuido en intensidad pero no resuelto |
| `resolved` | `FHIR_CONDITION_STATUS_RESOLVED` | Problema resuelto o inactivo |

### Estados de Observaciones (Observation)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `registered` | `FHIR_OBS_STATUS_REGISTERED` | Observación registrada pero no verificada |
| `preliminary` | `FHIR_OBS_STATUS_PRELIMINARY` | Observación verificada parcialmente |
| `final` | `FHIR_OBS_STATUS_FINAL` | Observación completada y verificada |
| `amended` | `FHIR_OBS_STATUS_AMENDED` | Observación modificada después de finalizada |
| `corrected` | `FHIR_OBS_STATUS_CORRECTED` | Observación corregida (reemplaza otra) |
| `cancelled` | `FHIR_OBS_STATUS_CANCELLED` | Observación cancelada por error |
| `entered-in-error` | `FHIR_OBS_STATUS_ENTERED_IN_ERROR` | Observación creada por error |
| `unknown` | `FHIR_OBS_STATUS_UNKNOWN` | Estado desconocido |

### Estados de Encuentros (Encounter)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `planned` | `FHIR_ENCOUNTER_STATUS_PLANNED` | Encuentro programado |
| `arrived` | `FHIR_ENCOUNTER_STATUS_ARRIVED` | Paciente ha llegado |
| `triaged` | `FHIR_ENCOUNTER_STATUS_TRIAGED` | Paciente ha sido evaluado inicialmente |
| `in-progress` | `FHIR_ENCOUNTER_STATUS_IN_PROGRESS` | Encuentro en curso |
| `onleave` | `FHIR_ENCOUNTER_STATUS_ONLEAVE` | Paciente ausente temporalmente |
| `finished` | `FHIR_ENCOUNTER_STATUS_FINISHED` | Encuentro finalizado |
| `cancelled` | `FHIR_ENCOUNTER_STATUS_CANCELLED` | Encuentro cancelado |

### Estados de Documentos (DocumentReference)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `current` | `FHIR_DOC_STATUS_CURRENT` | Documento vigente y actual |
| `superseded` | `FHIR_DOC_STATUS_SUPERSEDED` | Documento reemplazado por una versión más reciente |
| `entered-in-error` | `FHIR_DOC_STATUS_ENTERED_IN_ERROR` | Documento creado por error |

## Valores de Uso de Dirección (Address.use)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `home` | `FHIR_ADDRESS_USE_HOME` | Dirección de residencia personal |
| `work` | `FHIR_ADDRESS_USE_WORK` | Dirección laboral |
| `temp` | `FHIR_ADDRESS_USE_TEMP` | Dirección temporal |
| `old` | `FHIR_ADDRESS_USE_OLD` | Dirección anterior (histórica) |
| `billing` | `FHIR_ADDRESS_USE_BILLING` | Dirección de facturación |

## Tipos de Dirección (Address.type)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `postal` | `FHIR_ADDRESS_TYPE_POSTAL` | Dirección postal |
| `physical` | `FHIR_ADDRESS_TYPE_PHYSICAL` | Dirección física para visitas |
| `both` | `FHIR_ADDRESS_TYPE_BOTH` | Dirección tanto postal como física |

## Valores de Sexo (Patient.gender, Provider.gender)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `male` | `FHIR_GENDER_MALE` | Sexo masculino |
| `female` | `FHIR_GENDER_FEMALE` | Sexo femenino |
| `other` | `FHIR_GENDER_OTHER` | Otro sexo |
| `unknown` | `FHIR_GENDER_UNKNOWN` | Sexo desconocido |

## Clases de Encuentro (Encounter.class)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `AMB` | `FHIR_ENCOUNTER_CLASS_AMBULATORY` | Ambulatorio |
| `EMER` | `FHIR_ENCOUNTER_CLASS_EMERGENCY` | Emergencia |
| `IMP` | `FHIR_ENCOUNTER_CLASS_INPATIENT` | Paciente hospitalizado |
| `OUTPATIENT` | `FHIR_ENCOUNTER_CLASS_OUTPATIENT` | Paciente ambulatorio |

## Tipos de Notas Clínicas (ClinicalNote.note_type)

| Valor | Constante | Descripción |
|-------|-----------|-------------|
| `34839-1` | `FHIR_NOTE_TYPE_PHYSICIAN` | Nota médica |
| `34746-8` | `FHIR_NOTE_TYPE_NURSING` | Nota de enfermería |
| `11506-3` | `FHIR_NOTE_TYPE_PROGRESS` | Nota de evolución |
| `11488-4` | `FHIR_NOTE_TYPE_CONSULT` | Nota de consulta |
| `18842-5` | `FHIR_NOTE_TYPE_DISCHARGE` | Resumen de alta |
| `28570-0` | `FHIR_NOTE_TYPE_PROCEDURE` | Nota de procedimiento |

## Estructuras JSON Estándar

### Ejemplo de estructura para nombre (Patient.name, Provider.name)

```json
{
  "use": "official",
  "family": "Pérez",
  "given": ["Juan", "Carlos"],
  "prefix": ["Dr."],
  "suffix": ["Jr."],
  "text": "Dr. Juan Carlos Pérez Jr."
}
```

### Ejemplo de estructura para identificadores (Patient.identifier)

```json
[
  {
    "system": "urn:oid:2.16.840.1.113883.4.330.2",
    "value": "32456789",
    "type": {
      "coding": [
        {
          "system": "http://terminology.hl7.org/CodeSystem/v2-0203",
          "code": "NI",
          "display": "National Unique Individual Identifier"
        }
      ],
      "text": "DNI"
    }
  }
]
```

### Ejemplo de estructura para códigos de diagnóstico (Condition.code)

```json
{
  "coding": [
    {
      "system": "http://snomed.info/sct",
      "code": "279039007",
      "display": "Low back pain"
    },
    {
      "system": "http://hl7.org/fhir/sid/icd-10",
      "code": "M54.5",
      "display": "Low back pain"
    }
  ],
  "text": "Dolor lumbar"
}
```

### Ejemplo de estructura para valores de observación (Observation.value)

```json
{
  "valueQuantity": {
    "value": 140,
    "unit": "mmHg",
    "system": "http://unitsofmeasure.org",
    "code": "mm[Hg]"
  }
}
```

## Referencias

- [FHIR ValueSet Status Codes](http://hl7.org/fhir/valueset-resource-status.html)
- [FHIR Observation Status Codes](https://www.hl7.org/fhir/valueset-observation-status.html)
- [FHIR Condition Status Codes](https://www.hl7.org/fhir/valueset-condition-status.html)
- [FHIR Encounter Status Codes](https://www.hl7.org/fhir/valueset-encounter-status.html)
- [FHIR Address Use Codes](https://www.hl7.org/fhir/valueset-address-use.html)
- [FHIR Address Type Codes](https://www.hl7.org/fhir/valueset-address-type.html)
- [FHIR Administrative Gender](https://www.hl7.org/fhir/valueset-administrative-gender.html)
- [FHIR Encounter Class Codes](https://www.hl7.org/fhir/valueset-encounter-class.html)
- [LOINC Clinical Document Ontology](https://loinc.org/document-ontology/) 
