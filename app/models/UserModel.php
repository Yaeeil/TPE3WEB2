<?php
    require_once 'app/models/Model.php';

class UserModel extends Model {
    public function getByNombreUsuario($nombre) {
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE nombre_usuario = ?');
        $query->execute([$nombre]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
}