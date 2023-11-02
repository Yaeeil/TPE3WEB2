# Web2TrabajoEspecial Parte 3
Integrantes del grupo:

Orellano Yael Eileen
Email:yael.e.orellano@gmail.com

Corbellini Joaquin
Email:Corbellini47@gmail.com

Temática del Trabajo Practico Especial:
Venta de Viajes

Descripción:

Temática del Trabajo Practico Especial:
Venta de Viajes

Documentacion de Endpoints:
CARPETA representaria la carpeta en donde estan los archivos de este trabajo, poner el nombre correspondiente.

Para la tabla de viajes:

Todos los viajes (GET) ordenados por destino (lista una colección entera debe poder ordenarse opcionalmente por al menos un campo de la tabla, de manera ascendente o descendente)

Endpoint: http://localhost/CARPETA/api/viajesOrderDestino
Este endpoint permite listar todos los viajes ordenados "destino". Se puede especificar el orden ascendente o descendente utilizando el parámetro de consulta sort con los valores "asc/ASC" o "desc/DESC".
Ejemplo: http://localhost/CARPETA/api/viajesOrderDestino?sort=asc

Obtener todos los viajes(GET)

Endpoint: http://localhost/CARPETA/api/viajes
Este endpoint permite listar todos los viajes. 
Se puede ordenar los resultados por cualquier campo utilizando los parámetros de consulta order y sort.

Posibles campos para ordenar:(Opcional)
destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente
Ver la tabla en la base de datos para ver los valores cargados 
Ejemplo de uso: http://localhost/CARPETA/api/viajes?order=nombre&sort=asc


Obtener viajes con paginación (GET):(Opcional)
Endpoint: http://localhost/CARPETA/api/viajes?page=1&limit=10
Permite obtener una lista de los viajes de manera paginada. Debes especificar los parámetros de consulta page (página) y limit (límite de resultados por página).
Page no puede ser 0 y debe ser numerico:
Limit debe ser numerico.

Ejemplo de uso: http://localhost/CARPETA/api/viajes?page=2&limit=20

Listar Viajes Filtrados por Destino (GET):(Opcional)

Obtiene la lista de viajes por un destino específico utilizando el parámetro de consulta filter. Reemplaza "París" por el destino deseado.
Ejemplo de Endpoint: http://localhost/CARPETA/api/viajes?filter=París

Obtener Viaje por ID (GET):

Endpoint: http://localhost/CARPETA/api/viajes/:ID
Obtiene un viaje específico por su ID. Se reemplaza :ID por el ID del viaje que queres obtener.
Ejemplo de uso: http://localhost/CARPETA/api/viajes/123


Crear un Nuevo Viaje (POST):

Endpoint: http://localhost/CARPETA/api/viajes
Descripción: Permite agregar un nuevo viaje. Hay que enviar los datos del viaje en el cuerpo de la solicitud en formato JSON, seleccionando body en postman y apretando la opcion raw.

Ejemplo de solicitud POST en el body:
{
  "destino": "Destino Nuevo",
  "fecha_salida": "2023-12-01",
  "fecha_regreso": "2023-12-10",
  "descripcion": "Viaje nuevo",
  "precio": 1500,
  "id_cliente": 4
}

Actualizar un Viaje por ID (PUT):

Endpoint: http://localhost/CARPETA/api/viajes/:ID
Descripción: Permite actualizar un viaje existente por su ID. Reemplaza :ID por el ID del viaje que queres actualizar. Hay que enviar los datos actualizados en el cuerpo de la solicitud en formato JSON, seleccionando body en postman y apretando la opcion raw.

Ejemplo de solicitud PUT en el body:
{
  "destino": "Actualizo destino",
  "fecha_salida": "2023-12-02",
  "fecha_regreso": "2023-12-12",
  "descripcion": "Viaje a destino (actualizado)",
  "precio": 1600,
  "id_cliente": 13
}

Eliminar un Viaje por ID (DELETE):

Endpoint: http://localhost/CARPETA/api/viajes/:ID
Permite eliminar un viaje por su ID. Reemplaza :ID por el ID del viaje que queres eliminar.
Ejemplo de uso: http://localhost/CARPETA/api/viajes/123

Obtener subrecurso de un Viaje por ID (GET):

Endpoint: http://localhost/CARPETA/api/viajes/:ID/:subrecurso
 Permite obtener subrecursos específicos de un viaje por su ID. Reemplaza :ID por el ID del viaje y :subrecurso por el subrecurso que deseas obtener(por ejemplo, destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente).
Ejemplo de uso: http://localhost/CARPETA/api/viajes/123/destino


Para la tabla Clientes: