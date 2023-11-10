<?php
require_once 'app/controllers/ApiController.php';
require_once 'app/helpers/AuthApiHelper.php';

require_once 'app/models/ViajeModel.php';

class ViajeApiController extends ApiController
{
    private $model;
    private $modelCliente;
    private $authHelper;


    function __construct()
    {
        parent::__construct();
        $this->model = new ViajeModel();
        $this->modelCliente = new ClienteModel();
        $this->authHelper = new AuthHelper();
    }

    public function get($params = [])
    {
        if (empty($params)) {
            return $this->getViajes();
        }
        $id = $params[':ID'];
        $viaje = $this->model->getViajeById($id);
        if (empty($viaje)) {
            $this->view->response("El viaje con el id " . $id . " no existe", 404);
            return;
        }
        if (isset($params[':subrecurso'])) {
            $subrecurso = $params[':subrecurso'];
            return $this->getCampoViaje($viaje, $subrecurso);
        }
        $this->view->response($viaje, 200);
    }


    private function validarSort()
    {
        $sortDir = strtolower($_GET['sort_dir']);
        if (!in_array($sortDir, array('asc', 'desc'))) {
            return False;
        }
        return True;
    }

    private function validarSortBy()
    {
        $sortBy = strtolower($_GET['sort_by']);
        if (!in_array($sortBy, array('destino', 'fecha_salida', 'fecha_regreso', 'descripcion', 'precio', 'id_cliente', 'id_viaje'))) {
            return False;
        }
        return True;
    }

    private function validarFilterKey()
    {
        $filter_key = strtolower($_GET['filter_key']);
        if (!in_array($filter_key, array('destino', 'fecha_salida', 'fecha_regreso', 'descripcion', 'precio', 'id_cliente', 'id_viaje'))) {
            return False;
        }
        return True;
    }
    private function validarPage()
    {
        if (!($_GET['page'] >= 0 || is_numeric($_GET['page']))) {
            return false;
        }
        return true;
    }
    private function validarSize()
    {
        if (!is_numeric($_GET['size'])) {
            return false;
        }
        return true;
    }


    private function verificarSobrecurso($subrecurso)
    {
        if (!in_array($subrecurso, array('destino', 'fecha_salida', 'fecha_regreso', 'descripcion', 'precio', 'id_cliente', 'id_viaje'))) {
            return False;
        }
        return True;
    }
    private function getCampoViaje($viaje, $subrecurso)
    {
        if (isset($viaje->$subrecurso) && $this->verificarSobrecurso($subrecurso))
            $this->view->response($viaje->$subrecurso, 200);
        else {
            $this->view->response("El Viaje con el subrecurso: " . $subrecurso . " no existe", 404);
        }
    }

    private function getViajes()
    {
        $params = [];
        if (isset($_GET['filter_key']) && isset($_GET['filter_value'])) {
            if (!$this->validarFilterKey()) {
                $this->view->response("filter_key incorrecto, revise la documentacion", 400);
                return;
            }
            $params['filter_key'] = $_GET['filter_key']; //el campo
            $params['filter_value'] = $_GET['filter_value']; //el valor
        }
        if (isset($_GET['sort_dir'])) {
            if (!$this->validarSort()) {
                $this->view->response("sort_dir no es asc ni desc", 400);
                return;
            }
            $params['sort_dir'] = $_GET['sort_dir'];
            $params['sort_by'] = "destino"; //por defecto
        }
        if (isset($_GET['sort_by'])) {
            if (!$this->validarSortBy()) {
                $this->view->response("sort_by incorrecto, revise la documentacion", 400);
                return;
            }
            $params['sort_by'] = $_GET['sort_by'];
        }
        if (isset($_GET['page'])) {
            if (!$this->validarPage()) {
                return $this->view->response("Page no puede ser menor o igual a 0,  ni String, revise la documentación", 400);
            }
            $params['page'] = $_GET['page'];
            $params['size'] = 10; //por defecto
        }
        if (isset($_GET['size'])) {
            if (!$this->validarSize()) {
                return $this->view->response("el tamaño no puede ser String, revise la documentación", 400);
            }
            $params['size'] = $_GET['size'];
        }

        $viajes = $this->model->getViajes($params);
        $this->view->response($viajes, 200);
    }


    function delete($params = [])
    {
        if (empty($this->authHelper->currentUser())) {
            $this->view->response("No tienes permisos para realizar esta accion", 401);
            return;
        }
        try {
            $id = $params[':ID'];
            $viaje = $this->model->getViajeById($id);

            if ($viaje) {
                $this->model->deleteViaje($id);
                return $this->view->response('El Viaje con id=' . $id . ' ha sido borrada.', 200);
            } else {
                return $this->view->response('El Viaje con id=' . $id . ' no existe.', 404);
            }
        } catch (\Throwable $e) {
            $this->view->response("No se puede eliminar, intente con otro elemento", 500);
        }
    }

    function create($params = [])
    {
        if (empty($this->authHelper->currentUser())) {
            $this->view->response("No tienes permisos para realizar esta accion", 401);
            return;
        }
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
                $viaje = $this->model->getViajeById($id);
                return $this->view->response($viaje, 201);
            }
        } catch (\Throwable $e) {
            $this->view->response("Error no encontrado, revise la documentacion", 500);
        }
    }

    function update($params = [])
    {
        if (empty($this->authHelper->currentUser())) {
            $this->view->response("No tienes permisos para realizar esta accion", 401);
            return;
        }
        try {
            $id = $params[':ID'];
            $viaje = $this->model->getViajeById($id);

            if ($viaje) {
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
