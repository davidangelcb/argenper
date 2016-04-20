function addGrupoSelect(){
    $('#select-from option:selected').each( function() {
                $('#select-to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            $(this).remove();
    });    
}
function addGrupoSelectAll(){
    $('#select-from option').each( function() {
                $('#select-to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            $(this).remove();
    });
}
function delGrupoSelect(){
    $('#select-to option:selected').each( function() {
            $('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            $(this).remove();
    }); 
}
function delGrupoSelectAll(){
    $('#select-to option').each( function() {
            $('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            $(this).remove();
    });
}
function sendCalls(){
    var ids = '0';
    $('#select-to option').each( function() {
            //$('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            ids += ','+$(this).val();
    });
    
    var idTemplate = $('#templateActive').val();
    if(idTemplate=='0'){
        alert("Seleccione Grabacion Valida!");
        return;
    }
    if(ids=='0'){
        alert("Seleccione algun Grupo!");
        return;
    }
    var htmlcall =$('#callBotones').html(); 
     $('#callBotones').html("Procesando..."); 
    $.ajax({
        url: 'models/llamada.php',
        type: 'post',
        async: false,
        data: {
            oper: 'add', 'idtemplate': idTemplate, 'ids': ids
        }
        ,
        dataType: 'json',
        success: function (data) {
            $('#callBotones').html(htmlcall); 
            if(data.error==0){
                resetAll();
                alert("Se envio a Procesar correctamente!");
            }else{
                
                alert(data.message);
                
            }
        }
    }); 
}
function resetAll(){
    delGrupoSelectAll();
    $('#templateActive').val('0');
}