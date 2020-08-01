<?php

namespace App\Controllers;
use App\Models\ChoiceModal;
use App\Models\PollModal;
use App\Models\VoteModal;

class View extends BaseController
{

    public function __construct()
    {
        $session = session();
        $session->remove('page_message');
    }
    public function index()
    {
        return redirect()->to(getenv('baseURL')); 
    }
    public function poll($id=null){
        if($id==null){
            return redirect()->to(getenv('baseURL')); 
        }
        $poll = new PollModal();
        $choice = new ChoiceModal();
        $vote=new VoteModal();
        $poll_fetched=$poll->where("poll_id",$id)->first();
        $data['poll_data']=$poll_fetched;
        $data['isUserEligible']=$vote->isUserEligible($id);
        $choice_fetched=$choice->where("poll_id",$id)->orderby('vote Desc')->find();
        $data['choice_data']=$choice_fetched;
        $data['total_vote']=$choice->countPoll($id);
        return view('poll',$data);
    }
    public function result($id=null)
    {
        if($id==null){
            return redirect()->to(getenv('baseURL')); 
        }
        $poll = new PollModal();
        $choice = new ChoiceModal();
        $poll_fetched=$poll->where("poll_id",$id)->first();
        $data['poll_data']=$poll_fetched;
        $choice_fetched=$choice->where("poll_id",$id)->orderby('vote Desc')->find();
        $data['choice_data']=$choice_fetched;
        $data['total_vote']=$choice->countPoll($id);
        return view('result',$data);
    }
}
