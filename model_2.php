<div class="tituloModulo">ENVIAR SMS</div>
<div class="closeModulo" onclick="closeModulo(2)">[ close window ] &nbsp;&nbsp;</div>
<br>
<br>
<div id="left_contacto" style="width: 98%;    margin: 0 auto;text-align:right;  ">

    <div class="tableContenidos">
        <div class="tableTd num" id="DirectorioGrupos">
            
        </div>
        <div class="tableTd num" style="text-align: center">
            &nbsp;<a href="javascript:addGrupoSelect()" class="classnameSmall"  > Agregar ></a> &nbsp; 
            &nbsp;<a href="javascript:addGrupoSelectAll()" class="classnameSmall"  > Todo >> </a> &nbsp; 
            &nbsp;<a href="javascript:delGrupoSelect()" class="classnameSmall"  >< Quitar </a> &nbsp; 
            &nbsp;<a href="javascript:delGrupoSelectAll()" class="classnameSmall"  ><< Todo </a>&nbsp;                            
        </div>
        <div class="tableTd num" id="DirectorioGruposTo">
            
        </div>
    </div>   
    <div style="clear: both"></div>

    <div id="selectTemplate" style="text-align: left"></div>
    <br>
    <div style="text-align: left" id="callBotones"> &nbsp;<a href="javascript:sendCalls()" class="classnameSmall"  > SEND CALLS</a> &nbsp; </div>
    <br><br>
    
    <div id="dialog-confirm-calls" title="Seguro de Hacer las Llamadas?" style="display:none">
      <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;" ></span>Se Van a Realizar <b id='sumContactView'></b> llamadas. Esta Seguro?</p>
    </div>
</div>    
