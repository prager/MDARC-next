<?php namespace App\Models;

use CodeIgniter\Model;

class TMembers_model extends Model {
    protected $table         = 'tMembers m';   // your table name
    protected $primaryKey    = 'id_members';
    protected $returnType    = 'array';

    public function getList(int $perPage = 20, string $group = 'members') {

        $dateColumn  = 'm.cur_year';
        $now   = new \DateTime('now');
        $year  = (int)$now->format('Y');
        $month = (int)$now->format('m');

        $start = new \DateTime("$year-01-01 00:00:00");

        if ($month >= 10) {
            // October–December → include current + next year
            $end = new \DateTime(($year + 2) . "-01-01 00:00:00");
        } else {
            // Otherwise only current year
            $end = new \DateTime(($year + 1) . "-01-01 00:00:00");
        }

        return $this->select("m.id_members, t.description, m.active, m.mem_type, m.cur_year, m.life_mem, m.fname, m.lname, m.callsign, m.license, m.address, m.city, m.state, m.zip, m.hard_news, m.hard_dir, m.paym_date, m.mem_since, m.mem_date, m.comment, m.h_phone, m.w_phone, m.email, m.cell, m.mem_card, m.ok_mem_dir, m.silent_date, m.silent_year, m.parent_primary")
        ->join('tMembers AS children', 'children.parent_primary = m.id_members', 'left')
        ->join('tMemTypes as t', 't.id_mem_types = m.id_mem_types')
        ->where("$dateColumn >=", $start->format('Y-m-d H:i:s'))
		->where("$dateColumn <",  $end->format('Y-m-d H:i:s'))
        ->groupBy('m.id_members')
        ->paginate($perPage, $group);
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