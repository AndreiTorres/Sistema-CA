var tabla;

//funcion que se ejecuta al inicio
function init() {
    mostrarform(false);
    mostrarform_clave(false);
    listar();
    $("#formularioc").on("submit", function(c) {
        editar_clave(c);
    })
    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

    $("#imagenmuestra").hide();
    //mostramos los permisos
    $.post("../ajax/usuario.php?op=permisos&id=", function(r) {
        $("#permisos").html(r);
    });

    //cargamos los items al select departamento
    $.post("../ajax/departamento.php?op=selectDepartamento", function(r) {
        $("#iddepartamento").html(r);
        $('#iddepartamento').selectpicker('refresh');
    });

    //cargamos los items al select tipousuario
    $.post("../ajax/tipousuario.php?op=selectTipousuario", function(r) {
        $("#idtipousuario").html(r);
        $('#idtipousuario').selectpicker('refresh');
    });

    //cargamos los items al select estado
    $.post("../ajax/estadousuario.php?op=selectEstado", function(r) {
        $("#estado").html(r);
        $('#estado').selectpicker('refresh');
    });

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
}

//funcion mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#Horas").hide();
        $("#Estado").hide();
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

function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, //activamos el procedimiento del datatable
        "aServerSide": true, //paginacion y filrado realizados por el server
        dom: 'Blfrtlip', //definimos los elementos del control de la tabla
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0,
        }, 
        {
            orderData: [9],
            targets: [9],
            render: function ( data, type, row ) {
                if ( type === 'sort') {
                //     var number = data.replace(':','');
                //     number = number.replace(':','');
                //   var sortValue = parseInt(number);
                if (data==null) return 0
                var segundos = data.slice(-2);
                var minutos = data.slice(-5,-3);
                var horas = data.slice(0,-6)
                
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
        buttons: [
            'excelHtml5',
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape', //portrait
                pageSize: 'letter', //A3 , A5 , A6 , legal , letter
                exportOptions: {
                    columns: ':visible',
                    search: 'applied',
                    order: 'applied'
                },
                customize: function(doc) {
                    doc.defaultStyle.font = "mon";
                    let table = doc.content[1].table.body;
                    var numCols = $('#tbllistado').DataTable().columns(':visible').nodes().length;
                    var numFilas = table.length;
                    doc.styles.tableBodyEven.alignment = "center";
                    doc.styles.tableBodyOdd.alignment = "center";
                    for (i = 0; i < numCols; i++) {
                        table[0][i].fillColor = "#9D2449";
                        for (j = 1; j < numFilas; j++) {
                            table[j][i].fillColor = "#D4C19C"
                            j = j + 1;
                        }
                        if (table[0][i].text == "TipoTodosAdministradorPracticanteServicio social") {
                            table[0][i].text = "Tipo";
                        }
                    }

                    doc.content.splice(0, 1); // quitar titulo de datatables
                    doc.pageMargins = [20, 60, 20, 30];
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
                    /*
                    variable donde se guarda la imagen
                    var logo = 'data:image/jpeg;base64,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ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA=';
                    */
                    doc['header'] = (function() {
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
            {
                extend: 'colvis',
                text: 'Columnas',
            }
        ],
        "ajax": {
            url: '../ajax/usuario.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //paginacion
        "order": [
            [0, "desc"]
        ], //ordenar (columna, orden)

        initComplete: function() { //Funcion para filtrar por tipo de usuario.
            this.api()
                .columns([4]) //Columna de la tabla a filtrar
                .every(function() {
                    var column = this;
                    var select = $('<select><option value="">Todos</option></select>') //Dropdown
                        .appendTo($(column.header()))
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });

                    $(select).click(function(e) { //funcion para evitar que el dropdown
                        e.stopPropagation(); //interfiera con la tabla.
                    });
                });
        },

    }).DataTable();
    tabla.on('order.dt search.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function(cell) {
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
        url: "../ajax/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
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
    $("#btnGuardar_clave").prop("disabled", true);
    var formData = new FormData($("#formularioc")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=editar_clave",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
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
    $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario },
        function(data, status) {
            data = JSON.parse(data);
            mostrarform(true);
            $("#Horas").show();
            $("#Estado").show();
            if ($("#idusuario").val(data.idusuario).length == 0) {
                $("#claves").show();

            } else {
                $("#claves").hide();

            }
            $("#nombre").val(data.nombre);
            $("#iddepartamento").val(data.iddepartamento);
            $("#iddepartamento").selectpicker('refresh');
            $("#idtipousuario").val(data.idtipousuario);
            $("#idtipousuario").selectpicker('refresh');
            $("#apellidos").val(data.apellidos);
            $("#email").val(data.email);
            $("#login").val(data.login);
            $("#codigo_persona").val(data.codigo_persona);
            $("#imagenmuestra").show();
            $("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen);
            $("#imagenactual").val(data.imagen);
            $("#idusuario").val(data.idusuario);
            $("#estado").val(data.estado);
            $("#estado").selectpicker('refresh');
        });
    $.post("../ajax/usuario.php?op=horas_totales", { idusuario: idusuario }, function(data, status) {
        data = JSON.parse(data);
        $("#horas").val(data.horas);
    });
    $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function(r) {
        $("#permisos").html(r);
    });
}

function reiniciar_horas() {
    bootbox.confirm("¿Esta seguro que desea reiniciar las horas de este usuario? Se eliminaran todos sus registros de entrada/salida", function(result) {
        if (result) {
            $.post("../ajax/usuario.php?op=reiniciar_horas", {
                    codigo_persona: $("#codigo_persona").val()
                },
                function(e) {
                    $("#horas").val(0);
                    bootbox.alert(e);
                });
        }
    });


}


function mostrar_clave(idusuario) {
    $("#getCodeModal").modal('show');
    $.post("../ajax/usuario.php?op=mostrar_clave", { idusuario: idusuario },
        function(data, status) {
            data = JSON.parse(data);
            $("#idusuarioc").val(data.idusuario);
        });
}

//funcion para desactivar
function desactivar(codigo_persona) {
    bootbox.confirm("¿Esta seguro de eliminar a este usuario?", function(result) {
        if (result) {
            $.post("../ajax/usuario.php?op=desactivar", { codigo_persona: codigo_persona }, function(e) {
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

init();
