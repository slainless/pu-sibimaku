<?php

if(isset($code['load'], $code['subload']))
	switch ($code['load']) {
		case 'tab-content':
			
	        ob_start();
	        
	        switch ($code['subload']) {
	        	case 'home':
	        		require 'loader/01.php';
	        	break;
	        }

	        $output = ob_get_contents();
	        ob_end_clean();

	    return $output;
		
		default:
			# code...
			break;
	}
