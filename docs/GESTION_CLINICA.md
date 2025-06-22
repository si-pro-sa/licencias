# Gestión Clínica - Módulo FHIR

Este módulo permite gestionar prestadores de salud y establecimientos utilizando estándares FHIR (Fast Healthcare Interoperability Resources).

## Características

### Prestadores (Providers)
- **Agentes internos**: Vinculación con agentes existentes del sistema
- **Prestadores externos**: Registro de profesionales externos
- **Campos FHIR**: Nombre, género, fecha de nacimiento, calificaciones, telecomunicaciones
- **Especialidades**: Médicos, enfermeros, psicólogos, kinesiólogos, etc.
- **Matrícula profesional**: Registro de credenciales
- **Vinculación con establecimientos**: Asignación a centros de trabajo

### Establecimientos (Facilities)
- **Información básica**: Nombre, código SISA, tipo, nivel de atención
- **Ubicación**: Dirección completa con provincia y localidad
- **Contacto**: Teléfono, email, responsable
- **Servicios**: Lista de servicios disponibles
- **Estándares FHIR**: Estructura compatible con FHIR R4

## Instalación

### 1. Ejecutar migraciones
```bash
php artisan migrate
```

### 2. Ejecutar seeders (opcional)
```bash
php artisan db:seed --class=FhirGestionClinicaSeeder
```

### 3. Agregar permisos
Ejecutar en la base de datos:
```sql
INSERT INTO permissions (name, display_name, description) 
VALUES ('ver-gestionClinica', 'Ver Gestión Clínica', 'Permite acceder al módulo de gestión clínica');
```

### 4. Asignar permisos a roles
```sql
-- Ejemplo: asignar a rol de administrador (ajustar según tu estructura)
INSERT INTO permission_role (permission_id, role_id) 
SELECT p.id, r.id 
FROM permissions p, roles r 
WHERE p.name = 'ver-gestionClinica' 
AND r.name = 'admin';
```

### 5. Compilar assets
```bash
npm run dev
# o para producción
npm run production
```

## Uso

### Acceso al módulo
1. Navegar al menú lateral
2. Buscar la sección "Gestión Clínica"
3. Seleccionar "Prestadores" o "Establecimientos"

### Gestión de Prestadores

#### Crear prestador interno (agente)
1. Hacer clic en "Nuevo Prestador"
2. Seleccionar "Agente interno"
3. Buscar el agente por nombre o DNI
4. Completar especialidad y matrícula
5. Asignar establecimiento (opcional)
6. Guardar

#### Crear prestador externo
1. Hacer clic en "Nuevo Prestador"
2. Seleccionar "Prestador externo"
3. Completar datos personales (nombre, apellido, DNI)
4. Completar información profesional
5. Guardar

### Gestión de Establecimientos

#### Crear establecimiento
1. Hacer clic en "Nuevo Establecimiento"
2. Completar información básica
3. Agregar ubicación completa
4. Configurar contacto
5. Seleccionar servicios disponibles
6. Guardar

## API Endpoints

### Prestadores (FHIR Providers)
- `GET /api/providers` - Listar prestadores
- `POST /api/providers` - Crear prestador
- `GET /api/providers/{id}` - Ver prestador
- `PUT /api/providers/{id}` - Actualizar prestador
- `DELETE /api/providers/{id}` - Eliminar prestador
- `GET /api/providers/search/agentes?q={query}` - Buscar agentes

### Establecimientos (FHIR Facilities)
- `GET /api/facilities` - Listar establecimientos
- `POST /api/facilities` - Crear establecimiento
- `GET /api/facilities/{id}` - Ver establecimiento
- `PUT /api/facilities/{id}` - Actualizar establecimiento
- `DELETE /api/facilities/{id}` - Eliminar establecimiento

## Estructura de datos FHIR

### Provider (Prestador)
```json
{
  "fhir_id": "uuid",
  "active": true,
  "name": {
    "use": "official",
    "family": "Apellido",
    "given": ["Nombre"],
    "identifier": "DNI"
  },
  "gender": "male|female|other|unknown",
  "birth_date": "1980-01-01",
  "qualification": [{
    "code": {
      "coding": [{
        "system": "http://terminology.hl7.org/CodeSystem/practitioner-role",
        "code": "medico-general",
        "display": "Médico/a General"
      }]
    },
    "identifier": [{
      "system": "matricula",
      "value": "MN-12345"
    }]
  }],
  "telecom": [{
    "system": "phone|email",
    "value": "valor",
    "use": "work"
  }]
}
```

### Facility (Establecimiento)
```json
{
  "fhir_id": "uuid",
  "status": "active|inactive",
  "name": {
    "text": "Nombre del establecimiento",
    "use": "official"
  },
  "alias": ["CODIGO-SISA"],
  "type": [{
    "coding": [{
      "system": "http://terminology.hl7.org/CodeSystem/v3-RoleCode",
      "code": "1|2|3",
      "display": "Tipo de establecimiento"
    }]
  }],
  "telecom": [{
    "system": "phone|email",
    "value": "valor",
    "use": "work"
  }]
}
```

## Validaciones

### Prestadores
- **Agente interno**: Debe seleccionar un agente existente
- **Prestador externo**: Nombre, apellido y DNI son obligatorios
- **Especialidad**: Campo obligatorio
- **Matrícula**: Formato alfanumérico opcional
- **Email**: Formato válido si se proporciona
- **DNI**: 7-8 dígitos numéricos

### Establecimientos
- **Nombre**: Obligatorio, mínimo 3 caracteres
- **Tipo**: Obligatorio
- **Nivel de atención**: 1-3 (Primario, Especializado, Alta complejidad)
- **Dirección**: Obligatoria
- **Provincia y localidad**: Obligatorias
- **Email**: Formato válido si se proporciona

## Filtros y búsqueda

### Prestadores
- Búsqueda por nombre completo
- Filtro por tipo de prestador
- Filtro por estado (activo/inactivo)

### Establecimientos
- Búsqueda por nombre
- Filtro por tipo de establecimiento
- Filtro por estado (activo/inactivo)

## Consideraciones técnicas

### Base de datos
- Utiliza las tablas FHIR existentes: `fhir_providers`, `fhir_facilities`, `fhir_addresses`
- Soft deletes habilitado para mantener historial
- Índices optimizados para búsquedas
- Relaciones con tabla `agentes` existente

### Frontend
- Componentes Vue.js con Vuetify
- Validación en tiempo real
- Interfaz responsive
- Autocomplete para búsqueda de agentes

### Backend
- Controladores FHIR Laravel con validación robusta
- Manejo de errores centralizado
- Logs de auditoría
- Transacciones de base de datos
- Endpoints FHIR estándar bajo `/api/fhir/`

## Troubleshooting

### Error: "Prestador no encontrado"
- Verificar que el ID existe en la base de datos
- Comprobar permisos de usuario

### Error: "Agente no encontrado"
- Verificar que la tabla `agentes` tiene datos
- Comprobar que el agente está activo

### Error de permisos
- Verificar que el usuario tiene el permiso `ver-gestionClinica`
- Comprobar asignación de roles

### Problemas de compilación
```bash
# Limpiar cache
npm run clean
php artisan config:clear
php artisan route:clear

# Reinstalar dependencias
npm install
npm run dev
``` 
