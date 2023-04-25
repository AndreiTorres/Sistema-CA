

$("#frmValidar").on('submit',function(e)
{
    e.preventDefault();
    clavep=$("#clavepolicia").val();
    if($("clavepolicia").val()==""){
        bootbox.alert("Favor de colocar un token correcto.");
    }else{
        $.post("ajax/verificar.php?op=verificar",
        {"clavepolicia":clavep},
        function(data)
        {
            if(data!="null")
            {
                $(location).attr("href","vistas/asistencia.php")
            }
            else{
                bootbox.alert("Token invalido.")
            }
        });
    }
})