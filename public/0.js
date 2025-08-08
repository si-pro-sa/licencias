(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./resources/js/api/interceptors.js":
/*!******************************************!*\
  !*** ./resources/js/api/interceptors.js ***!
  \******************************************/
/*! exports provided: setupInterceptors */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setupInterceptors", function() { return setupInterceptors; });
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../store */ "./resources/js/store.js");
/* harmony import */ var _router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../router */ "./resources/js/router.js");
/**
 * @file api/interceptors.js
 * @description Interceptores axios para peticiones API
 */



/**
 * Configura interceptores axios
 * @param {Object} axiosInstance - Instancia de axios
 */
function setupInterceptors(axiosInstance) {
  // Interceptor de peticiones
  axiosInstance.interceptors.request.use(function (config) {
    // Añadir encabezado de autorización con token JWT si está disponible
    var token = _store__WEBPACK_IMPORTED_MODULE_0__["default"].getters['auth/getToken'];
    if (token) {
      config.headers.Authorization = "Bearer ".concat(token);
    }

    // Añadir marca de tiempo para depuración
    config.metadata = {
      startTime: new Date().getTime()
    };

    // Registrar peticiones salientes en desarrollo
    if (true) {
      console.log('Petición API:', {
        method: config.method.toUpperCase(),
        url: config.url,
        params: config.params,
        data: config.data
      });
    }
    return config;
  }, function (error) {
    console.error('Error de petición:', error);
    return Promise.reject(error);
  });

  // Interceptor de respuestas
  axiosInstance.interceptors.response.use(function (response) {
    // Calcular duración de la petición
    var duration = new Date().getTime() - response.config.metadata.startTime;

    // Registrar tiempo de respuesta en desarrollo
    if (true) {
      console.log("Respuesta API (".concat(duration, "ms):"), {
        status: response.status,
        data: response.data
      });
    }
    return response;
  }, function (error) {
    var _error$response, _error$response2;
    // Manejar errores 401 No autorizado redirigiendo al login
    if (error.response && error.response.status === 401) {
      _store__WEBPACK_IMPORTED_MODULE_0__["default"].dispatch('auth/logout');
      _router__WEBPACK_IMPORTED_MODULE_1__["default"].push({
        name: 'login',
        query: {
          redirect: _router__WEBPACK_IMPORTED_MODULE_1__["default"].currentRoute.fullPath
        }
      });
    }

    // Registrar errores API
    console.error('Error API:', {
      message: error.message,
      status: (_error$response = error.response) === null || _error$response === void 0 ? void 0 : _error$response.status,
      data: (_error$response2 = error.response) === null || _error$response2 === void 0 ? void 0 : _error$response2.data
    });
    return Promise.reject(error);
  });
}

/***/ }),

/***/ "./resources/js/api/licenciaApi.js":
/*!*****************************************!*\
  !*** ./resources/js/api/licenciaApi.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _errorHandler__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./errorHandler */ "./resources/js/api/errorHandler.js");
/* harmony import */ var _interceptors__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./interceptors */ "./resources/js/api/interceptors.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_3__);
/**
 * @file api/licenciaApi.js
 * @description Servicio API para gestión de licencias
 */





// Cliente axios con configuración predeterminada
var apiClient = axios__WEBPACK_IMPORTED_MODULE_0___default.a.create({
  baseURL: '/api',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json'
  }
});

// Configurar interceptores
Object(_interceptors__WEBPACK_IMPORTED_MODULE_2__["setupInterceptors"])(apiClient);

/**
 * Servicio API de licencias
 * @namespace LicenciaApi
 */
/* harmony default export */ __webpack_exports__["default"] = ({
  /**
   * Obtiene todas las licencias con filtrado opcional
   * @memberof LicenciaApi
   * @param {Object} params - Parámetros de consulta
   * @param {number} params.page - Número de página
   * @param {string} params.search - Término de búsqueda
   * @param {string} params.estado - Estado de la licencia
   * @returns {Promise<Object>} Respuesta con datos de licencias
   */
  getLicencias: function getLicencias() {
    var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return apiClient.get('/licencias', {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias');
    });
  },
  /**
   * Obtiene una licencia por ID
   * @memberof LicenciaApi
   * @param {number|string} id - ID de la licencia
   * @returns {Promise<Object>} Datos de la licencia
   */
  getLicencia: function getLicencia(id) {
    if (!id) throw new Error('Se requiere el ID de la licencia');
    return apiClient.get("/licencias/".concat(id)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, "Error al obtener la licencia ".concat(id));
    });
  },
  /**
   * Obtiene las licencias de un agente específico
   * @memberof LicenciaApi
   * @param {number|string} agenteId - ID del agente
   * @param {Object} params - Parámetros adicionales
   * @returns {Promise<Object>} Licencias del agente
   */
  getLicenciasPorAgente: function getLicenciasPorAgente(agenteId) {
    var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    if (!agenteId) throw new Error('Se requiere el ID del agente');
    return apiClient.get("/agentes/".concat(agenteId, "/licencias"), {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, "Error al obtener licencias del agente ".concat(agenteId));
    });
  },
  /**
   * Obtiene licencias de un agente por tipo de licencia
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con parámetros
   * @param {number|string} obj.idagente - ID del agente
   * @param {number|string} obj.tipoLicencia - ID del tipo de licencia
   * @returns {Promise<Object>} Licencias del agente del tipo específico
   */
  getLicenciasPorAgenteYTipo: function getLicenciasPorAgenteYTipo(obj) {
    if (!obj.idagente) throw new Error('Se requiere el ID del agente');
    if (!obj.tipoLicencia) throw new Error('Se requiere el tipo de licencia');
    return apiClient.get("/api/licencias/agente/".concat(obj.idagente, "/").concat(obj.tipoLicencia)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias');
    });
  },
  /**
   * Obtiene las licencias totales de un agente
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con parámetros
   * @param {number|string} obj.idagente - ID del agente
   * @returns {Promise<Object>} Todas las licencias del agente
   */
  getLicenciasTotales: function getLicenciasTotales(obj) {
    if (!obj.idagente) throw new Error('Se requiere el ID del agente');
    return apiClient.get("/api/licencias/agente/".concat(obj.idagente)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias totales');
    });
  },
  /**
   * Obtiene licencias por dependencia con filtros
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con parámetros y filtros
   * @returns {Promise<Object>} Licencias filtradas
   */
  getLicenciasDependientes: function getLicenciasDependientes(obj) {
    var params = new URLSearchParams();

    // Parámetros de paginación y ordenamiento
    var _ref = obj.options || {},
      page = _ref.page,
      itemsPerPage = _ref.itemsPerPage,
      sortBy = _ref.sortBy,
      sortDesc = _ref.sortDesc;
    if (page) params.append('page', page);
    if (itemsPerPage) params.append('itemsPerPage', itemsPerPage);
    if (sortBy) params.append('sortBy', sortBy);
    if (sortDesc) params.append('sortDesc', sortDesc);

    // ID del agente
    if (obj.idagente) params.append('idagente', obj.idagente);

    // Aplicar filtros si existen
    if (obj.filters) {
      var _obj$filters = obj.filters,
        efector = _obj$filters.efector,
        codigo_nombre = _obj$filters.codigo_nombre,
        documento = _obj$filters.documento,
        apellido_nombre = _obj$filters.apellido_nombre,
        tipoLicencia = _obj$filters.tipoLicencia,
        dias = _obj$filters.dias,
        idlicencia = _obj$filters.idlicencia,
        fecha_pedido_inicio = _obj$filters.fecha_pedido_inicio,
        fecha_pedido_final = _obj$filters.fecha_pedido_final,
        primer_visado = _obj$filters.primer_visado,
        segundo_visado = _obj$filters.segundo_visado,
        fecha_efectiva_inicio = _obj$filters.fecha_efectiva_inicio,
        fecha_efectiva_final = _obj$filters.fecha_efectiva_final,
        cuarta_visado = _obj$filters.cuarta_visado,
        fecha_interrupcion_inicio = _obj$filters.fecha_interrupcion_inicio;
      if (efector) params.append('efector', efector.toUpperCase());
      if (codigo_nombre) params.append('codigo_nombre', codigo_nombre.toUpperCase());
      if (documento) params.append('documento', documento);
      if (apellido_nombre) params.append('apellido_nombre', apellido_nombre.toUpperCase());
      if (tipoLicencia) params.append('tipoLicencia', tipoLicencia.toLowerCase());
      if (dias) params.append('dias', dias);
      if (idlicencia) params.append('idlicencia', idlicencia);
      if (fecha_pedido_inicio) params.append('fecha_pedido_inicio', fecha_pedido_inicio);
      if (fecha_pedido_final) params.append('fecha_pedido_final', fecha_pedido_final);
      if (primer_visado >= 0) params.append('primer_visado', primer_visado);
      if (segundo_visado >= 0) params.append('segundo_visado', segundo_visado);
      if (cuarta_visado) params.append('cuarta_visado', cuarta_visado);
      if (fecha_efectiva_inicio) params.append('fecha_efectiva_inicio', fecha_efectiva_inicio);
      if (fecha_efectiva_final) params.append('fecha_efectiva_final', fecha_efectiva_final);
      if (fecha_interrupcion_inicio) params.append('fecha_interrupcion_inicio', fecha_interrupcion_inicio);
    }

    // Dependencia
    if (obj.dependencia) params.append('dependencia', obj.dependencia);
    return apiClient.get('/api/licencias/dependiente/', {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias dependientes');
    });
  },
  /**
   * Obtiene licencias para capacitación
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con parámetros
   * @returns {Promise<Object>} Licencias de capacitación
   */
  getLicenciasDependientesCapacitacion: function getLicenciasDependientesCapacitacion(obj) {
    var params = new URLSearchParams();

    // Parámetros similares a getLicenciasDependientes
    // Añadiendo parámetros específicos para capacitación
    if (obj.filters) {
      var _obj$filters2 = obj.filters,
        alcance = _obj$filters2.alcance,
        caracter = _obj$filters2.caracter,
        tipo_evento = _obj$filters2.tipo_evento,
        evento_nombre = _obj$filters2.evento_nombre,
        evento_lugar = _obj$filters2.evento_lugar;
      if (alcance) params.append('alcance', alcance);
      if (caracter) params.append('caracter', caracter);
      if (tipo_evento) params.append('tipoEvento', tipo_evento);
      if (evento_lugar) params.append('evento_lugar', evento_lugar);
      if (evento_nombre) params.append('evento_nombre', evento_nombre);
    }

    // Añadir los demás parámetros como en getLicenciasDependientes
    // ...

    return apiClient.get('/api/licencias/masivo/capacitacion/', {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias de capacitación');
    });
  },
  /**
   * Obtiene licencias para consulta en un rango de fechas
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con fecha_desde, fecha_hasta y dependencia opcional
   * @returns {Promise<Object>} Licencias en el rango de fechas
   */
  getLicenciasConsulta: function getLicenciasConsulta(obj) {
    var params = new URLSearchParams();
    params.append('fecha_desde', obj.fecha_desde);
    params.append('fecha_hasta', obj.fecha_hasta);
    if (obj.dependencia) {
      params.append('dependencia', obj.dependencia);
    }
    if (obj.tipo_licencias) {
      params.append('tipo_licencias', obj.tipo_licencias);
    }
    return apiClient.get('/api/licencias/consulta', {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias para consulta');
    });
  },
  /**
   * Obtiene licencias mensuales
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con mes y dependencia opcional
   * @returns {Promise<Object>} Licencias mensuales
   */
  getLicenciasMensuales: function getLicenciasMensuales(obj) {
    var params = new URLSearchParams();
    params.append('mes', obj.mes);
    if (obj.dependencia) {
      params.append('dependencia', obj.dependencia);
    }
    if (obj.tipo_licencias) {
      params.append('tipo_licencias', obj.tipo_licencias);
    }
    return apiClient.get('/api/licencias/consulta/mensual', {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias mensuales');
    });
  },
  /**
   * Obtiene licencias retroactivas
   * @memberof LicenciaApi
   * @param {Object} obj - Objeto con mes y dependencia opcional
   * @returns {Promise<Object>} Licencias retroactivas
   */
  getLicenciasRetroactiva: function getLicenciasRetroactiva(obj) {
    var params = new URLSearchParams();
    params.append('mes', obj.mes);
    if (obj.dependencia) {
      params.append('dependencia', obj.dependencia);
    }
    if (obj.tipo_licencias) {
      params.append('tipo_licencias', obj.tipo_licencias);
    }
    return apiClient.get('/api/licencias/consulta/mensual/retroactiva', {
      params: params
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener licencias retroactivas');
    });
  },
  /**
   * Crea una nueva licencia
   * @memberof LicenciaApi
   * @param {Object} licenciaData - Datos de la licencia
   * @returns {Promise<Object>} Datos de la licencia creada
   */
  crearLicencia: function crearLicencia(licenciaData) {
    // Validación de campos requeridos se realiza en el API endpoint

    // Verificar si es una licencia de salud ocupacional para incluir diagnóstico
    var salud_ocupacional = [1, 2, 3, 4, 7, 8, 11, 21, 22, 38, 40, 39, 38, 37];
    return apiClient.post('/api/licencias/complete', licenciaData).then(function (response) {
      var _licenciaData$;
      // Si hay un diagnóstico y es una licencia de salud ocupacional
      if (salud_ocupacional.includes(licenciaData[0].idtipoLicencia) && ((_licenciaData$ = licenciaData[4]) === null || _licenciaData$ === void 0 ? void 0 : _licenciaData$.codigo) !== '') {
        var licencia_id = response.data.data.idlicencia;
        var formData = licenciaData[4];

        // Subir diagnóstico
        return apiClient.post("/api/diagnosticos/licencia/".concat(licencia_id), formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(function () {
          return response.data;
        });
      }
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al crear la licencia');
    });
  },
  /**
   * Actualiza una licencia existente
   * @memberof LicenciaApi
   * @param {number|string} id - ID de la licencia
   * @param {Object} licenciaData - Datos actualizados
   * @returns {Promise<Object>} Datos de la licencia actualizada
   */
  actualizarLicencia: function actualizarLicencia(id, licenciaData) {
    if (!id) throw new Error('Se requiere el ID de la licencia');

    // Verificar si es una licencia de salud ocupacional para actualizar diagnóstico
    var salud_ocupacional = [1, 2, 3, 4, 7, 8, 11, 21, 22, 38, 40, 39, 38, 37];
    return apiClient.put("/api/licencias/".concat(id), licenciaData).then(function (response) {
      var _licenciaData$2;
      // Si hay un diagnóstico y es una licencia de salud ocupacional
      if (salud_ocupacional.includes(licenciaData[0].idtipoLicencia) && ((_licenciaData$2 = licenciaData[4]) === null || _licenciaData$2 === void 0 ? void 0 : _licenciaData$2.codigo) !== '') {
        var formData = licenciaData[4];

        // Actualizar diagnóstico
        return apiClient.put("/api/diagnosticos/licencia/".concat(id), formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(function () {
          return response.data;
        });
      }
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, "Error al actualizar la licencia ".concat(id));
    });
  },
  /**
   * Realiza el primer visado de múltiples licencias
   * @memberof LicenciaApi
   * @param {Object} data - Datos para el visado masivo
   * @returns {Promise<Object>} Resultado de la operación
   */
  primerVisadoTodo: function primerVisadoTodo(data) {
    return apiClient.put('/api/licencias/masivo/primer', data).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al realizar el primer visado masivo');
    });
  },
  /**
   * Realiza el segundo visado de múltiples licencias
   * @memberof LicenciaApi
   * @param {Object} data - Datos para el visado masivo
   * @returns {Promise<Object>} Resultado de la operación
   */
  segundoVisadoTodo: function segundoVisadoTodo(data) {
    return apiClient.put('/api/licencias/masivo/segundo', data).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al realizar el segundo visado masivo');
    });
  },
  /**
   * Desvisar una licencia
   * @memberof LicenciaApi
   * @param {Object} licencia - Licencia a desvisar
   * @returns {Promise<Object>} Licencia actualizada
   */
  desvisarLicencia: function desvisarLicencia(licencia) {
    if (!licencia.idlicencia) throw new Error('Se requiere el ID de la licencia');
    return apiClient.put("/api/licencias/desvisar/".concat(licencia.idlicencia)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al desvisar la licencia');
    });
  },
  /**
   * Cambia el estado de una licencia
   * @memberof LicenciaApi
   * @param {number|string} id - ID de la licencia
   * @param {string} nuevoEstado - Nuevo estado de la licencia
   * @returns {Promise<Object>} Respuesta del servidor
   */
  cambiarEstadoLicencia: function cambiarEstadoLicencia(id, nuevoEstado) {
    if (!id) throw new Error('Se requiere el ID de la licencia');
    if (!nuevoEstado) throw new Error('Se requiere el nuevo estado');
    return apiClient.patch("/licencias/".concat(id, "/estado"), {
      estado: nuevoEstado
    }).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, "Error al cambiar estado de la licencia ".concat(id));
    });
  },
  /**
   * Elimina una licencia
   * @memberof LicenciaApi
   * @param {number|string} id - ID de la licencia
   * @returns {Promise<Object>} Respuesta del servidor
   */
  eliminarLicencia: function eliminarLicencia(id) {
    if (!id) throw new Error('Se requiere el ID de la licencia');
    return apiClient["delete"]("/api/licencias/".concat(id)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, "Error al eliminar la licencia ".concat(id));
    });
  },
  /**
   * Exporta licencias a Excel
   * @memberof LicenciaApi
   * @param {Object} data - Datos para la exportación
   * @returns {Promise<Object>} Resultado de la exportación
   */
  exportXLS: function exportXLS(data) {
    return apiClient.put('/api/licencias/exportar', data).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al exportar licencias a Excel');
    });
  },
  /**
   * Obtiene días posibles de licencia para un agente
   * @memberof LicenciaApi
   * @param {number|string} idagente - ID del agente
   * @returns {Promise<Object>} Días posibles de licencia
   */
  getDiasPosibles: function getDiasPosibles(idagente) {
    if (!idagente) throw new Error('Se requiere el ID del agente');
    return apiClient.get("/api/licencias/dias/".concat(idagente)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener días posibles');
    });
  },
  /**
   * Obtiene feriados
   * @memberof LicenciaApi
   * @returns {Promise<Object>} Lista de feriados
   */
  getFeriados: function getFeriados() {
    return apiClient.get('/api/licencias/feriados').then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener feriados');
    });
  },
  /**
   * Obtiene personas activas
   * @memberof LicenciaApi
   * @param {Array} arr - Array con parámetros
   * @returns {Promise<Object>} Lista de personas activas
   */
  getPersonasActivas: function getPersonasActivas(arr) {
    return apiClient.get("/api/licencias/personas/".concat(arr[1])).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener personas activas');
    });
  },
  /**
   * Obtiene personas discapacitadas activas
   * @memberof LicenciaApi
   * @param {number|string} idagente - ID del agente
   * @returns {Promise<Object>} Lista de personas discapacitadas activas
   */
  getPersonasDiscapacitadaActivas: function getPersonasDiscapacitadaActivas(idagente) {
    return apiClient.get("/api/licencias/personasDiscapacitada/".concat(idagente)).then(function (response) {
      return response.data;
    })["catch"](function (error) {
      return Object(_errorHandler__WEBPACK_IMPORTED_MODULE_1__["handleError"])(error, 'Error al obtener personas discapacitadas activas');
    });
  }
});

/***/ })

}]);
//# sourceMappingURL=0.js.map