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
    $("#login").val("");
    $("#email").val("");
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

tipoUsuario = "";
Universidad = "";

function listar() {
    tabla = $('#tbllistado').dataTable({
        stateSave: true,
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

                for(i=sortValue.length;i < 8;i++) {
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
            {
                extend: "excelHtml5",
                title: "Becarios"
            }
                ,
            {
                extend: 'pdfHtml5',
                title: "Becarios",
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
                        if (table[0][i].text == "TipoTodos" + tipoUsuario) {
                            table[0][i].text = "Tipo";
                        }
                        if (table[0][i].text == "UniversidadTodos" + Universidad) {
                            table[0][i].text = "Universidad";
                        }
                    }

                    doc.content.splice(0, 1); // quitar titulo de datatables
                    doc.pageMargins = [20, 60, 20, 30];
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fontSize = 10;
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
        "iDisplayLength": 25, //paginacion
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
                            tipoUsuario += d; 
                        });
                    $(select).click(function(e) { //funcion para evitar que el dropdown
                        e.stopPropagation(); //interfiera con la tabla.
                    });
                    var currSearch = column.search();
                        if ( currSearch ) {
                        select.val( currSearch.substring(1, currSearch.length-1) );
                    }
                });
            
                this.api()
                .columns([5]) //Columna de la tabla a filtrar
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
                            Universidad += d; 
                        });
                    $(select).click(function(e) { //funcion para evitar que el dropdown
                        e.stopPropagation(); //interfiera con la tabla.
                    });
                    var currSearch = column.search();
                        if ( currSearch ) {
                        select.val( currSearch.substring(1, currSearch.length-1) );
                    }
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
    for(let [name, value] of formData) {
        if (name === "imagen" || name === "imagenactual"){
            continue; 
        } else {
            formData.set(name,getCleanedString(value+""))
        }
        //alert(`${name} = ${value}`); // key1 = value1, luego key2 = value2
    }
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
            $("#login").val(data.login);
            $("#email").val(data.email);
            $("#codigo_persona").val(data.codigo_persona);
            $("#imagenmuestra").show();
            $("#imagenmuestra").attr("src", "../../files/usuarios/" + data.imagen);
            $("#imagenactual").val(data.imagen);
            $("#idusuario").val(data.idusuario);
            $("#estado").val(data.estado);
            $("#estado").selectpicker('refresh');
        });
    $.post("../ajax/usuario.php?op=horas_totales", { idusuario: idusuario }, function(data, status) {
        data = JSON.parse(data);
        $("#horas").val(data.horas);
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
                }
            );
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

function getCleanedString(cadena){
    // Definimos los caracteres que queremos eliminar
    var specialChars = "!#$^&%*()+=-[]\/{}|:<>?,";
    // Los eliminamos todos
    for (var i = 0; i < specialChars.length; i++) {
        cadena= cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
    }   
    // Quitamos acentos y "ñ". Fijate en que va sin comillas el primer parametro
    cadena = cadena.replace(/á/gi,"A");
    cadena = cadena.replace(/é/gi,"E");
    cadena = cadena.replace(/í/gi,"I");
    cadena = cadena.replace(/ó/gi,"O");
    cadena = cadena.replace(/ú/gi,"U");
    return cadena;
}

init();