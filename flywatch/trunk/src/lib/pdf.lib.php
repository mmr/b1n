<?
define('b1n_PDF_AUTHOR', 'Marcio Ribeiro');
define('FPDF_FONTPATH', b1n_LIBPATH . '/fpdf/font/');

define('b1n_PDF_LOGO', 'img/b4logo.png');
define('b1n_PDF_LOGO_LINK', 'http://b4br.net/');

require(b1n_LIBPATH . '/fpdf/fpdf.php');

class PDF extends FPDF
{
var $b1n_footer   = NULL;
var $b1n_header   = NULL;
var $b1n_header_have_image = NULL;
var $widths;

//Constructor
function PDF($orientation='P', $unit='mm', $format='A4')
{
    $this->SetAuthor(b1n_PDF_AUTHOR);
    return $this->FPDF($orientation, $unit, $format);
}

//Page header
function Header()
{
    $multi_cell_height = 10;

    if($this->b1n_header_have_image)
    {
        if(is_readable(b1n_PDF_LOGO))
        {
            // The size of the image is given in Pixels
            // I'm using mm as the unit for the PDF
            // PDF are dot based, so...:
            // 1 dot = 1/72in
            // 1 in  = 2.54cm
            // 1 cm  = 10mm
            // ...
            // 1 in  = 25.4mm
            // 1 px  = 1 dot = 1/72in = 1/(72/25.4)mm = 0.353mm

            $img_size = getimagesize(b1n_PDF_LOGO);
            $img_size[0] = $img_size[0] * 0.28;
            $img_size[1] = $img_size[1] * 0.28;

            //Logo
            $this->Image(b1n_PDF_LOGO, 10, 8, $img_size[0], $img_size[1], '', b1n_PDF_LOGO_LINK);

            //Move to the right
            $this->Cell($img_size[0]*1.5);
            $multi_cell_height = $img_size[1]*0.45;
        }
    }

    //Arial bold 15
    $this->SetFont('Arial', 'B', 25);

    //Title
    $this->MultiCell(0, $multi_cell_height, $this->b1n_header, 1, 'C');

    //Line break
    $this->Ln(10);
}

//Page footer
function Footer()
{
    $this->SetY(-14);

    //Arial 7
    $this->SetFont('Arial', '', 7);
    $this->MultiCell(0, 2.6, $this->b1n_footer . "\n\nPage " . $this->PageNo() . "/{nb}", 0, 'C');
}

function b1n_SetHeader($b1n_header, $b1n_header_have_image = true)
{
    $this->b1n_header = $b1n_header;
    $this->b1n_header_have_image = $b1n_header_have_image; 
}

function b1n_SetFooter($b1n_footer)
{
    $this->b1n_footer = $b1n_footer;
}

//------------------------

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function Row($data, $border = true)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();

        if($border)
        {
            //Draw the border
            $this->Rect($x,$y,$w,$h);
        }

        //Print the text
        $this->MultiCell($w, 5, $data[$i], 0, 'L');

        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}
?>
