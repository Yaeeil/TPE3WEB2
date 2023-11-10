<?php
require_once './database/config.php';

//realiza una codificación de URL segura para los datos proporcionados en formato base64.
function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class AuthHelper
{
    //intenta obtener el encabezado de autorización 
    function getAuthHeaders()
    {
        $header = "";
        if (isset($_SERVER['HTTP_AUTHORIZATION']))
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        return $header;
    }

    //crea un token JWT. (agrega información sobre el algoritmo de firma (alg),
    // el tipo (typ), y una marca de tiempo de expiración (exp) al encabezado y 
    //payload del token. Luego, genera una firma HMAC usando la clave secreta (JWT_KEY))
    function createToken($payload)
    {
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT'
        );

        $payload['exp'] = time() + JWT_EXP;

        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $signature = base64url_encode($signature);

        $token = "$header.$payload.$signature";

        return $token;
    }

    //verifica la validez de un token JWT. Divide el token en sus partes (encabezado, 
    //payload y firma), recalcula la firma y compara las firmas. Luego verifica la expiración 
    //del token comparando la marca de tiempo de expiración (exp) con el tiempo actual.
    function verify($token)
    {

        $token = explode(".", $token); // [$header, $payload, $signature]
        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        $new_signature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $new_signature = base64url_encode($new_signature);

        if ($signature != $new_signature) {
            return false;
        }

        $payload = json_decode(base64_decode($payload));

        if ($payload->exp < time()) {
            return false;
        }

        return $payload;
    }

    //intenta obtener y verificar el token de autenticación actual en el encabezado Authorization. 
    function currentUser()
    {
        $auth = $this->getAuthHeaders(); // "Bearer $token"
        $auth = explode(" ", $auth); // ["Bearer", "$token"]

        if ($auth[0] != "Bearer") {
            return false;
        }

        return $this->verify($auth[1]); // Si está bien nos devuelve el payload
    }
}
