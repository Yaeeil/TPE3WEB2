<?php
    require_once 'app/controllers/ApiController.php';

    require_once 'app/models/ViajeModel.php';

    class ViajeApiController extends ApiController {
        private $model;

        function __construct() {
            parent::__construct();
            $this->model = new ViajeModel();
        }


        //modificar todos y adaptarlos /manejar mas codigos y el tema de errores (creo que falta el 400 de BAD REQUEST)

        //este get permite ordenar los resultados por atributo y orden
        //En postman usas por ejemplo http://localhost/TPE3/api/viajes?order=destino&sort=ASC
        //hay que ver si hay que manejar errores como filtar por cosas que no existen(Creo que si porque hay que usar el codigo 400)

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
    
                $viaje = $this->model->getViajes($parametros);
                $this->view->response($viaje, 200);
            } else {
                $id = $params[':ID'];
                $viaje = $this->model->getViajeById($id);
                if (!empty($viaje)) {
                    if ($params[':subrecurso']) {
                        switch ($params[':subrecurso']) {
                            case 'id_viaje':
                                $this->view->response($viaje->id_viaje, 200);
                                break;
                            case 'destino':
                                $this->view->response($viaje->destino, 200);
                                break;
                            case 'fecha_salida':
                                $this->view->response($viaje->fecha_salida, 200);
                                break;
                            case 'fecha_regreso':
                                $this->view->response($viaje->fecha_regreso, 200);
                                break;
                            case 'descripcion':
                                $this->view->response($viaje->descripcion, 200);
                                break;
                            case 'precio':
                                $this->view->response($viaje->precio, 200);
                                break;
                            case 'id_cliente':
                                $this->view->response($viaje->id_cliente, 200);
                                break;
                            default:
                                $this->view->response("El viaje con el subrecurso: " . $params[':subrecurso'] . " no existe", 404);
                        }
                    }
                    else{
                        $this->view->response($viaje, 200);
                    }
                } else {
                    $this->view->response("El viaje con el id " . $id . " no existe", 404);
                }
            }
        }  
        // // if (isset($_GET['filter']) && !empty($_GET['filter'])) {   
                     //       $games = $this->model->filter($filter);
                    //}
       
        //agregar un get por una condicion especifica asc o desc

        //no lo pide pero lo podriamos dejar
        function delete($params = []) {
            $id = $params[':ID'];
            $tarea = $this->model->getViajeById($id);

            if($tarea) {
                $this->model->deleteViaje($id);
                $this->view->response('La tarea con id='.$id.' ha sido borrada.', 200);
            } else {
                $this->view->response('La tarea con id='.$id.' no existe.', 404);
            }
        }


        //modificar para que coincida con viajes
        function create($params = []) {
            $body = $this->getData();
            $destino = $body->destino;
            $fechaS = $body->fecha_salida;
            $fechaR = $body->fecha_regreso;
            $descripcion= $body->descripcion;
            $precio = $body->precio;
            $cliente = $body->id_cliente;

            if (empty( $destino) || empty($fechaS) || empty($fechaR) || empty($descripcion) || empty($precio) || empty ($cliente)){
                $this->view->response("Complete los datos", 400);
            } else {
                $id = $this->model->addViaje( $destino, $fechaS, $fechaR, $descripcion, $precio, $cliente);
                $tarea = $this->model->getViajeById($id);
                $this->view->response($tarea, 201);
            }
    
        }


        //modificar para que coincida con viajes
        function update($params = []) {
            $id = $params[':ID'];
            $tarea = $this->model->getViajeById($id);

            if($tarea) {
                $body = $this->getData();
                $destino = $body->destino;
                $fechaS = $body->fecha_salida;
                $fechaR = $body->fecha_regreso;
                $descripcion= $body->descripcion;
                $precio = $body->precio;
                $cliente = $body->id_cliente;
                $this->model->updateViaje($destino, $fechaS, $fechaR, $descripcion, $precio, $cliente, $id);

                $this->view->response('La tarea con id='.$id.' ha sido modificada.', 200);
            } else {
                $this->view->response('La tarea con id='.$id.' no existe.', 404);
            }
        }
    }