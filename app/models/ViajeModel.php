<?php
require_once "./app/models/Model.php";
class ViajeModel extends Model
{


    public function getViajes($parametros)
    {
        $sql = "SELECT * FROM viajes";
        if (isset($parametros['order'])) {
            $sql .= " ORDER BY " . $parametros['order'];
            if (isset($parametros['sort'])) {
                $sql .= " " . $parametros['sort'];
            }
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    //ver si seria asi
    //page: Este parámetro representa la página que deseas mostrar limit: Este parámetro representa la cantidad de registros que deseas mostrar en cada página
    //http://localhost/TPE3/api/viajes?page=2&limit=8 (x ej muestra desde el 9 al 16)
    function Paginated($page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $query = $this->db->prepare("SELECT * FROM viajes LIMIT $offset, $limit");
        $query->execute();
        $viajes = $query->fetchAll(PDO::FETCH_OBJ);
        return $viajes;
    }

    function filter($destino)
    {
        $query = $this->db->prepare("SELECT * FROM viajes WHERE destino = ?");
        $query->execute([$destino]);
        $viajes = $query->fetchAll(PDO::FETCH_OBJ);
        return $viajes;
    }

    function getViajeById($id)
    {
        $query = $this->db->prepare("SELECT * FROM viajes WHERE id_viaje=?");
        $query->execute([$id]);
        $viajes = $query->fetch(PDO::FETCH_OBJ);
        return $viajes;
    }

    function getViajesOrdenDestino($parametros){
    $sql = "SELECT * FROM viajes ORDER BY destino";
    if (isset($parametros['sort'])) {
        $sql .= " " . $parametros['sort'];
    }
    $query = $this->db->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

    function addViaje($destino, $fechaS, $fechaR, $descripcion, $precio, $cliente)
    {
        $query = $this->db->prepare('INSERT INTO viajes (destino, fecha_salida, fecha_regreso, descripcion, precio, id_cliente) VALUES (?, ?, ?, ?, ?, ?)');
        $query->execute([$destino, $fechaS, $fechaR, $descripcion, $precio, $cliente]);
        return $this->db->lastInsertId();
    }

    /* function getViajeByClienteId($id)
     {
         $query = $this->db->prepare("SELECT * FROM viajes WHERE id_cliente = ?");
         $query->execute([$id]);
         $viajes = $query->fetchAll(PDO::FETCH_OBJ);
         return $viajes;
     } */

    function deleteViaje($id)
    {
        $query = $this->db->prepare('DELETE FROM viajes WHERE id_viaje = ?');
        $query->execute([$id]);
    }



    function updateViaje($destino, $fechaS, $fechaR, $descripcion, $precio, $cliente, $id)
    {
        $query = $this->db->prepare('UPDATE viajes SET destino = ?, fecha_salida = ?, fecha_regreso = ?, descripcion = ?, precio = ?, id_cliente = ? WHERE id_viaje = ?');
        $query->execute([$destino, $fechaS, $fechaR, $descripcion, $precio, $cliente, $id]);
    }
}
