<?php
require_once 'app/controllers/ApiController.php';

require_once 'app/models/ClienteModel.php';

class ClienteApiController extends ApiController
{
    private $model;

    function __construct()
    {
        parent::__construct();
        $this->model = new ClienteModel();
    }

    public function get($params = [])
    {
        if (empty($params)) {
            return $this->getClientes();
        }
        $id = $params[':ID'];
        $cliente = $this->model->getClienteById($id);
        if (empty($cliente)) {
            $this->view->response("El cliente con el id " . $id . " no existe", 404);
            return;
        }
        if (isset($params[':subrecurso'])) {
            $subrecurso = $params[':subrecurso'];
            return $this->getCampoCliente($cliente, $subrecurso);
        }
        $this->view->response($cliente, 200);
    }

    function delete($params = [])
    {
        try {
            $id = $params[':ID'];
            $cliente = $this->model->getClienteById($id);

            if ($cliente) {
                $this->model->deleteCliente($id);
                $this->view->response('El cliente con id=' . $id . ' ha sido borrada.', 200);
            } else {
                $this->view->response('El cliente con id=' . $id . ' no existe.', 404);
            }
        } catch (\Throwable $e) {
            $this->view->response("No se puede eliminar, intente con otro elemento", 404);
        }
    }

    function create($params = [])
    {
        $body = $this->getData();
        $nombre = $body->nombre;
        $apellido = $body->apellido;
        $correoElectronico = $body->correo_electronico;
        $fechaDeNacimiento = $body->fecha_nacimiento;
        $dni = $body->dni;
        $direccion = $body->direccion;

        try {
            if (empty($nombre) || empty($apellido) || empty($correoElectronico) || empty($fechaDeNacimiento) || empty($dni) || empty($direccion)) {
                return $this->view->response('Complete los datos', 400);
            }
            $id = $this->model->addCliente($nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion);
            $cliente = $this->model->getClienteById($id);
            return $this->view->response($cliente, 201);
        } catch (\Throwable $e) {
            $this->view->response('Error no encontrado, revise la documentacion', 500);
        }
    }

    function update($params = [])
    {
       
        $id = $params[':ID'];
        $cliente = $this->model->getClienteById($id);
    try{
        if ($cliente) {
            $body = $this->getData();
            $nombre = $body->nombre;
            $apellido = $body->apellido;
            $correoElectronico = $body->correo_electronico;
            $fechaDeNacimiento = $body->fecha_nacimiento;
            $dni = $body->dni;
            $direccion = $body->direccion;
            
            $this->model->updateCliente($nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion, $id);
        
            return $this->view->response('El Cliente con id=' . $id . ' ha sido modificada.', 200);
        }
            else {
            return $this->view->response('El Cliente con id=' . $id . ' no existe.', 404);
        }
        }catch(\Throwable $e) {
            $this->view->response('Error no encontrado, revise la documentacion', 500);
       }
    }

    //para que este?
   // public function getViajesByCliente($params = [])
   // {
    //    $id = $params[':ID'];
    //    $viajes = $this->model->getViajesByCliente($id);
    //    $this->view->response($viajes, 200);
    //}

    
    //item obligatorio, sort_by fijo
    public function getOrdenApellido() {
        try {
            $parametros = [];
            if (isset($_GET['sort_dir'])) {
                if (!$this->validarSort()) {
                    $this->view->response("sort_dir incorrecto debe ser asc o desc, revise la documentacion", 400);
                    return;
                }
                $parametros['sort_dir'] = $_GET['sort_dir'];
                
            }
            $clientes = $this->model->getClientesOrdenApellido($parametros);
            return $this->view->response($clientes, 200);
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
        if (!in_array($sortBy, array('nombre', 'apellido', 'correo_electronico', 'fecha_nacimiento', 'nombre', 'dni', 'direccion'))) {
            return False;
        }
        return True;
    }

    private function validarFilterKey()
    {
        $filter_key = strtolower($_GET['filter_key']);
        if (!in_array($filter_key, array('nombre', 'apellido', 'correo_electronico', 'fecha_nacimiento', 'nombre', 'dni', 'direccion'))) {
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
    private function validarTamPage()
    {
        if (!is_numeric($_GET['tamPage'])) {
            return false;
        }
        return true;
    }


    private function getCampoCliente($cliente, $subrecurso)
    {
        switch ($subrecurso) {
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
                $this->view->response("El cliente con el subrecurso: " . $subrecurso . " no existe", 404);
        }
    }
    private function getClientes()
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
            $params['sort_by'] = "nombre"; //por defecto
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
            $params['page'] = $_GET['page']; //pagina
            $params['tamPage'] = 10; //por defecto
        }
        if (isset($_GET['tamPage'])) {
            if(!$this->validarTamPage()){
                return $this->view->response("tamPage no puede ser String, revise la documentación", 400);
            }
            $params['tamPage'] = $_GET['tamPage']; //limite por pagina
        }

        $clientes = $this->model->getClientes($params);
        $this->view->response($clientes, 200);
    }
}
