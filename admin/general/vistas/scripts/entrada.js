function init(){
    $("#formularioc").on("submit", function(c) {
        editar_clave(c);
    })
}

function editar_clave(c) {
    c.preventDefault(); //no se activara la accion predeterminada 
    var formData = new FormData($("#formularioc")[0]);
    $.ajax({
        url: "../ajax/entrada.php?op=editar_clave",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            $("#inputPassword").val("");
            bootbox.alert(datos);
        }
    });
}

init()