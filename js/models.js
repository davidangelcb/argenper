function openMp3(id){
    
    
    $.ajax({
        url: 'models/grabacion.php',
        type: 'post',
        data: {
            oper: 'getMp3',idt: id},

        success: function (data) {

            var html = data;            
            $("#mp3File").html(html);
            $("#dialog-mp3").dialog({
                width: 400,
                modal: true 
            });
        }
    });

}
function directorio() {
    $.ajax({
        url: 'models/directorio.php',
        type: 'post',
        data: {
            oper: 'listar'},
        dataType: 'json',
        success: function (data) {
            var json = data;
            $("#contenedorGrupos").html(json.html);
        }
    });

}
function addGrupo() {

    $("#dialog-confirm").dialog({
        resizable: false,
        height: 130,
        width: 300,
        modal: true,
        buttons: {
            "Guardar Grupo": function () {
                var response = saveGrupo();
                if (response > 0) {                    
                    loadContact(response);
                    $(this).dialog("close");
                } else {
                    alert("Error" + response);
                }
            },
            "Cancelar": function () {
                $(this).dialog("close");
            }
        }
    });
}
function gotoView(open, close) {
    $("#" + close).hide();
    $("#" + open).show();
}
function saveGrupo() {
    var dataGrupo = 0;
    $.ajax({
        url: 'models/directorio.php',
        type: 'post',
        async: false,
        data: {
            oper: 'addGrupo', 'name': $("#namegruponew").val()
        }
        ,
        dataType: 'json',
        success: function (data) {
            dataGrupo = data.id;
        }
    });
    return dataGrupo;
}
var firstClick = true;
function loadContact(id) {
    gotoView("contactosView", "directorioView");
      
    $("#nameGrupo").html("Grupo: "+$("#grupoNombre_"+id).html());
    /*$("#contenedorContactos").html("Cargando...");
    $("#nameGrupo").val($("#grupoNombre_"+id).html());
        $.ajax({
            url: 'models/contactos.php',
            type: 'post',
            data: {
                oper: 'listar', 'directorio': id
            }
            ,
            dataType: 'json',
            success: function (data) {
                $("#contenedorContactos").html(data.html);
            }
        });*/
    if (!firstClick) {
        $("#list100").setGridParam(
        {
                url:'models/contactos.php?oper=listar&q=1&directorio='+id,
                page:1,
                editurl:"models/contactos.php?directorio="+id
        }).trigger("reloadGrid");
    }
    firstClick = false;
    jQuery("#list100").jqGrid({
            url:'models/contactos.php?oper=listar&q=1&directorio='+id,
            datatype: "json",
            colNames:['Id','Nombre', 'Celular','Pred. Valor 1','Pred. Valor 2','Email Redirect'],
            colModel:[
                    {name:'id',index:'id', width:15,editable:false,editoptions:{readonly:true,size:10}},
                    {name:'nombre',index:'titulo', width:250,editable:true,editoptions:{size:20}},
                    {name:'celular',index:'celular', width:50,editable:true,editoptions:{size:20}},
                    {name:'valor1',index:'valor1', width:80,editable:true,editoptions:{size:20}},
                    {name:'valor2',index:'valor2', width:80,editable:true,editoptions:{size:20}},
                    {name:'email',index:'email', width:80, editable:true,editoptions:{size:20}}		
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager100',
            sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        caption:"Contactos",
        editurl:"models/contactos.php?directorio="+id,
        multiselect: true,
        rownumbers: true, 
                rownumWidth: 25, 
        height: 300,
        width:800
    });    
    
}
function getSelectedContacts() {
            var grid = $("#list100");
            var rowKey = grid.getGridParam("selrow");

            if (!rowKey)
                alert("Seleccione un registro como minimo");
            else {
                var selectedIDs = grid.getGridParam("selarrrow");
                var result = "0";
                for (var i = 0; i < selectedIDs.length; i++) {
                    result += ","+selectedIDs[i];
                }

                    $.ajax({
                        url: 'models/contactos.php',
                        type: 'post',
                        async: false,
                        data: {
                            oper: 'delete',  'ids':result
                        }
                        ,
                        dataType: 'json',
                        success: function (data) {
                            if(data.error==0){
                                alert("Se Elimino correctamente");
                                 $('#list100').trigger( 'reloadGrid' );
                            }else{
                                alert(data.message);
                            }
                        }
                    });  
            }                
}
function addContacto(){
	jQuery("#list100").jqGrid('editGridRow',"new",{height:280,reloadAfterSubmit:true,afterSubmit: afterSaveContact,closeAfterAdd: true});
}
var afterSaveContact = function (xhr, postdata){
            var response = eval("(" + xhr.responseText + ")");
            alert(response.message);
            $('#list100').trigger( 'reloadGrid' );
            return true;
};
function editGrupos(id) {
    $("#dialog-confirm2").dialog({
        resizable: false,
        height: 130,
        width: 300,
        modal: true,
        buttons: {
            "Guardar Grupo": function () {
                var response = updateGrupo(id);
                if (response.error == 0) {
                    //pudate done
                    $("#grupoNombre_"+id).html($("#namegruponew2").val());
                    $(this).dialog("close");
                } else {
                    alert("Error: " + response.message);
                }
            },
            "Cancelar": function () {
                $(this).dialog("close");
            }
        }
    });
    $("#namegruponew2").val($("#grupoNombre_"+id).html());
    
}
function updateGrupo(id) {
    var dataGrupo = 0;
    $.ajax({
        url: 'models/directorio.php',
        type: 'post',
        async: false,
        data: {
            oper: 'updateGrupo', 'name': $("#namegruponew2").val(), 'directorio':id
        }
        ,
        dataType: 'json',
        success: function (data) {
            dataGrupo = data;
        }
    });
    return dataGrupo;
}
function grupoSelectAll(thiss){
      var checked_status = thiss.checked;
      $("input[name='select[]']").each(function(){
        this.checked = checked_status;
      });
}
function delGrupo(){
    var ids ='0';
    $("input[name='select[]']").each(function(){
        if(this.checked){
            ids += ","+this.value;
            
        }
    });
    if(ids=='0'){
        alert("Seleccione un Grupo.")
        return;
    }
    $.ajax({
        url: 'models/directorio.php',
        type: 'post',
        async: false,
        data: {
            oper: 'deleteGrupo',  'ids':ids
        }
        ,
        dataType: 'json',
        success: function (data) {
            if(data.error==0){
                alert("Se Elimino correctamente");
                var id = ids.split(",");

                for(var i=0; i<id.length; i++){
                    if(id[i]>0){
                     $("#row_"+id[i]).hide();
                    }
                }
            }else{
                alert(data.message);
            }
        }
    });    
}
function exportar(directorio){
    $("#directorio").val(directorio);
    document.export.action = "files/export.php";
    document.export.submit();
}
function importar(id) {
    $("#directorioActive").val(id);
    $("#dialog-message").dialog({
        modal: true 
    });
}
function closeWindowActive(){
    document.upload.reset();
    $("#dialog-message").dialog("close");
    directorio();
    validButton();
}
function addGrabacion(){
    $("#dialog-grabacion").dialog({
        modal: true 
    });
}
var bloqueUpload = true;

function uploadGrabacion(){
    if(bloqueUpload){
        var nombre =  $("#nombregra").val();
        if(nombre==''){
            alert("Ingrese el nombre");
            return;
        }
        if (document.uploadGra.upload.value == '')
        {
            alert('Ingrese archivo');
            return;
        }
        bloqueUpload=false;
        document.uploadGra.submit();
    }else{
        alert("Espere...");
    }
}
function closeGrabacion(){
    bloqueUpload=true;
    document.uploadGra.reset();
    $("#dialog-grabacion").dialog("close");
    $('#addgrid').trigger( 'reloadGrid' );
}