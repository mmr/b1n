<?
// $Id: caterer_pdf.php,v 1.8 2003/02/22 13:18:35 binary Exp $
define('b1n_PDF_CAT_MAX_CTC', '5');

    // Contacts
$pdf_data['contacts'] = $reg_data['contacts'];

    // FoodType X Food
foreach($fdt_fod as $r)
{
    $input_name = 'qtd_' . $r['fdt_id'] . '_' . $r['fod_id'];

    if($reg_data[$input_name] > 0)
    {
        $pdf_data['request'][$r['fdt_name']][$r['fod_name']] = $reg_data[$input_name];
    }
}

    // Remarks
$pdf_data['remarks']  = $reg_data['remarks'];

// Caterer/Operator/Aircraft Data
$data = $sql->singleQuery("SELECT * FROM \"view_catering_order\" WHERE leg_id = '" . $leg_id . "'");

if(!is_array($data))
{
    b1n_regGoBackExit('Could not get data from view_catering_order.\nAborting PDF Generation');
}

// Getting Data to Build the Document
    // Contacts
$query = "
    SELECT
        ctc_name
    FROM
        \"view_cat_ctc\"
    WHERE
        cat_id = '" . $cat_id . "' AND (ctc_id IS NULL";

foreach($pdf_data['contacts'] as $c)
{
    $query .= " OR ctc_id = '" . b1n_inBd($c) . "'";
}
$query .= ") LIMIT " . b1n_PDF_CAT_MAX_CTC;

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
    b1n_regGoBackExit('Could not get contacts for this Caterer.\nAborting PDF Generation.');
}

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

$pdf->b1n_SetHeader(strtoupper("Catering Order\n" . $data['acf_registry'] . ' ' . $data['acf_model']));
$pdf->b1n_SetFooter($footer);
$pdf->AddPage();
$pdf->SetFont('Arial', '', b1n_PDF_DEFAULT_FONTSIZE);

// Catering Order
$phone_prefix = '';

if(!empty($data['cat_phone_country_code']))
{
    $phone_prefix = '+' . $data['cat_phone_country_code'] . '-';
}

if(!empty($data['cat_phone_city_code']))
{
    $phone_prefix .= $data['cat_phone_city_code'] . '-';
}

$pdf->SetWidths(array(50));
$pdf->Row(array('Caterer'));

$pdf->SetWidths(array(50, 142));
$pdf->Row(array('',  $data['cat_name']));

if(!empty($data['cat_phone']))
{
    $pdf->Row(array('Phone',  $phone_prefix . $data['cat_phone']));
}

if(!empty($data['cat_fax']))
{
    $pdf->Row(array('Fax',  $phone_prefix . $data['cat_fax']));
}

if(!empty($data['cat_mobile']))
{
    $pdf->Row(array('Mobile',  $phone_prefix . $data['cat_mobile']));
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

// Request
$pdf->SetWidths(array(50, 142));
$pdf->Row(array('Request'));

foreach($pdf_data['request'] as $foodtype => $food)
{
    $pdf->SetWidths(array(192));
    $pdf->Row(array($foodtype));
    $pdf->SetWidths(array(7, 7, 178));

    foreach($food as $fod => $qtd)
    {
        $pdf->Row(array('', $qtd, $fod));
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
