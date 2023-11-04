<?php
require_once './app/models/Model.php';
class clienteModel extends Model
{


    public function getClientes($parametros)
    {
        $sql = "SELECT * FROM clientes";
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

    function getClienteById($id)
    {
        $query = $this->db->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $query->execute([$id]);
        $cliente = $query->fetch(PDO::FETCH_OBJ);
        return $cliente;
    }
    function getViajesByCliente($id)
    {
        $query = $this->db->prepare("SELECT * FROM viajes WHERE id_cliente = ?");
        $query->execute([$id]);
        $clientes = $query->fetch(PDO::FETCH_OBJ);
        return $clientes;
    }

    // function getClientesByDestino($destino)
    // {
    //     $query = $this->db->prepare("SELECT * FROM clientes JOIN viajes ON viajes.id_cliente = clientes.id_cliente WHERE viajes.destino = ?");
    //     $query->execute([$destino]);
    //     $clientes = $query->fetch(PDO::FETCH_OBJ);
    //     return $clientes;
    // }
    
    
    //orden fijo (item obligatorio)
    function getClientesOrdenApellido($parametros){
        $sql = "SELECT * FROM clientes ORDER BY apellido";
        if (isset($parametros['sort_dir'])) {
            $sql .= " " . $parametros['sort_dir'];
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    function addCliente($nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion)
    {
        $query = $this->db->prepare('INSERT INTO clientes (nombre, apellido, correo_electronico, fecha_nacimiento, dni, direccion) VALUES (?, ?, ?, ?, ?, ?)');
        $query->execute([$nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion]);
        return $this->db->lastInsertId();
    }
    function updateCliente($nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion,$id)
    {
        $query = $this->db->prepare('UPDATE clientes SET nombre = ?, apellido = ?, correo_electronico = ?, fecha_nacimiento = ?, dni = ?, direccion = ? WHERE id_cliente = ?');
        $query->execute([$nombre, $apellido, $correoElectronico, $fechaDeNacimiento, $dni, $direccion,$id]);
    }
    function deleteCliente($id)
    {
        $query = $this->db->prepare('DELETE FROM clientes WHERE id_cliente = ?');
        $query->execute([$id]);
    }
}
