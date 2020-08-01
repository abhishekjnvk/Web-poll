<?php

namespace App\Models;

use CodeIgniter\Model;

class PollModal extends Model
{
    protected $table      = 'poll';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['poll_id', 'timestamp', 'title', 'description', 'visibility', 'validity', 'ip'];
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;

    public function isPollValid($poll_id)
    {
        $poll = $this->where('poll_id', $poll_id)->first();
        if ($poll != null) {
            if ($poll->validity > time()) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function isPollExist($poll_id)
    {
        $poll = $this->where('poll_id', $poll_id)->first();
        if ($poll != null) {
            return true;
        }else{
            return false;
        }
    }
}
