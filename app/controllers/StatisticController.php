<?php



class StatisticController extends \Phalcon\Mvc\Controller
{
	public function array_row_remove($array, $row_index)
	{
	    if (is_array($array) && array_key_exists($row_index, $array))
	    {
	        unset($array[$row_index]);
	        $array = array_values($array);
	    }

	    return $array;
	}

	public function indexAction(){
        $type =$this->request->getPost("type");
        $date1 =$this->request->getPost("date1");
        $date2 =$this->request->getPost("date2");
       	if($type && $date1 && $date2){
       	$room1 = Room::find(
		    [
		        'date >= ?1 AND date <= ?2 AND type = ?3',
		        'bind' => [
		            1 => $date1,
                    2 => $date2,
                    3 => $type,
		        ],
		    ]
		);
		$room = count($room1);
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
       	$room1 = Room::find(
		    [
		        'date >= ?1',
		        'bind' => [
                    1 => $date1,
		        ],
		    ]
		);
		$room = count($room1);
       }
       if($date2){
       	$room1 = Room::find(
		    [
		        'date <= ?1',
		        'bind' => [
                    1 => $date2,
		        ],
		    ]
		);
		$room = count($room1);
       }
       if($date1 && $type){
       	$room1 = Room::find(
		    [
		        'date >= ?1 AND type = ?2',
		        'bind' => [
                    1 => $date1,
                    2 => $type,
		        ],
		    ]
		);
		$room = count($room1);
       }
       if($date2 && $type){
       	$room1 = Room::find(
		    [
		        'date <= ?1 AND type = ?2',
		        'bind' => [
                    1 => $date2,
                    2 => $type,
		        ],
		    ]
		);
		$room = count($room1);
       }
       if($date1 && $date2){
       	$room1 = Room::find(
		    [
		        'date >= ?1 AND date <= ?2',
		        'bind' => [
                    1 => $date1,
                    2 => $date2,
		        ],
		    ]
		);
		$room = count($room1);
       }
       if(!$type && !$date1 && !$date2){
       	$room1 = Room::find();
       	$room = count($room1);
       }
       	$gr = array();
       	$gr1 = array();
		if($room){
			$guest = UserHasRoom::find([
		        'role = ?1',
		        'bind' => [
                    1 => 'guest',
		        ],
		    ]
			);
			
			foreach ($room1 as $item) {
				$gr1[] = array($item->date,0);
			}
			for ($i=0; $i<count($gr1); $i++){
				for ($j=0; $j<count($gr1); $j++){
					if(isset($gr1[$i][1]))
				{
					if($gr1[$i][0]==$gr1[$j][0]){
						//echo $gr1[$i][0]." ".$gr1[$j][0]."<br>";
						$gr1[$i][1]=$gr1[$i][1]+1;
						if($gr1[$j][1]>1){
							unset($gr1[$i]);
						}
					}
				}}
			}
			$gu = array();
       		$gu1 = array();
			foreach ($room1 as $a) {
				foreach ($guest as $b) {
					if($a->room_id == $b->room_room_id){
						$gu1[] = array($a->date,0);
					}
				}
			}
			for ($i=0; $i<count($gu1); $i++){
				for ($j=0; $j<count($gu1); $j++){
					if(isset($gu1[$i][1]))
				{
					if($gu1[$i][0]==$gu1[$j][0]){
						//echo $gr1[$i][0]." ".$gr1[$j][0]."<br>";
						$gu1[$i][1]=$gu1[$i][1]+1;
						if($gu1[$j][1]>1){
							unset($gu1[$i]);
						}
					}
				}}
			}
			
			for ($i=0; $i<count($gr1); $i++){
				echo $gr1[$i][0]." ".$gr1[$i][1]."<br>";
				if($gr1[$i][1]!="")
				{
					$gr[] = array($gr1[$i][0], $gr1[$i][1]);
				}
			}
			
			for ($i=0; $i<count($gu1); $i++){
				
				if($gu1[$i][0]!="" || $gu1[$i][1]!="")
				{
					$gu[] = array($gu1[$i][0], $gu1[$i][1]);
				}
			}

			asort($gr);
			asort($gu);
			$summ = 0;
			foreach($gu as $v)
				  $summ += $v[1];
				  
			$this->view->summ = $summ;
			$this->view->gu = $gu;
			$this->view->gr = $gr;
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