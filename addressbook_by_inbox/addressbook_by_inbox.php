<?php
require_once('lib/addressbook_inbox.php');

/**
 * AddressbookByInbox
 *
 * Simple plugin to list last recipients of emails visualized in autocomplete of new mails.
 *
 * @version 0.1
 * @author Thiago Coutinho (thiago@osfeio.com)
 */

class addressbook_by_inbox extends rcube_plugin{

	private $data;
  private $abook_id = 'addressbook_by_inbox';
	private $key = 'addressbook_by_inbox';
  public $task = 'mail';
  

  function init() {
		$this->add_hook('messages_list', array($this, 'load_from_message_list'));
		$this->data = &$_SESSION['addressbook_by_inbox'];
		$this->rcmail = rcmail::get_instance();
    if($this->rcmail->task == 'mail' && $this->rcmail->action == 'autocomplete' ){
  		$addrs = $this->rcmail->config->get('autocomplete_addressbooks');
  		$addrs[] = "addressbook_by_inbox"; 
  		$this->rcmail->config->set('autocomplete_addressbooks', $addrs );
      $this->add_hook('addressbook_get', array($this, 'addressbook_get'));
    }
  }

  function addressbooks_list($p){

    $p['sources'][$this->abook_id] = array(
        'id' => $this->abook_id,
        'name' => 'last_contacts',
        'readonly' => true,
        'autocomplete' => true
    );
    return $p;
  }

  function addressbook_get($p){
    if ($p['id'] === $this->abook_id) {
      $p['instance'] = new addressbook_inbox($this->get_key());
    }
    return $p;
  }

  function load_from_message_list($args){
  	$list = array();
  	foreach ($args['messages'] as $msg) {
	  	preg_match("([\w\._-]+\@[\w\._-]+)", $msg->to, $to);
	  	$list[] = $to[0];
	  	preg_match("([\w\._-]+\@[\w\._-]+)", $msg->from, $from);
	  	$list[] = $from[0];
  	}
  	$list = array_unique($list);

    $_SESSION[$this->get_key()] = $list;
  	return $args;
  }

  function get_key(){
    $rcmail = rcmail::get_instance();
    $email = $rcmail->user->data['username'];
    return $this->abook_id . $email;
  }

}