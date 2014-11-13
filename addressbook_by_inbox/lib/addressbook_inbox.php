<?php

class addressbook_inbox extends rcube_addressbook
{
	private $key;
	private $result;

  public function __construct($key){
  	$this->key = $key;
    $this->rcmail = rcmail::get_instance();
  }

  public function get_name(){
  	return 'addressbook_inbox';
  }

  public function set_search_set($args){
  }

  public function get_search_set(){
  	return array();
  }

  public function list_records($cols=null, $subset=0){
  	
  }
  public function search($fields, $value, $mode=0, $select=true, $nocount=false, $required=array()){
    $data = $_SESSION[$this->key];
  	$this->result = new rcube_result_set(0,0);
  	foreach ($data as $key => $email) {
  		if(strpos($email,$value) !== false){
  			$this->result->add(array(
  				'ID'=> $key,
  				'email' => $email
  			));
  		}
  	}
  	return $this->result;
  }

  public function get_record($id, $assoc=false){

  }

  public function reset()
  {
  	# code...
  }
  public function count()
  {
  	# code...
  }
  public function get_result(){

  }

}