<?php
require_once 'app/controllers/ApiController.php';

require_once 'app/models/ViajeModel.php';

class ViajeApiController extends ApiController
{
    private $model;
    private $modelCliente;

    function __construct()
    {
        parent::__construct();
        $this->model = new ViajeModel();
        $this->modelCliente = new ClienteModel();
    }


    //modificar todos y adaptarlos /manejar mas codigos y el tema de errores (creo que falta el 400 de BAD REQUEST)

    //este get permite ordenar los resultados por atributo y orden
    //En postman usas por ejemplo http://localhost/TPE3/api/viajes?order=destino&sort=ASC
    //hay que ver si hay que manejar errores como filtar por cosas que no existen(Creo que si porque hay que usar el codigo 400)

    //ver como hacer lo de paginacion
    public function get($params = [])
    {
        try {
            $parametros = [];

            if (empty($params)) {
                if (isset($_GET['order'])) {
                    $parametros['order'] = $_GET['order'];
                }
                if (isset($_GET['sort'])) {
                    $parametros['sort'] = $_GET['sort'];
                    if (!($parametros['sort'] == "asc" || $parametros['sort'] == "ASC" || $parametros['sort'] == "desc" || $parametros['sort'] == "DESC")) {
                        return $this->view->response("El parámetro pasado a sort tiene un error, revise la documentación", 400);
                    }
                }

                if (isset($_GET['filter'])) {
                    $parametros['filter'] = $_GET['filter'];
                    $viaje = $this->model->filter($parametros['filter']);
                    if (empty($viaje)) {
                        return $this->view->response("El arreglo es vacío debido a un destino mal escrito o inexistente, revise la documentacion", 400);
                    } else {
                        return $this->view->response($viaje, 200);
                    }
                } elseif (isset($_GET['page']) && isset($_GET['limit'])) {
                    if ($_GET['page'] == 0 || !is_numeric($_GET['page']) || !is_numeric($_GET['limit'])) {
                        return $this->view->response("Page no puede ser 0 ni String, revise la documentación", 400);
                    }
                    $parametros['page'] = $_GET['page'];
                    $parametros['limit'] = $_GET['limit'];
                    $viaje = $this->model->Paginated($parametros['page'], $parametros['limit']);
                    if (empty($viaje)) {
                        return $this->view->response("El arreglo está vacío, revise la documentación", 400);
                    } else {
                        return $this->view->response($viaje, 200);
                    }
                }
                $viaje = $this->model->getViajes($parametros);
                return $this->view->response($viaje, 200);
            } else {
                $id = $params[':ID'];
                $viaje = $this->model->getViajeById($id);
                if (!empty($viaje)) {
                    if (isset($params[':subrecurso'])) {
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
                    } else {
                        return $this->view->response($viaje, 200);
                    }
                } else {
                    return $this->view->response("El viaje con el ID " . $id . " no existe", 404);
                }
            }
        } catch (\Throwable $e) {
            $this->view->response("Error no identificado, revise la documentacion", 500);
        }
    }

    //agregar un get ordenado por atributo especifico asc o desc
    public function getOrderDestino()
    {
        try {
            $parametros = [];
            if (isset($_GET['sort'])) {
                $parametros['sort'] = $_GET['sort'];
                if (!($parametros['sort'] == "asc" || $parametros['sort'] == "ASC" || $parametros['sort'] == "desc" || $parametros['sort'] == "DESC")) {
                    return $this->view->response("El parámetro pasado a sort tiene un error, revise la documentación", 400);
                } else {
                    $viaje = $this->model->getViajesOrdenDestino($parametros);
                    return $this->view->response($viaje, 200);
                }
            }
        } catch (\Throwable $e) {
            $this->view->response("Error no encontrado, revise la documentación", 500);
        }
    }
    //no lo pide pero lo podriamos dejar
    function delete($params = [])
    {
        try {
            $id = $params[':ID'];
            $tarea = $this->model->getViajeById($id);

            if ($tarea) {
                $this->model->deleteViaje($id);
                return $this->view->response('La tarea con id=' . $id . ' ha sido borrada.', 200);
            } else {
                return $this->view->response('La tarea con id=' . $id . ' no existe.', 404);
            }
        } catch (\Throwable $e) {
            $this->view->response("No se puede eliminar, intente con otro elemento", 404);
        }
    }

    //modificar para que coincida con viajes
    function create($params = [])
    {
        try {
            $body = $this->getData();
            $destino = $body->destino;
            $fechaS = $body->fecha_salida;
            $fechaR = $body->fecha_regreso;
            $descripcion = $body->descripcion;
            $precio = $body->precio;
            $cliente = $body->id_cliente;

            if (empty($destino) || empty($fechaS) || empty($fechaR) || empty($descripcion) || empty($precio) || empty($cliente)) {
                return $this->view->response("Complete los datos", 400);
            }
            $existeCliente = $this->modelCliente->getClienteById($cliente);
            if (empty($existeCliente)) {
                return $this->view->response("El cliente con ese id no existe", 400);
            } else {
                $id = $this->model->addViaje($destino, $fechaS, $fechaR, $descripcion, $precio, $cliente);
                $tarea = $this->model->getViajeById($id);
                return $this->view->response($tarea, 201);
            }
        } catch (\Throwable $e) {
            $this->view->response("Error no encontrado, revise la documentacion", 500);
        }
    }


    //modificar para que coincida con viajes
    function update($params = [])
    {
        try {
            $id = $params[':ID'];
            $tarea = $this->model->getViajeById($id);

            if ($tarea) {
                $body = $this->getData();
                $destino = $body->destino;
                $fechaS = $body->fecha_salida;
                $fechaR = $body->fecha_regreso;
                $descripcion = $body->descripcion;
                $precio = $body->precio;
                $cliente = $body->id_cliente;

                $existeCliente = $this->modelCliente->getClienteById($cliente);
                if (empty($existeCliente)) {
                    return $this->view->response("El cliente con ese id no existe", 400);
                } else {
                    $this->model->updateViaje($destino, $fechaS, $fechaR, $descripcion, $precio, $cliente, $id);

                    return $this->view->response('La tarea con id=' . $id . ' ha sido modificada.', 200);
                }
            } else {
                return $this->view->response('La tarea con id=' . $id . ' no existe.', 404);
            }
        } catch (\Throwable $e) {
            $this->view->response("Error no encontrado, revise la documentacion", 500);
        }
    }
}