<?php 
/*SIMPLEMAILER 
 * VERSION 1.4
 * Handles simple email functions for a contact form
 * 
 * CHANGELOG:
 * 1.1 added placeholder function (progess saves to "greymatter" cookie)
 * 1.2 Changes the use of Cookies to use Sessions instead (Added session_start() to Construct. Not sure if this is good?)
 * 1.4 added default config values for name, email, message, and submitID. Single param of string (send to) is acceptable if all other defaults are used.
 * 
 * TODO:
 * Enable mailer to verify wordpress nonce
 */

class simpleEmail extends greymatter
{

	function __construct($input)
	{
		session_start();
		//set variables
		$this->send_to = (isset($input['send_to'])) ? $input['send_to']: FALSE;
		
		$this->name = (isset($input['name'])) ? $_POST[$input['name']] : $_POST['contact-name'];
		$this->email = (isset($input['email'])) ? $_POST[$input['email']] : $_POST['contact-email'];
		$this->message = (isset($input['message'])) ? $_POST[$input['message']] : $_POST['contact-message'];
		$this->submit_id = (isset($input['submit_id']))? $input['submit_id'] : 'contact-submit';

		$this->inputs = array(
								'name'		=> $this->name, 
								'email' 	=> $this->email,
								'message' 	=> $this->message
							);
		//Error Check
		if(! $this->send_to) $this->error('GREYMATTER: Must specify a "send to" address');
		
		//run
		if($this->wasSent()){
			$this->cleanVals();
			$this->validate();
			$this->mail();
		}
	}
	
	function cleanVals()
	{
		foreach($this->inputs as $key => $input)
		{
			$input= trim($input);
			$input= stripslashes($input);
			$input= htmlspecialchars($input);
			$this->input[$key] = $input;
		}
	}
	
	function checkIfEmpty($input)
	{
		if($input == NULL || $input == '' || preg_match( "/[\r\n]/", $input )) return 1;
		else  return 0;
	}
	
	function deleteProgress()
	{
		foreach($this->input as $key => $input)
			unset($_SESSION[$key]);
	}
	
	function v($key)
	{
		//returns the saved progress value
		if(isset($_SESSION[$key])) echo $_SESSION[$key];
		else echo '';
	}
	
	function mail()
	{
			if($this->error == 0) {
				mail($this->send_to,'Web Contact from '.$this->name, $this->message,'From: '.$this->email);
				$this->deleteProgress();
			} else {
				$this->saveProgress();
			}	
	}
	
	function saveProgress()
	{
		foreach($this->inputs as $key => $input)	
			$_SESSION[$key] = $input;
	}
	
	function validate()
	{
		foreach ($this->inputs as $input)
		{
			$this->error += $this->checkIfEmpty($input);
		}
	}
	
	function wasSent()
	{
		if(isset($_POST[$this->submit_id])) return TRUE;
		else return FALSE;
	}
	
	function wasSuccess()
	{
		if(isset($_POST[$this->submit_id])) {
			if($this->error == 0) return TRUE;
			elseif ($this->error > 0) return FALSE;
		}
	}
	
	function response($options)
	{
		/*Public Function to render form response. This entails the most common response logic.
		 *TODO: Use setOptions from greyMatter for param set
		 */
		$message = (isset($options['success'])) ? $options['success'] : 'Thank You! Your message has been sent.';
		$error_message = (isset($options['error'])) ? $options['error'] : 'Error: message not sent. Please make sure all fields are completed and correct.';
		$tag = (isset($options['tag'])) ? $options['tag'] : 'h3';
		$user_class = (isset($options['class'])) ? $options['class'] : '';
		$class_success = (isset($options['class_success'])) ? $options['class_success'] : 'se-success';
		$class_error = (isset($options['class_error'])) ? $options['class_error'] : 'se-error';
		
		//Success or Error
		if($this->wasSuccess()) {
			$response = $message;
			$class = $class_success;
		} else {
			$response = $error_message;
			$class = $class_error;
		}
		
		//Render result, if sent
		if($this->wasSent()) echo "<$tag class='se-response $class $user_class'>$response</$tag>";
	}
	

	
}//end class


?>