<?php
require_once 'app/controllers/ApiController.php';
require_once 'app/helpers/AuthApiHelper.php';
require_once 'app/models/UserModel.php';

class UserApiController extends ApiController
{
  private $model;
  private $authHelper;

  function __construct()
  {
    parent::__construct();
    $this->authHelper = new AuthHelper();
    $this->model = new UserModel();
  }

  function getToken($params = [])
  {
    $basic = $this->authHelper->getAuthHeaders(); // Darnos el header 'Authorization:' 'Basic: base64(usr:pass)'

    if (empty($basic)) {
      $this->view->response('No envió encabezados de autenticación.', 401);
      return;
    }

    $basic = explode(" ", $basic); // ["Basic", "base64(usr:pass)"]

    if ($basic[0] != "Basic") {
      $this->view->response('Los encabezados de autenticación son incorrectos.', 401);
      return;
    }

    $userpass = base64_decode($basic[1]); // usr:pass
    $userpass = explode(":", $userpass); // ["usr", "pass"]

    $nombreUsuario = $userpass[0];
    $pass = $userpass[1];

    $user = $this->model->getByNombreUsuario($nombreUsuario);
    if (isset($user)) {
      if (password_verify($pass, $user->password)) {
        $userdata = ["id_usuario" => $user->id_usuario, "nombre_usuario" => $user->nombre_usuario];
        $token = $this->authHelper->createToken($userdata);
        $this->view->response($token, 200);
        return;
      }
    }
    $this->view->response('El usuario o contraseña son incorrectos.', 401);
  }
}
