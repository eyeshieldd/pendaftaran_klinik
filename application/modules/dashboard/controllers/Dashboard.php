<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Portal {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		parent::display();
	}


}