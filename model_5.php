<?php
 function _data_first_month_day() {
      $month = date('m');
      $year = date('Y');
      return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
  }
?>
<div id="registroView" style="display: block;">
    <div class="tituloModulo">Registro de LLamadas</div>
    <div class="closeModulo" onclick="closeModulo(5);">[ close window ] &nbsp;&nbsp;</div>
    <br>
    <br>
    <div style="width: 98%; margin: 0 auto;" >
        <div style="clear: both"></div>
    <br>
    <span class="titleSMS10" >
    Doble Click sobre cualquier Item para ver sus detalles, Puede Filtrar por fechas, por "Defecto" se muestra todo del ultimo mes.
    <br>(todos los resultados se muestran en orden DESCENDIENTE)<br><br></span>
    <span>
    <fieldset style="min-height:31px;width: 95%" class="xd">
            <div style="float: left; margin-left: 10px; height: 31px;">
                <span style="color:black; font-size: 12px;line-height: 31px;">Inicio:<input type="text" val="<?php echo _data_first_month_day("Y-m-d"); ?>" value="<?php echo _data_first_month_day("Y-m-d"); ?>" id="datepicker_1" class="dateT"></span>
                <span style="color:black; font-size: 12px;line-height: 31px;">Fin: <input type="text"  val="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>" id="datepicker_2" class="dateT"></span>
            </div>
            <div style="float: left; margin-left: 10px; height: 31px;">
                <a href="javascript:loadLlamadas()" class="classname" style="width:80px;height:22px;	line-height:22px;">Filtrar</a>&nbsp;&nbsp;
            </div>
    </fieldset>
    <br>
    </span>
    </div>

     <div style="clear: both"></div>
     <div style="width: 99%; margin: 0 auto;" >
        <table id="addgridReg" ></table> <div id="pageradReg" ></div>
    </div>
</div>
 <div id="detallesView" style="display: none;">
    <div class="tituloModulo">Detalles</div>
    <div class="closeModulo" onclick='gotoView("registroView","detallesView");closeModulo(5);'>[ close window ] &nbsp;&nbsp;</div>
    <br><br>                   
    
    <div>
        &nbsp;<a href='javascript:gotoView("registroView","detallesView");' class="classname"  >Volver</a>&nbsp;&nbsp;<br><br>
    </div>
    <div style="clear: both"></div>
   
    <div id="window_1_1" style="display: block;position: relative;padding: 5px;">
        <div id="registroGrupo"></div>
                 
        <table id="listDetalles"></table> <div id="pagerDetalles"></div>
        
        
    </div>
    
</div>