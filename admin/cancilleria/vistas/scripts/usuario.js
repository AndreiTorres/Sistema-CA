var tabla;

//funcion que se ejecuta al inicio
function init() {
    mostrarform(false);
    mostrarform_clave(false);
    listar();
    $("#formularioc").on("submit", function (c) {
        editar_clave(c);
    })
    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $("#imagenmuestra").hide();
    //mostramos los permisos
    $.post("../../cancilleria/ajax/usuario.php?op=permisos&id=", function (r) {
        $("#permisos").html(r);
    });

    //cargamos los items al select departamento
    $.post("../../cancilleria/ajax/departamento.php?op=selectDepartamento", function (r) {
        $("#iddepartamento").html(r);
        $('#iddepartamento').selectpicker('refresh');
    });

    //cargamos los items al select tipousuario
    $.post("../../cancilleria/ajax/tipousuario.php?op=selectTipousuario", function (r) {
        $("#idtipousuario").html(r);
        $('#idtipousuario').selectpicker('refresh');
    });

    let url = new URL(window.location.href);
    // Busca si existe el parámetro
    if(url.searchParams.get('id')!=null){
        mostrar(url.searchParams.get('id'));
    }
}

//funcion limpiar
function limpiar() {
    $("#nombre").val("");
    $("#apellidos").val("");
    $("#direccion").val("");
    document.getElementById("iddepartamento").selectedIndex = 0;
    $("#iddepartamento").selectpicker('refresh');
    document.getElementById("idtipousuario").selectedIndex = 0;
    $("#idtipousuario").selectpicker('refresh');
    $("#email").val("");
    $("#login").val("");
    $("#clave").val("");
    $("#codigo_persona").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#idusuario").val("");
    $("#estado").selectpicker("refresh");
    $("#horas").val("");
    $("#lunes").val("");
    $("#lunes").prop("disabled", true);
    $("#lunesd").prop('checked', true);
    $("#martes").val("");
    $("#martes").prop("disabled", true);
    $("#martesd").prop('checked', true);
    $("#miercoles").val("");
    $("#miercoles").prop("disabled", true);
    $("#miercolesd").prop('checked', true);
    $("#jueves").val("");
    $("#jueves").prop("disabled", true);
    $("#juevesd").prop('checked', true);
    $("#viernes").val("");
    $("#viernes").prop("disabled", true);
    $("#viernesd").prop('checked', true);
    $("#sabado").val("");
    $("#sabado").prop("disabled", true);
    $("#sabadod").prop('checked', true);
    $("#domingo").val("");
    $("#domingo").prop("disabled", true);
    $("#domingod").prop('checked', true);
}

//funcion mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#login").prop("disabled", false);
        $("#apellidos").prop("disabled", false);
        $("#horario").show();

    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

function mostrarform_clave(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formulario_clave").show();
        $("#btnGuardar_clave").prop("disabled", false);
        $("#btnagregar").hide();
        $("#login").prop("disabled", false);
        $("#apellidos").prop("disabled", false);
        $("#horario").show();
    } else {
        $("#listadoregistros").show();
        $("#formulario_clave").hide();
        $("#btnagregar").show();
    }
}
//cancelar form
function cancelarform() {
    $("#claves").show();
    limpiar();
    mostrarform(false);
}

function cancelarform_clave() {
    limpiar();
    mostrarform_clave(false);
}
//funcion listar

Puesto = "";
tipoUsuario = "";

function listar() {
    tabla = $('#tbllistado').dataTable({
        stateSave :true,
        "aProcessing": true, //activamos el procedimiento del datatable
        "aServerSide": true, //paginacion y filrado realizados por el server
        dom: 'Blfrtlip', //definimos los elementos del control de la tabla
        order: [[2,'asc']],
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0,
        },
        ],
        buttons: [
            {
                extend: "excelHtml5",
                title: "Usuarios Cancilleria"
            }
                ,
            {
                extend: 'pdfHtml5',
                title: "Usuarios Cancilleria",
                text: 'PDF',
                orientation: 'portrait', //landscape
                pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                exportOptions: {
                    columns: [0,2,3,5],
                    search: 'applied',
                    order: 'applied'
                },
                customize: function (doc) {
                    doc.defaultStyle.font = "mon";
                    //Width 100%
                    //doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    doc.content[1].table.widths = ['5%', '50%', '20%', '25%'];
                    let table = doc.content[1].table.body;            
                    var numCols = $('#tbllistado').DataTable().columns([0,2,3,5]).nodes().length;
                    var numFilas = table.length;
                    doc.styles.tableBodyEven.alignment = "center";
                    doc.styles.tableBodyOdd.alignment = "center";
                    for (i = 0; i < numCols; i++) {
                        table[0][i].fillColor = "#9D2449";
                        for (j = 1; j < numFilas; j++) {
                            table[j][i].fillColor = "#D4C19C"
                            j = j + 1;
                        }
                        if (table[0][i].text == "TipoTodos"+tipoUsuario) {
                            table[0][i].text = "Tipo";
                        }
                        if (table[0][i].text == "PuestoTodos"+Puesto) {
                            table[0][i].text = "Puesto";
                        }
                    }

                    doc.content.splice(0, 1); // quitar titulo de datatables
                    doc.pageMargins = [20, 60, 20, 30];
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    doc['header'] = (function () {
                        return {
                            columns: [
                                /*{
                                    image: logo,		propiedad para insertar imagen
                                    width: 24
                                },*/
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
                                    text: 'Lista de Usuarios\n Total: ' + (numFilas - 1)
                                }
                            ],
                            margin: 20
                        }
                    });
                }

            },
            
        ],
        "ajax": {
            url: '../ajax/usuario.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
            }
        },
        "bDestroy": true,
        "iDisplayLength": 25, //paginacion
        "order": [
            [0, "desc"]
        ], //ordenar (columna, orden)

        initComplete: function () { //Funcion para filtrar por tipo de usuario.
            this.api()
                .columns([3]) //Columna de la tabla a filtrar
                .every(function () {
                    var column = this;
                    var select = $('<select><option value="">Todos</option></select>') //Dropdown
                        .appendTo($(column.header()))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                            tipoUsuario += d;
                        });

                    $(select).click(function (e) { //funcion para evitar que el dropdown
                        e.stopPropagation(); //interfiera con la tabla.
                    });
                });
                this.api()
                .columns([5]) //Columna de la tabla a filtrar
                .every(function () {
                    var column = this;
                    var select = $('<select><option value="">Todos</option></select>') //Dropdown
                        .appendTo($(column.header()))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                            Puesto += d;
                        });

                    $(select).click(function (e) { //funcion para evitar que el dropdown
                        e.stopPropagation(); //interfiera con la tabla.
                    });
                    var currSearch = column.search();
                            if ( currSearch ) {
                            select.val( currSearch.substring(1, currSearch.length-1) );
                            }
                });    
        },

    }).DataTable();
    tabla.on('order.dt search.dt', function () {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
}
//funcion para guardaryeditar
function guardaryeditar(e) {
    e.preventDefault(); //no se activara la accion predeterminada 
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../../cancilleria/ajax/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }
    });
    $("#claves").show();
    limpiar();
}

function editar_clave(c) {
    c.preventDefault(); //no se activara la accion predeterminada 
    var formData = new FormData($("#formularioc")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=editar_clave",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            bootbox.alert(datos);
            mostrarform_clave(false);
            tabla.ajax.reload();
        }
    });
    limpiar();
    $("#getCodeModal").modal('hide');
}
//abre ventana de edicion 
function mostrar(idusuario) {
    limpiar();
    $.post("../../cancilleria/ajax/usuario.php?op=mostrar", { idusuario: idusuario },
        function (data, status) {
            data = JSON.parse(data);
            mostrarform(true);
            $("#Estado").show();
            if ($("#idusuario").val(data.idusuario).length == 0) {
                $("#claves").show();
            } else {
                $("#claves").hide();
            }
            $("#iddepartamento").val(data.iddepartamento);
            $("#iddepartamento").selectpicker('refresh');
            $("#idtipousuario").val(data.idtipousuario);
            $("#idtipousuario").selectpicker('refresh');
            if($("#idtipousuario option:selected").text()!="Administrador Cancillería" && $("#idtipousuario option:selected").text()!="Administrador"){
                $("#login").prop("disabled", true);
            }
            if($("#iddepartamento option:selected").text() == "Visitante") {
                $("#apellidos").prop("disabled", true);
                $("#horario").hide();
            }
            $("#nombre").val(data.nombre);
            $("#apellidos").val(data.apellidos);
            $("#login").val(data.login);
            $("#codigo_persona").val(data.codigo_persona);
            $("#imagenmuestra").show();
            $("#imagenmuestra").attr("src", "../../files/usuarios/" + data.imagen);
            $("#imagenactual").val(data.imagen);
            $("#idusuario").val(data.idusuario);
            if(data.Monday!==null){
                $("#lunes").val(data.Monday);
                $("#lunes").prop("disabled", false);
                $("#lunesd").prop('checked', false);
            }
            if(data.Tuesday!=null){
                $("#martes").val(data.Tuesday);
                $("#martes").prop("disabled", false);
                $("#martesd").prop('checked', false);
            }
            if(data.Wednesday!=null){
                $("#miercoles").val(data.Wednesday);
                $("#miercoles").prop("disabled", false);
                $("#miercolesd").prop('checked', false);
            }
            if(data.Thursday!=null){
                $("#jueves").val(data.Thursday);
                $("#jueves").prop("disabled", false);
                $("#juevesd").prop('checked', false);
            }
            if(data.Friday!=null){
                $("#viernes").val(data.Friday);
                $("#viernes").prop("disabled", false);
                $("#viernesd").prop('checked', false);
            }
            if(data.Saturday!=null){
                $("#sabado").val(data.Saturday);
                $("#sabado").prop("disabled", false);
                $("#sabadod").prop('checked', false);
            }
            if(data.Sunday!=null){
                $("#domingo").val(data.Sunday);
                $("#domingo").prop("disabled", false);
                $("#domingod").prop('checked', false);
            }
        });
}

function mostrar_clave(idusuario) {
    $("#getCodeModal").modal('show');
    $.post("../ajax/usuario.php?op=mostrar_clave", { idusuario: idusuario },
        function (data, status) {
            data = JSON.parse(data);
            $("#idusuarioc").val(data.idusuario);
        });
}

//funcion para desactivar
function desactivar(codigo_persona) {
    bootbox.confirm("¿Esta seguro de eliminar a este usuario?", function (result) {
        if (result) {
            $.post("../../cancilleria/ajax/usuario.php?op=desactivar", { codigo_persona: codigo_persona }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

function generar(longitud) {
    long = parseInt(longitud);
    var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789";
    var contraseña = "";
    for (i = 0; i < long; i++) contraseña += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    $("#codigo_persona").val(contraseña);
}

$(function () {
    $("#idtipousuario").change(function () {
        if ($(this).val() === "7") {
            $(".id_input").prop("disabled", true);
        } else {
            $(".id_input").prop("disabled", false);
            $("#apellidos").prop("disabled", false);
            $("#horario").show();
        }
        $('#iddepartamento').val(0);
        $('#iddepartamento').selectpicker('refresh');
    });
    $("#iddepartamento").change(function () {
        if ($("#iddepartamento option:selected").text() == "Visitante") {
            $("#apellidos").val("VISITANTE");
            $("#horario").hide();
        } else {
            $("#horario").show();
        }
    });
    $("#lunesd").change(function () {
        mod_horario($('#lunes'), document.getElementById("lunesd"))
    });
    $("#martesd").change(function () {
        mod_horario($('#martes'), document.getElementById("martesd"))
    });
    $("#miercolesd").change(function () {
        mod_horario($('#miercoles'), document.getElementById("miercolesd"))
    });
    $("#juevesd").change(function () {
        mod_horario($('#jueves'), document.getElementById("juevesd"))
    });
    $("#viernesd").change(function () {
        mod_horario($('#viernes'), document.getElementById("viernesd"))
    });
    $("#sabadod").change(function () {
        mod_horario($('#sabado'), document.getElementById("sabadod"))
    });
    $("#domingod").change(function () {
        mod_horario($('#domingo'), document.getElementById("domingod"))
    });

});


function mod_horario(dia, check) {
    try {
        if (check.checked == true) {
            dia.val("");
            dia.prop("disabled", true);
            check.prop('checked', true);
        } else {
            dia.prop("disabled", false);
            check.prop('checked', false);
        }
    } catch { }
}

init();