<?
// $Id: handler_pdf.php,v 1.7 2003/03/23 01:21:55 binary Exp $

define('b1n_PDF_HDL_MAX_CTC', '5');
define('b1n_PDF_HDL_MAX_SRV', '10');

// Data that we need to build the document
    // We need:
    // 1 - Handler
    //  1a - Handler Contact
    // 2 - Operator
    // 3 - Aircraft
    // 4 - Crew Members 
    //  4a - PIC
    //  4b - SIC
    //  4c - Extra1
    //  4d - Extra2
    // 5 - Services Requested
    // 6 - Pax Manifest
    // 7 - Itinerary Piece

$pdf_data = array();

// Getting Data to Build the Document
// -----------------------------------------------------------------
    // Getting 1, 2, 3 and 4 From the DataBase
$data = $sql->singleQuery("SELECT * FROM \"view_handling_request\" WHERE leg_id = '" . $leg_id . "'");

if(!is_array($data))
{
    b1n_regGoBackExit('Could not get data from view_handling_request.\nAborting PDF Generation');
}

    // Contacts
$query = "
    SELECT
        ctc_name
    FROM
        \"view_hdl_ctc\"
    WHERE
        hdl_id = '" . $hdl_id . "' AND (ctc_id IS NULL";

foreach($reg_data['contacts'] as $c)
{
    $query .= " OR ctc_id = '" . b1n_inBd($c) . "'";
}
$query .= ") LIMIT " . b1n_PDF_HDL_MAX_CTC;

$rs = $sql->query($query);

if($rs && is_array($rs))
{
    $pdf_data['contacts'] = $rs[0]['ctc_name'];
    array_shift($rs);

    foreach($rs as $r)
    {
        $pdf_data['contacts'] .= ' / ' . $r['ctc_name'];
    }
}
else
{
    b1n_regGoBackExit('Could not get contacts for this Handler.\nAborting PDF Generation.');
}

    // Services
$pdf_data['services'] = implode(', ', $reg_data['services']);
$query = "
    SELECT
        srv_name
    FROM
        \"service\"
    WHERE
        srv_provider = 'H' AND (srv_id IS NULL";

foreach($reg_data['services'] as $s)
{
    $query .= " OR srv_id = '" . b1n_inBd($s) . "'";
}
$query .= ") LIMIT " . b1n_PDF_HDL_MAX_SRV;

$rs = $sql->query($query);

if($rs && is_array($rs))
{
    $pdf_data['services'] = $rs[0]['srv_name'];
    array_shift($rs);

    foreach($rs as $r)
    {
        $pdf_data['services'] .= ', ' . $r['srv_name'];
    }
}
else
{
    b1n_regGoBackExit('Could not get services for this Handler.\nAborting PDF Generation.');
}

    // Remarks
$pdf_data['remarks'] = $reg_data['remarks'];

    // Itinerary
$query = "
    SELECT
         *
    FROM
        func_list_leg_pdf('" . implode(':', $ids) . "') AS 
        (
            apt_name_depart text,
            apt_name_arrive text,
            apt_timezone_depart text,
            apt_timezone_arrive text,
    
            leg_etd_dt text,
            leg_etd_localtime_dt text,

            leg_eta_dt text,
            leg_eta_localtime_dt text
        )";

$rs = $sql->query($query);

if($rs && is_array($rs))
{
    $pdf_data['itinerary'] = $rs;
}
else
{
    b1n_regGoBackExit('Could not get Legs.\nAborting PDF Generation.');
}

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

$pdf->b1n_SetHeader(strtoupper("Handling Request\n" . $data['acf_registry'] . ' ' . $data['acf_model']));
$pdf->b1n_SetFooter($footer);
$pdf->AddPage();
$pdf->SetFont('Arial', '', b1n_PDF_DEFAULT_FONTSIZE);

// Handler
$phone_prefix = '';

if(!empty($data['hdl_phone_country_code']))
{
    $phone_prefix = '+' . $data['hdl_phone_country_code'] . '-';
}

if(!empty($data['hdl_phone_city_code']))
{
    $phone_prefix .= $data['hdl_phone_city_code'] . '-';
}

$pdf->SetWidths(array(50));
$pdf->Row(array('Handler'));

$pdf->SetWidths(array(50, 142));
$pdf->Row(array('',  $data['hdl_name']));

if(!empty($data['hdl_phone']))
{
    $pdf->Row(array('Phone',  $phone_prefix . $data['hdl_phone']));
}

if(!empty($data['hdl_fax']))
{
    $pdf->Row(array('Fax',  $phone_prefix . $data['hdl_fax']));
}

if(!empty($data['hdl_mobile']))
{
    $pdf->Row(array('Mobile',  $phone_prefix . $data['hdl_mobile']));
}

if(!empty($data['hdl_arinc']))
{
    $pdf->Row(array('Arinc',  'VHF' . $data['hdl_arinc']));
}

if(!empty($pdf_data['contacts']))
{
    $pdf->Row(array('Contact(s)',  $pdf_data['contacts']));
}
$pdf->Ln(b1n_PDF_LINE_SPACE);

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
