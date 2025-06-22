import jsPDF from 'jspdf';
import 'jspdf-autotable';
import _ from 'lodash';
import moment from 'moment';

export default {
    data: (vm) => ({
        headings: {
            ConsultaAgenteLic: [
                { title: 'Dias', dataKey: 'dias' },
                { title: 'Nº de Lic.', dataKey: 'idlicencia' },
                { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                { title: 'Primer Visado', dataKey: 'primer_visado' },
                { title: 'Segundo Visado', dataKey: 'segundo_visado' },
                { title: 'Interrupcion', dataKey: 'cuarta_visado' },
                {
                    title: 'Fecha Desde',
                    dataKey: 'fecha_efectiva_inicio',
                },
                {
                    title: 'Fecha Hasta',
                    dataKey: 'fecha_efectiva_final',
                },
            ],
            ConsultaAgenteSaldo: [
                { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                { title: 'Año', dataKey: 'año' },
                { title: 'Disponible', dataKey: 'disponible' },
                { title: 'Pedido', dataKey: 'dias' },
            ],
            SaldosLAO: [
                { title: 'Efector', datakey: 'efector' },
                { title: 'Servicio', datakey: 'codigo_nombre' },
                { title: 'Documento', datakey: 'documento' },
                { title: 'Apellido y Nombre', datakey: 'apellido_nombre' },
                { title: 'Antiguedad', datakey: 'antiguedad' },
                { title: 'Año', datakey: 'año' },
                { title: 'Disponible', datakey: 'disponible' },
                { title: 'Pedido', datakey: 'pedido' },
                { title: 'Saldo', datakey: 'saldo' },
            ],
            Antiguedad: [
                { title: 'Año', dataKey: 'año' },
                { title: 'Pedido', dataKey: 'pedido' },
                { title: 'Disponible', dataKey: 'disponible' },
                { title: 'Vigente', dataKey: 'vigente' },
                { title: 'Fecha de Alta', dataKey: 'created_at' },
                { title: 'Usuario Responsable', dataKey: 'nombreusuario' },
            ],
            ExpedienteDDJJ: [
                { title: 'Nº de DDJJ', dataKey: 'nExpediente' },
                { title: 'Fecha de Alta', dataKey: 'created_at' },
                { title: 'Aprobado', dataKey: 'aprobado' },
                { title: 'Activo', dataKey: 'activo' },
                { title: 'Fecha de Vencimiento', dataKey: 'vencimiento' },
            ],
            ExpedienteFamiliares: [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre', dataKey: 'nombre' },
                { title: 'Apellido', dataKey: 'apellido' },
                { title: 'Fecha de Nacimiento', dataKey: 'fecha_nacimiento' },
                { title: 'Parentesco', dataKey: 'parentesco' },
                { title: 'Discapacidad', dataKey: 'discapacidad' },
            ],
            Sanciones: [
                { title: 'EXPEDIENTE', dataKey: 'expediente' },
                { title: 'RESOL. DE INST. SUMARIO', dataKey: 'resolucion' },
                { title: 'CAUSAL', dataKey: 'reseña' },
                { title: 'DICT. DE CONCL.', dataKey: 'conclusion' },
                { title: 'RESOL. DE CONCL.', dataKey: 'acuerdo' },
                { title: 'Usuario Responsable', dataKey: 'nombreusuario' },
                { title: 'Fecha Inicio Sanción', dataKey: 'fecha_inicio' },
                { title: 'Fecha Fin Sanción', dataKey: 'fecha_final' },
                { title: 'Fecha Creación Sanción', dataKey: 'created_at' },
            ],
            Licencias: [
                { title: 'Dias', dataKey: 'dias' },
                { title: 'Nº de Lic.', dataKey: 'idlicencia' },
                { title: 'Tipo de Licencia', dataKey: 'descripcion' },
                { title: 'Primer Visado', dataKey: 'primer_visado' },
                { title: 'Segundo Visado', dataKey: 'segundo_visado' },
                { title: 'Interrupcion', dataKey: 'cuarta_visado' },
                {
                    title: 'Fecha Inicial del Pedido',
                    dataKey: 'fecha_pedido_inicio',
                },
                {
                    title: 'Fecha Final del Pedido',
                    dataKey: 'fecha_pedido_final',
                },
                {
                    title: 'Fecha Inical Efectiva',
                    dataKey: 'fecha_efectiva_inicio',
                },
                {
                    title: 'Fecha Final Efectiva',
                    dataKey: 'fecha_efectiva_final',
                },
                {
                    title: 'Fecha de la Interrupción',
                    dataKey: 'fecha_interrupcion_inicio',
                },
            ],
            ConsultaLicencia: [
                { title: 'Numero de Licencia', datakey: 'idlicencia' },
                { title: 'Efector', datakey: 'efector' },
                { title: 'Servicio', datakey: 'codigo_nombre' },
                { title: 'Documento', datakey: 'documento' },
                { title: 'Apellido y Nombre', datakey: 'apellido_nombre' },
                { title: 'Tipo de Licencia', datakey: 'descripcion' },
                { title: 'Dias de Licencia', datakey: 'dias' },
                {
                    title: 'Fecha del Inicio del Pedido',
                    datakey: 'fecha_pedido_inicio',
                },
                {
                    title: 'Fecha Final del Pedido',
                    datakey: 'fecha_pedido_final',
                },
                { title: 'Primer Visado', datakey: 'primer_visado' },
                {
                    title: 'Fecha Efectiva de Inicio',
                    datakey: 'fecha_efectiva_inicio',
                },
                {
                    title: 'Fecha Efectiva Final',
                    datakey: 'fecha_efectiva_final',
                },
                { title: 'Segundo Visado', datakey: 'segundo_visado' },
            ],
        },
    }),
    methods: {
        preprocessDataset(name, datasets) {
            let dataset = _.cloneDeep(datasets);
            switch (name) {
                case 'Licencia':
                    _.each(dataset, function (row, idx) {
                        dataset[idx].primer_visado =
                            row.primer_visado == true ? 'Si' : 'No';
                        dataset[idx].segundo_visado =
                            row.segundo_visado == true ? 'Si' : 'No';
                        dataset[idx].cuarta_visado =
                            row.cuarta_visado == true ? 'Si' : 'No';
                        let fpi =
                            row.fecha_pedido_inicio != null
                                ? row.fecha_pedido_inicio
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fpf =
                            row.fecha_pedido_final != null
                                ? row.fecha_pedido_final
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fei =
                            row.fecha_efectiva_inicio != null
                                ? row.fecha_efectiva_inicio
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fef =
                            row.fecha_efectiva_final != null
                                ? row.fecha_efectiva_final
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fii =
                            row.fecha_interrupcion_inicio != null
                                ? row.fecha_interrupcion_inicio
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        dataset[idx].fecha_pedido_inicio =
                            fpi != null
                                ? new Date(
                                      fpi[0],
                                      parseInt(fpi[1]) - 1,
                                      fpi[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_pedido_final =
                            fpf != null
                                ? new Date(
                                      fpf[0],
                                      parseInt(fpf[1]) - 1,
                                      fpf[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_efectiva_inicio =
                            fei != null
                                ? new Date(
                                      fei[0],
                                      parseInt(fei[1]) - 1,
                                      fei[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_efectiva_final =
                            fef != null
                                ? new Date(
                                      fef[0],
                                      parseInt(fef[1]) - 1,
                                      fef[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_interrupcion_inicio =
                            fii != null
                                ? new Date(
                                      fii[0],
                                      parseInt(fii[1]) - 1,
                                      fii[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                    });
                    break;
                case 'Antiguedad':
                    _.each(dataset, function (row, idx) {
                        if (row.vigente === true) {
                            dataset[idx].vigente = 'Si';
                        } else {
                            dataset[idx].vigente = 'No';
                        }
                        var created_at = row.created_at
                            .substr(0, 10)
                            .split('-');
                        row.created_at = new Date(
                            created_at[0],
                            parseInt(created_at[1]) - 1,
                            created_at[2],
                        ).toLocaleDateString();
                    });
                    break;
                case 'Sancion':
                    _.each(dataset, (row) => {
                        let fecha_inicio =
                            row.fecha_inicio != null
                                ? row.fecha_inicio.substr(0, 10).split('-')
                                : null;
                        row.fecha_inicio =
                            fecha_inicio != null
                                ? new Date(
                                      fecha_inicio[0],
                                      parseInt(fecha_inicio[1]) - 1,
                                      fecha_inicio[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        let fecha_final =
                            row.fecha_final != null
                                ? row.fecha_final.substr(0, 10).split('-')
                                : null;
                        row.fecha_final =
                            fecha_final != null
                                ? new Date(
                                      fecha_final[0],
                                      parseInt(fecha_final[1]) - 1,
                                      fecha_final[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        let created_at =
                            row.created_at != null
                                ? row.created_at.substr(0, 10).split('-')
                                : null;
                        row.created_at =
                            created_at != null
                                ? new Date(
                                      created_at[0],
                                      parseInt(created_at[1]) - 1,
                                      created_at[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                    });
                    break;
                case 'DDJJ':
                    _.each(dataset, function (row, idx) {
                        dataset[idx].aprobado =
                            row.aprobado === true ? 'Si' : 'No';
                        dataset[idx].activo = row.activo === true ? 'Si' : 'No';
                        let venc = row.vencimiento.substr(0, 10).split('-');
                        dataset[idx].vencimiento = new Date(
                            venc[0],
                            venc[1] - 1,
                            venc[2],
                        ).toLocaleDateString();
                        let creac = row.created_at.substr(0, 10).split('-');
                        dataset[idx].created_at = new Date(
                            creac[0],
                            creac[1] - 1,
                            creac[2],
                        ).toLocaleDateString();
                    });

                    break;
                case 'Familiares':
                    _.each(dataset, function (row, idx) {
                        dataset[idx].discapacidad =
                            row.discapacidad === true ? 'Si' : 'No';
                        let nac = row.fecha_nacimiento.substr(0, 10).split('-');
                        dataset[idx].fecha_nacimiento = new Date(
                            nac[0],
                            nac[1] - 1,
                            nac[2],
                        ).toLocaleDateString();
                        let par = 0;
                        switch (row.idtipoParentesco) {
                            case 0:
                                par = 'Ninguno';
                                break;
                            case 1:
                                par = 'Padre';
                                break;
                            case 2:
                                par = 'Madre';
                                break;
                            case 3:
                                par = 'Suegro';
                                break;
                            case 4:
                                par = 'Suegra';
                                break;
                            case 5:
                                par = 'Hijo';
                                break;
                            case 6:
                                par = 'Hija';
                                break;
                            case 7:
                                par = 'Yerno';
                                break;
                            case 8:
                                par = 'Nuera';
                                break;
                            case 9:
                                par = 'Abuelo';
                                break;
                            case 10:
                                par = 'Abuela';
                                break;
                            case 11:
                                par = 'Nieto';
                                break;
                            case 12:
                                par = 'Nieta';
                                break;
                            case 13:
                                par = 'Hermano';
                                break;
                            case 14:
                                par = 'Hermana';
                                break;
                            case 15:
                                par = 'Cuñado';
                                break;
                            case 16:
                                par = 'Cuñada';
                                break;
                            case 17:
                                par = 'Primo';
                                break;
                            case 18:
                                par = 'Prima';
                                break;
                            case 19:
                                par = 'Tio';
                                break;
                            case 20:
                                par = 'Tia';
                                break;
                            case 21:
                                par = 'Sobrino';
                                break;
                            case 22:
                                par = 'Sobrina';
                                break;
                            case 23:
                                par = 'Biznieto';
                                break;
                            case 24:
                                par = 'Biznieta';
                                break;
                            case 25:
                                par = 'Bisabuelo';
                                break;
                            case 26:
                                par = 'Bisabuela';
                                break;
                            case 27:
                                par = 'Conyuge';
                                break;
                        }
                        dataset[idx].parentesco = par;
                    });
                    break;
                case 'ConsultaAgente':
                    _.each(dataset, function (row, idx) {
                        dataset[idx].primer_visado =
                            row.primer_visado == true ? 'Si' : 'No';
                        dataset[idx].segundo_visado =
                            row.segundo_visado == true ? 'Si' : 'No';
                        dataset[idx].cuarta_visado =
                            row.cuarta_visado == true ? 'Si' : 'No';
                    });
                    break;
                case 'ConsultaLicencia':
                    _.each(dataset, function (row, idx) {
                        dataset[idx].primer_visado =
                            row.primer_visado == true ? 'Si' : 'No';
                        dataset[idx].segundo_visado =
                            row.segundo_visado == true ? 'Si' : 'No';
                        dataset[idx].cuarta_visado =
                            row.cuarta_visado == true ? 'Si' : 'No';
                        let fpi =
                            row.fecha_pedido_inicio != null
                                ? row.fecha_pedido_inicio
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fpf =
                            row.fecha_pedido_final != null
                                ? row.fecha_pedido_final
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fei =
                            row.fecha_efectiva_inicio != null
                                ? row.fecha_efectiva_inicio
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fef =
                            row.fecha_efectiva_final != null
                                ? row.fecha_efectiva_final
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        let fii =
                            row.fecha_interrupcion_inicio != null
                                ? row.fecha_interrupcion_inicio
                                      .substr(0, 10)
                                      .split('-')
                                : null;
                        dataset[idx].fecha_pedido_inicio =
                            fpi != null
                                ? new Date(
                                      fpi[0],
                                      parseInt(fpi[1]) - 1,
                                      fpi[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_pedido_final =
                            fpf != null
                                ? new Date(
                                      fpf[0],
                                      parseInt(fpf[1]) - 1,
                                      fpf[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_efectiva_inicio =
                            fei != null
                                ? new Date(
                                      fei[0],
                                      parseInt(fei[1]) - 1,
                                      fei[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_efectiva_final =
                            fef != null
                                ? new Date(
                                      fef[0],
                                      parseInt(fef[1]) - 1,
                                      fef[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        dataset[idx].fecha_interrupcion_inicio =
                            fii != null
                                ? new Date(
                                      fii[0],
                                      parseInt(fii[1]) - 1,
                                      fii[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                    });
                    break;
                case 'ConsultaSancion':
                    _.each(dataset, (row) => {
                        let fecha_inicio =
                            row.fecha_inicio != null
                                ? row.fecha_inicio.substr(0, 10).split('-')
                                : null;
                        row.fecha_inicio =
                            fecha_inicio != null
                                ? new Date(
                                      fecha_inicio[0],
                                      parseInt(fecha_inicio[1]) - 1,
                                      fecha_inicio[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        let fecha_final =
                            row.fecha_final != null
                                ? row.fecha_final.substr(0, 10).split('-')
                                : null;
                        row.fecha_final =
                            fecha_final != null
                                ? new Date(
                                      fecha_final[0],
                                      parseInt(fecha_final[1]) - 1,
                                      fecha_final[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                        let created_at =
                            row.created_at != null
                                ? row.created_at.substr(0, 10).split('-')
                                : null;
                        row.created_at =
                            created_at != null
                                ? new Date(
                                      created_at[0],
                                      parseInt(created_at[1]) - 1,
                                      created_at[2],
                                  ).toLocaleDateString()
                                : 'No hay fecha';
                    });
                    break;
                case 'ConsultaSaldosLAO':
                    _.each(dataset, function (row, idx) {
                        row.pedido = row.pedido === null ? 0 : row.pedido;
                        row.saldo = row.saldo === null ? 0 : row.saldo;
                    });
                    break;
            }
            return dataset;
        },
        exportPDFMixin(
            titles = [],
            headings = [],
            datasets = [],
            portrait = true,
            filename = 'archivo',
            agenteBuscado,
        ) {
            const addFooters = (doc) => {
                const pageCount = doc.internal.getNumberOfPages();

                doc.setFont('helvetica', 'italic');
                doc.setFontSize(8);
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.text('SIARHU', 50, 811);
                    doc.text(this.user, 520, 811);
                    doc.text(
                        'Página ' + String(i) + ' de ' + String(pageCount),
                        doc.internal.pageSize.width / 2,
                        811,
                        {
                            align: 'center',
                        },
                    );
                }
            };
            const addHeaders = (doc) => {
                const pageCount = doc.internal.getNumberOfPages();
                doc.setFont('helvetica', 'italic');
                doc.setFontSize(8);
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.text(
                        moment().locale('es').format('DD/MM/YYYY, h:mm:ss a'),
                        doc.internal.pageSize.width * 0.75,
                        20,
                    );
                }
            };
            let headingAgente = [
                { title: 'Documento', dataKey: 'documento' },
                { title: 'Nombre Completo', dataKey: 'apellido_nombre' },
                { title: 'Efector', dataKey: 'efector' },
                { title: 'Servicio', dataKey: 'codigo_nombre' },
            ];

            var vm = this;
            if (portrait == true) {
                var doc = new jsPDF('p', 'pt');
            } else {
                var doc = new jsPDF('l', 'pt', 'a4');
            }

            // Informacion del Agente
            var img = new Image();
            img.src = 'images/rrhh_siprosa.png';
            doc.addImage(img, 'JPEG', 50, 60, 130, 44);
            img.src = 'images/logo_ministerio.jpg';
            doc.addImage(
                img,
                'JPEG',
                doc.internal.pageSize.width * 0.7,
                60,
                130,
                24,
            );
            doc.setFont('times');
            var texto = 'Listados de Sanciones Disciplinarias';
            var xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 120);
            texto = 'Datos del Agente';
            xOffset =
                doc.internal.pageSize.width / 2 -
                (doc.getStringUnitWidth(texto) * doc.internal.getFontSize()) /
                    2;
            doc.text(texto, xOffset, 150);

            let agentes = [];
            let agente = _.cloneDeep(agenteBuscado);
            agentes.push(agente);

            doc.autoTable(headingAgente, agentes, {
                startY: 150 + 25,
            });

            let arrayf = [];
            arrayf.push(datasets[0]);
            let tt = [];
            tt.push(headings[0]);
            console.log(tt);
            console.log(arrayf);

            if (datasets[0].length < 1) {
                if (this.sanciones.length < 1) {
                    var texto = 'No tiene sanciones';
                    var xOffset =
                        doc.internal.pageSize.width / 2 -
                        (doc.getStringUnitWidth(texto) *
                            doc.internal.getFontSize()) /
                            2;
                    doc.text(texto, xOffset, 150 + 25 + 40 + 60);
                } else {
                    var texto = 'No se ha seleccionado sanciones';
                    var xOffset =
                        doc.internal.pageSize.width / 2 -
                        (doc.getStringUnitWidth(texto) *
                            doc.internal.getFontSize()) /
                            2;
                    doc.text(texto, xOffset, 150 + 25 + 40 + 60);
                }
            } else {
                for (const row in datasets) {
                    xOffset =
                        doc.internal.pageSize.width / 2 -
                        (doc.getStringUnitWidth(texto) *
                            doc.internal.getFontSize()) /
                            2;
                    var finalY = doc.previousAutoTable.finalY;
                    doc.text(titles[row], xOffset, finalY + 20);
                    doc.autoTable(headings[row], datasets[row], {
                        styles: { overflow: 'linebreak', fontSize: 7 },
                        startY: finalY + 40,
                    });
                }
            }

            addHeaders(doc);
            addFooters(doc);
            doc.save(`${filename}.pdf`);
        },
    },
};
