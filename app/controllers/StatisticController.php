<?php



class StatisticController extends \Phalcon\Mvc\Controller
{
	public function indexAction(){
        $type =$this->request->getPost("type");
        $date1 =$this->request->getPost("date1");
        $date2 =$this->request->getPost("date2");
       	if($type && $date1 && $date2){
       	$room = Room::count(
		    [
		        'date > ?1 AND date < ?2 AND type = ?3',
		        'bind' => [
		            1 => $date1,
                    2 => $date2,
                    3 => $type,
		        ],
		    ]
		);
       }
       if($type){
       		$room1 = Room::find(
		    [
		        'type = ?1',
		        'bind' => [
                    1 => $type,
		        ],
		    ]
		);
       		$room = count($room1);
       }
       if($date1){
       	$room = Room::count(
		    [
		        'date > ?1',
		        'bind' => [
                    1 => $date1,
		        ],
		    ]
		);
       }
       if($date2){
       	$room = Room::count(
		    [
		        'date < ?1',
		        'bind' => [
                    1 => $date2,
		        ],
		    ]
		);
       }
       if($date1 && $type){
       	$room = Room::count(
		    [
		        'date > ?1 AND type = ?2',
		        'bind' => [
                    1 => $date1,
                    2 => $type,
		        ],
		    ]
		);
       }
       if($date2 && $type){
       	$room = Room::count(
		    [
		        'date < ?1 AND type = ?2',
		        'bind' => [
                    1 => $date2,
                    2 => $type,
		        ],
		    ]
		);
       }
       if($date1 && $date2){
       	$room = Room::count(
		    [
		        'date > ?1 AND date < ?2',
		        'bind' => [
                    1 => $date1,
                    2 => $date2,
		        ],
		    ]
		);
       }
       if(!$type && !$date1 && !$date2){
       	$room = Room::count();
       }

		if($room){
		$this->view->room = $room1;
		$this->view->col = $room;
        $this->view->date1 = $date1;
        $this->view->date2 = $date2;
		}
		else{
			$this->flash->notice('По данным параметрам конференций и переговоров не проводилось');
			$this->flash->error('Произошла ошибка: '.$type." ".$date1." ".$date2);
		}

	}
}