<?php
namespace App\Models;
use CodeIgniter\Model;
class Cabecera_model extends Model
{
	protected $table = 'ventas_cabecera';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_prov_client','costo_envio','monto_efectivo', 'monto_transfer','modo_compra','id_cae','id_usuario','fecha', 'hora_registro', 'hora' ,'id_cliente', 'total_venta', 'tipo_pago', 'tipo_compra', 'fecha_pedido','hora_entrega' , 'estado'];

    public function getVentasCabecera(){
      $db = db_connect();
      $builder = $db->table('ventas_cabecera u');
      $builder->join('usuarios d','u.usuario_id = d.id');
      $ventas = $builder->get();
      return $ventas;
    }

    public function getVentasConClientes($filtros = [])
    {
        // Conectarse a la base de datos
        $db = db_connect();
        
        // Construir la consulta con el join
        $builder = $db->table($this->table . ' u');
        $builder->select("
            u.id, 
            u.nombre_prov_client AS nombre_cliente, 
            v.nombre AS nombre_vendedor, 
            u.estado, 
            u.total_venta,
            u.modo_compra,
            (CASE 
                WHEN u.tipo_compra = 'Pedido' THEN u.fecha_pedido 
                ELSE u.fecha 
            END) AS fecha, 
            u.hora AS hora, 
            u.tipo_pago            
        ");
        $builder->join('cliente c', 'u.id_cliente = c.id_cliente');
        $builder->join('usuarios v', 'u.id_usuario = v.id');
        $builder->whereNotIn('u.estado', ['Cancelado', 'Pendiente']);
    
        // Aplicar filtros opcionales
        if (!empty($filtros['estado'])) {
            $builder->where('u.estado', $filtros['estado']);
        }
    
        if (!empty($filtros['fecha_desde'])) {
            $fechaDesde = date('Y-m-d', strtotime($filtros['fecha_desde']));
            $builder->where("STR_TO_DATE(
                (CASE 
                    WHEN u.tipo_compra = 'Pedido' THEN u.fecha_pedido 
                    ELSE u.fecha 
                END), '%d-%m-%Y') >= ", $fechaDesde);
        }
    
        if (!empty($filtros['fecha_hasta'])) {
            $fechaHasta = date('Y-m-d', strtotime($filtros['fecha_hasta']));
            $builder->where("STR_TO_DATE(
                (CASE 
                    WHEN u.tipo_compra = 'Pedido' THEN u.fecha_pedido 
                    ELSE u.fecha 
                END), '%d-%m-%Y') <= ", $fechaHasta);
        }
    
        // Ejecutar la consulta y retornar el resultado como array
        $ventas = $builder->get();
        return $ventas->getResultArray();
    }
    

    public function getVentasPorClienteYFecha($idCliente, $fechaHoy)
    {
        // Conectarse a la base de datos
        $db = db_connect();
        // Construir la consulta con join y filtros
        $builder = $db->table($this->table . ' u');
        $builder->select('u.id, d.nombre, d.apellido, d.telefono, d.direccion, u.total_venta, u.fecha, u.hora, u.tipo_pago');
        $builder->where('u.id_cliente', $idCliente); // Filtrar por cliente
        $builder->where('u.fecha', $fechaHoy);       // Filtrar por fecha
        $builder->join('usuarios d', 'u.id_cliente = d.id'); // Relación con usuarios

        // Ejecutar la consulta y retornar los resultados como array
        $ventas = $builder->get();
        return $ventas->getResultArray();
    }
 
    public function getDetallesVenta($idVenta)
{
    $db = db_connect();
    $builder = $db->table('ventas_detalle u');
    
    $builder->select('
        d.id, 
        d.nombre, 
        u.cantidad, 
        u.precio, 
        u.total,
        u.aclaraciones, 
        c.id_cae, 
        c.cae, 
        c.vto_cae,
        v.monto_efectivo,
        v.monto_transfer,
        v.total_venta
    ');
    
    $builder->where('u.venta_id', $idVenta);
    
    // Relación con productos
    $builder->join('productos d', 'u.producto_id = d.id');

    // Relación con ventas_cabecera
    $builder->join('ventas_cabecera v', 'u.venta_id = v.id');

    // LEFT JOIN con la tabla CAE para incluir ventas sin facturar
    $builder->join('cae c', 'v.id_cae = c.id_cae', 'left');

    $result = $builder->get();
    
    return $result->getResultArray(); // Devuelve todos los resultados como array
}


    public function obtenerPedidos($filtros = [])
     {
         // Conectarse a la base de datos
         $db = db_connect();
     
         // Construir la consulta con los joins necesarios
         $builder = $db->table($this->table . ' u');
         $builder->select('u.id, 
         u.nombre_prov_client AS nombre_cliente, 
         c.telefono, 
         u.total_venta, 
         u.fecha, 
         u.hora, 
         u.tipo_pago, 
         u.estado, 
         u.fecha_pedido, 
         u.hora_entrega,
         u.modo_compra, 
         usuarios.nombre AS nombre_usuario');
         $builder->join('cliente c', 'u.id_cliente = c.id_cliente'); // Relación con cliente
         $builder->join('usuarios usuarios', 'u.id_usuario = usuarios.id'); // Relación con usuario
         $builder->where('u.tipo_compra', 'Pedido');
         if($filtros['estado']){
            $builder->where('u.estado', $filtros['estado']);
         }else{ 
         $builder->whereIn('u.estado', ['Facturada', 'Cancelado', 'Sin_Facturar']);//Todos los pedidos menos los Pendientes
         }
         if($filtros['fecha_hoy']){
            $builder->where('u.fecha_pedido', $filtros['fecha_hoy']);
         }
         if (!empty($filtros['fecha_desde'])) {
            $builder->where('STR_TO_DATE(u.fecha_pedido, "%d-%m-%Y") >=', date('Y-m-d', strtotime($filtros['fecha_desde'])));
        }
        if (!empty($filtros['fecha_hasta'])) {
            $builder->where('STR_TO_DATE(u.fecha_pedido, "%d-%m-%Y") <=', date('Y-m-d', strtotime($filtros['fecha_hasta'])));
        }
        if (!empty($filtros['id_usuario'])) {
            $builder->where('u.id_usuario', $filtros['id_usuario']);
        }
         // Ejecutar la consulta y retornar el resultado como array
         $ventas = $builder->get();
         return $ventas->getResultArray();
     }

    //Elimina de forma fisica el turno porque el Cliente del Soft asi lo quiere.
    public function eliminarPedido($id_pedido)
    {
    return $this->db->table('ventas_cabecera')->delete(['id' => $id_pedido]);
    }
    

    // Cambia el estado del Pedido
    public function fiadoEntregado($id_pedido,$Costo_envio)
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaHoy = date('d-m-Y');
        $horaEntrega = date('H:i:s');
        return $this->update($id_pedido, [
                'estado' => 'Entregado',
                'fecha_pedido' => $fechaHoy,
                'hora_entrega' => $horaEntrega,
                'costo_envio' => $Costo_envio
            ]); 
                                            
    }

    // Actualizar la cabecera de la venta con el estado "facturado" y el ID del CAE
    public function facturado($id_cabecera, $new_cae)
    {
        // Establecer zona horaria y obtener fecha/hora en formato correcto
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaHoy = date('d-m-Y');
        $horaEntrega = date('H:i:s');
        return $this->update($id_cabecera, [
            'estado' => 'Facturada', // Asegúrate de que el campo "estado" existe en la base de datos
            'id_cae' => $new_cae, // Guarda el ID del CAE en la cabecera
            'fecha_pedido' => $fechaHoy,
            'hora_entrega' => $horaEntrega
        ]);
    }
     
}