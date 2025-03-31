<?php
namespace App\Models;
use CodeIgniter\Model;
class Cae_model extends Model
{
	protected $table = 'cae';
    protected $primaryKey = 'id_cae';
    protected $allowedFields = ['id_cae','cae','vto_cae'];
}