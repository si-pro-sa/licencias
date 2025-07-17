#!/bin/bash

# Configuración
os=$(uname)
deploy_user=alescano
SUDO=""
WINPTY=""
initialize_variables() {
    case "${os}" in
        Linux)
            SUDO="sudo"
            WINPTY=""
            ;;
        Darwin)
            SUDO=""
            WINPTY=""
            ;;
        MINGW*|CYGWIN*|MSYS*)
            SUDO=""
            WINPTY="winpty"
            os="Windows"
            ;;
    esac
    export SIARHU_DOCKER_DIR=$(pwd)
    export SUDO
    export WINPTY
    export SIARHU_DOCKER_COMPOSE="${SIARHU_DOCKER_DIR}/docker-compose.yml"
    export BACKUP_SERVER="192.168.10.41"
    export BACKUP_NAME="rrhh.backup"
    export BACKUP_DB="rrhh"
    export BACKUP_PATH="/var/lib/postgresql/${BACKUP_NAME}"  # Container path
}

download_backup() {
    echo "Verificando la conexión a la VPN..."
    read -p "¿Desea descargar el último Backup y está CONECTADO a la VPN? (S o N) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        echo "Descargando el último backup..."
        curl -o "${SIARHU_DOCKER_DIR}/${BACKUP_NAME}" "http://${BACKUP_SERVER}/scripts/get_backup.php?user=${deploy_user}"
    fi
}

stop_and_clean() {
    echo "Deteniendo y limpiando los servicios..."
    docker compose -f "${SIARHU_DOCKER_COMPOSE}" stop licencias-db
    ${SUDO} rm -rf "${SIARHU_DOCKER_DIR}/dbdata"
    mkdir -p "${SIARHU_DOCKER_DIR}/dbdata"
    if [ "${os}" != "Windows" ]; then
        ${SUDO} chown -R "$USER:$USER" "${SIARHU_DOCKER_DIR}/dbdata"
        ${SUDO} chmod -R 777 "${SIARHU_DOCKER_DIR}/dbdata"
    fi
}

start_services() {
    echo "Iniciando servicios..."
    docker compose -f "${SIARHU_DOCKER_COMPOSE}" up -d licencias-db --remove-orphans
    docker system prune -f
}

wait_for_db() {
    echo "Esperando a que el servicio de base de datos esté listo..."
    if [ "${os}" = "Windows" ]; then
        until ${WINPTY} docker exec licencias-db pg_isready -U "root" -d "${BACKUP_DB}"; do
            echo "La base de datos no está lista aún. Reintentando..."
            sleep 1
        done
    else
        until docker exec licencias-db pg_isready -U "root" -d "${BACKUP_DB}" > /dev/null 2>&1; do
            echo "La base de datos no está lista aún. Reintentando..."
            sleep 1
        done
    fi
    echo "La base de datos está lista."
}

eliminar_database() {
    backup_path_in_container="/var/lib/postgresql/rrhh.backup"
    echo "Eliminando la base de datos ${BACKUP_DB}..."
    # Wait for PostgreSQL to be ready
    echo "Verificando que el contenedor de PostgreSQL esté listo..."
    while ! ${WINPTY} docker exec licencias-db pg_isready -U root -d rrhh -h localhost; do
        echo "Esperando a que PostgreSQL esté operativo..."
        sleep 5 # Check every 5 seconds
    done

    # Using WINPTY for Docker commands on Windows
    if [ "${os}" = "Windows" ]; then
        ${WINPTY} docker exec -it licencias-db psql -U root -d postgres -c "DROP DATABASE IF EXISTS ${BACKUP_DB};"
    else
        docker exec -i licencias-db psql -U root -d postgres -c "DROP DATABASE IF EXISTS ${BACKUP_DB};"
    fi

    if [ $? -ne 0 ]; then
        echo "Falló la eliminación de la base de datos ${BACKUP_DB}."
        exit 1
    else
        echo "Base de datos ${BACKUP_DB} eliminada exitosamente."
    fi   
}

# Función para restaurar la base de datos
restore_database() {
    echo "Restaurando la base de datos desde ${backup_file}..."
    # Wait for PostgreSQL to be ready
    echo "Verificando que el contenedor de PostgreSQL esté listo..."
    while ! ${WINPTY} docker exec licencias-db pg_isready -U root -d rrhh -h localhost; do
        echo "Esperando a que PostgreSQL esté operativo..."
        sleep 5 # Check every 5 seconds
    done

    # Set the correct path for pg_restore based on the operating system
    backup_path_in_container="/var/lib/postgresql/rrhh.backup"  # Default path inside the container
    if [ "${os}" = "Windows" ]; then
        # In Windows, adjust the path as necessary, keeping in mind Docker volume mappings
        backup_path_in_container="/var/lib/postgresql/rrhh.backup"
    fi

    # Using WINPTY for Docker commands on Windows
    if [ "${os}" = "Windows" ]; then
        ${WINPTY} docker exec -it licencias-db psql -U root -d postgres -c "Create Database rrhh;"
        ${WINPTY} docker exec -it licencias-db pg_restore --no-acl --no-owner -v -U root -d rrhh "${backup_path_in_container}"
    else
        docker exec -it licencias-db psql -U root -d postgres -c "Create Database rrhh;"
        docker exec -i licencias-db pg_restore --no-acl --no-owner -v -U root -d rrhh "${backup_path_in_container}"
    fi

    if [ $? -ne 0 ]; then
        echo "Falló la restauración de la base de datos desde ${backup_file}."
        exit 1
    else
        echo "Base de datos restaurada exitosamente desde ${backup_file}."
    fi
    ${WINPTY} docker exec -it licencias-db psql -U root -d rrhh -c "UPDATE sistema.usuario SET clave='3ed072248edbce552216199915ae28ef';"
}


main() {
    set -xe
    initialize_variables
    #download_backup
    eliminar_database
    #stop_and_clean
    #start_services
    #wait_for_db
    restore_database
    set +xe
}

main
