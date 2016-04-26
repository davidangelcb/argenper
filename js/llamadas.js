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
var firstClickCalls = true;
function loadLlamadas(){
    var date1 = $( "#datepicker_1" ).val();
    var date2 = $( "#datepicker_2" ).val();
    if (!firstClickCalls) {
        
        $("#addgridReg").setGridParam(
        {
                url:'models/llamada.php?oper=listar&fechaini='+date1+'&fechafinal='+date2,
                page:1
        }).trigger("reloadGrid");
    }
    firstClickCalls = false;
    
    jQuery("#addgridReg").jqGrid({        
            url:'models/llamada.php?oper=listar&fechaini='+date1+'&fechafinal='+date2,
            datatype: "json",
            colNames:['Id','Fecha de Envio', 'Grupos', '# Procesados', '# Correctos','# Errores'],
            colModel:[
                    {name:'id',index:'id', width:20,editable:false,editoptions:{readonly:true,size:10}},
                    {name:'fecha',index:'fecha', width:80,editable:false,editoptions:{size:20}},
                    {name:'nombre',index:'nombre', width:250,editable:false,editoptions:{size:20}},
                    {name:'en_proceso',index:'en_proceso', width:60,editable:false,editoptions:{size:20}}    ,
                    {name:'correctos',index:'correctos', width:50,editable:false,editoptions:{size:20}}    ,
                    {name:'errores',index:'errores', width:50,editable:false,editoptions:{size:20}}    
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pageradReg',
            sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        ondblClickRow: function(id){ openDetalles(id);},
        caption:"Registro de LLamadas",
        height: 300,
        width:800
    }); 
}
function openDetalles(id){
    gotoView( "detallesView","registroView");
    loadDetalles(id);
}
var firstClickDetalles = true;
function loadDetalles(idCall){

    if (!firstClickDetalles) {
        
        $("#listDetalles").setGridParam(
        {
                url:'models/llamadaContactos.php?oper=listar&idcall='+idCall,
                page:1
        }).trigger("reloadGrid");
    }
    firstClickDetalles = false;
    //$response->rows[$i]['cell']=array($row['id'],$row['nombre'],$row['celular'],$row['fecha_proceso'],$row['id_sms'],$row['numero_envios'],$row['estatus_api']); 
    jQuery("#listDetalles").jqGrid({        
            url:'models/llamadaContactos.php?oper=listar&idcall='+idCall,
            datatype: "json",
            colNames:['Id','Nombre', 'Celular', 'FechaProceso', 'API ID','# Envios', 'Estatus'],
            colModel:[
                    {name:'id',index:'id', width:20,editable:false,editoptions:{readonly:true,size:10}},                    
                    {name:'nombre',index:'nombre', width:45,editable:false,editoptions:{size:20}},
                    {name:'celular',index:'nombre', width:42,editable:false,editoptions:{size:20}},
                    {name:'fecha_proceso',index:'fecha_proceso', width:73,editable:false,editoptions:{size:20}},
                    {name:'id_sms',index:'id_sms', width:30,editable:false,editoptions:{size:20}}    ,
                    {name:'numero_envios',index:'numero_envios', width:40,editable:false,editoptions:{size:20}}    ,
                    {name:'estatus_api',index:'estatus_api', width:250,editable:false,editoptions:{size:20}}    
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pagerDetalles',
            sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        ondblClickRow: function(id){ alert("Editando Muy Pronto: "+id);},
        caption:"Registro de LLamadas",
        height: 300,
        width:800
    }); 
}
function activeFilterReg(){
    $( "#datepicker_1" ).datepicker();
    $( "#datepicker_2" ).datepicker();
    var val1 = $( "#datepicker_1" ).attr( "val" );
    var val2 = $( "#datepicker_2" ).attr( "val" );
    $( "#datepicker_1" ).val(val1);
    $( "#datepicker_2" ).val(val2);
}
function buscarCriterioX(n){
        //clear data Temp
        TipoSearch=n;
        var objArray5 = [];
        jQuery("#listSearch").clearGridData(true).trigger("reloadGrid").addRowData('id',objArray5);        
        
        var tipo = n;
        $("#buscar_"+tipo+"_msg").html("");
        var fechaIni = $("#datepicker").val();
        var fechaFin = $("#datepicker2").val();
        var dropMenu = $("#sel_1").val();
        var columna=$("#sel_2").val();
        var criteria=$("#criteria").val();
        $.ajax({        
            url: 'ajax_search.php',        
            type: 'post',        
            data:{  
            oper1: 'searching', tipo: tipo,fechaIni: fechaIni,fechaFin: fechaFin,dropMenu: dropMenu,columna: columna,criteria: criteria},        
            datetype: 'json',        
                success: function(data){     
                    var mydata = eval(data); 
                    for(var i=0;i<=mydata.length;i++) jQuery("#listSearch").jqGrid('addRowData',i+1,mydata[i]);
                    
                    if(mydata.length>0){
                        $("#buscar_"+tipo+"_dw").show("slow");
                        $("#buscar_"+tipo+"_msg").html("");
                    }else{
                        $("#buscar_"+tipo+"_dw").hide("fast");
                        $("#buscar_"+tipo+"_msg").html("<span class='nomatch'>No hay resultados.</span>");
                    }
                }    
            });
}