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

Usar Postman


# Documentacion de Endpoints:
CARPETA representaria la carpeta en donde estan los archivos de este trabajo, poner el nombre correspondiente.

# Para el Token:
Para hacer POST/PUT/DELETE es necesaria la autenticacion por Token.

Endpoint: http://localhost/TPE3/api/user/token para crearlo

Usuario:webadmin
Contraseña:admin

Estos datos se ingresan en la seccion authorization de tipo Basic Auth.
Luego de ingresarlos, se debe hacer un send con el endpoint especificado. Una vez se obtiene el token, se debe ir al endpoint que necesite autorización y en la sección de authorization, de tipo Bearer Token, se ingresa dicho token.

# Para la tabla de viajes:

# Obtener todos los viajes(GET)

Endpoint: http://localhost/CARPETA/api/viajes

# Obtener todos los clientes (GET) ordenados por un campo y en cierta direccion (Opcional)
Posibles sort_by:
destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente, id_viaje.

sort_by: para el campo.
sort_dir: para la dirección (asc o desc).
Ejemplo de uso: http://localhost/CARPETA/api/viajes?sort_by=nombre&sort_dir=asc


# Obtener Viajes con paginación (GET):(Opcional)

page: para la página.
size: para el límite por página.

Ejemplo de uso: http://localhost/CARPETA/api/viajes?page=2&size=20

# Listar Viajes Filtrados por cualquier campo (GET):(Opcional)
Posibles filter_key:
destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente, id_viaje.
filter_key: para el campo.
filter_value: para su valor 
Ejemplo de uso: http://localhost/CARPETA/api/viajes?filter_key=destino&filter_value=Paris


# Obtener Viajes combinados(GET):

Ejemplo de uso: http://localhost/CARPETA/api/viajes?sort_by=destino&sort_dir=desc&filter_key=destino&filter_value=Paris&page=2&size=7


# Obtener Viaje por ID (GET):

Endpoint: http://localhost/CARPETA/api/viajes/:ID


# Obtener subrecurso de un Viaje por ID (GET):

Subrecursos posibles: destino, fecha_salida, fecha_regreso,descripcion, precio, id_cliente, id_viaje.

Endpoint: http://localhost/CARPETA/api/viajes/:ID/:subrecurso


# Crear un Nuevo Viaje (POST):
Debe autenticarse.

Endpoint: http://localhost/CARPETA/api/viajes


Ejemplo de solicitud POST en el body:
{
  "destino": "Destino Nuevo",
  "fecha_salida": "2023-12-01",
  "fecha_regreso": "2023-12-10",
  "descripcion": "Viaje nuevo",
  "precio": 1500,
  "id_cliente": 4
}

# Actualizar un Viaje por ID (PUT):
Debe autenticarse.

Endpoint: http://localhost/CARPETA/api/viajes/:ID

Ejemplo de solicitud PUT en el body:
{
  "destino": "Actualizo destino",
  "fecha_salida": "2023-12-02",
  "fecha_regreso": "2023-12-12",
  "descripcion": "Viaje a destino (actualizado)",
  "precio": 1600,
  "id_cliente": 13
}

# Eliminar un Viaje por ID (DELETE):
Debe autenticarse.

Endpoint: http://localhost/CARPETA/api/viajes/:ID


# Para la tabla de Clientes:


# Obtener todos los clientes (GET)
Endpoint: http://localhost/CARPETA/api/clientes



# Obtener todos los clientes (GET) con paginación (opcional)

page: para la página.
size: para el límite por página.


Ejemplo de uso: http://localhost/CARPETA/api/clientes?page=2&size=10

# Obtener clientes filtrados por cualquier campo (GET):(Opcional)

filter_key: para el campo. 
filter_value: para el valor.

Posibles valores de filter_key:
id_cliente, nombre, apellido, correo_electronico, fecha_nacimiento, dni,direccion.

Ejemplo de uso: http://localhost/CARPETA/api/clientes?filter_key=apellido&filter_value=Perez

# Obtener todos los clientes (GET) ordenados por un campo y en cierta direccion (Opcional)

Este endpoint permite listar todos los clientes. Puedes utilizar los siguientes parámetros de consulta para ordenar los resultados:

sort_by: Campo por el que deseas ordenar 
sort_dir: Dirección de ordenamiento (ascendente "asc" o descendente "desc").

Posibles valores de sort_by:
id_cliente, nombre, apellido, correo_electronico, fecha_nacimiento, dni,direccion.

Ejemplo de uso: http://localhost/CARPETA/api/clientes?sort_by=nombre&sort_dir=asc

# Obtener clientes con combinación de paginación, filtrado y orden (GET)

Ejemplo de uso: http://localhost/CARPETA/api/clientes?sort_by=apellido&sort_dir=desc&filter_key=nombre&filter_value=Juan&page=2&size=7

# Obtener cliente por ID (GET)
Endpoint: http://localhost/CARPETA/api/clientes/:ID


# Obtener subrecurso de un Cliente por ID (GET)
Endpoint: http://localhost/CARPETA/api/clientes/:ID/:subrecurso

Posibles sobrecurso:
id_cliente, nombre, apellido, correo_electronico, fecha_nacimiento, dni,direccion.

# Crear un nuevo cliente (POST)
Debe autenticarse.

Endpoint: http://localhost/CARPETA/api/clientes

Ejemplo de solicitud POST en el body:

{
  "nombre": "Nombre Cliente",
  "apellido": "Apellido Cliente",
  "correo_electronico": "cliente@example.com",
  "fecha_nacimiento": "2000-01-01",
  "dni": "1234567890",
  "direccion": "Dirección del Cliente"
}

# Actualizar un cliente por ID (PUT)
Debe autenticarse.

Endpoint: http://localhost/CARPETA/api/clientes/:ID

Ejemplo de solicitud PUT en el body:

{
  "nombre": "Nuevo Nombre",
  "apellido": "Nuevo Apellido",
  "correo_electronico": "nuevo@example.com",
  "fecha_nacimiento": "2000-02-02",
  "dni": "9876543210",
  "direccion": "Nueva Dirección"
}

# Eliminar un cliente por ID (DELETE)
Debe autenticarse.

Endpoint: http://localhost/CARPETA/api/clientes/:ID



