## Web2TrabajoEspecial Parte 3
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

#Documentacion de Endpoints:
CARPETA representaria la carpeta en donde estan los archivos de este trabajo, poner el nombre correspondiente.

#Para la tabla de viajes:

#Todos los viajes (GET) ordenados por destino (lista una colección entera debe poder ordenarse opcionalmente por al menos un campo de la tabla, de manera ascendente o descendente)

Endpoint: http://localhost/CARPETA/api/viajesOrderDestino
Este endpoint permite listar todos los viajes ordenados "destino". Se puede especificar el orden ascendente o descendente utilizando el parámetro de consulta sort con los valores "asc/ASC" o "desc/DESC".
Ejemplo: http://localhost/CARPETA/api/viajesOrderDestino?sort_dir=asc

#Obtener todos los viajes(GET)

Endpoint: http://localhost/CARPETA/api/viajes
Este endpoint permite listar todos los viajes. 

#Obtener todos los clientes (GET) ordenados por un campo y en cierta direccion (Opcional)
Posibles sort_by:
destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente

Ver la tabla en la base de datos para ver los valores cargados 
Se usa sort_by para el campo y sort_dir para la direccion (asc o desc).
Ejemplo de uso: http://localhost/CARPETA/api/viajes?sort_by=nombre&sort_dir=asc


#Obtener Viajes con paginación (GET):(Opcional)
Endpoint: http://localhost/CARPETA/api/viajes?page=1&tamPage=10
Permite obtener una lista de los viajes de manera paginada. Debes especificar los parámetros de consulta page (página) y tamPage (límite de resultados por página).
Page no puede ser 0 y debe ser numerico.
tamPage debe ser numerico.

Ejemplo de uso: http://localhost/CARPETA/api/viajes?page=2&tamPage=20

#Listar Viajes Filtrados por cualquier campo (GET):(Opcional)

Obtiene la lista de viajes por un campo específico utilizando el parámetro de consulta filter_key(campo) & filter_value(valor). 
Ejemplo de Endpoint: http://localhost/CARPETA/api/viajes?filter_key=destino&filter_value=Paris


Obtener Viajes combinados(GET):
Obtiene la lista de viajes uniendo filtros, paginado y ordenado (todos o combinaciones).
Ejemplo de Endpoint: http://localhost/CARPETA/api/viajes?sort_by=destino&sort_dir=desc&filter_key=destino&filter_value=Paris&page=2&tamPage=7


#Obtener Viaje por ID (GET):

Endpoint: http://localhost/CARPETA/api/viajes/:ID
Obtiene un viaje específico por su ID. Se reemplaza :ID por el ID del viaje que queres obtener.
Ejemplo de uso: http://localhost/CARPETA/api/viajes/123

#Obtener subrecurso de un Viaje por ID (GET):

Endpoint: http://localhost/CARPETA/api/viajes/:ID/:subrecurso
 Permite obtener subrecursos específicos de un viaje por su ID. Reemplaza :ID por el ID del viaje y :subrecurso por el subrecurso que deseas obtener(por ejemplo, destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente).
Ejemplo de uso: http://localhost/CARPETA/api/viajes/123/destino


#Crear un Nuevo Viaje (POST):

Endpoint: http://localhost/CARPETA/api/viajes
Permite agregar un nuevo viaje. Hay que enviar los datos del viaje en el cuerpo de la solicitud en formato JSON, seleccionando body en postman y apretando la opcion raw.

Ejemplo de solicitud POST en el body:
{
  "destino": "Destino Nuevo",
  "fecha_salida": "2023-12-01",
  "fecha_regreso": "2023-12-10",
  "descripcion": "Viaje nuevo",
  "precio": 1500,
  "id_cliente": 4
}

#Actualizar un Viaje por ID (PUT):

Endpoint: http://localhost/CARPETA/api/viajes/:ID
Permite actualizar un viaje existente por su ID. Reemplaza :ID por el ID del viaje que queres actualizar. Hay que enviar los datos actualizados en el cuerpo de la solicitud en formato JSON, seleccionando body en postman y apretando la opcion raw.

Ejemplo de solicitud PUT en el body:
{
  "destino": "Actualizo destino",
  "fecha_salida": "2023-12-02",
  "fecha_regreso": "2023-12-12",
  "descripcion": "Viaje a destino (actualizado)",
  "precio": 1600,
  "id_cliente": 13
}

#Eliminar un Viaje por ID (DELETE):

Endpoint: http://localhost/CARPETA/api/viajes/:ID
Permite eliminar un viaje por su ID. Reemplaza :ID por el ID del viaje que queres eliminar.
Ejemplo de uso: http://localhost/CARPETA/api/viajes/123


#Para la tabla de Clientes:

#Todos los clientes (GET) ordenados por Apellido (lista una colección entera debe poder ordenarse opcionalmente por al menos un campo de la tabla, de manera ascendente o descendente)

Endpoint: http://localhost/CARPETA/api/clientesOrderApellido
Este endpoint permite listar todos los clientes ordenados "Apellido". Se puede especificar el orden ascendente o descendente utilizando el parámetro de consulta sort con los valores "asc/ASC" o "desc/DESC".
Ejemplo: http://localhost/CARPETA/api/clientesOrderApellido?sort_dir=asc


#Obtener todos los clientes (GET)
Endpoint: http://localhost/CARPETA/api/clientes

Este endpoint permite listar todos los clientes.

#Obtener todos los clientes (GET) con paginación (opcional)
Endpoint: http://localhost/CARPETA/api/clientes

Este endpoint permite listar todos los clientes. se utilizan los siguientes parametros de consulta:
page: Número de página (debe ser un valor numérico mayor a cero).
tamPage: Límite de resultados por página (debe ser un valor numérico).

Ejemplo de uso: http://localhost/CARPETA/api/clientes?page=2&tamPage=10

#Obtener clientes filtrados por cualquier campo (GET):(Opcional)

Obtiene la lista de clientes por un campo específico utilizando el parámetro de consulta filter_key (campo) y filter_value (valor).

Ejemplo de Endpoint: http://localhost/CARPETA/api/clientes?filter_key=apellido&filter_value=Perez

#Obtener todos los clientes (GET) ordenados por un campo y en cierta direccion (Opcional)
Endpoint: http://localhost/CARPETA/api/clientes

Este endpoint permite listar todos los clientes. Puedes utilizar los siguientes parámetros de consulta para ordenar los resultados:

sort_by: Campo por el que deseas ordenar (por ejemplo, "id_cliente", "nombre", "apellido", "correo_electronico", "fecha_nacimiento", "dni", "direccion").
sort_dir: Dirección de ordenamiento (ascendente "asc" o descendente "desc").

Ejemplo de uso: http://localhost/CARPETA/api/clientes?sort_by=nombre&sort_dir=asc

#Obtener clientes con combinación de paginación, filtrado y orden (GET)
Este endpoint permite combinar paginación, filtrado y ordenamiento en una sola solicitud. Puedes utilizar los parámetros de consulta mencionados anteriormente en distinta combinación para personalizar tu consulta.

Ejemplo de Endpoint: http://localhost/CARPETA/api/clientes?sort_by=apellido&sort_dir=desc&filter_key=nombre&filter_value=Juan&page=2&tamPage=7

#Obtener cliente por ID (GET)
Endpoint: http://localhost/CARPETA/api/clientes/:ID

Obtiene un cliente específico por su ID. Reemplaza :ID por el ID del cliente que deseas obtener.

#Obtener subrecurso de un Cliente por ID (GET)
Endpoint: http://localhost/CARPETA/api/clientes/:ID/:subrecurso

Permite obtener subrecursos específicos de un cliente por su ID. Reemplaza :ID por el ID del cliente y :subrecurso por el subrecurso que deseas obtener (por ejemplo, nombre, apellido, correo_electronico, fecha_nacimiento, dni, direccion).

Ejemplo de uso: http://localhost/CARPETA/api/clientes/123/nombre

#Crear un nuevo cliente (POST)
Endpoint: http://localhost/CARPETA/api/clientes

Permite agregar un nuevo cliente. Debes enviar los datos del cliente en el cuerpo de la solicitud en formato JSON.

Ejemplo de solicitud POST en el body:

{
  "nombre": "Nombre Cliente",
  "apellido": "Apellido Cliente",
  "correo_electronico": "cliente@example.com",
  "fecha_nacimiento": "2000-01-01",
  "dni": "1234567890",
  "direccion": "Dirección del Cliente"
}

#Actualizar un cliente por ID (PUT)
Endpoint: http://localhost/CARPETA/api/clientes/:ID

Permite actualizar un cliente existente por su ID. Reemplaza :ID por el ID del cliente que deseas actualizar. Debes enviar los datos actualizados en el cuerpo de la solicitud en formato JSON.

Ejemplo de solicitud PUT en el body:

{
  "nombre": "Nuevo Nombre",
  "apellido": "Nuevo Apellido",
  "correo_electronico": "nuevo@example.com",
  "fecha_nacimiento": "2000-02-02",
  "dni": "9876543210",
  "direccion": "Nueva Dirección"
}

#Eliminar un cliente por ID (DELETE)
Endpoint: http://localhost/CARPETA/api/clientes/:ID

Permite eliminar un cliente por su ID. Reemplaza :ID por el ID del cliente que deseas eliminar.

