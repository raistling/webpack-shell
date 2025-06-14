## Ejercicio 7

Implementaremos un sistema de votación en nuestra página de productos, de manera que, cualquier cliente validado pueda dar una puntuación del 1 al 5 a cada producto. Las valoraciones se reflejarán de manera inmediata en nuestra página gracias a Xajax.

Haremos la página de Login similar a la del apartado 3.4.  En la validación, los errores se controlarán con Xajax.

Un cliente NO podrá valorar dos veces el mismo producto

Utilizaremos Xajax para presentar en tiempo real los cambios en la valoración cada vez que un cliente vote por un producto. Para ello implementaremos el  método PHP "miVoto" que insertará el voto, si es la primera vez que el cliente valora un producto, y devolverá:

La valoración de ese producto (la media de las valoraciones)
False si el usuario ya ha valorado ese producto.
El método PHP: "pintarEstrellas" que se encargará de devolver el número de clientes que han valorado ese producto y las estrellas que se pintarán.