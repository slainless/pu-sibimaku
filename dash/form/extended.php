<?php
class PDF extends FPDF
{

	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	protected $pos = 0;

	protected $tHeader = '';
	protected $tRows = array();

	protected $tOptions = array();

	function InitTable($header, $headerOption, $columnOption){
		$this->tHeader = $header;
		$this->tOptions['columnOption'] = $columnOption;
		$this->tOptions['headerOption'] = $headerOption;
	}

	function TableRows($rows, $height = 10, $align = ''){
		$this->tRows[] = array('rows' => $rows, 'height' => $height, 'align' => $align);
	}

	function OutputTable(){

		foreach($this->tHeader as $k => $v):
			if(isset($v['number']) && $v['number'] == true)
				$this->tOptions['key'] = $k;

			if($v['width'] === 0)
				$this->tOptions['flex'] = true;

			$this->tOptions['width'][] = $v['width'];
		endforeach;

		if(isset($this->tOptions['flex'])):
			$temp = (($this->GetPageWidth() - $this->lMargin - $this->rMargin) - array_sum($this->tOptions['width']))/3;

			foreach ($this->tOptions['width'] as $k => $v) {
				if($v === 0)
					$this->tOptions['width'][$k] = $temp;
			}
		endif;
		unset($temp);

		foreach ($this->tHeader as $k => $v) {
			$temp = 'TBR';
			if($k === 0)
				$temp .= 'L';

			$temp2 = 0;
			if($k === count($this->tHeader) -1)
				$temp2 = 1;

			$this->cell(
				$this->tOptions['width'][$k],
				$this->tOptions['headerOption']['height'],
				$v['title'],
				$temp,$temp2,'C'
			);
			$temp2 = 0;
		}

		$key = 1;
		foreach ($this->tRows as $kx => $vx) {
			
			foreach ($vx['rows'] as $ky => $vy) {
				$temp = 'BR';
				if($ky === 0)
					$temp .= 'L';

				$temp2 = 0;
				if($ky === count($this->tHeader) - 1)
					$temp2 = 1;

				if(empty($vx['align']))
					$temp3 = $this->tOptions['columnOption'][$ky]['align'];
				else
					$temp3 = $vx['align'];

				if($ky === $this->tOptions['key'])
					if(empty($vy)):
						$vy = $key.'.';
						$key++;
					endif;

				$this->cell(
					$this->tOptions['width'][$ky],
					$vx['height'],
					$vy,
					$temp,
					$temp2,
					$temp3
				);
				$temp2 = 0;
			}

		}

		//var_dump($this->tOptions);
		//var_dump($this->tHeader);
		//var_dump($this->tRows);
		//exit();
	}

	function GetMargins()
	{
		$temp = array(
			'top' => $this->tMargin,
			'bottom' => $this->bMargin,
			'right' => $this->rMargin,
			'left' => $this->lMargin,
		);
		return $temp;

	}

	function SetIndent($int = 0)
	{
		$this->pos = $int;
	}

	function Indent($int)
	{
		$this->pos += $int;
		$this->SetX(0);
		$this->SetLeftMargin($this->pos);
	}

	function SetProp($prop, $value){
		$this->{$prop} = $value;
	}

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function WriteHTML($html)
	{
	    // HTML parser
	    $html = str_replace("\n",' ',$html);
	    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	    foreach($a as $i=>$e)
	    {
	        if($i%2==0)
	        {
	            // Text
	            if($this->HREF)
	                $this->PutLink($this->HREF,$e);
	            else
	                $this->Write(5,$e);
	        }
	        else
	        {
	            // Tag
	            if($e[0]=='/')
	                $this->CloseTag(strtoupper(substr($e,1)));
	            else
	            {
	                // Extract attributes
	                $a2 = explode(' ',$e);
	                $tag = strtoupper(array_shift($a2));
	                $attr = array();
	                foreach($a2 as $v)
	                {
	                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
	                        $attr[strtoupper($a3[1])] = $a3[2];
	                }
	                $this->OpenTag($tag,$attr);
	            }
	        }
	    }
	}

	function OpenTag($tag, $attr)
	{
	    // Opening tag
	    if($tag=='B' || $tag=='I' || $tag=='U')
	        $this->SetStyle($tag,true);
	    if($tag=='A')
	        $this->HREF = $attr['HREF'];
	    if($tag=='BR')
	        $this->Ln(5);
	}

	function CloseTag($tag)
	{
	    // Closing tag
	    if($tag=='B' || $tag=='I' || $tag=='U')
	        $this->SetStyle($tag,false);
	    if($tag=='A')
	        $this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
	    // Modify style and select corresponding font
	    $this->$tag += ($enable ? 1 : -1);
	    $style = '';
	    foreach(array('B', 'I', 'U') as $s)
	    {
	        if($this->$s>0)
	            $style .= $s;
	    }
	    $this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
	    // Put a hyperlink
	    $this->SetTextColor(0,0,255);
	    $this->SetStyle('U',true);
	    $this->Write(5,$txt,$URL);
	    $this->SetStyle('U',false);
	    $this->SetTextColor(0);
	}
}