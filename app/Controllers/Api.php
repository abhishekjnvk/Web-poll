<?php

namespace App\Controllers;

use App\Models\ChoiceModal;
use App\Models\PollModal;
use App\Models\VoteModal;

class Api extends BaseController
{
    public function index()
    {
        $vote = new VoteModal();
        $poll = new PollModal();
        $myObj = new \stdClass();
        $myObj->abhi = $poll->isPollValid("123456");
        $myObj->status = $vote->isUserEligible("123456");;
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
    function voteNow()
    {
        if (isset($_POST['pole_id']) && isset($_POST['option'])) {
            $poll_id = $_POST['pole_id'];
            $option = $_POST['option'];
            $vote = new VoteModal();
            $poll = new PollModal();
            if ($poll->isPollExist($poll_id)) {
                if ($poll->isPollValid($poll_id)) {
                    if ($vote->isUserEligible($poll_id)) {
                        if ($vote->insertVote($poll_id, $option)) {
                            echo "Voted";
                        } else {
                            echo "Something Went Wrong From Our Side";
                        }
                    } else {
                        echo "You Already Votes For this poll";
                    }
                } else {
                    echo "Poll Expired";
                }
            } else {
                echo "Invalid Poll Or Doesn't Exist";
            }
        }else{
            echo "Missing Fields";
        }
    }
}
