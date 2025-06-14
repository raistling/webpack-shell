## Creación de la Base de datos

En el ejercicio que indicaban en la anterior tarea no aparecía la base de datos.
He creado la base de datos y he dejado en el archivo query.sql el slq para la creación de las tablas en la base de datos.

### Usuarios para prueba
- Usuario: admin1. Clave: 1234
- Usuario: admin2. Clave: 1234
- Usuario: admin3. Clave: 1234

En el archivo configDB.php se debe configurar los parámetros para la conexión a la BBDD.

La ruta principal es: 
```
localhost://[carpeta_proyecto]/public/login.php
```
Usando los usuarios indicados arriba para el acceso una vez ejecutado el script .sql en la BBDD.

// Para añadir la elevación, habría que registrarse en alguna API, pero me pedían tarjetas de crédito. De todas formas, el sistema es el mismo, haces una peticion JSOn con la latitud y longitud y te devuelve un json con la elevación.