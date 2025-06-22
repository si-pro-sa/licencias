# Documentación FHIR para Sistema de Licencias de Salud Ocupacional

Este directorio contiene la documentación relacionada con la implementación de FHIR (Fast Healthcare Interoperability Resources) en el sistema de licencias de salud ocupacional.

## Contenido

- [Licencias de Salud Ocupacional y FHIR](licencias-salud-ocupacional.md) - Descripción detallada de la integración entre el sistema de licencias y FHIR.

## Visión General

FHIR es un estándar para el intercambio de datos de salud que permite la interoperabilidad entre diferentes sistemas de información sanitaria. Nuestra implementación utiliza FHIR para estructurar la información clínica relacionada con las licencias de salud ocupacional, juntas médicas y todos los procesos relacionados.

## Arquitectura de Datos

El siguiente diagrama muestra la relación entre las entidades principales del sistema:

```
+------------------+     +--------------------+     +--------------------+
| Agente (Patient) +---->+ Licencia SO        +---->+ Junta Médica       |
+------------------+     +--------------------+     +--------------------+
        |                           |                        |
        v                           v                        v
+------------------+     +--------------------+     +--------------------+
| FHIR Patient     |     | FHIR Conditions    |     | FHIR Encounter     |
+------------------+     +--------------------+     +--------------------+
        |                                                    |
        v                                                    v
+------------------+                                +--------------------+
| FHIR Address     |                                | FHIR Observations  |
+------------------+                                +--------------------+
                                                            |
+------------------+                                        v
| FHIR Provider    |                                +--------------------+
+------------------+                                | FHIR Clinical Notes|
        |                                           +--------------------+
        v                                                    |
+------------------+                                         v
| FHIR Facility    |                                +--------------------+
+------------------+                                | FHIR Document Ref  |
                                                    +--------------------+
```

## Recursos FHIR Implementados

- **FhirPatient**: Representa a un agente que solicita o tiene una licencia de salud ocupacional.
- **FhirProvider**: Representa a un profesional médico involucrado en la evaluación o seguimiento.
- **FhirFacility**: Representa una instalación médica o institución donde se realizan las juntas médicas.
- **FhirLocation**: Representa una ubicación específica dentro de una instalación o área geográfica.
- **FhirEncounter**: Representa una junta médica o consulta realizada al paciente.
- **FhirCondition**: Representa un diagnóstico o condición médica identificada.
- **FhirObservation**: Representa mediciones, resultados o hallazgos clínicos.
- **FhirDocumentReference**: Representa referencias a documentos clínicos y administrativos.
- **FhirClinicalNote**: Representa notas y evaluaciones realizadas por los profesionales.
- **FhirAddress**: Representa direcciones físicas asociadas a pacientes, profesionales e instalaciones.

## Tecnologías Utilizadas

- Laravel 9.x como framework backend
- PHP 8.1+ con soporte para Enums
- MySQL/PostgreSQL para almacenamiento de datos
- Integración con servicios externos a través de APIs RESTful

## Guías para Desarrolladores

Para extender o modificar la implementación FHIR, consulte los siguientes documentos:

- [Guía para Agregar Nuevos Recursos FHIR](licencias-salud-ocupacional.md)
- [Estándares de Codificación](licencias-salud-ocupacional.md)

## Referencias

- [Especificación FHIR](https://www.hl7.org/fhir/)
- [Perfiles FHIR en Argentina](https://simplifier.net/argentinanacionalfhirr4)
