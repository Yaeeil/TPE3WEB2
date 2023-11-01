<?php
require_once "./app/models/Model.php";
class ViajeModel extends Model
{


    public function getViajes($parametros) {
        $sql="SELECT * FROM viajes";
        if(isset($parametros['order'])){
            $sql .= " ORDER BY " . $parametros['order'];
            if(isset($parametros['sort'])){
                $sql .= " " . $parametros['sort'];
            }
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    //ver si seria asi
    function Paginated ($page,$limit){ //page indica desde cual y limit cuantos resultados mostrar
        $offset = (($page - 1) * $limit); //calculo para pag usa page y limit
        $query = $this->db->prepare("SELECT viajes LIMIT "  .$offset ." , ".$limit);
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games; 
        }
        
        function filter($destino){
        $query = $this->db->prepare("SELECT * FROM viajes WHERE Destino = ?");
        $query->execute([$destino]);
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        return $games;}

    function getViajeById($id)
    {
        $query = $this->db->prepare("SELECT * FROM viajes WHERE id_viaje=?");
        $query->execute([$id]);
        $viajes = $query->fetch(PDO::FETCH_OBJ);
        return $viajes;
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
