# Licencias de Salud Ocupacional y FHIR

Este documento describe la integración entre el sistema de licencias de salud ocupacional y los recursos FHIR implementados.

## Visión General

El sistema de licencias de salud ocupacional gestiona el proceso completo de solicitud, evaluación, otorgamiento y seguimiento de licencias médicas para los agentes. La implementación FHIR permite estructurar la información clínica relacionada con estas licencias de manera estándar e interoperable.

## Entidades Principales

### Licencia de Salud Ocupacional

Representa una licencia médica otorgada a un agente por motivos de salud.

**Tabla**: `licencias_salud_ocupacional`

**Campos principales**:
- `idlicencia_salud_ocupacional`: Identificador único
- `numero`: Número de licencia
- `fecha_inicio`: Fecha de inicio de la licencia
- `fecha_fin`: Fecha de finalización de la licencia (puede ser null para licencias indefinidas)
- `estado`: Estado actual de la licencia (activa, finalizada, cancelada, etc.)
- `motivo`: Motivo de la licencia
- `tipo`: Tipo de licencia (enfermedad común, accidente laboral, maternidad, etc.)
- `idagente`: Relación con el agente (paciente)

**Relaciones**:
- Un agente (FhirPatient) puede tener múltiples licencias
- Una licencia puede tener múltiples juntas médicas

### Junta Médica

Representa una reunión de evaluación médica formada por múltiples profesionales de salud para determinar la concesión, extensión o finalización de una licencia.

**Tabla**: `juntas_medicas`

**Campos principales**:
- `idjuntamedica`: Identificador único
- `numero`: Número de junta médica
- `tipo`: Tipo de junta (inicial, seguimiento, alta)
- `descripcion`: Descripción de la junta
- `fecha`: Fecha de realización programada
- `estado`: Estado de la junta (programada, realizada, cancelada)
- `fhir_facility_id`: Facilidad donde se realiza la junta
- `idlicencia_salud_ocupacional`: Relación con la licencia evaluada
- `fecha_creacion`: Fecha en que se creó la junta
- `fecha_deliberacion`: Fecha en que la junta deliberó
- `fecha_finalizacion`: Fecha en que finalizó la junta
- `observaciones`: Observaciones generales de la junta
- `diagnostico`: Diagnóstico determinado por la junta
- `recomendaciones`: Recomendaciones emitidas
- `resolucion`: Resolución final de la junta
- `quorum_minimo`: Cantidad mínima de miembros para sesionar (por defecto 5)
- `idagente_presidente`: Agente que preside la junta

**Relaciones**:
- Una licencia puede tener múltiples juntas médicas
- Una junta médica tiene múltiples miembros (agentes)
- Una junta médica corresponde a un FhirEncounter en el sistema FHIR
- Una junta médica se realiza en una facilidad (FhirFacility)

### Miembros de Junta Médica

Representa la relación entre una junta médica y los agentes que la componen, junto con su rol y voto.

**Tabla**: `junta_medica_miembros`

**Campos principales**:
- `id`: Identificador único
- `idjuntamedica`: Relación con la junta médica
- `idagente`: Relación con el agente (profesional)
- `rol`: Rol en la junta (presidente, secretario, vocal, asesor, etc.)
- `voto`: Decisión del miembro (aprobado, rechazado, abstencion)
- `comentario`: Comentario o justificación del miembro
- `asistio`: Indicador de asistencia
- `fecha_voto`: Fecha y hora del voto

**Relaciones**:
- Un agente puede ser miembro de múltiples juntas médicas
- Una junta médica tiene múltiples miembros con diferentes roles

## Flujo de Trabajo

### 1. Solicitud de Licencia

```
Agente --> Sistema --> LicenciaSaludOcupacional --> JuntaMedica (inicial)
```

1. Un agente solicita una licencia por motivos de salud.
2. Se crea un registro en `licencias_salud_ocupacional` con estado "solicitada".
3. Se programa una junta médica inicial para evaluar la solicitud.
4. Se designan al menos 5 miembros para la junta médica con diferentes roles.

### 2. Evaluación Inicial

```
JuntaMedica --> Deliberación y Votación --> FhirEncounter --> [FhirCondition, FhirObservation, FhirClinicalNote]
```

1. Se realiza la junta médica en la fecha programada.
2. Los miembros asignados registran su asistencia.
3. Si se alcanza el quórum mínimo, se procede a la deliberación.
4. Cada miembro registra su voto y comentarios.
5. Se registran diagnósticos, observaciones y recomendaciones.
6. Se genera una resolución final basada en los votos.
7. Todo el proceso se registra como un FhirEncounter.
8. Las condiciones médicas se registran como FhirCondition.
9. Las observaciones y mediciones se registran como FhirObservation.
10. Se crean notas clínicas como FhirClinicalNote.
11. Se actualiza el estado de la licencia según el resultado de la evaluación.

### 3. Seguimiento

```
LicenciaSaludOcupacional (activa) --> JuntaMedica (seguimiento) --> Actualización
```

1. Para licencias activas, se programan juntas médicas de seguimiento periódicas.
2. El proceso es similar a la evaluación inicial, pero centrado en la evolución del caso.
3. Se pueden modificar la duración o condiciones de la licencia.

### 4. Alta Médica

```
JuntaMedica (alta) --> Resolución --> LicenciaSaludOcupacional (finalizada)
```

1. Se realiza una junta médica final para determinar el alta.
2. Los miembros votan sobre la aptitud del agente para retornar al trabajo.
3. Se registra una resolución final.
4. Se actualiza el estado de la licencia a "finalizada".
5. Se documentan las condiciones de retorno al trabajo.

## Mapeo FHIR

### Licencia --> Recursos FHIR

| Entidad de Negocio | Recurso FHIR | Descripción |
|-------------------|--------------|-------------|
| Agente | FhirPatient | Persona que solicita la licencia |
| Licencia | N/A (Objeto de negocio) | Se relaciona con múltiples recursos FHIR |
| Junta Médica | FhirEncounter | Evento clínico de evaluación |
| Miembro de Junta | FhirProvider | Profesional que participa en la junta |
| Diagnóstico | FhirCondition | Condición médica que justifica la licencia |
| Resultados | FhirObservation | Mediciones y hallazgos clínicos |
| Documentos | FhirDocumentReference | Documentos asociados (certificados, estudios) |
| Notas | FhirClinicalNote | Notas y evaluaciones médicas |

## Ejemplo de Integración

```json
{
  "licencia": {
    "numero": "LIC-2023-001",
    "fechaInicio": "2023-10-01",
    "fechaFin": "2023-11-15",
    "estado": "activa",
    "tipo": "enfermedad",
    "motivo": "Reposo por fractura",
    "agente": {
      "fhir_patient_id": 123,
      "fhir_id": "patient-uuid-123"
    },
    "juntasMedicas": [
      {
        "numero": "JM-2023-001",
        "fecha": "2023-09-28",
        "tipo": "inicial",
        "estado": "realizada",
        "quorum_minimo": 5,
        "fecha_deliberacion": "2023-09-28T10:00:00",
        "fecha_finalizacion": "2023-09-28T12:30:00",
        "presidente": {
          "idagente": 100,
          "nombre": "Dr. Juan Pérez",
          "especialidad": "Medicina Laboral"
        },
        "miembros": [
          {
            "idagente": 100,
            "rol": "presidente",
            "voto": "aprobado",
            "asistio": true
          },
          {
            "idagente": 101,
            "rol": "secretario",
            "voto": "aprobado",
            "asistio": true
          },
          {
            "idagente": 102,
            "rol": "vocal",
            "voto": "rechazado",
            "asistio": true,
            "comentario": "Considero que el tiempo solicitado es excesivo"
          },
          {
            "idagente": 103,
            "rol": "vocal",
            "voto": "aprobado",
            "asistio": true
          },
          {
            "idagente": 104,
            "rol": "asesor",
            "voto": "abstención",
            "asistio": true,
            "comentario": "No tengo suficiente información"
          }
        ],
        "resolucion": "Aprobada por mayoría",
        "diagnostico": "Fractura de rótula derecha",
        "observaciones": "El paciente debe seguir tratamiento de fisioterapia",
        "recomendaciones": "Reposo por 45 días con evaluación de seguimiento",
        "encounter": {
          "fhir_encounter_id": 456,
          "fhir_id": "encounter-uuid-456"
        },
        "condiciones": [
          {
            "fhir_condition_id": 789,
            "code": {
              "system": "ICD-10",
              "code": "S82.0",
              "display": "Fractura de rótula"
            }
          }
        ]
      }
    ]
  }
}
```

## Consideraciones Técnicas

### Quórum y Votación

- Una junta médica requiere un quórum mínimo (generalmente 5 miembros) para sesionar.
- La decisión final se basa en la mayoría de votos.
- Cada miembro tiene un voto: aprobado, rechazado o abstención.
- El presidente de la junta tiene voto calificado en caso de empate.

### Roles en la Junta Médica

Los roles típicos incluyen:
- **Presidente**: Dirige la sesión y tiene voto calificado
- **Secretario**: Documenta la sesión y resultados
- **Vocales**: Profesionales con voz y voto
- **Asesores**: Especialistas que pueden opinar pero no siempre votan

### Transaccionalidad

Las operaciones que involucran múltiples recursos FHIR y entidades de negocio deben manejarse de manera transaccional para garantizar la integridad de los datos.

### Trazabilidad

Todos los cambios en el estado de una licencia y sus juntas médicas asociadas deben ser auditables a través de registros de auditoría.

### Notificaciones

El sistema debe notificar automáticamente a los interesados sobre:
- Programación de juntas médicas
- Convocatoria a miembros
- Resultados de deliberaciones
- Cambios en el estado de las licencias

## Conclusión

La integración de FHIR con el sistema de licencias de salud ocupacional proporciona una estructura estandarizada para manejar los aspectos clínicos del proceso, manteniendo la flexibilidad necesaria para los flujos de trabajo específicos del negocio. La estructura de juntas médicas permite un proceso deliberativo y documentado que garantiza la correcta evaluación de cada caso. 
