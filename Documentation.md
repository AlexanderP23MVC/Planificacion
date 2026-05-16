#### Documentacion para proyecto de planificiacion



-[] el usuario y los departamento solo pueden ser creado por el super usuario o el usuario admin del departamento



-[] realizar tabla de medu para relacionar cada departamento con su actividades correspondiente


- [] realizar de registro de actividades de cada departamento

- [] realizar la certificacion de cada departamento

- [] realizar y normalizar las tablas para las actividades de cada departamento
- [] comparar las actividades realizada con el mes anterior y crear grafica de diferencia

- [] realizar documentos modificable online y aprobables por otras areas

- [] resguardar estado y estatus y reporte de los documentos validado y entregados

- [ ] notificar las entrega y recibido de las estadisticas

- [] las estadisticas son por semanas y cuenta la cantidad de actividades realizada por la semana

la problematica es  la siguiente:

se debe de ralizar un sistema de planificacion donde cada departamento tenga registrados las actividades que realiza durante las 5 semana del mes
esos cada departamento tiene su informacion personal de como llevan las actividades debido a que son de legales se debe de llevar acabo en cada parte del pais ,una vez cargada las actividades se debe de notificar al departamento de planificacion y
debido a que son semanales se llenara el formulario al final de cada semana, y planificacion debe de recaudar toda la informacion semanal 



datos que debe de llevar el sistema

Usuarios
usuario root (usuario que posee acceso de todos los departamento)
usuario por departamento (un usuario no puede acceder a informacion de otros departamento)
jerarquia de usuario para que al momento de ser llenado deba ser aprobador por el jefe y coordinador
usuario planificacion (el usuario que puede ver las estadisticas llenada de cada departamento incluyendo la fecha de vigencia y plazo en que se debe de llenar )
estadistica con grafico de cada departamento semanal
estatus de cada departamento si ya envio o lleno la informacion
reporteria para ser entregado al TSJ
por semana
por mes 
por año


formulario de Actividades
Departamentos
actividades
tipo de actividades
descripcion de las actividades 
representacion de las actividades en cantidad
status
total general de actividades 


lo que ve planificacion
quien falta por llenar la actividad correspondienten al dia para dar seguimiento
aprobacion por parte de los coordinadores 
las estadistica diaria semanal mensual y anual
gestion de reporteria 


Base de dato

tabla usuario
id usuario
nombre usaurio
contraseña
cargo
departamento


tabla estatus_usuario
id_estatus_usaurio
estatus_usuario

tabla de sesiones
id_sesiones
sesiones
tiempo_expiracion

tabla departamento
id_departamento
departamento
id_cargo

tabla de cargos
id_cargo
cargos
id_departamento
id_perfi (por defecto 0)

----evaluar los perfiles y cargos de aprobacion

tabla perfiles
id_perfiles
id_aprobacion
perfiles


tabla actividades
id_actividad
actividad
fecha
semana
total

table actividades informatica
table actividades capacitacion
table jornada RRHH

realizar tablas independiente para los que insertan sus propias actividades

tabla labores

Realizar una Tabla auto-referenciada (o tabla de auto-relación).
// esto es para las pais, regiones,estado,municipio,parroquia
tabla localidad
id_localidad
nivel
id_padre


tabla reportes
id_reportes

// almacenamiento de reporteria en pdf
table archivo
id_almacenamiento
ruta_artchivo

tabla notificacion
id_notificacion
notificacion
id_status_notificacion

// segun esta tabla es para cuando ya esta revisado por planificacion y no hay correcciones
tabla de revision 
id_revision
revision

tabla menu
id_menu
menu

tabla sub_menu
id_sub-menu
sub_menu

tabla de alertas
id_alerta
alerta
fecha
estatus

tabla auditoria
id_auditoria
auditoria
usuario
fecha
ejecucion

tabla unidad de actividades
id_actividades
descripcion_act


tabla categoria de actividades
id_cat_actividades
categoria
id_actividades

tabla de genero
id_genero
genero

tabla de permisologia
id_permisologia
permisologia




query para los dia de semana de los reportes

SELECT 
    fecha::date,
    to_char(fecha, 'TMDay') AS nombre_dia,
    extract(week from fecha) AS numero_semana
FROM generate_series(
    date_trunc('month', current_date)::date,               -- Primer día del mes actual
    (date_trunc('month', current_date) + interval '1 month - 1 day')::date, -- Último día del mes actual
    '1 day'::interval
) AS fecha
WHERE extract(isodow from fecha) < 6;




ldo que va al readme.md

instalacion de composer
instalacion de laravel
ingresar a php.init y descomentarextension=pdo_pgsql
extension=pdo_sqlite
extension=pgsql
generar una nueva APP_KEY=base64 para iniciar laravel en otro equipo
iniciar con php artisan
iniciar tambien npm start para las animaciones