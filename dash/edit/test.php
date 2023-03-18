<?php
	
	function randomInput($string, $delimiter, $count = 1, $group = 1, $gdelimiter = '', $gseparator = ' ') {
	
		$gseparator = str_replace('\n', chr(10), $gseparator);
	
		$string = explode($delimiter, $string);
		
		for($x=0;$x<$count;$x++){

			for($z=0;$z<$group;$z++){

				$temp = rand(0, count($string)-1);
				echo $string[$temp].($z === $group-1 ? $gseparator : $gdelimiter);
				
			}
		
		}
	
	}
?>
	<form method="GET">
	#===================================================#<br>
	string : <input type='text' name='string' width='500px' value='<?php echo $_GET['string'] ?? ''; ?>'>
	delimiter : <input type='text' name='delim' width='500px' value='<?php echo $_GET['delim'] ?? ' '; ?>'>
	<br>
	#===================================================#<br>
	count : <input type='text' name='count' width='500px' value='<?php echo $_GET['count'] ?? 10; ?>'>
	group : <input type='text' name='group' width='500px' value='<?php echo $_GET['group'] ?? 5; ?>'><br><br>
	in-group delimiter : <input type='text' name='gdel' width='500px' value='<?php echo $_GET['gdel'] ?? ''; ?>'>
	group separator : <input type='text' name='gsep' width='500px' value='<?php echo $_GET['gsep'] ?? '\n'; ?>'><br>
	#===================================================#<br>
	<button type='submit'>submit</button>
	</form>
	<div style='
		height: 350px;
		border: 1px solid rgba(0,0,0,0.2);
		column-count: 9;
		column-fill: auto;
	'>
	<pre><?php
	if(isset($_GET['string'])) randomInput($_GET['string'], $_GET['delim'], $_GET['count'], $_GET['group'], $_GET['gdel'], $_GET['gsep']);
?></pre>
	</div>