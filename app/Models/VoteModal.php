<?php

namespace App\Models;

use CodeIgniter\Model;

class VoteModal extends Model
{
    protected $table      = 'votes';
    protected $primaryKey = 'sl';
    protected $returnType     = 'object';
    protected $allowedFields = ['vote_id', 'poll_id', 'ip', 'option', 'timestamp'];
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;



    public function insertVote($poll_id, $option)
    {
        $data = ["ip" => $this->get_client_ip(), "option" => $option, "poll_id" => $poll_id, "vote_id" => uniqid("VOTE"), "timestamp" => time()];
        if ($this->save($data)) {
            $poll = new ChoiceModal();
            $poll->increaseVote($poll_id, $option);
            return true;
        } else {
            return false;
        }
    }

    public function isUserEligible($poll_id)
    {
        $result = $this->where('poll_id', $poll_id)->where('ip', $this->get_client_ip())->findAll();
        if ($result == null) {
            return true;
        } else {
            return false;
            // return true;
        }
    }
    public function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
