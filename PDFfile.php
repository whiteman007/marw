<?php

  include 'dbcontact.php';
  require ('TCPDF/tcpdf.php');

    // Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

  //Page header
  public function Header() {
      // Logo
      $image_file = K_PATH_IMAGES.'logo1.jpg';
      $this->Image($image_file, 15, 15, 20, '', 'jpg', '', 'T', false, 600, '', false, false, 0, false, false, false);
      // Set font
      $this->SetFont('freeserif', 'B', 20);
      // color
      $this->SetTextColor(255,222,44  );
      // Title
      $this->Cell(0, 15, ' أستمارة منحة ذوي الشهداء', 'B', false, 'C', 0, '', 0, false, 'M', 'M');
  }

  // Page footer
  public function Footer() {
      // Position at 15 mm from bottom
      $this->SetY(-15);
      // Set font
      $this->SetFont('freeserif', 'I', 8);
      // Page number
      $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle(' أستمارة منحة الشهداء');
$pdf->SetSubject('أستمارة');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//---------------------------------------------------------//
//------------------ جلب جميع البيانت -------------------//
// -------------------------------------------------------//
$MartyrID=isset($_GET['MartyrID']) && is_numeric($_GET['MartyrID']) ? intval($_GET['MartyrID']) : 0;
$stmt=$con->prepare('SELECT
                *
            FROM
                onlinform	

            WHERE	Martyr_ID=?
            
            ORDER BY
                `onlinform`.`Martyr_ID`
            DESC');
$stmt->execute(array($MartyrID));
$items=$stmt->fetchall();
foreach ($items as $item) {
// -------------------------------------------------------//
// set font
$pdf->SetFont('freeserif',0, 16);

// add a page
$pdf->AddPage();

// set some text to print
$txt = <<<EOD
معلومات المتقدمين لمنحة ذوي الشهداء 
EOD;

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
// ---------------------------------------------------------
//--- التسلسل ---------
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(1, 10,'',0,1);
$pdf->Cell(85, 10, '',0,0,'C');
$pdf->Cell(10, 10, $item['Martyr_ID'],1,0,'C');
$pdf->Cell(85, 10,'',0,1,'C');

//--- اسم الشهيد الرباعي ---------
$txt1='اسم الشهيد الرباعي واللقب';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(180, 10,$txt1,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(180, 10, $item['martyr_name'],1,1,'C');
// ---------------------------------------------------------

//--- أسم المستفيد ودائرة الشهيد ---------
$txt2='أسم دائرة الشهيد';
$txt15='اسم المستفيد الرباعي ';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt15,1,0,'C');
$pdf->Cell(90, 10,$txt2,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['benefit_name'],1,0,'C');
$pdf->Cell(90, 10,$item['work_place'],1,1,'C');
// --------------------------------------------------------
//--- عنوان السكن ورقم الهاتف ---------
$txt3='رقم الهاتف';
$txt4='عنوان السكن ';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt3,1,0,'C');
$pdf->Cell(90, 10,$txt4,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['phon_cell'],1,0,'C');
$pdf->Cell(90, 10,$item['benefit_adress'],1,1,'C');
// ---------------------------------------------------------
//--- ورقم الماستر كارد والحالة الزوجية  ---------
$txt5='رقم الماستر كارد';
$txt6='الحالة الزوجية';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt6,1,0,'C');
$pdf->Cell(90, 10,$txt5,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
if ($item['marige_statuse']== 0){
    $pdf->Cell(90, 10,'لم يتم الاختيار',1,0,'C');
}
if ($item['marige_statuse']== 1){
    $pdf->Cell(90, 10,'متزوج',1,0,'C');
}
if ($item['marige_statuse']== 2){
    $pdf->Cell(90, 10,'أعزب',1,0,'C');
}
$pdf->Cell(90, 10,$item['Mastercard_no'],1,1,'C');
// ---------------------------------------------------------
// ---------------------------------------------------------

//---  اسم الزوجة وعدد الاولاد---------
$txt7='اسم زوجة الشهيد';
$txt8='عدد الاولاد';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt8,1,0,'C');
$pdf->Cell(90, 10,$txt7,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['child_No'],1,0,'C');
if ($item['marige_statuse']== 0){
    $pdf->Cell(90, 10,'لم يتم الاختيار',1,1,'C');
}
if ($item['marige_statuse']== 1){
    $pdf->Cell(90, 10,$item['wife_name'],1,1,'C');
}
if ($item['marige_statuse']== 2){
    $pdf->Cell(90, 10,'أعزب',1,1,'C');
}
// ---------------------------------------------------------
//---  الرقم التقاعدي للشهيد والمستفيد ---------
$txt9='الرقم التقاعدي للمستفيد';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(180, 10,$txt9,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(180, 10,$item['retirement_benefitNo'],1,1,'C');

// ---------------------------------------------------------
//---   ---------
$txt14='وقت التسجيل';
$txt13='تاريخ التسجيل';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt14,1,0,'C');
$pdf->Cell(90, 10,$txt13,1,1,'C');

$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['Timeform'],1,0,'C');
$pdf->Cell(90, 10,$item['Dateform'],1,1,'C');


// ---------------------------------------------------------

//---   ---------
$txt11='توقيع ضابط التدقيق';
$txt12='توقيع رئيس اللجنة';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(187,28,39);
// Title
$pdf->Cell(90, 10,'',0,1);
$pdf->Cell(90, 10,$txt12,1,0,'C');
$pdf->Cell(90, 10,$txt11,1,1,'C');
$pdf->Cell(90, 20,'',1,0,'C');
$pdf->Cell(90, 20,'',1,1,'C');
// ---------------------------------------------------------
//--- ملاحظات  ---------
$txt11='الملاحظات';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(187,28,39);
// Title
$pdf->Cell(160, 35,'',1,0,'C');
$pdf->Cell(20, 35,$txt11,1,1,'C');

// ---------------------------------------------------------

}

//Close and output PDF document
$pdf->Output('أستمارة ذوي الشهداء', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>