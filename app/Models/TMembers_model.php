<?php namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time; // ✅ this line is required

class TMembers_model extends Model {
    protected $table         = 'tMembers';       // exact table name as in DB
    protected $primaryKey    = 'id_members';
    protected $returnType    = 'array';
    protected $allowedFields = [];

    public function getList(int $perPage = 20, string $sort = 'lname', string $dir = 'ASC') {

        // Determine which cur_year(s) are “in season”
        $now   = Time::now('America/Los_Angeles'); // set your local TZ
        $year  = (int) $now->format('Y');

        return $this->select("
                tMembers.*,
                COALESCE(tMembers.parent_primary, 0) AS parent_primary
            ")
            ->where('tMembers.cur_year >=', $year)
            ->orderBy($sort, $dir)
            ->paginate($perPage, 'members');

    }
}