<?php
require_once "./app/models/Model.php";
class ViajeModel extends Model
{


    public function getViajes($parametros)
    {
        $sql = "SELECT * FROM viajes";
        if (isset($parametros['filter_key'])) {
            $key = $parametros['filter_key'];
            $value = $parametros['filter_value'];
            $sql .= " WHERE " . $key . " = \"" . $value . "\"";
        }
        if (isset($parametros['sort_by'])) {
            $sql .= " ORDER BY " . $parametros['sort_by'];
            if (isset($parametros['sort_dir'])) {
                $sql .= " " . $parametros['sort_dir'];
            }
        }
        if (isset($parametros['page'])) {
            $offset = (($parametros['page'] - 1) * $parametros['tamPage']);
            $sql .= " LIMIT " . $parametros['tamPage'] . ' OFFSET '. $offset;
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    function getViajeById($id)
    {
        $query = $this->db->prepare("SELECT * FROM viajes WHERE id_viaje=?");
        $query->execute([$id]);
        $viajes = $query->fetch(PDO::FETCH_OBJ);
        return $viajes;
    }

    //orden fijo (item obligatorio)
    function getViajesOrdenDestino($parametros){
        $sql = "SELECT * FROM viajes ORDER BY destino";
        if (isset($parametros['sort_dir'])) {
            $sql .= " " . $parametros['sort_dir'];
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
