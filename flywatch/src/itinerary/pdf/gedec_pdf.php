<?
// $Id: gedec_pdf.php,v 1.3 2003/05/06 01:36:50 mmr Exp $

// Data that we need to build the document
    // We need:
    // 1 - Operator
    // 2 - Aircraft
    // 3 - Crew Members 
    //  3a - PIC
    //  3b - SIC
    //  3c - Extra1
    //  3d - Extra2
    // 4 - Pax Manifest
    // 5 - Itinerary Piece

$pdf_data = array();

// Getting Data to Build the Document
// -----------------------------------------------------------------
$data = $sql->singleQuery("SELECT * FROM \"view_general_declaration\" WHERE leg_id = '" . $leg_id . "'");

if(!is_array($data))
{
    b1n_regGoBackExit('Could not get data from view_general_declaration.\nAborting PDF Generation');
}

    // Remarks
$pdf_data['remarks'] = $reg_data['remarks'];

    // Pax Manifest
$query = "
    SELECT
        pax_name,
        TO_CHAR(pax_dob_dt, 'DDMONYY')  AS pax_dob_dt,
        cts_name AS pax_cts_name,
        pax_ppt_nbr
    FROM 
        \"leg_pax\"
        NATURAL JOIN \"pax\"
        NATURAL JOIN \"citizenship\"
    WHERE
        leg_id = '" . $leg_id . "'";

$rs = $sql->query($query);

$pdf_data['pax'] = $rs;

exit;
// Start Building the Document
// -----------------------------------------------------------------
$opr_phone_prefix = '';

if(!empty($data['opr_phone_country_code']))
{
    $opr_phone_prefix = '+' . $data['opr_phone_country_code'] . '-';
}

if(!empty($data['opr_phone_city_code']))
{
    $opr_phone_prefix .= $data['opr_phone_city_code'] . '-';
}

$footer  = $data['opr_name'] . '. ';
$footer .= 'Phone: ' . $opr_phone_prefix . $data['opr_coffice_phone'] . ' ';
$footer .= 'Fax: '   . $opr_phone_prefix . $data['opr_coffice_fax'] . "\n";
$footer .= $data['opr_address'] . ' - ' . $data['opr_city'] . ' - ' . $data['opr_country'];

$header  = "DEPARTMENT OF THE TREASURY\nUNITED STATES CUSTOMS SERVICE\n";
$header .= "G E N E R A L   D E C L A R A T I O N\n";
$header .= "(Outward/Inward)\n";
$header .= "Agriculture, Customs, Immigration and Public Health\n";

$pdf->b1n_SetHeader($header);
$pdf->b1n_SetFooter($footer);
$pdf->AddPage();
$pdf->SetFont('Arial', '', b1n_PDF_DEFAULT_FONTSIZE);

// Operator
$pdf->SetWidths(array(50));
$pdf->Row(array('Operator'));

$pdf->SetWidths(array(50, 142));
$pdf->Row(array('', $data['opr_name']));
if(!empty($data['opr_address']))
{
    $aux = $data['opr_address'];

    if(!empty($data['opr_country']))
    {
        $aux .= "\n";

        if(!empty($data['opr_city']))
        {
            $aux .= $data['opr_city'] . " - ";
        }

        $aux .= " " . $data['opr_country'];
        $pdf->Row(array('Address', $aux));
    }
}

    // Hangar & Corporate Office
$pdf->SetWidths(array(50, 142));
    // Hangar
if(!empty($data['opr_hangar_phone']))
{
    $pdf->Row(array('Hangar Phone', $opr_phone_prefix . $data['opr_hangar_phone']));
}

if(!empty($data['opr_hangar_fax']))
{
    $pdf->Row(array('Hangar Fax', $opr_phone_prefix . $data['opr_hangar_fax']));
}

if(!empty($data['opr_hangar_mobile']))
{
    $pdf->Row(array('Hangar Mobile', $opr_phone_prefix . $data['opr_hangar_mobile']));
}

    // Corporate Office
if(!empty($data['opr_coffice_phone']))
{
    $pdf->Row(array('Corp. Office Phone', $opr_phone_prefix . $data['opr_coffice_phone']));
}

if(!empty($data['opr_coffice_fax']))
{
    $pdf->Row(array('Corp. Office Fax', $opr_phone_prefix . $data['opr_coffice_fax']));
}

if(!empty($data['opr_coffice_email']))
{
    $pdf->Row(array('Corp. Email', $data['opr_coffice_email']));
}

$pdf->Ln(b1n_PDF_LINE_SPACE);

// Aircraft
$pdf->SetWidths(array(50, 142));
$pdf->Row(array('Aircraft'));
$pdf->Row(array('Registry',      $data['acf_registry']));
$pdf->Row(array('Type',          $data['acf_model']));
$pdf->Row(array('Max T/O WT',    $data['acf_mtow']));
$pdf->Row(array('Noise Cert',    'Stage ' . $data['acf_noise_cert']));

if(!empty($data['acf_satcom']))
{
    $satcom_prefix = '';
    if(!empty($data['acf_satcom_country_code']))
    {
        $satcom_prefix = '+' . $data['acf_satcom_country_code'] . '-';
    }

    if(!empty($data['acf_satcom_city_code']))
    {
        $satcom_prefix .= $data['acf_satcom_city_code'] . '-';
    }

    $pdf->Row(array('Satcom', $satcom_prefix . $data['acf_satcom']));
}

$pdf->Row(array('Route',         'Via Approved ATS Routes')); 
$pdf->Row(array('Purpose',       'Private / Non Revenue')); 
$pdf->Ln(b1n_PDF_LINE_SPACE);

// Crew Members
    // Initializing
$pic = array('', '', '', '');
$sic = $pic;
$ex1 = $pic;
$ex2 = $pic;

    // PIC
$pic[0] = 'Captain ' . $data['pic_cmb_name'];
$pic[1] = $data['pic_cmb_cts_name'] . ', PPT ' . $data['pic_cmb_ppt_nbr'];
if(!empty($data['pic_cmb_dob_dt']))
{
    $pic[2] = 'DOB ' . $data['pic_cmb_dob_dt'] . ', ';
}
else
{
    $pic[2] = '';
}
$pic[2] .= 'DAC ' . $data['pic_cmb_cdac'];

if(!empty($data['pic_cmb_atp']))
{
    $pic[3] = 'ATP ' . $data['pic_cmb_atp'] . ' ';
}

if(!empty($data['pic_cmb_cp']))
{
    $pic[3] .= 'PC ' . $data['pic_cmb_cp'];
}

    // SIC
$sic[0] = 'F/O ' . $data['sic_cmb_name'];
$sic[1] = $data['sic_cmb_cts_name'] . ', PPT ' . $data['sic_cmb_ppt_nbr'];
if(!empty($data['sic_cmb_dob_dt']))
{
    $sic[2] = 'DOB ' . $data['sic_cmb_dob_dt'] . ', ';
}
$sic[2] .= 'DAC ' . $data['sic_cmb_cdac'];

if(!empty($data['sic_cmb_atp']))
{
    $sic[3] = 'ATP ' . $data['sic_cmb_atp'] . ' ';
}

if(!empty($data['sic_cmb_cp']))
{
    $sic[3] .= 'PC ' . $data['sic_cmb_cp'];
}

    // EX1
$widths = array(96, 96);
if(!empty($data['ex1_cmb_name']))
{
    $widths = array(64, 64, 64);

    $ex1[0] = 'Copl ' . $data['ex1_cmb_name'];
    $ex1[1] = $data['ex1_cmb_cts_name'] . ', PPT ' . $data['ex1_cmb_ppt_nbr'];

    if(!empty($data['ex1_cmb_dob_dt']))
    {
        $ex1[2] = 'DOB ' . $data['ex1_cmb_dob_dt'] . ', ';
    }
    $ex1[2] .= 'DAC ' . $data['ex1_cmb_cdac'];

    if(!empty($data['ex1_cmb_atp']))
    {
        $ex1[3] = 'ATP ' . $data['ex1_cmb_atp'] . ' ';
    }

    if(!empty($data['ex1_cmb_cp']))
    {
        $ex1[3] .= 'PC ' . $data['ex1_cmb_cp'];
    }

    // EX2
    if(!empty($data['ex2_cmb_name']))
    {
        $widths = array(48, 48, 48, 48);

        $ex2[0] = 'Copl ' . $data['ex2_cmb_name'];
        $ex2[1] = $data['ex2_cmb_cts_name'] . ', PPT ' . $data['ex2_cmb_ppt_nbr'];

        if(!empty($data['ex2_cmb_dob_dt']))
        {
            $ex2[2] = 'DOB ' . $data['ex2_cmb_dob_dt'] . ', ';
        }
        $ex2[2] .= 'DAC ' . $data['ex2_cmb_cdac'];

        if(!empty($data['ex2_cmb_atp']))
        {
            $ex2[3] = 'ATP ' . $data['ex2_cmb_atp'] . ' ';
        }
        
        if(!empty($data['ex2_cmb_cp']))
        {
            $ex2[3] .= 'PC ' . $data['ex2_cmb_cp'];
        }
    }
}

$pdf->Row(array('Crew'));
$pdf->SetWidths($widths);
for($i=0; $i<=3; $i++)
{
    $pdf->Row(array($pic[$i], $sic[$i],$ex1[$i], $ex2[$i]));
}
$pdf->Ln(b1n_PDF_LINE_SPACE);

// Request
$pdf->SetWidths(array(50, 142));
$pdf->Row(array('Request'));

$pdf->SetWidths(array(192));
$pdf->Row(array($pdf_data['services']));
$pdf->Ln(b1n_PDF_LINE_SPACE);

// Itinerary
$pdf->SetWidths(array(50, 142));
$pdf->Row(array('Itinerary'));
$pdf->SetWidths(array(17, 50, 56, 16, 53));
$pdf->Row(array('', 'Airport', 'LocalTime', 'TZ', 'GMT'));
foreach($pdf_data['itinerary'] as $i)
{
    $pdf->Row(array('Depart', $i['apt_name_depart'], $i['leg_etd_localtime_dt'], $i['apt_timezone_depart'], $i['leg_etd_dt']));
    $pdf->Row(array('Arrive', $i['apt_name_arrive'], $i['leg_eta_localtime_dt'], $i['apt_timezone_arrive'], $i['leg_eta_dt']));
}
$pdf->Ln(b1n_PDF_LINE_SPACE);

// Pax
if(is_array($pdf_data['pax']))
{
    $pdf->SetWidths(array(50, 142));
    $pdf->Row(array('Pax Manifest'));

    $pdf->SetWidths(array(61, 35, 48, 48));
    $pdf->Row(array('Name', 'DOB', 'Citizenship', 'Passport Number'));
    foreach($pdf_data['pax'] as $p)
    {
        $pdf->Row(array($p['pax_name'], $p['pax_dob_dt'], $p['pax_cts_name'], $p['pax_ppt_nbr']));
    }
}
$pdf->Ln(b1n_PDF_LINE_SPACE);

// Remarks
if(!empty($pdf_data['remarks']))
{
    $pdf->SetWidths(array(50));
    $pdf->Row(array('Remarks'));

    $pdf->SetWidths(array(192));
    $pdf->Row(array($pdf_data['remarks']));
}
?>
