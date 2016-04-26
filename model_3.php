<div class="tituloModulo">MODULO DE GRABACIONES</div>
<div class="closeModulo" onclick="closeModulo(3);">[ close window ] &nbsp;&nbsp;</div>
<br>
<br>
<div style="width: 99%; margin: 0 auto;" >
    <div style="float: left">
            <a href="javascript:addGrabacion()" class="classname"  >Agregar Nueva Grabacion</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <!--a href="javascript:editTemplate()" class="classname"  >Editar Item Seleccionado</a-->
    </div>
    <div style="float: right">
        <a href="javascript:delTemplate()" class="classname" id="eData" >Eliminar Item Seleccionado</a>
    </div>
    <div style="clear: both"></div>
<br>
<span style="font-size: 11px;">
Info: <br>
Solo MP3 y WAV, son los formatos soportado.
<br><br>
</span>
</div>
<div id="dialog-grabacion"   title="Subir Grabacion" style="display: none;">
        <form name="uploadGra" id="uploadGra" enctype='multipart/form-data' action='models/grabacion_upload.php' method='post' target="bodycsv">
            
            <br><br>
            <label>Nombre:</label><br>
            <input type="text" id="nombregra"  name="nombregra" style="width: 100%">
            <br><br>
            </p>
            <p>
            mp3 y wav, formato permitido, Seleccione File:<br><br>
            </p>
            <p><input size='50' type='file' name='upload'><br /><br>
                <input type='button' name='Crear' value='Upload' onclick="uploadGrabacion()"> <br /><br>             
            </p>
        </form>
</div>
 <div style="clear: both"></div>
 <div style="width: 99%; margin: 0 auto;" >
    <table id="addgrid" ></table> <div id="pagerad" ></div>
</div>