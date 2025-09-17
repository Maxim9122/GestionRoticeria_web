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


    public function obtenerComidas($filtros = [])
    {
        $db = db_connect();

    $builder = $db->table('ventas_detalle vd');
    $builder->select("
    p.categoria_id,
    cat.descripcion AS nombre_categoria,
    SUM(
        vd.cantidad * 
        CASE 
            WHEN p.nombre LIKE '%x12u%' THEN 12
            WHEN p.nombre LIKE '%x6u%' THEN 6
            WHEN p.nombre LIKE '%x1u%' THEN 1
            ELSE 1
        END
    ) AS total_vendidos
    ");


    // Join con cabecera para la fecha y otros datos
    $builder->join('ventas_cabecera vc', 'vd.venta_id = vc.id');
    // Join con productos para acceder a la categoría
    $builder->join('productos p', 'vd.producto_id = p.id');
    // Join con categorías para obtener el nombre
    $builder->join('categorias cat', 'p.categoria_id = cat.categoria_id');

    // Excluir estados no deseados
    $builder->whereNotIn('vc.estado', ['Cancelado', 'Pendiente']);

    // Filtro por categoría específica si se indica
    if (!empty($filtros['categoria_id'])) {
        $builder->where('p.categoria_id', $filtros['categoria_id']);
    }

    // Filtro por fecha desde
    if (!empty($filtros['fecha_desde'])) {
        $fechaDesde = date('Y-m-d', strtotime($filtros['fecha_desde']));
        $builder->where("STR_TO_DATE(
            (CASE 
                WHEN vc.tipo_compra = 'Pedido' THEN vc.fecha_pedido 
                ELSE vc.fecha 
            END), '%d-%m-%Y') >=", $fechaDesde);
    }

    // Filtro por fecha hasta
    if (!empty($filtros['fecha_hasta'])) {
        $fechaHasta = date('Y-m-d', strtotime($filtros['fecha_hasta']));
        $builder->where("STR_TO_DATE(
            (CASE 
                WHEN vc.tipo_compra = 'Pedido' THEN vc.fecha_pedido 
                ELSE vc.fecha 
            END), '%d-%m-%Y') <=", $fechaHasta);
    }

    // Agrupar por categoría
    $builder->groupBy('p.categoria_id, cat.descripcion');

    $query = $builder->get();
    return $query->getResultArray();
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
        u.fecha_pedido AS fecha, 
        u.hora_entrega AS hora, 
        u.tipo_pago,
        u.costo_envio          
    ");
    $builder->join('cliente c', 'u.id_cliente = c.id_cliente');
    $builder->join('usuarios v', 'u.id_usuario = v.id');
    $builder->whereNotIn('u.estado', ['Pendiente']);

    // Aplicar filtros opcionales
    if (!empty($filtros['modo_compra'])) {
        $builder->where('u.modo_compra', $filtros['modo_compra']);
    }

    if (!empty($filtros['estado'])) {
        $builder->where('u.estado', $filtros['estado']);
    }

    // Filtro por fecha DESDE
    if (!empty($filtros['fecha_desde'])) {
        $fechaDesde = date('Y-m-d', strtotime($filtros['fecha_desde']));
        $horaDesde = !empty($filtros['hora_desde']) ? $filtros['hora_desde'] : '00:00';
        $desdeCompleto = $fechaDesde . ' ' . $horaDesde . ':00';

        $builder->where("STR_TO_DATE(CONCAT(u.fecha_pedido, ' ', u.hora_entrega), '%d-%m-%Y %H:%i') >=", $desdeCompleto);
    }
    
    // Filtro por fecha HASTA
    if (!empty($filtros['fecha_hasta'])) {
        $fechaHasta = date('Y-m-d', strtotime($filtros['fecha_hasta']));
        $horaHasta = !empty($filtros['hora_hasta']) ? $filtros['hora_hasta'] : '23:59';
        $hastaCompleto = $fechaHasta . ' ' . $horaHasta . ':59';

        $builder->where("STR_TO_DATE(CONCAT(u.fecha_pedido, ' ', u.hora_entrega), '%d-%m-%Y %H:%i') <=", $hastaCompleto);
    }
        
    // Filtro por cliente
    if (!empty($filtros['id_cliente'])) {
        $builder->where('u.id_cliente', $filtros['id_cliente']);
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