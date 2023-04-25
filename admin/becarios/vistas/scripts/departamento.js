var tabla;

//funcion que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})
}

//funcion limpiar
function limpiar() {
	$("#iddepartamento").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
}

//funcion mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
	} else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//funcion listar
function listar() {
	tabla = $('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0,
        }], 
		buttons: [
			'excelHtml5',
			{
				extend: 'pdfHtml5',
				text: 'PDF',
				orientation: 'portrait',
				pageSize: 'letter', //A3 , A5 , A6 , legal , letter
				exportOptions: {
					columns: [0,2,3],
					search: 'applied',
					order: 'applied'
				},
				customize: function (doc) {
					doc.defaultStyle.font = "mon";
					doc.content[1].table.widths = ['5%','50%', '45%'];
					let table = doc.content[1].table.body;
					var numCols = $('#tbllistado').DataTable().columns([0,2,3]).nodes().length;
					var numFilas = table.length;
					doc.styles.tableBodyEven.alignment = "center";
					doc.styles.tableBodyOdd.alignment = "center";
					for (i = 0; i < numCols; i++) {
						table[0][i].fillColor = "#9D2449";
						for (j = 1; j < numFilas; j++) {
							table[j][i].fillColor = "#D4C19C"
							j = j + 1;
						}
					}

					doc.content.splice(0, 1); // quitar titulo de datatables
					doc.pageMargins = [20, 60, 20, 30];
					doc.defaultStyle.fontSize = 9;
					doc.styles.tableHeader.fontSize = 10;
					doc['header'] = (function () {
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
									text: 'Lista de Universidades'
								}
							],
							margin: 20
						}
					});
				}
			},
		],
		"ajax":
		{
			url: '../ajax/departamento.php?op=listar',
			type: "get",
			dataType: "json",
			error: function (e) {
			}
		},
		"bDestroy": true,
		"iDisplayLength": 25,//paginacion
		"order": [[0, "desc"]]//ordenar (columna, orden)
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
	e.preventDefault();//no se activara la accion predeterminada 
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/departamento.php?op=guardaryeditar",
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

	limpiar();
}

function mostrar(iddepartamento) {
	$.post("../ajax/departamento.php?op=mostrar", { iddepartamento: iddepartamento },
		function (data, status) {
			data = JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#descripcion").val(data.descripcion);
			$("#iddepartamento").val(data.iddepartamento);
		})
}


//funcion para desactivar
function desactivar(iddepartamento) {
	bootbox.confirm("¿Esta seguro de eliminar a este usuario?", function (result) {
		if (result) {
			$.post("../ajax/departamento.php?op=desactivar", { iddepartamento: iddepartamento }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(iddepartamento) {
	bootbox.confirm("¿Esta seguro de activar este dato?", function (result) {
		if (result) {
			$.post("../ajax/departamento.php?op=activar", { iddepartamento: iddepartamento }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();