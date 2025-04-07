<?php
namespace App\Models;
use CodeIgniter\Model;
class categoria_model extends Model
{
	protected $table = 'categorias';
    protected $primaryKey = 'categoria_id';
    protected $allowedFields = ['descripcion','eliminado'];
    public function getCategoria(){

    	return $this->findAll();
    }
    //trae la lista de las categorias
    public function getProdBaja($eliminado){

    	return $this->where('eliminado',$eliminado)->findAll();
    }
    //verifica el id de la categoria para cambiar su estado
    public function getEliminar($categoria_id){

    	return $this->where('categoria_id',$categoria_id)->first();
    }
    public function getEdit($categoria_id){

    	return $this->where('categoria_id',$categoria_id)->first();
    }
    //actualiza la categoria
    public function updateDatosCateg($categoria_id,$datos){

    	return $this->update($categoria_id,$datos);
    }
    public function getCateg($categoria_id){

    	return $this->where('categoria_id',$categoria_id)->first();
    }

    // En categoria_model.php
public function getCategoriaPorProducto($producto_id) {
    $builder = $this->db->table('productos');
    $builder->select('categorias.descripcion as categoria');
    $builder->join('categorias', 'categorias.categoria_id = productos.categoria_id');
    $builder->where('productos.id', $producto_id);
    $query = $builder->get();
    
    if ($query->getNumRows() > 0) {
        return $query->getRow()->categoria; // Devuelve directamente el string
    }
    return null;
}

}