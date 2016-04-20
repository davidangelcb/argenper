
<div id="directorioView" style="display: block;">
    <div class="tituloModulo">DIRECTORIO</div>
    <div class="closeModulo" onclick="closeModulo(1)">[ close window ] &nbsp;&nbsp;</div>
    <br><br>                   
    
    <div>
        &nbsp;<a href="javascript:addGrupo()" class="classname"  >Crear Grupo de Contactos</a>&nbsp;&nbsp;<br><br>
        &nbsp;<a href="javascript:delGrupo()" class="classname"  >Remover Grupo(s)</a>&nbsp;&nbsp;<br><br>
    </div>
    <div style="clear: both"></div>

    <div id="window_1" style="display: block;position: relative;padding: 5px;">
        <div style="height: 500px;overflow: auto;">
            <div class="tableContenedor" id="contenedorGrupos">Loading...</div>
            <br>
            <br>
        </div>
        <table id="list0"></table> <div id="pager0"></div>
    </div>
    <div id="dialog-confirm" title="Nuevo Grupo" style="display: none;">
        <div>
            <label class="field">Nombre del Grupo<br><br></label>
        </div>
        <div>
            <input type="text" id="namegruponew" class="newGrupo">
        </div>
    </div>
    <div id="dialog-confirm2" title="Actualizar Grupo" style="display: none;">
        <div>
            <label class="field">Nombre del Grupo<br><br></label>
        </div>
        <div>
            <input type="text" id="namegruponew2" class="newGrupo">
        </div>
    </div>
    <div id="dialog-message" title="Import CSV" style="display: none;">
        <form name="upload" id="upload" enctype='multipart/form-data' action='files/upload.php' method='post' target="bodycsv">
            <p><input type="hidden" id="directorioActive" name="directorioActive">
            <br><br>
        </p>CSV Unico formato permitido, Seleccione File:<br><br>
        </p>
        <p><input size='50' type='file' name='filename'><br /><br>
          <input type='submit' name='submit' value='Upload'></form>
        </p>
    </div>
    
    
</div>
<div id="contactosView" style="display: none;">
    <div class="tituloModulo">CONTACTOS</div>
    <div class="closeModulo" onclick='gotoView("directorioView","contactosView");closeModulo(1);directorio()'>[ close window ] &nbsp;&nbsp;</div>
    <br><br>                   
    
    <div>
        &nbsp;<a href="javascript:addContacto()" class="classname"  >Agregar Contactos</a>&nbsp;&nbsp;<br><br>
        &nbsp;<a href='javascript:gotoView("directorioView","contactosView");' class="classname"  >Volver</a>&nbsp;&nbsp;<br><br>
        &nbsp;<a href="javascript:getSelectedContacts()" class="classname"  >Remover Contacto(s)</a>&nbsp;&nbsp;<br><br>
    </div>
    <div style="clear: both"></div>
   
    <div id="window_1_1" style="display: block;position: relative;padding: 5px;">
        <div id="nameGrupo">Grupo: CASA</div>
         
        <div style="height: 1px;overflow: auto;">
            <div class="tableContenedor" id="contenedorContactos"></div>            
        </div>
        <table id="list100"></table> <div id="pager100"></div>
        
        
    </div>
    
</div>