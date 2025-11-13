<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectoryModel extends Model
{
    protected $DBGroup = 'default';

    /**
     * orderByLastName: 1 = order by lname, 0 = order by callsign
     */
    public function getDirectory(int $orderByLastName = 1): array
    {
        $db = \Config\Database::connect($this->DBGroup);

        // Call the stored procedure
        $query = $db->query('CALL GetDirectory(?)', [$orderByLastName]);

        // Fetch rows
        $rows = $query->getResultArray();

        // IMPORTANT for MySQL stored procedures: free/advance the result set
        $query->freeResult();
        // If you still get “Commands out of sync”, uncomment the next two lines:
        // while ($db->connID->more_results()) { $db->connID->next_result(); }

        return $rows;
    }
}