<?php

class PowerBall
{

	public $drawings = 0;
	public $results = array(
		"win lotto"=>0,
		"win 5"=>0,
		"win 4+P"=>0,
		"win 4"=>0,
		"win 3+P"=>0,
		"win 2+P"=>0,
		"win 1+P"=>0,
		"win P"=>0,
	);
	public $ticket = array();
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
		$r = array_intersect($this->ticket['white_balls'],$this->current_numbers['white_balls']);
		
		$whiteballs = count($r);
		
		$powerball = $this->ticket['power_ball'] == $this->current_numbers['power_ball'];
		if($whiteballs == 5 && $powerball)
		{
			$this->results["win lotto"]++;
		}
		elseif($whiteballs == 5)
		{
			$this->results["win 5"]++;
		}
		elseif($whiteballs == 4 && $powerball)
		{
			$this->results["win 4+P"]++;
		}
		elseif($whiteballs == 4 )
		{
			$this->results["win 4"]++;
		}
		elseif($whiteballs == 3 && $powerball)
		{
			$this->results["win 3+P"]++;
		}
		elseif($whiteballs == 2 && $powerball)
		{
			$this->results["win 2+P"]++;
		}
		elseif($whiteballs == 1 && $powerball)
		{
			$this->results["win 1+P"]++;
		}
		elseif($powerball)
		{
			$this->results["win P"]++;
		}
	}
	
	public function draw_numbers($number=1,$numbers)
	{		
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

	public function set_white_balls($max=59)
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

}

$pb = new PowerBall();
$time = microtime(true);
$pb->set_ticket(array(23,44,55,56,68),4);

for($i=0;$i<500000;$i++)
{
	$pb->draw();//exit;
}

print_r($pb->results);
echo PHP_EOL;
echo microtime(true) - $time;