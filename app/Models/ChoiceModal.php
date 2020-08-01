<?php

namespace App\Models;

use CodeIgniter\Model;

class ChoiceModal extends Model
{
    protected $table      = 'choice';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['poll_id', 'option', 'value'];
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;



    public function countPoll($poll_id)
    {
        $db = db_connect();
        $query=$db->query("SELECT sum(vote) FROM $this->table WHERE poll_id='$poll_id'");
        foreach ($query->getResult('array') as $row) {
            return $row['sum(vote)'];
        }
    }
    public function increaseVote($poll_id,$option){
        $db = db_connect();
        $query=$db->query("UPDATE $this->table set vote=vote+1 WHERE `poll_id`='$poll_id' AND `option`=$option");
    }
    
}
