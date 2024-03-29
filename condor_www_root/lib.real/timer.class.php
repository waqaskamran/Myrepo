<?php

/**
 * Desc:
 *		This class is a timer and should be used internally only. It takes
 *		a reference to applog.1.php, computes the elapsed time between the
 *		calls to Timer_Start and Timer_Stop, and writes them to the log
 *		file.
 *
 * Auth:
 *		G. Smoot
 *
 * Date:
 *		02/10/05
 */
class Timer
{
	private $arr_times;
	private $log;
	private $timer_limit;	// Minimum time timer must run before it logs the run.

	/**
	 * Desc:
	 *		This is the constructor and is used to initialize the local vars.
	 *
	 * Param:
	 *		$log - This is reference to the applog.1.php.
	 *
	 * Return:
	 * 	none.
	 */
	public function __construct($log, $timer_limit = 0)
	{
		$this->arr_times = array();
		$this->log = $log;
		$this->timer_limit = $timer_limit;
	}


	/**
	 * Desc:
	 *		This is function is called to start the running time.
	 *
	 * Param:
	 *		$time_name - This is a unique name for the time you are keep track of.
	 *
	 * Return:
	 * 	none.
	 */
	public function Timer_Start($time_name)
	{
		// set name and time
		$this->arr_times = array_merge($this->arr_times, array($time_name => microtime()));
	}


	/**
	 * Desc:
	 *		This is function is called to stop the running time, compute the time, and write 
	 *		to a log.
	 *
	 * Param:
	 *		$time_name - This is a unique name for the time you are calculating. 
	 *
	 * Return:
	 * 	none.
	 */
	public function Timer_Stop($timer_name)
	{
		if (isset($this->arr_times[$timer_name]))
		{
			$stop_time = microtime();

			$elapsed_time = number_format(((substr($stop_time, 0, 9) + substr($stop_time, -10))
								- (substr($this->arr_times[$timer_name], 0, 9))
									- (substr($this->arr_times[$timer_name], -10))), 4);

			$this->elapsed_time[$timer_name] = $elapsed_time;
			
			$log_text = "Elapsed time for [" . $timer_name . "]  is " . $elapsed_time . " seconds.";
		}
		else
		{
			$log_text = "Elapsed time for [" . $timer_name . "] has no start time.";
		}

		if($elapsed_time >= $this->timer_limit)
		{
		$this->log->Write($log_text, LOG_INFO);
	}
	}
	
	public function Get_Elapsed($timer_name)
	{
		if( isset( $this->elapsed_time[$timer_name] ) )
		{
			$return = $this->elapsed_time[$timer_name];
		}
		else 
		{
			$return = NULL;
		}

		return $return;
	}
}

?>
