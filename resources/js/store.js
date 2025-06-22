import Vue from 'vue'
import Vuex from 'vuex'

import GrupoModule from './modules/GrupoModule.js'
import AdminGrupoModule from './modules/AdminGrupoModule.js'
import AgenteModule from './modules/AgenteModule.js'
import LicenciaModule from './modules/LicenciaModule.js'
import AntiguedadModule from './modules/AntiguedadModule.js'
import SancionModule from './modules/SancionModule'
import UserModule from './modules/UserModule'
import TipoLicenciaModule from './modules/TipoLicenciaModule'
import SaldoModule from './modules/SaldoModule'
import TipoFuncionModule from './modules/TipoFuncionModule'
import AlcanceModule from './modules/AlcanceModule'
import CapacitacionModule from './modules/CapacitacionModule'
import CaracterModule from './modules/CaracterModule'
import TipoEventoModule from './modules/TipoEventoModule'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        grupo: GrupoModule,
        adminGrupo: AdminGrupoModule,
        agente: AgenteModule,
        licencia: LicenciaModule,
        sancion: SancionModule,
        antiguedad: AntiguedadModule,
        user: UserModule,
        tipoLicencia: TipoLicenciaModule,
        saldo: SaldoModule,
        tipoFuncion: TipoFuncionModule,
        capacitacion: CapacitacionModule,
        alcance: AlcanceModule,
        caracter: CaracterModule,
        tipoEvento: TipoEventoModule
    }
})
