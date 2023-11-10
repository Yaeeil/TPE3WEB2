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

    //Se divide el encabezado de autenticación para obtener el tipo de autenticación y los datos codificados en base64.
    $basic = explode(" ", $basic); // ["Basic", "base64(usr:pass)"] 

    if ($basic[0] != "Basic") {
      $this->view->response('Los encabezados de autenticación son incorrectos.', 401);
      return;
    }

    //Se decodifican los datos codificados en base64 para obtener el nombre de usuario y la contraseña.
    $userpass = base64_decode($basic[1]); // usr:pass
    $userpass = explode(":", $userpass); // ["usr", "pass"]

    $nombreUsuario = $userpass[0];
    $pass = $userpass[1];

    $user = $this->model->getByNombreUsuario($nombreUsuario);

    //Se verifica la contraseña utilizando password_verify, si es correcta, se crea un token 
    //utilizando el método createToken del auth y se devuelve
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
