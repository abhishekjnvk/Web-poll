<?php

namespace App\Controllers;

use App\Models\ChoiceModal;
use App\Models\PollModal;
use App\Models\VoteModal;
class Home extends BaseController
{
	public function index()
	{
		$session = session();
		$poll = new PollModal();
		$data['live_poll'] = $poll->findAll();
		return view('index', $data);
	}
	public function add()
	{
		$session = session();
		$session->remove('page_message');
		$choice = new ChoiceModal();
		$poll = new PollModal();
		$vote=new VoteModal();
		if ( !isset($_POST['options']) || !isset($_POST['poll_title'])) {
			$session->setFlashdata('page_message', "Missing Required Filed 1");
		} else {

			if ($_POST['options'] == null || $_POST['poll_hash'] == null || $_POST['poll_title'] == null) {
				$session->setFlashdata('page_message', "Missing Required Filed");
			} else {
				$poll_title = $_POST['poll_title'];
				$options = $_POST['options'];
				if (isset($_POST['visibility'])) {
					if ($_POST['visibility'] == 'on') {
						$visibility = 1;
					} else {
						$visibility = 0;
					}
				} else {
					$visibility = 0;
				}
				$poll_id = uniqid("Poll");
				$data = [
					'poll_id' => $poll_id,
					'timestamp' => time(),
					'title' => $poll_title,
					'description' => "",
					'visibility' => $visibility,
					'validity' => time() + 7200,
					'ip' => $vote->get_client_ip()
				];
				if ($poll->save($data)) {
					$sl = 0;
					foreach ($options as $option) {
						$sl++;
						$option_data = ['poll_id' => $poll_id, 'option' => $sl, 'value' => $option];
						$choice->save($option_data);
					}
					$session->setFlashdata('page_message', "Poll Successfully Created with Poll ID: ".$poll_id."<br><center> <a target='_blank' href='".getenv('baseURL')."/view/poll/$poll_id'>Click Here</a></center>");
				}
			}
		}
		return redirect()->to(getenv('baseURL'));
	}
}
