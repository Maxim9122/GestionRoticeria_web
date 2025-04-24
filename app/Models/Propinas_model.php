<?php
namespace App\Models;
use CodeIgniter\Model;

class Propinas_model extends Model
{
    protected $table = 'propinas';
    protected $primaryKey = 'id_propina';
    protected $allowedFields = ['monto', 'fecha', 'hora'];

    public function obtenerPropinas($filtros = [])
{
    $db = db_connect();

    $builder = $db->table('propinas p');
    $builder->select("
        p.id_propina,
        p.fecha,
        p.hora,
        p.monto
    ");

    // Filtro por fecha desde
    if (!empty($filtros['fecha_desde'])) {
        $fechaDesde = date('Y-m-d', strtotime($filtros['fecha_desde']));
        $builder->where("STR_TO_DATE(p.fecha, '%d-%m-%Y') >=", $fechaDesde);
    }

    // Filtro por fecha hasta
    if (!empty($filtros['fecha_hasta'])) {
        $fechaHasta = date('Y-m-d', strtotime($filtros['fecha_hasta']));
        $builder->where("STR_TO_DATE(p.fecha, '%d-%m-%Y') <=", $fechaHasta);
    }

    // Filtro por hora desde
    if (!empty($filtros['hora_desde'])) {
        $builder->where('STR_TO_DATE(p.hora, "%H:%i") >=', date('H:i', strtotime($filtros['hora_desde'])));
    }

    // Filtro por hora hasta
    if (!empty($filtros['hora_hasta'])) {
        $builder->where('STR_TO_DATE(p.hora, "%H:%i") <=', date('H:i', strtotime($filtros['hora_hasta'])));
    }

    $query = $builder->get();
    return $query->getResultArray();
}

}
