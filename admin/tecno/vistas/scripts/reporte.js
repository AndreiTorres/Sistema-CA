var tabla;
var flag;

//funcion que se ejecuta al inicio
function init() {
    listar_dia();
}

TurnoL = "";

//funcion listar
function listar() {
    $("#tperiodo").show();
    $("#tdia").hide();
    flag = false;
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    tabla = $("#tbllistadoperiodo")
        .dataTable({
            stateSave: true,
            aProcessing: true, //activamos el procedimiento del datatable
            aServerSide: true, //paginacion y filrado realizados por el server
            dom: "Blfrtlip", //definimos los elementos del control de la tabla
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 0,
            },
            ],
            buttons: [{
                extend: "excelHtml5",
                title: "Reporte Grupo Tecno"
            }
                ,
            {
                extend: 'pdfHtml5',
                title: "Reporte Grupo Tecno",
                text: 'PDF',
                orientation: 'portrait', //portrait
                pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                exportOptions: {
                    columns: ':visible',
                    search: 'applied',
                    order: 'applied'
                },
                customize: function (doc) {
                    doc.defaultStyle.font = "mon";
                    let table = doc.content[1].table.body;
                    //Width de tabla
                    doc.content[1].table.widths = ['4%', '40%', '11%', '12%', '12%', '12%', '8%'];
                    //Centrar columnas de horas
                    var rowCount = doc.content[1].table.body.length;
                    doc.styles.tableBodyEven.alignment = "center";
                    doc.styles.tableBodyOdd.alignment = "center";
                    var numCols = $('#tbllistadoperiodo').DataTable().columns(':visible').nodes().length;
                    for (i = 0; i < numCols; i++) {
                        table[0][i].fillColor = "#092969";
                        for (j = 1; j < rowCount; j++) {
                            table[j][i].fillColor = "#e1e1e1";
                            j = j + 1;
                        }
                        if (table[0][i].text == "Turno" + TurnoL) {
                            table[0][i].text = "Turno";
                        }
                    }
                    //table[0][3].text = "Turno";
                    doc.content.splice(0, 1); // quitar titulo de datatables
                    doc.pageMargins = [20, 80, 20, 20];
                    doc.defaultStyle.fontSize = 11;
                    doc.styles.tableHeader.fontSize = 12;
                    doc['header'] = (function () {
                        return {
                            columns: [
                                {
                                    image: tecno_logo,		
                                    width: 100,
                                    margin:[0,-20]
                                },
                                {
                                    alignment: 'right',
                                    fontSize: 11,
                                    font: "mon",
                                    text: 'Reporte del periodo. \nInicio: ' + fecha_inicio + ' Fin: ' + fecha_fin + "\nEntradas: " + (rowCount - 1),
                                }
                            ],
                            margin: 20
                        }
                    });
                }
            }

            ],
            ajax: {
                url: "../ajax/reporte.php?op=listar",
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
                type: "post",
                dataType: "json",
                error: function (e) {
                },
            },
            bDestroy: true,
            iDisplayLength: 25, //paginacion
            order: [
                [0, "asc"]
            ],
            initComplete: function () {
                //Funcion para filtrar por tipo de usuario.
                this.api()
                    .columns([2]) //Columna de tipo
                    .every(function () {
                        var column = this;
                        var select = $('<select><option value="">Turno</option></select>') //Dropdown
                            .appendTo($(column.header()).empty())
                            .on("change", function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? "^" + val + "$" : "", true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + "</option>");
                                TurnoL += d;
                            });

                        $(select).click(function (e) {
                            //funcion para evitar que el dropdown
                            e.stopPropagation(); //interfiera con la tabla.
                        });

                        var currSearch = column.search();
                            if ( currSearch ) {
                            select.val( currSearch.substring(1, currSearch.length-1) );
                            }
                    });
            },
        })
        .DataTable();
    tabla.on('order.dt search.dt', function () {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
}

Turno = "";

function listar_dia() {
    $("#tdia").show();
    $("#tperiodo").hide();
    flag = false;
    var fecha = $("#dia").val();
    //tabla y peticion
    tabla = $("#tbllistadodia")
        .dataTable({
            stateSave: true,
            aProcessing: true, //activamos el procedimiento del datatable
            aServerSide: true, //paginacion y filrado realizados por el server
            dom: "Blfrtlip", //definimos los elementos del control de la tabla
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 0,
            },
            ],
            buttons: [{
                extend: "excelHtml5",
                title: "Reporte Grupo Tecno"
            }
                ,
            {
                extend: 'pdfHtml5',
                title: "Reporte Grupo Tecno",
                text: 'PDF',
                orientation: 'portrait', //landscape
                pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                exportOptions: {
                    columns: ':visible',
                    search: 'applied',
                    order: 'applied'
                },
                customize: function (doc) {
                    doc.defaultStyle.font = "mon";
                    let table = doc.content[1].table.body;
                    //Width de tabla
                    doc.content[1].table.widths = ['5%', '45%', '20%', '20%', '10%'];
                    //Centrar columnas
                    doc.styles.tableBodyEven.alignment = "center";
                    doc.styles.tableBodyOdd.alignment = "center";
                    //Pintar header
                    var numCols = $('#tbllistadodia').DataTable().columns(':visible').nodes().length;
                    var numFilas = table.length;
                    for (i = 0; i < numCols; i++) {
                        table[0][i].fillColor = "#092969";
                        for (j = 1; j < numFilas; j++) {
                            table[j][i].fillColor = "#e1e1e1";
                            j = j + 1;
                        }
                        if (table[0][i].text == "Turno" + Turno) {
                            table[0][i].text = "Turno";
                        }
                    }
                    //table[0][2].text = "Turno";
                    doc.content.splice(0, 1); // quitar titulo de datatables
                    doc.pageMargins = [20, 80, 20, 20];
                    doc.defaultStyle.fontSize = 11;
                    doc.styles.tableHeader.fontSize = 12;
                    doc['header'] = (function () {
                        return {
                            columns: [
                                {
                                    image: tecno_logo,		
                                    width: 100,
                                    margin:[0,-20]
                                },
                                {
                                    alignment: 'right',
                                    fontSize: 11,
                                    font: "mon",
                                    text: 'Reporte diario. \nFecha: ' + fecha + "\nAsistencia: " + (numFilas - 1),
                                }
                            ],
                            margin: 20
                        }
                    });
                }
            }
            ],
            ajax: {
                url: "../ajax/reporte.php?op=listar_dia",
                data: { fecha: fecha },
                type: "post",
                dataType: "json",
                error: function (e) {
                },
            },
            bDestroy: true,
            iDisplayLength: 25, //paginacion
            order: [
                [0, "asc"]
            ], //ordenar (columna, orden)
            initComplete: function () {
                //Funcion para filtrar por tipo de usuario.
                this.api()
                    .columns([4]) //Columna de tipo
                    .every(function() {

                        var column = this;
                        var select = $('<select><option value="">Turno</option></select>') //Dropdown
                            .appendTo($(column.header()).empty())
                            .on("change", function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? "^" + val + "$" : "", true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + "</option>");
                                Turno += d;
                            });

                        $(select).click(function (e) {
                            //funcion para evitar que el dropdown
                            e.stopPropagation(); //interfiera con la tabla.
                        });

                        var currSearch = column.search();
                            if ( currSearch ) {
                            select.val( currSearch.substring(1, currSearch.length-1) );
                            }
                    });
            },
        })
        .DataTable();
    tabla.on('order.dt search.dt', function () {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
}

function mostrarES(codigopersona, fecha_inicio, fecha_fin, tipo) {
    if (flag) {
        fecha_inicio = dateFormat(fecha_inicio, 'yyyy-MM-dd');
        fecha_fin = dateFormat(fecha_fin, 'yyyy-MM-dd');
    }
    location.href = `../vistas/entradas_salidas.php?codigopersona=${codigopersona}&fechaini=${fecha_inicio}&fechafin=${fecha_fin}&tipo=${tipo}`;
}

function mostrarUsuario(idusuario) {
    location.href = `../vistas/usuario.php?id=${idusuario}`;
}

init();