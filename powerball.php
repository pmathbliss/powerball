<?php

class PowerBall
{

	public $drawings = 0;
	public $results = array(
		"win 5+P"=>0,
		"win 5"=>0,
		"win 4+P"=>0,
		"win 4"=>0,
		"win 3+P"=>0,
		"win 3"=>0,
		"win 2+P"=>0,
		"win 2"=>0,
		"win 1+P"=>0,
		"win 0+P"=>0,
		"win 1"=>0,
		"win 0"=>0,
	);
	public $ticket = array();
	public $tickets = array();
	public $white_balls = array();
	public $power_balls = array();
	public $current_numbers = array('white_balls'=>array(),'power_ball'=>array());
	
	public function draw()
	{
		$this->set_white_balls();
		$this->set_power_balls();
		$this->current_numbers['white_balls'] = $this->draw_numbers(5,$this->white_balls);
		$this->current_numbers['power_ball'] = $this->draw_numbers(1,$this->power_balls);
		//$this->print_numbers();
		$this->result();
	}
	
	public function result()
	{
		$this->drawings++;
		foreach($this->tickets as $ticket)
		{
			$r = array_intersect($ticket['white_balls'],$this->current_numbers['white_balls']);
			
			$whiteballs = count($r);
			
			$powerball = $ticket['power_ball'] == $this->current_numbers['power_ball'];
			
			$key = "win {$whiteballs}".($powerball?'+P':'');
			$this->results[$key]++;
		}
		if($this->results["win 5+P"] > 0)
		{
			echo "Drawings: {$this->drawings}".PHP_EOL;
			echo "Cost: ";
			echo ($this->drawings*$this->ticketcost*$this->numberTickets);
			echo PHP_EOL;
			$this->print_results();
			die("Won Lotto");
		}
		
		/*if($this->results["win 5"] > 0)
		{
			echo "Drawings: {$this->drawings}".PHP_EOL;
			echo "Cost: ";
			echo ($this->drawings*$this->ticketcost*$this->numberTickets);
			echo PHP_EOL;
			$this->print_results();
			die("Won 2Million");
		}*/
	}
	
	public function draw_numbers($number,$numbers)
	{	srand();	
		$k = array_rand($numbers,$number);
		
		if(is_array($k))
		{
			foreach($k as $i=>&$v)
			{
				$v++;
			}
		}
		else
		{
			$k++;
		}
		return $k;
	}
	
	public function draw_numbers1($number,$numbers)
	{		
		shuffle($numbers);
		$elements = array_slice($numbers, 0, $number);
		return $number == 1?$elements[0]:$elements;
		
	}

	public function set_white_balls($max=69)
	{
		$this->white_balls = range(1,$max);
	}
	
	public function set_power_balls($max=35)
	{
		$this->power_balls = range(1,$max);
	}
	
	public function print_numbers()
	{
		echo implode(",",$this->current_numbers['white_balls']) . ",{$this->current_numbers['power_ball']}".PHP_EOL;
	}
	
	public function set_ticket($white_balls,$power_ball)
	{
		$this->ticket = array("white_balls"=>$white_balls,"power_ball"=>$power_ball);
	}
	
	public function add_ticket($white_balls,$power_ball)
	{
		$this->tickets[] = array("white_balls"=>$white_balls,"power_ball"=>$power_ball);
	}
	
	public function print_results()
	{
		foreach($this->results as $key => $value)
		{
			$perc = number_format(100 * ($value/$this->drawings),6);

			echo "{$key}\t{$value}\t{$perc}".PHP_EOL;
		}
	}
	
	public function set_vars($numberTickets, $drawings, $ticketcost)
	{
		$this->numberTickets = $numberTickets;
		$this->ticketcost = $ticketcost;
		
		//select n tickets
		for($i=0;$i<$numberTickets;$i++)
		{
			$this->add_ticket($this->draw_numbers(5,range(1,69)),$this->draw_numbers(1,range(1,35)));
		}

		for($i=1;$i<$drawings+1;$i++)
		{
			$this->draw();//exit;
			if($i%100 == 0)
			{
				//$curr = microtime(true) - $time;
				//echo $i . "\t" . $curr .  PHP_EOL;
			}
		}
	}

}
//@todo add the payout shedule and return the exported
$pb = new PowerBall();

$pb->set_vars(500,2000,2);exit;

$time = microtime(true);