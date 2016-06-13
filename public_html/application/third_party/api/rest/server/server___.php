<?php 
// How often to poll, in microseconds (1,000,000 µs equals 1 s)
define('MESSAGE_POLL_MICROSECONDS', 500000);
 
// How long to keep the Long Poll open, in seconds
define('MESSAGE_TIMEOUT_SECONDS', 15);
 
// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
  
// Close the session prematurely to avoid usleep() from locking other requests
session_write_close();
 
// Automatically die after timeout (plus buffer)
set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);
 
// Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
$counter = MESSAGE_TIMEOUT_SECONDS;

// where does the data come from ? In real world this would be a SQL query or something
$data_source_file = 'write/resultado.json';

// if ajax request has send a timestamp, then $last_ajax_call = timestamp, else $last_ajax_call = null
$last_ajax_call = isset($_POST['timestamp']) ? (int)$_POST['timestamp'] : 0;
	
// get timestamp of when file has been changed the last time
$last_change_in_data_file = filemtime($data_source_file);

ob_clean();
clearstatcache();

// main loop
while($counter > 0)
{
	ob_clean();
	clearstatcache();
	
	// Check for new data (not illustrated)
    if($last_change_in_data_file > $last_ajax_call)
    {
		// get content of data.txt
		$data = file_get_contents($data_source_file);
		
		//print_r('enquanto->'.$last_change_in_data_file.' for maior que->'.$last_ajax_call);
		
		$data = base64_decode($data);
			
		// put data.txt's content and timestamp of last data.txt change into array
		$result = array(
			'data_from_file' => $data,
			'timestamp' => $last_change_in_data_file
		);

		// encode to JSON, render the result (for AJAX)
		// trava de seguranca para o arquivo nao ser lido por URL
		/*if($_POST['act4turbLS'] AND $_POST['act4turbLS']=="slbrut4tca")
		{
		   echo json_encode($result);
		}*/
		
		// leave this loop step
		break;
	}
    else
    {
		ob_clean();
		flush();
		
        // Otherwise, sleep for the specified time, after which the loop runs again
        usleep(MESSAGE_POLL_MICROSECONDS);
 
        // Decrement seconds from counter (the interval was set in µs, see above)
        $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
    }
}
 
// If we've made it this far, we've either timed out or have some data to deliver to the client
if(isset($result) AND !empty($result))
{
	ob_clean();
	flush();
	// Send data to client; you may want to precede it by a mime type definition header, eg. in the case of JSON or XML
    echo json_encode($result);
}

/*
while(true) 
{
	// PHP caches file data, like requesting the size of a file, by default. clearstatcache() clears that cache
	//clearstatcache();
	
	// if ajax request has send a timestamp, then $last_ajax_call = timestamp, else $last_ajax_call = null
	$last_ajax_call = isset($_POST['timestamp']) ? (int)$_POST['timestamp'] : 0;
		
	// get timestamp of when file has been changed the last time
	$last_change_in_data_file = filemtime($data_source_file);

	if($last_change_in_data_file > $last_ajax_call)
	{
		// get content of data.txt
		$data = file_get_contents($data_source_file);
		
		//print_r('enquanto->'.$last_change_in_data_file.' for maior que->'.$last_ajax_call);
		
		if(!empty($data))
		{
			$data = base64_decode($data);
			
			// put data.txt's content and timestamp of last data.txt change into array
			$result = array(
				'data_from_file' => $data,
				'timestamp' => $last_change_in_data_file
			);

			// encode to JSON, render the result (for AJAX)
			// trava de seguranca para o arquivo nao ser lido por URL
			if($_POST['act4turbLS'] AND $_POST['act4turbLS']=="slbrut4tca")
			{
			   echo json_encode($result);
			}
			
			// leave this loop step
			break;
		}
	}else{
		// Otherwise, sleep for the specified time, after which the loop runs again
        usleep(MESSAGE_POLL_MICROSECONDS);
 
        // Decrement seconds from counter (the interval was set in µs, see above)
        $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
	}
}
*/