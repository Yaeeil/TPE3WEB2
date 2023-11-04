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

    //item obligatorio, sort_by fijo
    public function getOrdenDestino() {
        try {
            $parametros = [];
            if (isset($_GET['sort_dir'])) {
                if (!$this->validarSort()) {
                    $this->view->response("sort_dir incorrecto debe ser asc o desc, revise la documentacion", 400);
                    return;
                }
                $parametros['sort_dir'] = $_GET['sort_dir'];
                
            }
            $viajes = $this->model->getViajesOrdenDestino($parametros);
            return $this->view->response($viajes, 200);
        } catch (\Throwable $e) {
            $this->view->response("Error no encontrado, revise la documentación", 500);
        }
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
        if (!in_array($sortBy, array('destino', 'fecha_salida', 'fecha_regreso', 'descripcion', 'precio', 'id_cliente'))) {
            return False;
        }
        return True;
    }

    private function validarFilterKey()
    {
        $filter_key = strtolower($_GET['filter_key']);
        if (!in_array($filter_key, array('destino', 'fecha_salida', 'fecha_regreso', 'descripcion', 'precio', 'id_cliente'))) {
            return False;
        }
        return True;
    }
    private function validarPage(){
        if (!($_GET['page'] >= 0 || is_numeric($_GET['page']))) {
                  return false;       
        }
        return true;
        }
        private function validarTamPage(){
            if (!is_numeric($_GET['tamPage'])) {
                      return false;       
            }
            return true;
            }
        

    private function getCampoViaje($viaje, $subrecurso)
    {
        switch ($subrecurso) {
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
            $this->view->response("El cliente con el subrecurso: " . $subrecurso . " no existe", 404);
        
        }
    }

    private function getViajes()
    {
        $params = [];
        if (isset($_GET['filter_key']) && isset($_GET['filter_value'])) {
            if(!$this->validarFilterKey()){
                $this->view->response("filter_key incorrecto, revise la documentacion", 400);
                return;
            }
            $params['filter_key'] = $_GET['filter_key'];//el campo
            $params['filter_value'] = $_GET['filter_value'];//el valor
        }
        if (isset($_GET['sort_dir'])) {
            if (!$this->validarSort()) {
                $this->view->response("sort_dir no es asc ni desc", 400);
                return;
            }
            $params['sort_dir'] = $_GET['sort_dir'];
            $params['sort_by'] = "nombre";//por defecto
        }
        if (isset($_GET['sort_by'])) {
            if (!$this->validarSortBy()) {
                $this->view->response("sort_by incorrecto, revise la documentacion", 400);
                return;
            }
            $params['sort_by'] = $_GET['sort_by'];
        }
        if (isset($_GET['page'])) {
            if(!$this->validarPage()){
                return $this->view->response("Page no puede ser menor o igual a 0,  ni String, revise la documentación", 400);
            }
            $params['page'] = $_GET['page'];
            $params['tamPage'] = 10; //por defecto
        }
        if (isset($_GET['tamPage'])) {
            if(!$this->validarTamPage()){
                return $this->view->response("tamPage no puede ser String, revise la documentación", 400);
            }
            $params['tamPage'] = $_GET['tamPage'];
        }

        $viajes = $this->model->getViajes($params);
        $this->view->response($viajes, 200);
    }

   
    function delete($params = [])
    {
        try {
            $id = $params[':ID'];
            $viaje = $this->model->getViajeById($id);

            if ($viaje) {
                $this->model->deleteViaje($id);
                return $this->view->response('El Cliente con id=' . $id . ' ha sido borrada.', 200);
            } else {
                return $this->view->response('El Cliente con id=' . $id . ' no existe.', 404);
            }
        } catch (\Throwable $e) {
            $this->view->response("No se puede eliminar, intente con otro elemento", 404);
        }
    }

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
               $viaje = $this->model->getViajeById($id);
                return $this->view->response($viaje, 201);
            }
        } catch (\Throwable $e) {
            $this->view->response("Error no encontrado, revise la documentacion", 500);
        }
    }

    function update($params = [])
    {
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
