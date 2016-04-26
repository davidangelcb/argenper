{ARCHIVO CREDENCIALES}
========
lib/config.php

[Credenciales de MensajesOnline]
USER_SMS => user de mensajes
PASS_SMS => password de mensajes
HTTP_DOMAIN => dominio del servicio

[credenciales de BD]

lib/ArAccess_db.php

[FOLDER UPLOAD PATH]
UPLOAD_FOLDER = CARPETA DE DESTINO DE LAS GRABACIONES




{folder}
==============================================================
*Permisos de escritura en lo siguientes folders:

temp/

{CRON}
==============================
Activar los Cron para tres proceso [estos files tiene que tener permisos de ejecucion]

1# proceso de envio de mensajes lo esta haciendo por lotes de 50 en 50 por ejecucion, asi que se puede programar cada 3 minutos o 5 minutos
cron_sendSMS.sh

2# proceso de verificacion de estatus por primera vez, podria ser un minutos despues del anterior y con la misma frecuencia
cron_getStatusSms.sh

3# proceso de verificacion de estatus si en el tiempo se cambia a otro,puede correar cada 4 horas o 6 ya queda a criterio
cron_sendSMS.sh

{DB}
=================================================
1.- ejecutar la estructura de BD
sql/sms_admin_structure.sql

2.- Ejecuta SQL para login inicial
sql/data.sql

User: admin
pass: admindemo






