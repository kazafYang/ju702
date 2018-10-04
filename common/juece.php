<?php
class Juece{

	function __construct() {
		$this->log = new logs();

	}
	function get_buy($trade_type){
	  $this->log -> log_work("comming get_buy\n");
		
	}

	function get_sell($trade_type){
	  $this->log -> log_work("comming get_sell\n");

	}

	function get_returnbuy($trade_type){
	  $this->log -> log_work("comming get_returnbuy\n");

	}

	function get_returnsell($trade_type){
	  $this->log -> log_work("comming get_returnsell\n");

	}
	
}
?>
