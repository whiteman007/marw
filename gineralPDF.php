<?php
    include 'session.php';
  include 'dbcontact.php';
  require ('TCPDF/tcpdf.php');
  if(isset($_SESSION['user'])){
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
      $this->Cell(0, 15, 'أستمارة الشاملة للمعاملات الغير منجزة', 'B', false, 'C', 0, '', 0, false, 'M', 'M');
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
$pdf->SetTitle(' أستمارة الطلبات الشاملة    ');
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


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//---------------------------------------------------------//
//------------------ جلب جميع البيانت -------------------//
// -------------------------------------------------------//
$gineralformID=isset($_GET['gineralformID']) && is_numeric($_GET['gineralformID']) ? intval($_GET['gineralformID']) : 0;
$stmt=$con->prepare('SELECT
                *
            FROM
            ganiralform

            WHERE	
            Gineral_ID=?

            ORDER BY
                `ganiralform`.`Gineral_ID`
            DESC');
$stmt->execute(array($gineralformID));
$items=$stmt->fetchall();
foreach ($items as $item) {
// -------------------------------------------------------//
// set font
$pdf->SetFont('freeserif',0, 16);

// add a page
$pdf->AddPage();

// set some text to print
$txt = <<<EOD
معلومات وتفاصيل عن استمارة الطلبات - 2023
EOD;

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
// ---------------------------------------------------------
//--- التسلسل ---------
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(160, 10,'',0,0);
$pdf->Cell(10, 10,$item['Gineral_ID'],0,0);
$pdf->Cell(20, 10,'العدد:',0,1);
$pdf->Cell(149, 10,'',0,0);
$pdf->Cell(21, 10,$item['gineral_Date'],0,0);
$pdf->Cell(20, 10,'التاريخ:',0,1);

//--- اسم الجريح الرباعي ---------
$txt10='الحالة';
$txt1='الاسم الرباعي واللقب';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(180, 10,$txt1,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(180, 10, $item['Gineral_Name'],1,1,'C');
// ---------------------------------------------------------
//---  الحالة واسم الام الثلاثي---------
$txt2='الحالة';
$txt2_1='حالة الجريح';
$txt2_2='نسبة العجز';
$txt3='أسم الام الثلاثي';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt3,1,0,'C');
if ($item['Status_Death']== 4){
    $pdf->Cell(30, 10,$txt2_2,1,0,'C');
    $pdf->Cell(30, 10,$txt2_1,1,0,'C');
    $pdf->Cell(30, 10,$txt2,1,1,'C');
}else{
    $pdf->Cell(90, 10,$txt2,1,1,'C');
}
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['Mother_name'],1,0,'C');
if ($item['Status_Death']== 1){
$pdf->Cell(90, 10,'شهـــيد',1,1,'C');}
if ($item['Status_Death']== 2){
$pdf->Cell(90, 10,'متـــوفي',1,1,'C');}
if ($item['Status_Death']== 3){
$pdf->Cell(90, 10,'مفقــود',1,1,'C');}
if ($item['Status_Death']== 4){
    $pdf->Cell(30, 10,$item['percentag_wounded'],1,0,'C');
    if ($item['status_wounded']== 1){
    $pdf->Cell(30, 10,'متــقاعد',1,0,'C');
    }
    if ($item['status_wounded']== 2){
        $pdf->Cell(30, 10,'مستـــمر',1,0,'C');
    }
    $pdf->Cell(30, 10,'جــريح',1,1,'C');
}

// --------------------------------------------------------
//---  تاريخ الاستشهاد الوفاه الاصابة الفقدان , اسم دائرة ---------

// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
if ($item['Status_Death']== 1){
    $pdf->Cell(90, 10,'تاريخ الاستشهاد',1,0,'C');}
if ($item['Status_Death']== 2){
    $pdf->Cell(90, 10,'تاريخ الوفاه',1,0,'C');}
if ($item['Status_Death']== 3){
    $pdf->Cell(90, 10,'تاريخ الفقدان',1,0,'C');}
if ($item['Status_Death']== 4){
    $pdf->Cell(90, 10,'تاريخ الاصابة',1,0,'C');}
$txt4='أسم الدائرة ';
$pdf->Cell(90, 10,$txt4,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['Date_of_Death'],1,0,'C');
$pdf->Cell(90, 10,$item['Derectry_name'],1,1,'C');

// ---------------------------------------------------------
//---  +هل انجز المجلس التحقيقي؟ +رقم الامر الاداري + هل صدر امر اداري بالاستشهاد ---------
// Set font
$txt6='رقم وتاريخ الامر';
$pdf->SetFont('freeserif','B', 10);
// color
$pdf->SetTextColor(28,3,156);
// Title
//هل انجز المجلس التحقيقي
    if ($item['Amor_idary']== 7){
    $pdf->Cell(50, 10,'الرقم التقاعدي',1,0,'C');
}
if ($item['Amor_idary']== 8){
    if ($item['Status_Death']== 1){
        $pdf->Cell(50, 10,'هل انجز المجلس التحقيقي للشهيد؟',1,0,'C');}
    if ($item['Status_Death']== 2){
        $pdf->Cell(50, 10,'هل انجز المجلس التحقيقي للمتوفي؟',1,0,'C');}
    if ($item['Status_Death']== 3){
        $pdf->Cell(50, 10,'هل انجز المجلس التحقيقي للفقيد؟',1,0,'C');}
    if ($item['Status_Death']== 4){
        $pdf->Cell(50, 10,'هل انجز المجلس التحقيقي للجريح؟',1,0,'C');}
}


// رقم وتاريخ الكتابة
$pdf->Cell(80, 10,$txt6,1,0,'C');

//هل صدر امر اداري بالاستشهاد
if ($item['Status_Death']== 1){
    $pdf->Cell(50, 10,'هل صدر أمر اداري بالاستشهاد؟',1,1,'C');}
if ($item['Status_Death']== 2){
    $pdf->Cell(50, 10,'هل صدر امر اداري بالوفاه؟',1,1,'C');}
if ($item['Status_Death']== 3){
    $pdf->Cell(50, 10,'هل صدر امر اداري بالفقدان؟',1,1,'C');}
if ($item['Status_Death']== 4){
    $pdf->Cell(50, 10,'هل صدر امر اداري بالاصابة؟',1,1,'C');}

$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);

if ($item['Amor_idary']== 7){
    $pdf->Cell(50, 10,$item['NO_taqaud'],1,0,'C');
}
if ($item['Amor_idary']== 8){
    if ($item['Investy_board']== 5){
        $pdf->Cell(50, 10,'نــعــم',1,0,'C');}
        if ($item['Investy_board']== 6){
        $pdf->Cell(50, 10,'لا',1,0,'C');
    }
}



if ($item['Amor_idary']== 7){
$pdf->Cell(80, 10,$item['No_Data_Book'],1,0,'C');}
if ($item['Amor_idary']== 8){
$pdf->Cell(80, 10,'لا يوجد امر ',1,0,'C');}


if ($item['Amor_idary']== 7){
$pdf->Cell(50, 10,'نــعــم',1,1,'C');}
if ($item['Amor_idary']== 8){
$pdf->Cell(50, 10,'لا',1,1,'C');}


// ---------------------------------------------------------
//--- اسم صاحب الطلب , صلة القرابة  ---------

$txt5='أسم صاحب الطلب';
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
if ($item['Status_Death']== 1){
    $pdf->Cell(90, 10,'صلة القرابة بالشهيد',1,0,'C');}
if ($item['Status_Death']== 2){
    $pdf->Cell(90, 10,'صلة القابة بالمتوفي',1,0,'C');}
if ($item['Status_Death']== 3){
    $pdf->Cell(90, 10,'صلة القرابة بالفقيد',1,0,'C');}
if ($item['Status_Death']== 4){
    $pdf->Cell(90, 10,'صلة القرابة بالجريح',1,0,'C');}

$pdf->Cell(90, 10,$txt5,1,1,'C');

$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
if ($item['Relative_relashion']== 9){
$pdf->Cell(90, 10,'أب',1,0,'C');}
if ($item['Relative_relashion']== 10){
$pdf->Cell(90, 10,'أم',1,0,'C');}
if ($item['Relative_relashion']== 111){
$pdf->Cell(90, 10,'أبن',1,0,'C');}
if ($item['Relative_relashion']== 112){
$pdf->Cell(90, 10,'أبنة',1,0,'C');}
if ($item['Relative_relashion']== 11){
$pdf->Cell(90, 10,'أخ',1,0,'C');}
if ($item['Relative_relashion']== 12){
$pdf->Cell(90, 10,'أخت',1,0,'C');}
if ($item['Relative_relashion']== 13){
$pdf->Cell(90, 10,'زوجة',1,0,'C');}
if ($item['Relative_relashion']== 113){
$pdf->Cell(90, 10,'نفسة',1,0,'C');}

$pdf->Cell(90, 10,$item['Name_Of_Form'],1,1,'C');



// ---------------------------------------------------------
//---  رقم الهاتف + العنوان ---------
$txt7='رقم الهاتف';
$txt8='العنوان';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
// Title
$pdf->Cell(90, 10,$txt7,1,0,'C');
$pdf->Cell(90, 10,$txt8,1,1,'C');
$pdf->SetFont('freeserif',0, 12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(90, 10,$item['Phon_Nu'],1,0,'C');
$pdf->Cell(90, 10,$item['Adress_gform'],1,1,'R');


// ---------------------------------------------------------

//---  ---------
$txt8='هل تم استلام الدفن والكفن؟';
$txt9='هل تم استلام الاجازات المتراكمة؟';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(28,3,156);
if ($item['Status_Death']== 1){
    $pdf->Cell(60, 10,'هل تم استلام الكفن والدفن؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام الاجازات المتراكمة؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام راتب تقاعدي؟',1,1,'C');}
 if ($item['Status_Death']== 2){
    $pdf->Cell(60, 10,'هل تم استلام الكفن والدفن؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام الاجازات المتراكمة؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام راتب تقاعدي؟',1,1,'C');}
if ($item['Status_Death']== 3){
    $pdf->Cell(60, 10,'هل تم استلام الكفن والدفن؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام الاجازات المتراكمة؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام راتب تقاعدي؟',1,1,'C');}
if ($item['Status_Death']== 4){
    $pdf->Cell(60, 10,'هل تم استلام الاجازات المتراكمة؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام راتب تقاعدي؟',1,0,'C');
    $pdf->Cell(60, 10,'هل تم استلام منحة الصندوق؟',1,1,'C');}

    $pdf->SetFont('freeserif',0, 12);
    $pdf->SetTextColor(0,0,0);

if ($item['Status_Death']== 1){
    if ($item['Kafan']== 14){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Kafan']== 15){
    $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Vaction']== 16){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Vaction']== 17){
        $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Salary']== 18){
        $pdf->Cell(60, 10,'نـعـم',1,1,'C');
    }
    if ($item['Salary']== 19){
        $pdf->Cell(60, 10,'لا',1,1,'C');
    }
}

if ($item['Status_Death']== 2){
    if ($item['Kafan']== 14){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Kafan']== 15){
    $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Vaction']== 16){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Vaction']== 17){
        $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Salary']== 18){
        $pdf->Cell(60, 10,'نـعـم',1,1,'C');
    }
    if ($item['Salary']== 19){
        $pdf->Cell(60, 10,'لا',1,1,'C');
    }
}
if ($item['Status_Death']== 3){
    if ($item['Kafan']== 14){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Kafan']== 15){
    $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Vaction']== 16){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Vaction']== 17){
        $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Salary']== 18){
        $pdf->Cell(60, 10,'نـعـم',1,1,'C');
    }
    if ($item['Salary']== 19){
        $pdf->Cell(60, 10,'لا',1,1,'C');
    }
}
if ($item['Status_Death']== 4){

    if ($item['Vaction']== 16){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Vaction']== 17){
        $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Salary']== 18){
        $pdf->Cell(60, 10,'نـعـم',1,0,'C');
    }
    if ($item['Salary']== 19){
        $pdf->Cell(60, 10,'لا',1,0,'C');
    }
    if ($item['Asaba']== 20){
        $pdf->Cell(60, 10,'نـعـم',1,1,'C');
    }
    if ($item['Asaba']== 21){
    $pdf->Cell(60, 10,'لا',1,1,'C');
    }
}


// ---------------------------------------------------------
//--- ملاحظات  ---------
$txt11='المشكلة';
$pdf->SetFont('freeserif',0, 11);
$pdf->SetTextColor(0,0,0);
// Title
$pdf->MultiCell(180, 30, $item['Description_Of'],1,'R', false);


//---  اماكن التواقيع ---------
$txt11='هامش المكتب';
$txt12='هامش السيد المدير';
// Set font
$pdf->SetFont('freeserif','B', 13);
// color
$pdf->SetTextColor(187,28,39);
// Title
$pdf->Cell(90, 10,$txt12,1,0,'C');
$pdf->Cell(90, 10,$txt11,1,1,'C');
$pdf->Cell(90, 40,'',1,0,'C');
$pdf->Cell(90, 40,'',1,1,'C');
// ---------------------------------------------------------
// -- set new background ---

// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
if($item['ganiral_Jinsai']!=null){
$pdf->AddPage();    
$img_file = K_PATH_IMAGES.'/ganiralForm/'.$item['ganiral_Jinsai'];
$pdf->Image($img_file, 30, 50, 160, 150, '', '', '', false, 0, '', false, false, 0);
}
if($item['ganiral_omorIstshad']!=null){
$pdf->AddPage();    
$img_file = K_PATH_IMAGES.'/ganiralForm/'.$item['ganiral_omorIstshad'];
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
}
if($item['ganiral_omorIsaba']!=null){
$pdf->AddPage();    
$img_file = K_PATH_IMAGES.'/ganiralForm/'.$item['ganiral_omorIsaba'];
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
}
if($item['ganiral_Ajaz']!=null){
$pdf->AddPage();    
$img_file = K_PATH_IMAGES.'/ganiralForm/'.$item['ganiral_Ajaz'];
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
}
if($item['ganiral_omorWafa']!=null){
$pdf->AddPage();    
$img_file = K_PATH_IMAGES.'/ganiralForm/'.$item['ganiral_omorWafa'];
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
}
if($item['ganiral_omorFuqdan']!=null){
$pdf->AddPage();
$img_file = K_PATH_IMAGES.'/ganiralForm/'.$item['ganiral_omorFuqdan'];
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
}
                                                
// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
}
ob_end_clean();//Close and output PDF document
$pdf->Output('أستمارة الطلبات وزارة الداخلية', 'I');

//============================================================+
// END OF FILE
//============================================================+
}else{
    header('location:index.php');
	exit();
 }
?>
