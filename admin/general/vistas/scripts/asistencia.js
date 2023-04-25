var tabla;
var flag;

//funcion que se ejecuta al inicio
function init() {
    if (document.getElementById("semana") != null) {
        listarSemana();
    }

    listar_asistenciau();
    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    let url = new URL(window.location.href);
    // Busca si existe el parámetro
    let cd = url.searchParams.get('codigopersona');
    let fi = url.searchParams.get('fechaini');
    let ff = url.searchParams.get('fechafin');

    if(fi && ff) {
        $("#fecha_inicio").val(fi);
        $("#fecha_fin").val(ff);
    }

    //cargamos los items al select cliente
    $.post("../ajax/asistencia.php?op=selectPersona", function(r) {
        $("#codigo_persona").html(r);
        $('#codigo_persona').val(cd);
        $("#codigo_persona").selectpicker("refresh");

        if (document.getElementById("semana") != null) {
            listarSemana();
        }else {
            listar_asistencia();
        }
    });

}

//funcion listar
function listar() {
    flag = false;
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    tabla = $("#tbllistado")
        .dataTable({
            aProcessing: true, //activamos el procedimiento del datatable
            aServerSide: true, //paginacion y filrado realizados por el server
            dom: "Blfrtlip", //definimos los elementos del control de la tabla
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 0,
            },
            {
                targets: [5,6],
                render: function ( data, type, row ) {
                    if ( type === 'sort') {
                    if (data==null) return 0
                    var tiempo = data.split(" ");

                        var segundos = tiempo[0].slice(-2);
                        var minutos = tiempo[0].slice(-5,-3);
                        var horas = tiempo[0].slice(0,-6)
                    
                        sortValue = (parseInt(horas)*3600+parseInt(minutos)*60+parseInt(segundos)).toString();

                    for(i=sortValue.length;i<8;i++) {
                        sortValue = "0"+sortValue;
                    }
                      return sortValue;
                    } else { 
                      return data;
                    }
                }
            }
        ],
            buttons: ["excelHtml5",
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    orientation: 'portrait', //portrait
                    pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                    exportOptions: {
                        columns: ':visible',
                        search: 'applied',
                        order: 'applied'
                    },
                    customize: function(doc) {
                        doc.defaultStyle.font = "mon";
                        let table = doc.content[1].table.body;
                        //Width de tabla
                        doc.content[1].table.widths = ['5%', '23%', '23%', '16%', '9%', '11%', '12%'];

                        //Centrar columnas de horas
                        var rowCount = doc.content[1].table.body.length;
                        doc.styles.tableBodyEven.alignment = "center";
                        doc.styles.tableBodyOdd.alignment = "center";
                        var numCols = $('#tbllistado').DataTable().columns(':visible').nodes().length;
                        for (i = 0; i < numCols; i++) {
                            table[0][i].fillColor = "#9D2449";
                            for (j = 1; j < rowCount; j++) {
                                table[j][i].fillColor = "#D4C19C";
                                j = j + 1;
                            }

                        }
                        table[0][3].text = "Tipo";
                        doc.content.splice(0, 1); // quitar titulo de datatables
                        doc.pageMargins = [20, 80, 20, 30];
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 12;
                        doc['header'] = (function() {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        text: 'OFICINA DE PASAPORTES YUCATÁN',
                                        color: '#621132',
                                        font: 'gmx',
                                        fontSize: 14,
                                        margin: [0, 0]
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
                url: "../ajax/asistencia.php?op=listar",
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
                type: "get",
                dataType: "json",
                error: function(e) {
                },
            },
            bDestroy: true,
            iDisplayLength: 10, //paginacion
            order: [
                [0, "asc"]
            ],
            initComplete: function() {
                //Funcion para filtrar por tipo de usuario.
                this.api()
                    .columns([3]) //Columna de tipo
                    .every(function() {
                        var column = this;
                        var select = $('<select><option value="">Tipo</option></select>') //Dropdown
                            .appendTo($(column.header()).empty())
                            .on("change", function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? "^" + val + "$" : "", true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                select.append('<option value="' + d + '">' + d + "</option>");
                            });

                        $(select).click(function(e) {
                            //funcion para evitar que el dropdown
                            e.stopPropagation(); //interfiera con la tabla.
                        });
                    });
            },
        })
        .DataTable();
    tabla.on('order.dt search.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function(cell) {
            this.data(i++);
        });
    }).draw();
    
}

function listarSemana() {
    flag = true;
    let semana = document.querySelector("#semana").value;

    let year = parseInt(semana.slice(0, 4), 10);
    let week = parseInt(semana.slice(6), 10);

    let day;

    if(year == 2022){       // Condicional porque en 2022 no funcionaba, solo funcionaba a partir de 2023
        day = 1 + week * 7; // 1st of January + 7 days for each week
    }else{
        day = 1 + (week-1) * 7;
    }
    
    let dayOffset = new Date(year, 0, 1).getDay(); // we need to know at what day of the week the year start
    dayOffset--; // depending on what day you want the week to start increment or decrement this value. This should make the week start on a monday
    let days = [];
    for (
        let i = 0; i < 7; i++ // do this 7 times, once for every day
    ) {
        days.push(new Date(year, 0, day - dayOffset + i)); // add a new Date object to the array with an offset of i days relative to the first day of the week
    }
    let inicio =
        days[0].getFullYear() +
        "-" +
        (days[0].getMonth() + 1) +
        "-" +
        days[0].getDate();
    let fin =
        days[6].getFullYear() +
        "-" +
        (days[6].getMonth() + 1) +
        "-" +
        days[6].getDate();
    //tabla y peticion
    tabla = $("#tbllistado")
        .dataTable({
            aProcessing: true, //activamos el procedimiento del datatable
            aServerSide: true, //paginacion y filrado realizados por el server
            dom: "Blfrtlip", //definimos los elementos del control de la tabla
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets:0,
            }, 
            {
                targets: [5,6],
                render: function ( data, type, row ) {
                    if ( type === 'sort') {
                        if (data==null) return 0
                        var tiempo = data.split(" ");

                        var segundos = tiempo[0].slice(-2);
                        var minutos = tiempo[0].slice(-5,-3);
                        var horas = tiempo[0].slice(0,-6)
                        sortValue = (parseInt(horas)*3600+parseInt(minutos)*60+parseInt(segundos)).toString();
                        for(i=sortValue.length;i<8;i++) {
                            sortValue = "0"+sortValue;
                        }
                      return sortValue;
                    } else { 
                      return data;
                    }
                }
            }],
            buttons: ["excelHtml5",
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    orientation: 'portrait', //landscape
                    pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                    exportOptions: {
                        columns: ':visible',
                        search: 'applied',
                        order: 'applied'
                    },
                    customize: function(doc) {
                        doc.defaultStyle.font = "mon";
                        let table = doc.content[1].table.body;
                        //Width de tabla
                        doc.content[1].table.widths = ['5%', '23%', '23%', '16%', '9%', '11%', '12%'];
                        //Centrar columnas
                        doc.styles.tableBodyEven.alignment = "center";
                        doc.styles.tableBodyOdd.alignment = "center";
                        //Pintar header
                        var numCols = $('#tbllistado').DataTable().columns(':visible').nodes().length;
                        var numFilas = table.length;
                        for (i = 0; i < numCols; i++) {
                            table[0][i].fillColor = "#9D2449";
                            for (j = 1; j < numFilas; j++) {
                                table[j][i].fillColor = "#D4C19C";
                                j = j + 1;
                            }
                        }
                        table[0][3].text = "Tipo";

                        doc.content.splice(0, 1); // quitar titulo de datatables
                        doc.pageMargins = [20, 80, 20, 30];
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 12;
                        doc['header'] = (function() {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        text: 'OFICINA DE PASAPORTES YUCATÁN',
                                        color: '#621132',
                                        font: 'gmx',
                                        fontSize: 14,
                                        margin: [0, 0]
                                    },
                                    {
                                        alignment: 'right',
                                        fontSize: 11,
                                        font: "mon",
                                        text: 'Reporte semanal. \nInicio: ' + inicio + ' Fin: ' + fin + "\nEntradas: " + (numFilas - 1),
                                    }
                                ],

                                margin: 20
                            }
                        });
                    }

                }
            ],
            ajax: {
                url: "../ajax/asistencia.php?op=listar",
                data: { fecha_inicio: inicio, fecha_fin: fin },
                type: "get",
                dataType: "json",
                error: function(e) {
                },
            },
            bDestroy: true,
            iDisplayLength: 10, //paginacion
            order: [
                [0, "asc"]
            ], //ordenar (columna, orden)
            initComplete: function() {
                //Funcion para filtrar por tipo de usuario.
                this.api()
                    .columns([3]) //Columna de tipo
                    .every(function() {
                        var column = this;
                        var select = $('<select><option value="">Tipo</option></select>') //Dropdown
                            .appendTo($(column.header()).empty())
                            .on("change", function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? "^" + val + "$" : "", true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                select.append('<option value="' + d + '">' + d + "</option>");
                            });

                        $(select).click(function(e) {
                            //funcion para evitar que el dropdown
                            e.stopPropagation(); //interfiera con la tabla.
                        });
                    });
            },
        })
        .DataTable();
    tabla.on('order.dt search.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function(cell) {
            this.data(i++);
        });
    }).draw();
}


function listar_asistencia() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var codigo_persona = $("#codigo_persona").val();
    //var nombre_persona = $("#codigo_persona").options[$("#codigo_persona").selectedIndex].text;
    var e = document.getElementById("codigo_persona");
    var nombre_persona = e.options[e.selectedIndex].text;

    var numero_asistencias = "";
    var horas_periodo = "";
    //Horas y dias
    $.ajax({
        url: "../ajax/asistencia.php?op=numero_asistencias",
        type: "POST",
        data: { fecha_inicio:fecha_inicio, fecha_fin:fecha_fin,codigo_persona: codigo_persona},
        processData: JSON,
        success: function(datos) {
            numero_asistencias = datos;
        },
    })
    $.ajax({
        url: "../ajax/asistencia.php?op=horas_periodo",
        type: "POST",
        data: { fecha_inicio:fecha_inicio, fecha_fin:fecha_fin,codigo_persona: codigo_persona},
        processData: JSON,
        success: function(datos) {
            horas_periodo = datos;
        },
    })

    tabla = $("#tbllistado_asistencia")
        .dataTable({
            aProcessing: true, //activamos el procedimiento del datatable
            aServerSide: true, //paginacion y filrado realizados por el server
            dom: 'Blfrtip', //definimos los elementos del control de la tabla
            columnDefs: [
            {
                orderData: [2],
                targets: [2],
                render: function ( data, type, row ) {
                    if ( type === 'sort') {
                    if (data==null) return 0
                    var tiempo = data.split(" ");

                        var segundos = tiempo[0].slice(-2);
                        var minutos = tiempo[0].slice(-5,-3);
                        var horas = tiempo[0].slice(0,-6)
                    
                        sortValue = (parseInt(horas)*3600+parseInt(minutos)*60+parseInt(segundos)).toString();

                    for(i=sortValue.length;i<8;i++) {
                        sortValue = "0"+sortValue;
                    }
                      return sortValue;
                    } else { 
                      return data;
                    }
                }
            }
        ],
            buttons: ["excelHtml5",
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    orientation: 'portrait', //landscape
                    pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                    exportOptions: {
                        columns: ':visible',
                        search: 'applied',
                        order: 'applied'
                    },
                    customize: function(doc) {
                        doc.defaultStyle.font = "mon";
                        doc.styles.tableBodyEven.alignment = "center";
                        doc.styles.tableBodyOdd.alignment = "center";
                        let table = doc.content[1].table.body;
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        var numCols = $('#tbllistado_asistencia').DataTable().columns(':visible').nodes().length;
                        var numRows = doc.content[1].table.body.length;
                        for (i = 0; i < numCols; i++) {
                            table[0][i].fillColor = "#9D2449";
                            for (j = 1; j < numRows; j++) {
                                table[j][i].fillColor = "#D4C19C";
                                j = j + 1;
                            }
                        }
                        doc.content.splice(0, 1); // quitar titulo de datatables
                        doc.pageMargins = [20, 70, 20, 30];
                        doc.defaultStyle.fontSize = 11;
                        doc.styles.tableHeader.fontSize = 12;
                        doc['header'] = (function() {
                            return {
                                columns: [{
                                        alignment: 'left',
                                        text: 'OFICINA DE PASAPORTES YUCATÁN',
                                        color: '#621132',
                                        font: 'gmx',
                                        fontSize: 14,
                                        margin: [0, 0],
                                    },
                                    {
                                        alignment: 'right',
                                        font: 'mon',
                                        fontSize: 11,
                                        text: 'Asistencias por Fecha. \nInicio: ' + fecha_inicio + ' Fin: ' + fecha_fin + '\n Nombre: ' + nombre_persona  
                                    },
                                ],
                                margin: 20
                            }
                        });
                        doc['footer'] = (function(page,pages) {
                            return {
                                columns: [
                                    {
                                        alignment: 'center',
                                        font: 'mon',
                                        fontSize: 11,
                                        text: 'Días asistidos: ' +numero_asistencias + '\n Horas en el periodo: ' +  horas_periodo
                                    },
                                ],
                                margin: [10,-10]
                            }
                        });
                    }
                },
            ],
            ajax: {
                url: "../ajax/asistencia.php?op=listar_asistencia",
                data: {
                    fecha_inicio: fecha_inicio,
                    fecha_fin: fecha_fin,
                    codigo_persona: codigo_persona,
                },
                type: "get",
                dataType: "json",
                error: function(e) {
                },
            },
            bDestroy: true,
            iDisplayLength: 10, //paginacion
            order: [
                [0, "desc"]
            ], //ordenar (columna, orden)
        })
        .DataTable();
}

function listar_asistenciau() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    tabla = $("#tbllistado_asistenciau")
        .dataTable({
            aProcessing: true, //activamos el procedimiento del datatable
            aServerSide: true, //paginacion y filrado realizados por el server
            dom: "frtip", //definimos los elementos del control de la tabla
            ajax: {
                url: "../ajax/asistencia.php?op=listar_asistenciau",
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
                type: "get",
                dataType: "json",
                error: function(e) {
                },
            },
            bDestroy: true,
            iDisplayLength: 10, //paginacion
            order: [
                [0, "desc"]
            ], //ordenar (columna, orden)
        })
        .DataTable();
}

function editar_hora(idasistencia) {
    bootbox.dialog({
        title: "<h2>Editar hora</h2>",
        message: "<form> <input id='horas' type='number'  min = '0' max = '23' placeholder = '00'>:<input id='minutos' type='number' min = '0' max = '59' placeholder = '00'></form>",
        size: 'small',
        buttons: {
            ok: {
                label: "Aceptar",
                className: 'btn-success btn-md col-md-4  pull-left',
                callback: function() {
                    //Input time
                    document.querySelectorAll('input[type=number]')
                        .forEach(e => e.oninput = () => {
                            if (e.value.length >= 2) e.value = e.value.slice(0, 2);
                            if (e.value.length === 1) e.value = '0' + e.value;
                            if (!e.value) e.value = '00';
                        });
                    if ($("#horas").val() < 0 || $("#horas").val() == "") {
                        $("#horas").val('00');
                    }
                    if ($("#minutos").val() > 59) {
                        $("#minutos").val("59");
                    }
                    if ($("#minutos").val() < 0 || $("#minutos").val() == "") {
                        $("#minutos").val("00");
                    }
                    nueva_hora = Math.floor($("#horas").val()) + ":" + Math.floor($("#minutos").val());
                    $.ajax({
                        url: "../ajax/asistencia.php?op=nueva_hora",
                        type: "POST",
                        data: { idasistencia: idasistencia, nueva_hora: nueva_hora },
                        processData: JSON,
                        success: function(data) {
                            listar_asistencia();
                        }
                    })

                }
            },
            cancel: {
                label: "Cancelar",
                className: 'btn-warning btn-md col-md-4 pull-right',
                callback: function() {
                    //No hay
                }
            },
        }
    })
}

function crear_asistencia() {
    bootbox.dialog({
        title: "<h2>Agregar asistencia</h2>",
        message: "<div class='form-group'><label>Becario</label><select name='codigo_persona' id='codigo_persona1' class='form-control selectpicker' data-live-search='true' required></select></div><div class='form-group'><label>Fecha <p id='alerta_fecha' class='text-danger'></p></label> <input type='date' class='form-control' name='fecha' id='fecha' value='<?php echo date(Y-m-d) required; ?>'></div><div class='form-group'><label>Horas</label><div><input id='horas' type='number'  min = '0' max = '23' placeholder = '00'>:<input id='minutos' type='number' min = '0' max = '59' placeholder = '00'></div></div>",
        size: 'small',
        buttons: {
            ok: {
                label: "Aceptar",
                className: 'btn-success btn-md col-md-4  pull-left',
                callback: function() {
                    //Input time
                    document.querySelectorAll('input[type=number]')
                        .forEach(e => e.oninput = () => {
                            if (e.value.length >= 2) e.value = e.value.slice(0, 2);
                            if (e.value.length === 1) e.value = '0' + e.value;
                            if (!e.value) e.value = '00';
                        });
                    if ($("#horas").val() < 0 || $("#horas").val() == "") {
                        $("#horas").val('00');
                    }
                    if ($("#minutos").val() > 59) {
                        $("#minutos").val("59");
                    }
                    if ($("#minutos").val() < 0 || $("#minutos").val() == "") {
                        $("#minutos").val("00");
                    }
                    var fecha = $("#fecha").val();
                    var codigo_persona = $("#codigo_persona1").val();
                    var nueva_hora = Math.floor($("#horas").val()) + ":" + Math.floor($("#minutos").val());
                    if (fecha ==""){
                        document.getElementById("alerta_fecha").innerHTML = "Debes seleccionar una fecha"
                        return false;
                    }
                    $.ajax({
                        url: "../ajax/asistencia.php?op=nueva_asistencia",
                        type: "POST",
                        data: { codigo_persona: codigo_persona,fecha: fecha, nueva_hora: nueva_hora},
                        processData: JSON,
                        success: function(datos) {
                            bootbox.alert(datos);
                            listar_asistencia();
                        },
    
                    })
                }
            },
            cancel: {
                label: "Cancelar",
                className: 'btn-warning btn-md col-md-4 pull-right',
                callback: function() {
                    //No hay
                }
            },
        }
    })
    $.post("../ajax/asistencia.php?op=selectPersona", function(r) {
        $("#codigo_persona1").html(r);
        $("#codigo_persona1").selectpicker("refresh");
    });
}

function mostrarES(codigopersona,fecha_inicio,fecha_fin) {
    if(flag){
        fecha_inicio = dateFormat(fecha_inicio, 'yyyy-MM-dd');
        fecha_fin = dateFormat(fecha_fin, 'yyyy-MM-dd');
    }
    location.href = `../vistas/rptasistencia.php?codigopersona=${codigopersona}&fechaini=${fecha_inicio}&fechafin=${fecha_fin}`;

}

//a simple date formatting function
function dateFormat(inputDate, format) {
    //parse the input date
    const date = new Date(inputDate);
    //extract the parts of the date

    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();    

    //replace the month
    format = format.replace("MM", month.toString().padStart(2,"0"));        

    //replace the year
    if (format.indexOf("yyyy") > -1) {
        format = format.replace("yyyy", year.toString());
    } else if (format.indexOf("yy") > -1) {
        format = format.replace("yy", year.toString().substr(2,2));
    }

    //replace the day
    format = format.replace("dd", day.toString().padStart(2,"0"));
    return format;

}

init();