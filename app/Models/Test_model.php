<?php namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products p';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name','category_id','supplier_id','parent_id','price','description','created_at'];

    public function listWithParent(int $perPage = 25, string $group = 'products')
    {
        return $this->select("
                    p.id, p.name, p.price, p.created_at, p.parent_id,
                    c.name AS category_name,
                    s.name AS supplier_name,
                    parent.name AS parent_name
                ")
                ->join('categories c', 'c.id = p.category_id', 'left')
                ->join('suppliers s',  's.id = p.supplier_id',  'left')
                ->join('products parent', 'parent.id = p.parent_id', 'left')
                ->orderBy('p.created_at', 'DESC')
                ->groupBy('p.id')
                ->paginate($perPage, $group);
    }

    public function findDetail(int $id): array
    {
        $row = $this->select("
                    p.*, 
                    c.name AS category_name,
                    s.name AS supplier_name,
                    parent.id AS parent_id, parent.name AS parent_name
                ")
                ->join('categories c', 'c.id = p.category_id', 'left')
                ->join('suppliers s',  's.id = p.supplier_id',  'left')
                ->join('products parent', 'parent.id = p.parent_id', 'left')
                ->where('p.id', $id)
                ->get()->getRowArray();

        if (! $row) return [];

        // children (sub-products)
        $db = db_connect();
        $children = $db->table('products')
                       ->select('id, name, price')
                       ->where('parent_id', $id)
                       ->orderBy('name')
                       ->get()->getResultArray();

        $row['children'] = $children;
        return $row;
    }
}
