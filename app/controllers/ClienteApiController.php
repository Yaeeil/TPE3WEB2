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


    //modificar todos y adaptarlos /manejar mas codigos y el tema de errores(creo que falta el 400 de BAD REQUEST)
    //este get permite ordenar los resultados por atributo y orden
    //En postman usas por ejemplo http://localhost/TPE3/api/clientes?sort_by=nombre&sort_dir=ASC
    //hay que ver si hay que manejar errores como filtar por cosas que no existen
    //(Creo que si porque hay que usar el codigo 400)
    //ver como hacer lo de paginacion

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

    //no lo pide pero lo podriamos dejar
    function delete($params = [])
    {
        try {
            $id = $params[':ID'];
            $tarea = $this->model->getClienteById($id);

            if ($tarea) {
                $this->model->deleteCliente($id);
                $this->view->response('La tarea con id=' . $id . ' ha sido borrada.', 200);
            } else {
                $this->view->response('La tarea con id=' . $id . ' no existe.', 404);
            }
        } catch (\Throwable $e) {
            $this->view->response("No se puede eliminar, intente con otro elemento", 404);
        }
    }

    function create($params = [])
    {
        $body = $this->getData();
        $id = $body->id_cliente;
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
            $existeCliente = $this->model->getClienteById($id);
            if (empty($existeCliente)) {
                return $this->view->response('El cliente con ese id no existe', 400);
            } else {
                $id = $this->model->addCliente($nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion);
                $tarea = $this->model->getClienteById($id);
                return $this->view->response($tarea, 201);
            }
        } catch (\Throwable $e) {
            $this->view->response('Error no encontrado, revise la documentacion', 500);
        }
    }

    function update($params = [])
    {
        $id = $params[':ID'];
        $tarea = $this->model->getClienteById($id);

        if ($tarea) {
            $body = $this->getData();
            $id = $body->id_cliente;
            $nombre = $body->nombre;
            $apellido = $body->apellido;
            $correoElectronico = $body->correo_electronico;
            $fechaDeNacimiento = $body->fecha_nacimiento;
            $dni = $body->dni;
            $direccion = $body->direccion;

            $this->model->updateCliente($nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion, $id);

            $this->view->response('La tarea con id=' . $id . ' ha sido modificada.', 200);
        } else {
            $this->view->response('La tarea con id=' . $id . ' no existe.', 404);
        }
    }

    public function getViajesByCliente($params = [])
    {
        $id = $params[':ID'];
        $viajes = $this->model->getViajesByCliente($id);
        $this->view->response($viajes, 200);
    }


    private function validarSort()
    {
        $sortDir = strtolower($_GET['sort_dir']);
        if (!in_array($sortDir, array('asc', 'desc'))) {
            return False;
        }
        return True;
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
        if (isset($_GET['filter_key'])) {
            $params['filter_key'] = $_GET['filter_key'];
            $params['filter_value'] = $_GET['filter_value'];
        }
        if (isset($_GET['sort_dir'])) {
            if (!$this->validarSort()) {
                $this->view->response("sort_dir no es asc ni desc", 400);
                return;
            }
            $params['sort_dir'] = $_GET['sort_dir'];
            $params['sort_by'] = "nombre";
        }
        if (isset($_GET['sort_by'])) {
            $params['sort_by'] = $_GET['sort_by'];
        }
        if (isset($_GET['page'])) {
            $params['page'] = $_GET['page'];
            $params['tamPage'] = 10;
        }
        if (isset($_GET['tamPage'])) {
            $params['tamPage'] = $_GET['tamPage'];
        }

        $clientes = $this->model->getClientes($params);
        $this->view->response($clientes, 200);
    }
}
