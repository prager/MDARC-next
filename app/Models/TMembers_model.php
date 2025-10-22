<?php namespace App\Models;

use CodeIgniter\Model;

class TMembers_model extends Model {
    protected $table         = 'tMembers';       // exact table name as in DB
    protected $primaryKey    = 'id_members';
    protected $returnType    = 'array';
    protected $allowedFields = [];

    public function getList(int $perPage = 20, string $sort = 'id_member', string $dir = 'ASC') {

        return $this->select('*')
            ->orderBy($sort, $dir)
            ->paginate($perPage, 'members');

    }

    public function getDetails(int $id): ?array {
        $row = $this->select("m.id_members, m.id_mem_types, tMemTypes.description, m.active, m.mem_type, m.cur_year, m.life_mem, m.fname, m.lname, m.callsign, m.license, m.address, m.city, m.state, m.zip, m.hard_news, m.hard_dir, m.paym_date, m.mem_since, m.mem_date, m.comment, m.h_phone, m.w_phone, m.email, m.cell, m.mem_card, m.ok_mem_dir, m.silent_date, m.silent_year, m.parent_primary")
        ->from('tMembers AS m')
        ->join('tMemTypes as t', 't.id_mem_types = m.id_member_types')
        ->join('tMembers as parent', 'parent.id_members = p.parent_primary')
        ->where('p.id', $id)
        ->get()->getRowArray();

        if(! $row) return null;

        return $row;
    }
}