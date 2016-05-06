<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of view
 *
 * @author david.cama
 */
class view {
    //put your code here
    public function grupos($param) {
$HTMLDINAMIC = <<<STRHTML
<div class="tableContenidosHeader">
    <div class="tableColumna num">*</div>
    <div class="tableColumna nom">Nombre de Grupo</div>
    <div class="tableColumna gen">Codigo</div>
    <div class="tableColumna gen">Miembros</div>
    <div class="tableColumna gen">Acciones</div>
    <div class="tableColumna num"><input type="checkbox" id="selectAll" onclick="grupoSelectAll(this)"></div>
</div>
STRHTML;
        $class = 'uno';
        $n=0;
        foreach ($param as $value) {
            $n++;
          if($class=='uno'){$class='dos';}else{$class='uno';}  
$HTMLDINAMIC .= <<<STRHTML
<div class="tableContenidos {$class}" id="row_{$value['id']}">
    <div class="tableTd num">{$n}</div>
    <div class="tableTd nom" id="grupoNombre_{$value['id']}">{$value['nombre']}</div>
    <div class="tableTd gen">{$value['codigo']}</div>
    <div class="tableTd gen">{$value['miembros']}</div>
    <div class="tableTd gen">
        <img class="mano" src="media/modifier-icone-7876-16.png" alt="[Editar Grupo]" title="[Editar Grupo]" onclick="editGrupos({$value['id']})">
        <img class="mano" src="media/edit_16.png" alt="[Editar Miembros]" title="[Editar Miembros]" onclick="loadContact({$value['id']})">
        <img class="mano" src="media/exportar.png" alt="[Importar]" title="[Importar]" onclick="importar({$value['id']})">
        <img class="mano" src="media/importar.png" alt="[Exportar]" title="[Exportar]" onclick="exportar({$value['id']})">                                        
    </div><input type="hidden" id="id_grupo_{$value['id']}">
    <div class="tableTd num"><input value="{$value['id']}" type="checkbox" id="selectAll" onclick="" name="select[]" ></div>
</div>
STRHTML;

        }
        return $HTMLDINAMIC;
    }
    
    public function contactos($param) {
$HTMLDINAMIC = <<<STRHTML
<div class="tableContenidosHeader">
    <div class="tableColumna num">*</div>
    <div class="tableColumna nomContac">Nombre Contacto</div>
    <div class="tableColumna gen">Celular</div>
    <div class="tableColumna gen">Perso. Valor 1</div>
    <div class="tableColumna gen">Perso. Valor 2</div>
    <div class="tableColumna gen">Email[Redirect]</div>
    <div class="tableColumna num"><input type="checkbox" id="selectAll"></div>
</div>
STRHTML;
        $class = 'uno';
        $n=0;//id, nombre, celular, valor1,valor2,email
        foreach ($param as $value) {
            $n++;
          if($class=='uno'){$class='dos';}else{$class='uno';}  
$HTMLDINAMIC .= <<<STRHTML
<div class="tableContenidos {$class}">
    <div class="tableTd num">{$n}</div>
    <div class="tableTd nomContac" id="grupoNombre_{$value['id']}">{$value['nombre']}</div>
    <div class="tableTd gen">{$value['celular']}</div>
    <div class="tableTd gen">{$value['valor1']}</div>
    <div class="tableTd gen">{$value['valor2']}</div>
    <div class="tableTd gen">{$value['email']}</div>
    <div class="tableTd num"><input type="checkbox" id="selectAll" onclick="selectAll(this)"></div>
</div>
STRHTML;

        }
        if($n==0){
            $HTMLDINAMIC.='<div class="tableContenidos"><div class="tableTd num"></div><div class="tableTd nom"> Grupo Vacio.</div></div>';
        }
        return $HTMLDINAMIC;
    }    
    
    public function selectGrupos($array) {
       $html = ' <span class="titleSMS">Directorio - Seleccionar Grupo(s)<br><br></span>';
       
       $html.= '      <select name="selectfrom" id="select-from" multiple size="5" class="multiple-select">';
       foreach ($array as $value) {
           $html.= '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
       } 
        $html.= '    </select>';
        return $html;
    } 
    public function mp3($param) {
        $mp3 = file_get_contents(realpath($param['url']));

        file_put_contents('../temp/test_preview.mp3', $mp3);


        $url  = realpath($param['url']);
$HTMLDINAMIC = <<<STRHTML
<audio controls> 
    <source src='temp/test_preview.mp3' type='audio/mpeg'> 
</audio>
STRHTML;
return $HTMLDINAMIC;
    }    
}
