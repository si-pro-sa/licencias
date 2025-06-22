import moment from 'moment';

export default {
    mounted() {},
    data: (vm) => ({
        personas: 0,
        idlicencia: 0,
        tipoLicencia: 0,
        licenciasRequierenUnaPersonaACargo: [11, 21, 22],
        licenciasConMinimoPorVez: [19],
        licenciasHabiles: [11, 14, 16, 17, 25, 27, 28],
        licenciasLAO: [16, 17, 25, 27],
        licenciasConMaximoPorVez: [
            1, 2, 7, 8, 9, 10, 12, 13, 14, 15, 17, 18, 19, 25, 38, 39, 40,
        ],
        licenciasConMaximoAnual: [1, 10, 11, 15, 21, 22, 25, 28, 35, 36, 40],
        licenciasConMaximoMensual: [17, 38],
        licenciasConMaximoAntiguedad: [16, 17, 25, 27],
        maximo21: 0,
        maximo22: 0,
        maximo11: 0,
        diasAnualesMaximos: {
            2010: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2011: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2012: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2013: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2014: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2015: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2016: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2017: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2018: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2019: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
            },
            2020: {
                10: 150,
                11: 10,
                1: 300,
                15: 93,
                21: 150,
                22: 300,
                25: 6,
                28: 20,
                32: 30,
            },
            2021: {
                10: 150,
                11: 10,
                1: 150,
                15: 93,
                21: 150,
                22: 300,
                25: 6,
                28: 20,
                32: 30,
            },
            2022: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 150,
                22: 300,
                25: 6,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
            },
            2023: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
                40: 2,
            },
            2024: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
                40: 2,
            },
            2025: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                25: 6,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
                40: 2,
            },
        },
        diasInsertarSaldo: {
            2010: {},
            2011: {},
            2012: {},
            2013: {},
            2014: {},
            2015: {},
            2016: {},
            2017: {},
            2018: {},
            2019: {},
            2020: {},
            2021: {},
            2022: {},
            2023: {},
            2024: {},
            2025: {},
        },
        diasMensualesMaximos: {
            2010: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2011: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2012: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2013: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2014: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2015: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2016: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2017: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2018: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2019: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2020: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2021: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2022: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2023: {
                1: { 17: 1, 38: 1 },
                2: { 17: 1, 38: 1 },
                3: { 17: 1, 38: 1 },
                4: { 17: 1, 38: 1 },
                5: { 17: 1, 38: 1 },
                6: { 17: 1, 38: 1 },
                7: { 17: 1, 38: 1 },
                8: { 17: 1, 38: 1 },
                9: { 17: 1, 38: 1 },
                10: { 17: 1, 38: 1 },
                11: { 17: 1, 38: 1 },
                12: { 17: 1, 38: 1 },
            },
            2024: {
                1: { 17: 1, 38: 1 },
                2: { 17: 1, 38: 1 },
                3: { 17: 1, 38: 1 },
                4: { 17: 1, 38: 1 },
                5: { 17: 1, 38: 1 },
                6: { 17: 1, 38: 1 },
                7: { 17: 1, 38: 1 },
                8: { 17: 1, 38: 1 },
                9: { 17: 1, 38: 1 },
                10: { 17: 1, 38: 1 },
                11: { 17: 1, 38: 1 },
                12: { 17: 1, 38: 1 },
            },
            2025: {
                1: { 17: 1, 38: 1 },
                2: { 17: 1, 38: 1 },
                3: { 17: 1, 38: 1 },
                4: { 17: 1, 38: 1 },
                5: { 17: 1, 38: 1 },
                6: { 17: 1, 38: 1 },
                7: { 17: 1, 38: 1 },
                8: { 17: 1, 38: 1 },
                9: { 17: 1, 38: 1 },
                10: { 17: 1, 38: 1 },
                11: { 17: 1, 38: 1 },
                12: { 17: 1, 38: 1 },
            },
        },
        diasMaximosPorVez: {
            1: 150,
            2: 30,
            7: 93,
            8: 30,
            9: 12,
            10: 150,
            12: 15,
            13: 60,
            14: 2,
            15: 93,
            17: 1,
            18: 7,
            19: 93,
            25: 6,
            28: 5,
            38: 1,
            39: 2,
            40: 2,
        },
        resetdiasAnualesMaximos: {
            2010: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2011: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2012: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2013: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2014: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2015: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2016: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2017: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2018: {
                10: 150,
                11: 10,
                1: 30,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2019: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 93,
                22: 180,
                28: 20,
            },
            2020: {
                10: 150,
                11: 10,
                1: 300,
                15: 93,
                21: 150,
                22: 300,
                28: 20,
                32: 30,
            },
            2021: {
                10: 150,
                11: 10,
                1: 150,
                15: 93,
                21: 150,
                22: 300,
                28: 20,
                32: 30,
            },
            2022: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 150,
                22: 300,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
            },
            2023: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 150,
                22: 300,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
                40: 2,
            },
            2024: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 150,
                22: 300,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
                40: 2,
            },
            2025: {
                10: 150,
                11: 10,
                1: 45,
                15: 93,
                21: 150,
                22: 300,
                28: 20,
                32: 30,
                35: 90,
                36: 180,
                40: 2,
            },
        },
        resetdiasInsertarSaldo: {
            2010: {},
            2011: {},
            2012: {},
            2013: {},
            2014: {},
            2015: {},
            2016: {},
            2017: {},
            2018: {},
            2019: {},
            2020: {},
            2021: {},
            2022: {},
            2023: {},
            2024: {},
        },
        resetdiasMensualesMaximos: {
            2010: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2011: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2012: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2013: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2014: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2015: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2016: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2017: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2018: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2019: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2020: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2021: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2022: {
                1: { 17: 1 },
                2: { 17: 1 },
                3: { 17: 1 },
                4: { 17: 1 },
                5: { 17: 1 },
                6: { 17: 1 },
                7: { 17: 1 },
                8: { 17: 1 },
                9: { 17: 1 },
                10: { 17: 1 },
                11: { 17: 1 },
                12: { 17: 1 },
            },
            2023: {
                1: { 17: 1, 38: 1 },
                2: { 17: 1, 38: 1 },
                3: { 17: 1, 38: 1 },
                4: { 17: 1, 38: 1 },
                5: { 17: 1, 38: 1 },
                6: { 17: 1, 38: 1 },
                7: { 17: 1, 38: 1 },
                8: { 17: 1, 38: 1 },
                9: { 17: 1, 38: 1 },
                10: { 17: 1, 38: 1 },
                11: { 17: 1, 38: 1 },
                12: { 17: 1, 38: 1 },
            },
            2024: {
                1: { 17: 1, 38: 1 },
                2: { 17: 1, 38: 1 },
                3: { 17: 1, 38: 1 },
                4: { 17: 1, 38: 1 },
                5: { 17: 1, 38: 1 },
                6: { 17: 1, 38: 1 },
                7: { 17: 1, 38: 1 },
                8: { 17: 1, 38: 1 },
                9: { 17: 1, 38: 1 },
                10: { 17: 1, 38: 1 },
                11: { 17: 1, 38: 1 },
                12: { 17: 1, 38: 1 },
            },
            2025: {
                1: { 17: 1, 38: 1 },
                2: { 17: 1, 38: 1 },
                3: { 17: 1, 38: 1 },
                4: { 17: 1, 38: 1 },
                5: { 17: 1, 38: 1 },
                6: { 17: 1, 38: 1 },
                7: { 17: 1, 38: 1 },
                8: { 17: 1, 38: 1 },
                9: { 17: 1, 38: 1 },
                10: { 17: 1, 38: 1 },
                11: { 17: 1, 38: 1 },
                12: { 17: 1, 38: 1 },
            },
        },
        resetdiasMaximosPorVez: {
            1: 150,
            2: 30,
            7: 93,
            8: 30,
            10: 150,
            12: 15,
            13: 60,
            14: 2,
            15: 93,
            17: 1,
            18: 7,
            19: 93,
            25: 6,
            28: 5,
            38: 1,
            39: 2,
            40: 2,
        },
    }),
    methods: {
        viewLicenciasTables(licencias = [], codigosTipoLicencia = []) {
            const formatDate = 'DD/MM/YYYY';
            let filtrado = _.filter(licencias, (l) => {
                return codigosTipoLicencia.includes(l.idtipoLicencia);
            });
            let arreglo = _.map(filtrado, (value, index) => {
                return {
                    idlicencia: value.idlicencia,
                    primer_visado: value.primer_visado,
                    segundo_visado: value.segundo_visado,
                    cuarta_visado: value.cuarta_visado,
                    dias: value.dias,
                    fecha_efectiva_inicio:
                        value.fecha_efectiva_inicio === null
                            ? new moment(
                                  value.fecha_pedido_inicio.slice(0, 10),
                              ).format(formatDate)
                            : new moment(
                                  value.fecha_efectiva_inicio.slice(0, 10),
                              ).format(formatDate),
                    fecha_efectiva_final:
                        value.fecha_interrupcion_inicio !== null
                            ? new moment(
                                  value.fecha_interrupcion_inicio.slice(0, 10),
                              ).format(formatDate)
                            : value.fecha_efectiva_final === null
                            ? new moment(
                                  value.fecha_pedido_final.slice(0, 10),
                              ).format(formatDate)
                            : new moment(
                                  value.fecha_efectiva_final.slice(0, 10),
                              ).format(formatDate),
                    descripcion: value.descripcion.toUpperCase(),
                };
            });
            return arreglo;
        },
        /**
         * Trae licencias especificas del tipo de Licencias
         */
        async fetchLicenciasPorTipoAgente() {
            this.selected = [];
            await this.$store
                .dispatch('licencia/getLicencias', {
                    idagente: this.agente.idagente,
                    tipoLicencia: this.tipoLicencia,
                })
                .then(() => {
                    this.licencias = this.getLicencias;
                    this.licencias = this.filterLicenciasAprobadas(
                        this.licencias,
                    );
                    this.fetchSaldosTotales();
                })
                .catch((err) => {
                    console.error('fetchLicenciasPorTipoAgente ', err);
                });
            try {
                await this.$store.dispatch('licencia/getPersonasSaldos', {
                    idagente: this.agente.idagente,
                });
            } catch (err) {
                console.error(err);
            }
        },
        /**
         * Get Saldos de LAO
         */
        async fetchSaldosTotales() {
            await this.$store
                .dispatch('licencia/fetchLicenciasSaldos', {
                    idagente: this.agente.idagente,
                })
                .then(() => {
                    this.fetchAntiguedades();
                })
                .catch((err) => {
                    console.error('fetchSaldosTotales ', err);
                });
        },
        /**
         * Busca Antiguedades api/antiguedad/getAntiguedades/&idagente
         * idagente: this.$store.state.agente.agente.idagente
         * then => this.antiguedades y resetAntiguedades
         * actualizarMaximos
         */
        async fetchAntiguedades() {
            await this.$store
                .dispatch('antiguedad/getAntiguedadesMenosLicencia', {
                    idagente: this.agente.idagente,
                    idlicencia: this.idlicencia,
                })
                .then(() => {
                    this.resetAntiguedades = JSON.parse(
                        JSON.stringify(this.antiguedades),
                    );
                    this.actualizarMaximos();
                })
                .catch((err) => {
                    console.error('fetchAntiguedades ', err);
                });
        },
        /**
         * Actualiza todos los saldos exceptuando los dependientes de la antiguedad pero la antiguedad es
         * actualizada en el getAntiguedad del controlador
         * Actualiza maximos mensuales y anuales y setea el reset de antiguedades
         */
        actualizarMaximos() {
            if (this.idlicencia) {
                this.saldos = _.filter(this.saldos, (saldo) => {
                    return saldo.idlicencia != this.idlicencia;
                });
                this.saldos_totales = _.filter(this.saldos_totales, (saldo) => {
                    return saldo.idlicencia != this.idlicencia;
                });
            }
            if (this.licenciasConMaximoMensual.includes(this.tipoLicencia)) {
                for (var asiento in this.saldos) {
                    var anio = this.saldos[asiento]['año'];
                    var mes = this.saldos[asiento]['mes'];
                    var dias = this.saldos[asiento]['dias'];
                    this.diasMensualesMaximos[anio][mes][this.tipoLicencia] -=
                        dias;
                }
                this.resetdiasMensualesMaximos = JSON.parse(
                    JSON.stringify(this.diasMensualesMaximos),
                );
            } else if (
                this.licenciasConMaximoAnual.includes(this.tipoLicencia)
            ) {
                for (var asiento in this.saldos) {
                    var anio = this.saldos[asiento]['año'];
                    var dias = this.saldos[asiento]['dias'];
                    this.diasAnualesMaximos[anio][this.tipoLicencia] -= dias;
                }
                this.resetdiasAnualesMaximos = JSON.parse(
                    JSON.stringify(this.diasAnualesMaximos),
                );
            } else if (
                this.licenciasConMaximoAntiguedad.includes(this.tipoLicencia)
            ) {
                this.resetAntiguedades = JSON.parse(
                    JSON.stringify(this.antiguedades),
                );
            } else {
                return -1;
            }
            return 0;
        },
        setearDiasMaximosFamiliares(discapacitado) {
            console.log('_______Busqueda de Personas__________');
            let personas = this.$store.state.licencia.personas;
            console.log('Personas', personas);
            console.log('Discapacitado', discapacitado);
            console.log(this.diasAnualesMaximos[moment().year()][11]);
            console.log('cantidad de familiares');
            console.log(this.cantidadDiasPorFamiliares());
            this.diasAnualesMaximos[moment().year()][11] =
                this.cantidadDiasPorFamiliares();
            this.diasAnualesMaximos[moment().year() + 1][11] =
                this.cantidadDiasPorFamiliares();
            this.diasAnualesMaximos[moment().year() - 1][11] =
                this.cantidadDiasPorFamiliares();
            if (this.tipoLicencia == 21 || this.tipoLicencia == 22) {
                personas = personas.filter((element) => {
                    return element.discapacidad;
                });
                if (this.licencia.idpersona > 0) {
                    let personaSeleccionada = personas.filter((element) => {
                        return element.idpersona === this.licencia.idpersona;
                    });
                    const reducer = (accumulator, currentValue) =>
                        accumulator + currentValue.dias;
                    this.diasAnualesMaximos[moment().year()][22] =
                        180 -
                        personaSeleccionada
                            .map((element) => {
                                if (
                                    moment().year() == element.año &&
                                    element.idtipolicencia == 22
                                ) {
                                    return {
                                        dias: element.dias,
                                    };
                                } else {
                                    return {
                                        dias: 0,
                                    };
                                }
                            })
                            .reduce(reducer, 0);

                    this.diasAnualesMaximos[moment().year() + 1][22] =
                        180 -
                        personaSeleccionada
                            .map((element) => {
                                if (
                                    moment().year() + 1 === element.año &&
                                    element.idtipolicencia === 22
                                ) {
                                    return {
                                        dias: element.dias,
                                    };
                                } else {
                                    return {
                                        dias: 0,
                                    };
                                }
                            })
                            .reduce(reducer, 0);
                    this.diasAnualesMaximos[moment().year() - 1][22] =
                        180 -
                        personaSeleccionada
                            .map((element) => {
                                if (
                                    moment().year() - 1 === element.año &&
                                    element.idtipolicencia === 22
                                ) {
                                    return {
                                        dias: element.dias,
                                    };
                                } else {
                                    return {
                                        dias: 0,
                                    };
                                }
                            })
                            .reduce(reducer, 0);
                    this.diasAnualesMaximos[moment().year()][21] =
                        90 -
                        personaSeleccionada
                            .map((element) => {
                                if (
                                    moment().year() === element.año &&
                                    element.idtipolicencia === 21
                                ) {
                                    return {
                                        dias: element.dias,
                                    };
                                } else {
                                    return {
                                        dias: 0,
                                    };
                                }
                            })
                            .reduce(reducer, 0);
                    this.diasAnualesMaximos[moment().year() + 1][21] =
                        90 -
                        personaSeleccionada
                            .map((element) => {
                                if (
                                    moment().year() + 1 === element.año &&
                                    element.idtipolicencia === 21
                                ) {
                                    return {
                                        dias: element.dias,
                                    };
                                } else {
                                    return {
                                        dias: 0,
                                    };
                                }
                            })
                            .reduce(reducer, 0);
                    this.diasAnualesMaximos[moment().year() - 1][21] =
                        90 -
                        personaSeleccionada
                            .map((element) => {
                                if (
                                    moment().year() - 1 === element.año &&
                                    element.idtipolicencia === 21
                                ) {
                                    return {
                                        dias: element.dias,
                                    };
                                } else {
                                    return {
                                        dias: 0,
                                    };
                                }
                            })
                            .reduce(reducer, 0);
                }
            }
        },
        // Busca las personas declaradas como grupo familiar excepto para la licencia de familiar discapacitado que tiene en cuenta si alguien ya saco licencia por este ultimo
        async buscarPersonasActivas(discapacitado = false) {
            await this.$store
                .dispatch('licencia/getPersonasActivas', [
                    this.tipoLicencia,
                    this.agente.idagente,
                ])
                .then(() => {
                    this.setearDiasMaximosFamiliares();
                    this.personas = this.$store.state.licencia.personas;
                    this.personas = this.personas.map((element) => {
                        return {
                            text: element.nombre + ' ' + element.apellido,
                            value: element.idpersona,
                        };
                    });
                })
                .catch();
        },
        cantidadDiasPorFamiliares() {
            var cantidad = this.personas.length;
            if (cantidad > 4) {
                cantidad = (cantidad - 4) * 2;
            } else {
                return 10;
            }
            return cantidad + 10;
        },
        restarAnualesMaximos(descuentos, maximos, idTipoLicencia) {
            var anios = Object.keys(descuentos);
            var anio = 0;
            for (anio in anios) {
                var meses = Object.keys(descuentos[anios[anio]]);
                var contadorMeses = 0;
                for (var mes in meses) {
                    contadorMeses +=
                        descuentos[anios[anio]][meses[mes]][idTipoLicencia];
                }
                if (maximos[anios[anio]][idTipoLicencia] - contadorMeses >= 0) {
                    maximos[anios[anio]][idTipoLicencia] -= contadorMeses;
                } else {
                    this.mensaje += ' \n Se ha superado el maximo anual';
                    this.resetearValores();
                    return -1;
                }
            }
            return 0;
        },
        restarMensualesMaximos(descuentos, maximos, idTipoLicencia) {
            var anios = Object.keys(descuentos);
            var anio = 0;
            for (anio in anios) {
                var meses = Object.keys(descuentos[anios[anio]]);
                for (var mes in meses) {
                    if (
                        maximos[anios[anio]][meses[mes]][idTipoLicencia] -
                            descuentos[anios[anio]][meses[mes]][
                                idTipoLicencia
                            ] >=
                        0
                    ) {
                        maximos[anios[anio]][meses[mes]][idTipoLicencia] -=
                            descuentos[anios[anio]][meses[mes]][idTipoLicencia];
                    } else {
                        this.mensaje += ' \n Se ha superado el maximo mensual';
                        this.resetearValores();
                        return -1;
                    }
                }
            }
            return 0;
        },
        resetearValores() {
            console.log('Reseteo de valores');
            this.mensaje += ' \n El descuento de la fecha pedida no es posible';
            this.diasAnualesMaximos = JSON.parse(
                JSON.stringify(this.resetdiasAnualesMaximos),
            );
            this.diasMensualesMaximos = JSON.parse(
                JSON.stringify(this.resetdiasMensualesMaximos),
            );
            this.antiguedades = JSON.parse(
                JSON.stringify(this.resetAntiguedades),
            );
            this.saldosInsertar = [];
            this.diasInsertarSaldo = JSON.parse(
                JSON.stringify(this.resetdiasInsertarSaldo),
            );
            this.setearDiasMaximosFamiliares();
        },
        restarAntiguedad(descuentos, maximos, idTipoLicencia) {
            var diasAnuales = 0;
            for (var idxAnual in descuentos) {
                for (var idxMensual in descuentos[idxAnual]) {
                    diasAnuales +=
                        descuentos[idxAnual][idxMensual][this.tipoLicencia];
                }
            }
            var variosAnios = false;
            // Si son mas de 3 dias descuenta de todos los años vigentes sino solo del actual
            if (diasAnuales >= 3) {
                maximos = _.orderBy(maximos, ['año'], ['asc']);
                variosAnios = true;
            } else {
                maximos = _.find(maximos, (m) => {
                    return m.año === new Date().getFullYear();
                });
            }
            if (maximos) {
                if (variosAnios) {
                    for (var antiguedad in maximos) {
                        if (
                            maximos[antiguedad]['disponible'] -
                                (maximos[antiguedad]['pedido'] + diasAnuales) >=
                            0
                        ) {
                            maximos[antiguedad]['pedido'] += diasAnuales;
                            diasAnuales = 0;
                        } else if (
                            maximos[antiguedad]['disponible'] -
                                maximos[antiguedad]['pedido'] >
                            0
                        ) {
                            let saldo =
                                maximos[antiguedad]['disponible'] -
                                maximos[antiguedad]['pedido'];
                            maximos[antiguedad]['pedido'] += saldo;
                            diasAnuales -= saldo;
                        } else {
                        }
                    }
                    if (diasAnuales > 0) {
                        console.log(
                            'los dias a descontar son mas de los disponibles ',
                            diasAnuales,
                        );
                        return -1;
                    }
                } else {
                    if (
                        maximos['disponible'] -
                            (maximos['pedido'] + diasAnuales) >=
                        0
                    ) {
                        maximos['pedido'] += diasAnuales;

                        this.resetearValores();
                        return -1;
                    }

                    return -1;
                }
            }
        },
        filterLicenciasAprobadas() {
            return _.filter(this.licencias_totales, (lic) => {
                return (
                    (lic.primer_visado === null ||
                        lic.primer_visado === true) &&
                    (lic.segundo_visado === null ||
                        lic.segundo_visado === true) &&
                    (lic.cuarta_visado === null || lic.cuarta_visado === true)
                );
            });
        },
    },
    computed: {
        antiguedades() {
            return this.$store.getters['antiguedad/antiguedades'];
        },
        getLicencias() {
            return this.$store.getters['licencia/obtenerLicencias'];
        },
        saldos() {
            return this.$store.getters['licencia/obtenerSaldosTotales'];
        },
        dependencia() {
            return this.$store.getters['user/dependencia'];
        },
        dependencia_padre() {
            return this.$store.getters['user/dependencia_padre'];
        },
    },
};
