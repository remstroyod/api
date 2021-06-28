<?php

if(!class_exists('PLD_Init')){
	class PLD_Init{
		function __construct(){
			add_action('init',array($this,'pld_init'));
		}
		
		function pld_init(){
			do_action('pld_init');
		}
	}
	
	new PLD_Init();
}