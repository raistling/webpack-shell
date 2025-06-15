## Tienes que programar una aplicación web sencilla que permita gestionar una serie de preferencias del usuario. La aplicación se dividirá en dos páginas:

### preferencias.php
Permitirá al usuario escoger sus preferencias y las almacenará en la sesión del usuario.

![Imagen de una página web con tres listas desplegables, una para el idioma, otra para el perfil y otra para la zona horaria.](https://fpadistancia.edu.xunta.gal/pluginfile.php/1321709/mod_resource/content/1/DWES04_v1/ArchivosUnidad/Moodle/DWES04_Tarea/DWES04_TAR_R02_Mostrar.png)

Mostrará un cuadro desplegable por cada una de las preferencias. Estas serán:

- **Idioma**. El usuario podrá escoger un idioma entre "inglés" y "español".
- **Perfil público**. Sus posibles opciones serán "sí" y "no".
- **Zona horaria**. Los valores en este caso estarán limitados a "GMT-2", "GMT-1", "GMT", "GMT+1" y "GMT+2".

Además en la parte inferior tendrá un botón con el texto "Establecer preferencias" y un enlace que ponga "Mostrar preferencias".

El botón almacenará las preferencias en la sesión del usuario y volverá a cargar esta misma página, en la que se mostrará el texto "Preferencia de usuario guardadas". Una vez establecidas esas preferencias, deben estar seleccionadas como valores por defecto en los tres cuadros desplegables.

![Imagen de un formulario web con tres listas de selección, arriba pone en azul preferencias de usuario guardadas, en la lista del idioma está seleccionado Inglés, en la de perfil público Sí y en la de la zona horaria GMT+1, debajo los botones Mostrar Preferencias y Establecer Preferencias.](https://fpadistancia.edu.xunta.gal/pluginfile.php/1321709/mod_resource/content/1/DWES04_v1/ArchivosUnidad/Moodle/DWES04_Tarea/DWES04_TAR_R03_Pref.png)

El botón "Establecer preferencias" llevará a la página "mostrar.php".

### mostrar.php
Debe mostrar un texto con las preferencias que se encuentran almacenadas en la sesión del usuario. Además, en la parte inferior tendrá un botón con el texto "Borrar" y otro que ponga "Establecer".

![Imagen de una página web que muestra en verde una tarjeta, arriba el texto preferencias y debajo y tres filas Idioma: Inglés, Perfil Público: No y Zona Horaria: GMT+1, debajo los botones Establecer y Borrar.](https://fpadistancia.edu.xunta.gal/pluginfile.php/1321709/mod_resource/content/1/DWES04_v1/ArchivosUnidad/Moodle/DWES04_Tarea/DWES04_TAR_R04_Pref.png)

El botón borrará las preferencias de la sesión del usuario y volverá a cargar esta misma página, en la que se mostrará el texto "Preferencias Borradas.". Una vez borradas esas preferencias, se debe comprobar que sus valores no se muestran en el texto de la página.

![Imagen de una página web que muestra en verde una tarjeta, arriba el texto Preferencias Borradas y debajo y tres filas Idioma: No establecido, Perfil Público: No establecido y Zona Horaria: No establecido, debajo los botones Establecer y Borrar.](https://fpadistancia.edu.xunta.gal/pluginfile.php/1321709/mod_resource/content/1/DWES04_v1/ArchivosUnidad/Moodle/DWES04_Tarea/DWES04_TAR_R05_MostrarError.png)

Si pulsamos el botón Borrar y no tenemos establecidas las preferencias se nos mostrará el mensaje "Debes fijar primero las preferencias."

![Imagen de una página web que muestra en verde una tarjeta, arriba el texto: "Debes fijar primero las preferencias" y debajo y tres filas Idioma: No establecido, Perfil Público: No establecido y Zona Horaria: No establecido, debajo los botones Establecer y Borrar.](https://fpadistancia.edu.xunta.gal/pluginfile.php/1321709/mod_resource/content/1/DWES04_v1/ArchivosUnidad/Moodle/DWES04_Tarea/DWES04_TAR_R05_MostrarError.png)

El botón establecer llevará a la página "preferencias.php".

Se valorará el uso de Bootstrap y Font Awesome para los estilos.