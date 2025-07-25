<?php
// This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
//
// VPL for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// VPL for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with VPL for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Spanish language strings for the VPL module.
 *
 * @author Juan Carlos Rodríguez-del-Pino <jc.rodriguezdelpino@ulpgc.es>
 * @copyright 2010-2025 Juan Carlos Rodríguez-del-Pino
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var array $string
 * @package mod_vpl
 */

$string['VPL_COMPILATIONFAILED'] = 'La compilación o preparación de la ejecución ha fallado';
$string['about'] = 'Acerca de';
$string['acceptcertificates'] = 'Aceptar certificados auto firmados';
$string['acceptcertificates_description'] = 'Si sus servidores de ejecución NO están usando certificados auto firmados desmarque';
$string['acceptcertificatesnote'] = '<p>Usted está usando una conexión cifrada.</p>
<p>Para usar una conexión cifrada con los servidores de ejecución usted debe aceptar sus certificados de seguridad.</p>
<p>Si no quiere aceptar los certificados o tiene problemas con ese proceso, puede
probar a usar una conexión http (no cifrada) u otro navegador.</p>
<p>Por favor, pulse sobre los siguientes enlaces (Servidor #) y acepte los certificados ofrecidos.</p>';
$string['addfile'] = 'Añadir fichero';
$string['advanced'] = 'Avanzado';
$string['allfiles'] = 'Todos los ficheros';
$string['allsubmissions'] = 'Todas las entregas';
$string['always_use_ws'] = 'Siempre usar protocolo websocket sin cifrado (ws)';
$string['always_use_wss'] = 'Siempre usar protocolo websocket cifrado (wss)';
$string['anyfile'] = 'Cualquier fichero';
$string['attemptnumber'] = 'Intento número {$a}';
$string['autodetect'] = 'Autodetectar';
$string['automaticevaluation'] = 'Evaluación automática';
$string['automaticgrading'] = 'Calificación automática';
$string['averageperiods'] = 'Periodos promedio {$a}';
$string['averagetime'] = 'Tiempo promedio {$a}';
$string['basedon'] = 'Basado en';
$string['basedon_chain_broken'] = 'Error: La cadena de actividades "basado en" está rota. Revise dichas actividades.';
$string['basedon_deleted'] = 'Error: Se perdió la actividad "basado en". Puede que se borrara. Establezca una actividad "basado en';
$string['basedon_missed'] = 'Error: Se perdió la actividad "basado en" al restaurar o importar. Por favor, incluya "{$a}"';
$string['basic'] = 'Básico';
$string['binaryfile'] = 'Fichero binario';
$string['breakpoint'] = 'Punto de parada';
$string['browserupdate'] = 'Actualice su navegador a la última versión<br />o use otro que soporte Websocket';
$string['calculate'] = 'Calcular';
$string['calendardue'] = 'Vence entrega VPL';
$string['calendarexpectedon'] = 'Entrega VPL esperada';
$string['changesNotSaved'] = 'Cambios no almacenados';
$string['check_jail_servers'] = 'Comprobación de servidores de ejecución';
$string['check_jail_servers_help'] = '<p>Esta página comprueba y muestra el estado de los servidores de ejecución usados por esta actividad.</p>';
$string['clipboard'] = 'Portapapeles';
$string['closed'] = 'Cerrado';
$string['comments'] = 'Comentarios';
$string['compilation'] = 'Compilación';
$string['connected'] = "conectado";
$string['connecting'] = "conectando";
$string['connection_closed'] = "conexión cerrada";
$string['connection_fail'] = "conexión fallida";
$string['console'] = 'Consola';
$string['control'] = 'Control';
$string['copy'] = 'Copiar';
$string['create_new_file'] = 'Crea un nuevo fichero para editar';
$string['currentstatus'] = 'Estado actual';
$string['cut'] = 'Cortar';
$string['datesubmitted'] = 'Entregado el';
$string['debug'] = 'Depurar';
$string['debugging'] = "Depurando";
$string['debugscript'] = 'Script de depuración';
$string['debugscript_help'] = 'Seleccione el script a usar al depurar entregas en esta actividad';
$string['defaultexefilesize'] = 'Máximo tamaño por defecto de un fichero en ejecución';
$string['defaultexememory'] = 'Máxima memoria usada por defecto';
$string['defaultexeprocesses'] = 'Máximo número de procesos por defecto';
$string['defaultexetime'] = 'Máximo tiempo de ejecución por defecto';
$string['defaultfilesize'] = 'Tamaño máximo por defecto de cada fichero de subida';
$string['defaultresourcelimits'] = 'Límites por defecto de recursos de ejecución';
$string['defaultvalue'] = 'Por defecto ({$a})';
$string['delete'] = 'Borrar';
$string['delete_file'] = 'Borra el fichero';
$string['delete_file_fq'] = '¿Confirma el borrado del fichero \'{$a}\'?';
$string['delete_file_q'] = '¿Borrar el fichero?';
$string['deleteallsubmissions'] = 'Elimina todas las entregas';
$string['depends_on_https'] = 'Usar wss o ws dependiendo de si se usa http o https';
$string['description'] = 'Descripción';
$string['diff'] = 'diff';
$string['directory_not_renamed'] = 'El directorio \'{$a}\' no se ha renombrado';
$string['discard_submission_period'] = 'Periodo de descarte de entregas';
$string['discard_submission_period_description'] = 'Para cada estudiante y tarea, se intenta descartar entregas manteniendo la última y al menos una por cada periodo';
$string['download'] = 'Descargar';
$string['downloadallsubmissions'] = 'Descargar todas las entregas';
$string['downloadsubmissions'] = 'Descargar entregas';
$string['duedate'] = 'Límite de entrega';
$string['dueevent'] = 'Vence la entrega de {$a}';
$string['dueeventaction'] = 'Desarrollar/Entregar';
$string['edit'] = 'Editar';
$string['editing'] = 'Editando';
$string['editortheme'] = 'Tema de editor';
$string['evaluate'] = 'Evaluar';
$string['evaluateonsubmission'] = 'Evaluar al entregar';
$string['evaluating'] = "evaluando";
$string['evaluation'] = 'Evaluación';
$string['evaluation_mode'] = 'Modo de evaluación';
$string['evaluation_mode:default'] = 'Evaluar en modo terminal (predeterminado)';
$string['evaluation_mode:textingui'] = 'Evaluar aplicación de texto en modo GUI';
$string['evaluation_mode_help'] = 'Selecciona el modo de ejecución del evaluador para esta actividad.<br>
<b>Predeterminado</b>: Evaluar en modo terminal (comportamiento original).<br>
<b>Texto en GUI</b>: Evaluar en entorno gráfico (GUI).<br>
<b>Nota</b>: Si se usa un script de evaluación personalizado, este puede ignorar el modo seleccionado.<br>';
$string['examples'] = 'Ejemplos';
$string['execution'] = 'Ejecución';
$string['executionfiles'] = 'Ficheros para la ejecución';
$string['executionfiles_help'] = '<p>Aquí se establecen los ficheros necesarios para la ejecución, depurado o evaluación de una entrega.
Esto incluye ficheros script, programas de prueba y ficheros de datos.</p>
<p>Si no se establecen los script de ejecución o depuración,
el sistema deduce el lenguaje empleado atendiendo a la extensión de los ficheros entregados
para usar un script predefinidos. La siguiente tabla muestra los lenguajes soportados, las extensiones de ficheros usadas, los script disponibles, el compilador/interprete y depurador usado
por este y finalmente un comentario sobre uso del lenguaje.</p>';
$string['executionoptions'] = 'Opciones de ejecución';
$string['executionoptions_help'] = '<p>En esta página se establecen diferentes opciones de ejecución</p>
<ul>
<li><b>Basado en</b>: permite establecer otra instanción VPL de la que se toman diversas caracteristicas:
<ul><li>Ficheros de ejecución (los guiones predefinidos se concatenan)</li>
<li>Límites de los recursos de ejecución.</li>
<li>Variaciones, que se concatenan generando variaciones múltiples.</li>
<li>Tamaño máximo de cada fichero a subir</li>
</ul>
</li>
<li><b>Ejecutar, Depurar y Evaluar</b>: establecen si se puede usar la opción correspondiente durante la edición de la entrega. Esto sólo afecta a los estudiantes, los usuarios con capacidad de calificación pueden usar estas opciones en cualquier caso.</li>
<li><b>Evaluar al entregar</b>: al subir los ficheros se produce el proceso de evaluación automáticamente.</li>
<li><b>Calificación automática</b>: si el resultado de la evaluación contiene códigos de nota automática estos se toman como nota definitiva.</li>
</ul>';
$string['file'] = 'Fichero';
$string['fileNotChanged'] = 'Fichero no modificado';
$string['file_name'] = 'Nombre del fichero';
$string['fileadded'] = "El fichero '{\$a}' ha sido añadido";
$string['filedeleted'] = "El fichero '{\$a}' ha sido borrado";
$string['filelist'] = "Lista de ficheros";
$string['filenotadded'] = 'Fichero no añadido';
$string['filenotdeleted'] = 'El fichero \'{$a}\' NO ha sido borrado';
$string['filenotrenamed'] = 'El fichero \'{$a}\' NO ha sido renombrado';
$string['filerenamed'] = "El fichero '{\$a->from}' ha sido renombrado a '{\$a->to}'";
$string['filesChangedNotSaved'] = "Ficheros modificados pero no guardados";
$string['filesNotChanged'] = 'Ficheros no modificados';
$string['filestoscan'] = 'Ficheros a procesar';
$string['fileupdated'] = "El fichero '{\$a}' ha sido actualizado";
$string['finalreduction'] = "Reducción final";
$string['finalreduction_help'] = '<b>RF [NE/ER R]</b><br>
<b>RF</b> Reducción final de la calificación.<br>
<b>NE</b> Número de evaluaciones automáticas solicitadas por el estudiante.<br>
<b>ER</b> Evaluaciones sin coste de reducción.<br>
<b>R</b> Reducción por cada evaluación. Si es un porcentaje, se aplicará sucesivamente.<br>';
$string['find'] = "Buscar";
$string['find_replace'] = 'Buscar/Reemplazar';
$string['freeevaluations'] = 'Evaluaciones sin reducción';
$string['freeevaluations_help'] = 'Número de evaluaciones automáticas que se pueden solicitar sin coste de reducción';
$string['fulldescription'] = 'Descripción completa';
$string['fulldescription_help'] = '<p>Escriba aquí la descripción completa de la tarea a realizar en el laboratorio de programación.</p>
<p>En caso de que no escriba nada se mostrará en su lugar la descripción corta.</p>
<p>Si desea realizar una evaluación automática, es aconsejable que la especificación de las interfaces sea lo más detallada posible y que no tenga ambigüedad.</p>';
$string['fullscreen'] = 'Pantalla completa';
$string['functions'] = 'Funciones';
$string['getjails'] = 'Obteniendo servidores de ejecución';
$string['gradeandnext'] = 'Calificar & Sig';
$string['graded'] = 'Evaluadas';
$string['gradedbyuser'] = 'Evaluadas por el usuario';
$string['gradedon'] = "Evaluada el";
$string['gradedonby'] = 'Evaluada el {$a->date} por {$a->gradername}';
$string['gradenotremoved'] = 'La calificación NO ha sido eliminada. Comprueba la configuración de la actividad en calificaciones.';
$string['gradenotsaved'] = 'Calificación NO almacenada. Comprueba la configuración de la actividad en calificaciones.';
$string['gradeoptions'] = 'Evaluación';
$string['grader'] = "Evaluada por";
$string['gradercomments'] = "Comentarios del revisor";
$string['graderemoved'] = 'La calificación ha sido eliminada';
$string['groupwork'] = 'En grupo';
$string['inconsistentgroup'] = 'Usted no es miembro de un único grupo (0 o >1)';
$string['incorrect_directory_name'] = 'Nombre de directorio incorrecto';
$string['incorrect_file_name'] = 'Nombre de fichero incorrecto';
$string['individualwork'] = 'Individual';
$string['inheritvalue'] = 'Heredado ({$a})';
$string['inputoutput'] = 'Entrada/salida';
$string['instanceselection'] = 'Selección de VPL';
$string['intermediate'] = 'Intermedio';
$string['isexample'] = 'Esta actividad actúa como ejemplo';
$string['jail_servers'] = "Lista de servidores de ejecución";
$string['jail_servers_config'] = "Configuración de servidores de ejecución";
$string['jail_servers_description'] = "Escriba un servidor en cada línea";
$string['joinedfiles'] = 'Ficheros seleccionados unidos';
$string['keepfiles'] = "Ficheros a mantener mientras se ejecuta";
$string['keepfiles_help'] = '<p>Por razones de seguridad, los ficheros añadidos en "Ficheros de ejecución", se borran  antes de ejecutar el fichero vpl_execution.</p>
<p>Si es necesario que alguno de estos ficheros permanezca en la fase de ejecución,
por ejemplo, para usarlo como datos de entrada de las pruebas, márquelos en esta página</p>';
$string['keyboard'] = 'Teclado';
$string['lasterror'] = 'Información último error';
$string['lasterrordate'] = 'Fecha último error';
$string['listofcomments'] = 'Lista de comentarios';
$string['lists'] = 'Listas';
$string['listsimilarity'] = 'Lista de similitud encontrada';
$string['listwatermarks'] = 'Listado marcas de agua';
$string['load'] = 'Carga';
$string['loading'] = 'Cargando';
$string['local_jail_servers'] = 'Servidores de ejecución locales';
$string['local_jail_servers_help'] = '<p>Aquí se establecen los servidores de ejecución locales para esta actividad y las que se basen en ella.</p>
<p>Escriba una la URL completa de servidor en cada línea. Se pueden introducir líneas en blanco y comentarios comenzando la línea por "#".</p>
<p>Si se quiere impedir que esta actividad y las que se basen en ella no use los servidores especificados en las actividades derivadas ni
los especificados globalmente, añada al final una línea que contenga "end_of_jails".
</p>';
$string['manualgrading'] = 'Calificación manual';
$string['math'] = 'Matemáticas';
$string['maxexefilesize'] = 'Máximo tamaño de un fichero en ejecución';
$string['maxexememory'] = 'Máxima memoria usada';
$string['maxexeprocesses'] = 'Máximo número de procesos';
$string['maxexetime'] = 'Máximo tiempo de ejecución';
$string['maxfiles'] = 'Número máximo de ficheros';
$string['maxfilesexceeded'] = 'Superado el número máximo de ficheros';
$string['maxfilesize'] = 'Tamaño máximo de cada fichero de subida';
$string['maxfilesizeexceeded'] = 'Superado el tamaño máximo de los ficheros';
$string['maximumperiod'] = 'Periodo máximo {$a}';
$string['maxresourcelimits'] = 'Límites máximos de recursos de ejecución';
$string['maxsimilarityoutput'] = 'Máxima salida por similitud';
$string['menucheck_jail_servers'] = 'Comprobación servidores ejecución';
$string['menuexecutionfiles'] = 'Ficheros ejecución';
$string['menuexecutionoptions'] = 'Opciones';
$string['menukeepfiles'] = "Ficheros a mantener";
$string['menulocal_jail_servers'] = 'Servidores ejecución locales';
$string['menuresourcelimits'] = 'Límites de recursos';
$string['minsimlevel'] = 'Nivel de similitud mínima a mostrar';
$string['moduleconfigtitle'] = 'Configuración del módulo VPL';
$string['modulename'] = 'Laboratorio virtual de programación';
$string['modulename_help'] = '<p>VPL permite la gestión de prácticas de programación teniendo como características más destacadas:</p>
<ul>
<li>Posibilidad de editar el código fuente en el navegador.</li>
<li>Posibilidad de ejecutar las prácticas de forma interactiva desde el navegador.</li>
<li>Posibilidad de ejecutar pruebas que revisen las prácticas.</li>
<li>Búsqueda de similitud entre prácticas para el control del plagio.</li>
<li>Restricciones de entrega de prácticas que limitan el corta y pega de código externo.</li>
</ul>
<p><a href="http://vpl.dis.ulpgc.es">Página oficial de Virtual Programming lab</a></p>';
$string['modulename_link'] = 'mod/vpl/view';
$string['modulenameplural'] = 'Laboratorios virtuales de programación';
$string['multidelete'] = 'Borrado múltiple';
$string['nevaluations'] = '{$a} evaluaciones automáticas realizadas';
$string['new'] = 'Nuevo';
$string['new_file_name'] = 'Nombre del fichero nuevo';
$string['next'] = 'Siguiente';
$string['nojailavailable'] = "No hay servidor de ejecución disponible";
$string['noright'] = 'No tiene permiso de acceso';
$string['nosubmission'] = 'No hay entrega';
$string['notexecuted'] = 'No ejecutado';
$string['notgraded'] = 'No evaluadas';
$string['notsaved'] = 'No guardado';
$string['novpls'] = 'No existe laboratorio de programación definido';
$string['nowatermark'] = 'Marcas de agua propias {$a}';
$string['nsubmissions'] = '{$a} entregas';
$string['numcluster'] = 'Grupo {$a}';
$string['open'] = 'Abierto';
$string['operatorsvalues'] = 'Operadores/valores';
$string['opnotallowfromclient'] = 'Acción no permitida desde esta máquina';
$string['options'] = 'Opciones';
$string['optionsnotsaved'] = "Opciones no guardas";
$string['optionssaved'] = "Opciones guardadas";
$string['origin'] = 'Origen';
$string['othersources'] = 'Otras fuentes a usar';
$string['outofmemory'] = 'Memoria agotada';
$string['override'] = 'Excepción';
$string['override_options'] = 'Opciones de excepción';
$string['override_users'] = 'Usuarios afectados';
$string['overridefor'] = '{$a->base} - Vence la entrega VPL para {$a->for}';
$string['overrideforgroup'] = '{$a->base} - Vence la entrega VPL para los miembros de {$a->for}';
$string['overrides'] = 'Excepciones';
$string['paste'] = 'Pegar';
$string['pause'] = 'Pausar';
$string['pluginadministration'] = 'Administración de VPL';
$string['pluginname'] = 'Laboratorio virtual de programación';
$string['pluginnotfound'] = 'Subplugin de VPL no encontrado o mal definido: {$a}';
$string['previoussubmissionslist'] = 'Lista entregas previas';
$string['print'] = 'Imprimir';
$string['privacy:metadata:vpl'] = 'Informacion de la actividad';
$string['privacy:metadata:vpl:assignedvariationdescription'] = 'Descripción de la variación asignada si se ha signado una';
$string['privacy:metadata:vpl:freeevaluations'] = 'Numero de evaluaciones automáticas sin reducción de nota';
$string['privacy:metadata:vpl:grade'] = 'Evaluación de la actividad';
$string['privacy:metadata:vpl:id'] = 'Identificador numérico de la actividad';
$string['privacy:metadata:vpl:name'] = 'Nombre de la actividad';
$string['privacy:metadata:vpl:reductionbyevaluation'] = 'Reducción de la nota por cada petición del estudiante de evaluación automática';
$string['privacy:metadata:vpl:shortdescription'] = 'Descripción corta de la actividad';
$string['privacy:metadata:vpl_acetheme'] = 'Tema del editor del IDE preferido por el usuario';
$string['privacy:metadata:vpl_editor_fontsize'] = 'Tamaño de letra del editor de IDE preferido por el usuario';
$string['privacy:metadata:vpl_submissions'] = 'Información sobre el intento/entrega y sobre su evaluación si se realizó';
$string['privacy:metadata:vpl_submissions:dategraded'] = 'Fecha y hora de la evaluación';
$string['privacy:metadata:vpl_submissions:datesubmitted'] = 'Fecha y hora de la entrega';
$string['privacy:metadata:vpl_submissions:debug_count'] = 'Número de veces que se ha depurado la entrega';
$string['privacy:metadata:vpl_submissions:grade'] = 'La nota obtenida en esta entrega. Esta nota puede no coincidir con la que aparece en el libro de calificaciones';
$string['privacy:metadata:vpl_submissions:grader'] = 'Usuario que evaluó esta entrega';
$string['privacy:metadata:vpl_submissions:gradercomments'] = 'Comentarios del evaluador sobre esta entrega';
$string['privacy:metadata:vpl_submissions:groupid'] = 'Identificador del grupo al que pertenece el usuario que realizó la entrega';
$string['privacy:metadata:vpl_submissions:nevaluations'] = 'Número de evaluaciones automáticas pedidas por el estudiante';
$string['privacy:metadata:vpl_submissions:run_count'] = 'Número de veces que se ha ejecutado la entrega';
$string['privacy:metadata:vpl_submissions:save_count'] = 'Número de veces que se ha guardado la entrega';
$string['privacy:metadata:vpl_submissions:studentcomments'] = 'Comentario escrito por el estudiante sobre la entrega';
$string['privacy:metadata:vpl_submissions:userid'] = 'Identificador del usuario que realizó la entrega';
$string['privacy:metadata:vpl_terminaltheme'] = 'Combinación de colores de la terminal preferida por el usuario';
$string['privacy:submissionpath'] = 'entrega_{$a}';
$string['proposedgrade'] = 'Nota propuesta: {$a}';
$string['proxy'] = 'proxy';
$string['proxy_description'] = 'Proxy de Moodle a servidores de ejecución';
$string['redo'] = 'Rehacer';
$string['regularscreen'] = 'Pantalla normal';
$string['removeallsubmissions'] = 'Elimina todas las entregas y notas';
$string['removeallsubmissions_help'] = 'Elimina todas las entregas y notas en todas las actividades VPL del curso selecionado';
$string['removebreakpoint'] = 'Elimina punto de parada';
$string['removegrade'] = 'Borra calificación';
$string['removegroupoverrides'] = 'Elimina las excepciones a grupos';
$string['removegroupoverrides_help'] = 'Elimina todas las asignaciones de excepciones a grupos en todas las actividades VPL del curso selecionado';
$string['removeoverrides'] = 'Elimina todas las excepciones';
$string['removeoverrides_help'] = 'RElimina todas las excepciones en todas las actividades VPL del curso selecionado';
$string['removeuseroverrides'] = 'Elimina las excepciones a usuarios';
$string['removeuseroverrides_help'] = 'Elimina todas las asignaciones de excepciones a usuarios en todas las actividades VPL del curso selecionado';
$string['rename'] = 'Renombrar';
$string['rename_directory'] = 'Renombrar directorio';
$string['rename_file'] = 'Renombrar fichero';
$string['replace_find'] = 'Reemplazar/Buscar';
$string['replacenewer'] = "Se tiene guardada una versión más nueva.\n¿Seguro que quiere reemplazarla por la actual?";
$string['requestedfiles'] = 'Ficheros requeridos';
$string['requestedfiles_help'] = '<p>Aquí se fijan nombres y contenido inicial para los ficheros requeridos.</p>
<p>Si no se fijan nombres para el número máximo de ficheros establecido en la definición básica de la actividad, los ficheros para los que no se han establecido nombres son opcionales y pueden tener cualquier nombre.</p>
<p>Además, se pueden establecer contenidos para los ficheros requeridos, de forma que dichos contenidos estarán disponibles la primera vez que el fichero se abra usando el editor, si no se ha realizado una entrega previa.</p>';
$string['requirednet'] = 'Entregas restringidas a la red';
$string['requiredpassword'] = 'Se necesita una clave';
$string['reset'] = 'Reinicia las actividades VPL';
$string['resetfiles'] = 'Reestablecer ficheros';
$string['resetvpl'] = 'Elimna las entregas VPL en {$a}';
$string['resourcelimits'] = 'Límites de recursos de ejecución';
$string['resourcelimits_help'] = '<p>Se pueden establecer límites máximos para el tiempo de ejecución, la memoria usada, el tamaño de los ficheros generados durante la ejecución y el número de procesos simultáneos.</p>
<p>Estos límites se aplican al ejecutar los ficheros de script  vpl_run.sh, vpl_debug.sh y vpl_evaluate.sh, y el fichero the file vpl_execution generado por ellos.</p>
<p>Si la actividad está basada en otra, los límites establecidos se pueden ver restringidos por los establecidos en aquella y otras en la que la misma se base, además de por los establecidos en la configuración global del módulo.</p>';
$string['restrictededitor'] = "Desactivar la carga de archivos, pegar y soltar contenido externo";
$string['resume'] = 'Continuar';
$string['retrieve'] = 'Recupera resultados';
$string['run'] = 'Ejecutar';
$string['run_mode'] = 'Modo de ejecución';
$string['run_mode:default'] = 'Detectar automáticamente el modo de ejecución (predeterminado)';
$string['run_mode:gui'] = 'Ejecutar en una terminal gráfica';
$string['run_mode:text'] = 'Ejecutar en una terminal de texto';
$string['run_mode:textingui'] = 'Ejecutar aplicación de texto en una terminal gráfica';
$string['run_mode:webapp'] = 'Ejecutar como una aplicación web';
$string['run_mode_help'] = 'Selecciona el modo de ejecución interactiva para esta actividad.<br>
<b>Predeterminado</b>: Ejecutar usando detección automática (comportamiento original).
En este modo, use @vpl_run_[text|gui|webapp|textingui]_mode dentro de un comentario al inicio del código para seleccionar el modo de ejecución.<br>
<b>Texto</b>: Ejecutar en una terminal de texto (sin GUI).<br>
<b>GUI</b>: Ejecutar en una terminal gráfica (GUI).<br>
<b>Aplicación web</b>: Ejecutar como una aplicación web (sin terminal).<br>
<b>Texto en GUI</b>: Ejecutar aplicación de texto en una terminal gráfica (GUI).<br>
<b>Nota</b>: Todos los modos no están disponibles en todos los lenguajes.<br>
Si se usa un script de ejecución personalizado, este puede ignorar el modo de ejecución seleccionado.<br>';
$string['running'] = "Ejecutando";
$string['runscript'] = 'Script de ejecución';
$string['runscript_help'] = 'Seleccione el script a usar al ejecutar entregas en esta actividad';
$string['save'] = 'Guardar';
$string['savecontinue'] = 'Guardar y continuar';
$string['saved'] = 'Guardado';
$string['savedfile'] = "El fichero '{\$a}' ha sido guardado";
$string['saveforotheruser'] = "Está guardando para otro ususario,\n¿está segurode querer hacerlo?";
$string['saveoptions'] = 'Guardar opciones';
$string['saving'] = "Guardando";
$string['scanactivity'] = 'Actividad';
$string['scandirectory'] = 'Directorio';
$string['scanningdir'] = 'Examinando el directorio ...';
$string['scanoptions'] = 'Opciones de búsqueda';
$string['scanother'] = 'Buscar similitudes en otras fuentes';
$string['scanzipfile'] = 'Fichero zip';
$string['search:activity'] = 'Virtual Programming Lab - información de la actividad (nombre y descripción)';
$string['sebkeys'] = 'Clave(s) de examen SEB';
$string['sebkeys_help'] = 'Las claves de examen SEB se obtienen de los ficheros .seb<br>Este mecanismo es más seguro que sólo comprobar el navegador.<br>https://safeexambrowser.org';
$string['sebrequired'] = 'Se requiere navegador SEB';
$string['sebrequired_help'] = 'Se requiere el navegador SEB apropiadamente configurado.';
$string['select_all'] = 'Seleccionar todo';
$string['selectbreakpoint'] = 'Selecciona punto de parada';
$string['server'] = 'Servidor';
$string['serverexecutionerror'] = 'Error en el servidor de ejecución';
$string['shortcuts'] = 'Atajos de teclado';
$string['shortdescription'] = 'Descripción corta';
$string['shrightpanel'] = 'Muestra/oculta panel derecho';
$string['similarity'] = 'Similitud';
$string['similarto'] = 'Similar a';
$string['start'] = 'Ejecutar';
$string['startanimate'] = 'Ejecutar animado';
$string['startdate'] = 'Disponibilidad';
$string['starting'] = 'Iniciando';
$string['step'] = 'Un paso';
$string['stop'] = 'Parar';
$string['submission'] = 'Entrega';
$string['submissionperiod'] = 'Periodo de entrega';
$string['submissionrestrictions'] = 'Restricciones de entrega';
$string['submissions'] = 'Entregas';
$string['submissions_graded_overview_help'] = '[Número de estudiantes o grupos]<br>
 / [Entregas] (% de estudiantes o grupos con entrega)<br>
 / [Entregas evaluadas] (% de entregas evaluadas)<br>
 - [Entregas no evaluadas] (% de entregas no evaluadas)';
$string['submissions_overview_help'] = '[Número de estudiantes o grupos] / [Número de entregas] (% de estudiantes o grupos con entrega)';
$string['submissionselection'] = 'Selección de entregas';
$string['submissionslist'] = 'Lista de entregas';
$string['submissionview'] = 'Ver entrega';
$string['submittedby'] = 'Entregada por {$a}';
$string['submittedon'] = 'Entregada el';
$string['submittedonp'] = 'Entregada el {$a}';
$string['subplugintype_vplevaluator'] = 'Evalador de entregas para VPL';
$string['subplugintype_vplevaluator_plural'] = 'Evaladores de entregas para VPL';
$string['sureresetfiles'] = '¿Quiere perder todo su trabajo y reestablecer los ficheros a su estado original?';
$string['test'] = 'Probar actividad';
$string['testcases'] = 'Casos de prueba';
$string['testcases_help'] = '<p>Para usar las caracteristicas de evaluación automática de programas de VPL debe rellenar el fichero "vpl_evaluate.cases".
Este fichero tiene el siguiente formato:
<ul>
<li>"<b>case</b> = Descripción del caso": Optional. Establece el inicio de un caso de prueba.</li>
<li>"<b>input</b> = texto": puede ocupar varias líneas. Finaliza cuando se introduce otra instrucción.</li>
<li>"<b>output</b> = texto": puede ocupar varias líneas.  Finaliza con otra instrucción. Un caso de prueba puede tener varias salidas válidas. Existen tres tipos de salidas: sólo números, texto y texto exacto:
 <ul>
 <li><b>números</b>: Se escriben sólo números. Solo se comprueban los número de la salida, el resto del texto es ignorado. Los números reales se comprueban con cierta tolerancia</li>
 <li><b>texto</b>: Sólo se comprueban palabras, la comparación es insensible a mayúsculas y se ignara el resto de los caracteres.</li>
 <li><b>texto exacto</b>: El texto se escribe entre comillas dobles.</li>
 </ul>
 </li>
<li>"<b>grade reduction</b> = [valor|porcentaje%]" : Por defecto cuando se produce un error se descuenta de la nota máxima  (rango_nota/número de casos)
 pero con esta instrucción se puede cambiar el descuento por otro valor o porcentaje.
</li>
</ul>';
$string['text'] = 'Texto';
$string['timeleft'] = 'Tiempo restante';
$string['timelimited'] = 'Limitado en tiempo';
$string['timeout'] = 'Tiempo sobrepasado';
$string['timeshift'] = 'Desplaza fechas en actividades VPL en {$a}';
$string['timespent'] = 'Tiempo dedicado';
$string['timespent_help'] = 'Tiempo dedicado en esta actividad basado en las versiones almacenadas<br>El gráfico de barras muestra el número de estudiantes en cada rango de tiempo.';
$string['timeunlimited'] = 'Sin límite de tiempo';
$string['totalnumberoferrors'] = "Errores";
$string['undo'] = 'Deshacer';
$string['unexpected_file_name'] = "Nombre de fichero incorrecto: se esperaba '{\$a->expected}' y se encontro '{\$a->found}'";
$string['unzipping'] = 'Descomprimiendo ...';
$string['update'] = 'Actualización';
$string['updating'] = 'Actualizando';
$string['uploadfile'] = 'Cargar fichero';
$string['use_xmlrpc'] = 'Usar XML-RPC';
$string['use_xmlrpc_description'] = 'Si se establece, el sistema usara XML-RPC en vez de JSON-RPC para comunicarse con los vpl-jail-servers. Debe establecer esta opción si usa vpl-jail-servers con versiones inferiores a la V3.0.0';
$string['usevariations'] = 'Usar variaciones';
$string['usewatermarks'] = 'Usar marcas de agua';
$string['usewatermarks_description'] = 'Añade marcas de agua a los ficheros de los estudiantes (sólo en lenguajes soportados)';
$string['variables'] = 'Variables';
$string['variation_n'] = 'Variación {$a}';
$string['variation_n_i'] = 'Variación {$a->number}: {$a->identification}';
$string['variation_options'] = 'Opciones de variación';
$string['variations'] = 'Variaciones';
$string['variations_help'] = '<p>Se pueden definir variaciones para las actividades. Las variaciones se asignan de forma aleatoria a los estudiantes.</p>
<p>En esta página se puede indicar si la actividad tiene variaciones, dar un título al conjunto de variaciones, y añadir las variaciones deseadas.</p>
<p>Cada variación tiene un código de identificación y una descripción. El identificador se usa en el fichero <b>vpl_enviroment.sh</b> para pasar la
variación asignada al estudiante a los scripts. La descripción, con formato HTML, se muestra a los estudiantes a los que ha sido asignada la variación
correspondiente.</p>';
$string['variations_unused'] = 'Esta actividad tiene variaciones, pero están desactivadas';
$string['variationtitle'] = 'Título de variación';
$string['varidentification'] = 'Identificación';
$string['visiblegrade'] = 'Mostrar evaluación';
$string['vpl'] = 'Laboratorio virtual de programación';
$string['vpl:addinstance'] = 'Añade nuevas actividades VPL';
$string['vpl:editothersgrades'] = 'Editar notas realizadas por otros';
$string['vpl:grade'] = 'Evaluar una entrega';
$string['vpl:manage'] = 'Gestionar un VPL';
$string['vpl:setjails'] = 'Establece servidores de ejecución para instancias concretas de VPL';
$string['vpl:similarity'] = 'Buscar similiudes entre entregas';
$string['vpl:submit'] = 'Hacer entregas';
$string['vpl:view'] = 'Ver la descripción completa de un VPL';
$string['vpl_debug.sh'] = "Prepara la depuración del programa";
$string['vpl_evaluate.cases'] = 'Escriba aquí los casos de prueba para evaluar automáticamente el programa';
$string['vpl_evaluate.sh'] = "Evalúa el programa";
$string['vpl_run.sh'] = "Prepara la ejecución del programa";
$string['websocket_protocol'] = 'Protocolo WebSocket';
$string['websocket_protocol_description'] = 'Tipo protocolo WebSocket (ws:// or wss://) a usar por el navegador al conectarse al servidor de ejecución.';
$string['workingperiods'] = 'Periodos de trabajo';
$string['worktype'] = 'Tipo de trabajo';
