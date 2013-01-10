<?php
class Chaplin_Async
{
	public static function setTimeout($intTime, Closure $closure)
	{
		switch(pcntl_fork()) {
			case -1:
				throw new Exception('Error forking');
			case 0:
				sleep($intTime);
				$closure();
				self::setTimeout($intTime, $closure);
			default:
		}
		
		while(pcntl_wait($status, WNOHANG OR WUNTRACED) > 0) {
  			usleep(5000);
 		}
	}

	public static function async(Closure $closure)
	{
		$pid = pcntl_fork();
		switch(pcntl_fork()) {
			case -1:
				throw new Exception('Error forking');
			case 0:
				return $closure();
			default:
		}
		
		while(pcntl_wait($status, WNOHANG OR WUNTRACED) > 0) {
  			usleep(5000);
 		}
	}
}