<?php
	function numtoText($int, $decimal = false){

		$int = abs($int);
		$num = array('','satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');

		if(!$decimal):
			switch(true):
				case ($int < 12): return $num[$int]; break;
				case ($int < 100): return $int >= 20 ? $num[$int / 10].' puluh '.$num[$int % 10] : $num[$int % 10].' belas'; break;
				case ($int < 1000): return $int >= 200 ? $num[$int / 100].' ratus '.numtoText($int % 100) : 'seratus '.numtoText($int % 100); break;
				case ($int < 10000): return $int >= 2000 ? $num[$int / 1000].' ribu '.numtoText($int % 1000) : 'seribu '.numtoText($int % 1000); break;
				case ($int < 100000): return 
					$int >= 20000 
						? $num[$int / 10000].' puluh '.($int % 10000 !== 0 
							? numtoText($int % 10000) 
							: 'ribu')
						: numtoText($int / 1000).' ribu '.($int % 1000 !== 0 
							? numtoText($int % 1000) 
							: ''); 
				break;
				case ($int < 1000000): return 
					($int >= 200000 ? $num[$int / 100000].' ratus ' : 'seratus ').($int % 100000 === 0 ? 'ribu' : numtoText($int % 100000)); break;
				case ($int < 1000000000): return numtoText($int / 1000000).' juta '.numtoText(fmod($int, 1000000)); break;
				case ($int < 1000000000000): return numtoText($int / 1000000000).' miliar '.numtoText(fmod($int, 1000000000)); break;
				case ($int < 1000000000000000): return numtoText($int / 1000000000000).' triliun '.numtoText(fmod($int, 1000000000000)); break;
			endswitch;
		else:

			if(strpos($int, '.') !== false):
				$temp = explode('.', $int)[1];

				$string = '';
				$count = strlen($temp);
				for($x=0;$x<$count;$x++):
					$string .= numtoText($temp[$x]).' ';
				endfor;

				return trim(trim(numtoText($int)).' koma '.$string);

			else:
				return trim(numtoText($int));
			endif;

		endif;

		return $int;

	}

echo numtoText(363636);