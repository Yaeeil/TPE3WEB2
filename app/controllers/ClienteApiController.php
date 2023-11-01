<?php
    require_once 'app/controllers/ApiController.php';

    require_once 'app/models/ClienteModel.php';

    class ClienteApiController extends ApiController {
        private $model;

        function __construct() {
            parent::__construct();
            $this->model = new ClienteModel();
        }


        //modificar todos y adaptarlos /manejar mas codigos y el tema de errores(creo que falta el 400 de BAD REQUEST)
        
        
        //este get permite ordenar los resultados por atributo y orden
        //En postman usas por ejemplo http://localhost/TPE3/api/clientes?order=nombre&sort=ASC
        //hay que ver si hay que manejar errores como filtar por cosas que no existen
        //(Creo que si porque hay que usar el codigo 400)

          //ver como hacer lo de paginacion
        public function get($params = []) {
            $parametros = [];
    
            if (empty($params)) {
                if (isset($_GET['order'])) {
                    $parametros['order'] = $_GET['order'];
                }
                if (isset($_GET['sort'])) {
                    $parametros['sort'] = $_GET['sort'];
                }
    
                $cliente = $this->model->getClientes($parametros);
                $this->view->response($cliente, 200);
            } else {
                $id = $params[':ID'];
                $cliente = $this->model->getClienteById($id);
                if (!empty($cliente)) {
                    if ($params[':subrecurso']) {
                        switch ($params[':subrecurso']) {
                            case 'id_cliente':
                                $this->view->response($cliente->id_cliente, 200);
                                break;
                            case 'nombre':
                                $this->view->response($cliente->nombre, 200);
                                break;
                            case 'apellido':
                                $this->view->response($cliente->apellido, 200);
                                break;
                            case 'correo_electronico':
                                $this->view->response($cliente->correo_electronico, 200);
                                break;
                            case 'fecha_nacimiento':
                                $this->view->response($cliente->fecha_nacimiento, 200);
                                break;
                            case 'dni':
                                $this->view->response($cliente->dni, 200);
                                break;
                            case 'direccion':
                                $this->view->response($cliente->direccion, 200);
                                break;
                            default:
                                $this->view->response("El cliente con el subrecurso: " . $params[':subrecurso'] . " no existe", 404);
                        }
                    }
                    else{
                        $this->view->response($cliente, 200);
                    }
                } else {
                    $this->view->response("El cliente con el id " . $id . " no existe", 404);
                }
            }
        }


         //agregar un get por una condicion especifica asc o desc

        //no lo pide pero lo podriamos dejar
        function delete($params = []) {
            $id = $params[':ID'];
            $tarea = $this->model->getTask($id);

            if($tarea) {
                $this->model->deleteTask($id);
                $this->view->response('La tarea con id='.$id.' ha sido borrada.', 200);
            } else {
                $this->view->response('La tarea con id='.$id.' no existe.', 404);
            }
        }

        //modificar para que coincida con clientes
        function create($params = []) {
            $body = $this->getData();

            $titulo = $body->titulo;
            $descripcion = $body->descripcion;
            $prioridad = $body->prioridad;

            if (empty($titulo) || empty($prioridad)) {
                $this->view->response("Complete los datos", 400);
            } else {
                $id = $this->model->insertTask($titulo, $descripcion, $prioridad);

                // en una API REST es buena práctica es devolver el recurso creado
                $tarea = $this->model->getTask($id);
                $this->view->response($tarea, 201);
            }
    
        }

        // //modificar para que coincida con clientes
        function update($params = []) {
            $id = $params[':ID'];
            $tarea = $this->model->getTask($id);

            if($tarea) {
                $body = $this->getData();
                $titulo = $body->titulo;
                $descripcion = $body->descripcion;
                $prioridad = $body->prioridad;
                $this->model->updateTaskData($id, $titulo, $descripcion, $prioridad);

                $this->view->response('La tarea con id='.$id.' ha sido modificada.', 200);
            } else {
                $this->view->response('La tarea con id='.$id.' no existe.', 404);
            }
        }
    }