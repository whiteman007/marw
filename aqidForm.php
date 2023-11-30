<?php

	/*
	================================================
	== istilamat Page
	================================================
	*/
	ini_set('display_errors','on');
	error_reporting(E_ALL);
	include 'session.php';
    include 'init.php';
    require ('TCPDF/tcpdf.php');

	$pagetitle = 'استمارة الراغبين بالتعاقد للخدمة في وزارة الداخلية من ذوي شهداء الوزارة للاعمار 18 – 19 – 20 – 21 – 22';


		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {
            if(isset($_SESSION['user'])){
                // START OUR PAGNATION BOX
                $rows=countitem('Taqud_ID' , 'taqudform') ;
                $page_rows=100;
                // This tells us the page number of our last page
                $last = ceil($rows/$page_rows);
                // This makes sure $last cannot be less than 1
                if($last < 1){
                    $last = 1;
                }
                // Establish the $pagenum variable
                $pagenum = 1;
                // Get pagenum from URL vars if it is present, else it is = 1
                if(isset($_GET['pn'])){
                    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
                }
                // This makes sure the page number isn't below 1, or more than our $last page
                if ($pagenum < 1) {
                        $pagenum = 1;
                } else if ($pagenum > $last) {
                        $pagenum = $last;
                }
                // This sets the range of rows to query for the chosen $pagenum
                $limit1 = ' LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
                // This shows the user what page they are on, and the total number of pages
                $textline1 = "Total Items (<b>$rows</b>)";
                $textline2 = "Page <b>$pagenum</b> of <b>$last</b>";

                // thos to bring all users that they need to actavite
                $stmt=$con->prepare("SELECT * FROM taqudform $limit1");
                $stmt->execute(array());
                $TaqudDatas=$stmt->fetchall();
            if (! empty($TaqudDatas)){
            ?>
            <!-- Search form -->
            <section class=" ganiral_admin my-5" dir="rtl">
            <div class="container">
                    <div class="row">
                        <div class="col-12">
                    <div class="ganira_searchbox">
                        <form name="frmSearch" class="form-inline mr-4" action="?do=taqudSearch" method="POST">
                            <?php
                            // ganerate CSRF token to protact Form 
                            if(isset($_POST['CSRF_token_taqudsearchform'])){
                                if($_POST['CSRF_token_taqudsearchform'] == $_SESSION['CSRF_token_taqudsearchform']){
                                }else{
                                    echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
                                }
                                $max_time = 60*60*24;
                                if(isset($_SESSION['CSRF_token_time'])){
                                    $token_time=$_SESSION['CSRF_token_time'];
                                    if(($token_time + $max_time) >= time()){
                                    }else{
                                        unset($_SESSION['CSRF_token_taqudsearchform']);
                                        unset($_SESSION['CSRF_token_time']);
                                        echo "CSRF TOKEN EXPIRED";
                                    }
                                }
                            }
                            $token = md5(uniqid(mt_rand(), true));
                            $_SESSION['CSRF_token_taqudsearchform'] = $token;
                            $_SESSION['CSRF_token_time'] = time();
                            // ganerate CSRF token to protact Form 
                            ?>
                            <input type="hidden" name="CSRF_token_taqudsearchform" value="<?php echo $token; ?>">
                            <input class="form-control search-box arabic-text-align" type="text" name="taqudkeyword" placeholder="بحث" aria-label="Search" >
                            <button class="btn  btn-lg  btn-danger"id="btnsearch"  type="submit" value="Search"><i class="fa fa-search text-white" aria-hidden="true"></i> بحث</button>
                        </form>
                        </div>
                    </div> 
                </div>
            </div>
            <!-- section head -->
            <h2 class=" text-center mb-4 mt-4">بيانات استمارة المتعاقدين </h2>
            <!-- start creat the table to show all data -->
                <div class="container">
                    <div class="row">
                        <div class="col-12 ganiral_datatable">
                            <!--Table-->
                        <table class="main-table table  text-center  align-middle table-bordered table-responsive">
                            <!--Table head-->
                            <thead class="mdb-color darken-3">
                                <tr class="text-white ">
                                    <th>التسلسل</th>
                                    <th>الاسم الرباعي واللقب للمتعاقد</th>
                                    <th>التولد</th>
                                    <th>التحصيل الدراسة</th>
                                    <th>تاريخ الاضافة</th>
                                    <th> لوحة التحكم</th>
                                </tr>
                            </thead>
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                                <?php
                                foreach ($TaqudDatas as $TaqudData) {
                                echo "<tr>";
                                    echo "<td>" . $TaqudData['Taqud_ID'] . "</td>";
                                    echo "<td>" . $TaqudData['Taqud_NameMutaqid'] . "</td>";
                                    echo "<td>" . $TaqudData['Taqud_Tawilid'] . "</td>";
                                    echo "<td>" . $TaqudData['Taqud_Dirasy'] . "</td>";
                                    echo "<td>" . $TaqudData['Taqud_AddDate'] . "</td>";
                                    echo '<td>
                                        <a href="aqidForm.php?do=TaqudPDF&Taqud_ID=' . $TaqudData['Taqud_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
                                        <a href="aqidForm.php?do=Delete&Taqud_ID=' . $TaqudData['Taqud_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
                                    </td>';
                                echo "</tr>";
                                    }
                                ?>
                            </tbody>
                            <!--Table body-->
                        </table>
                    </div>
                </div>
            </div>
                <!-- end creat the table to show all data -->
            </section>
            <?php
        // Establish the $paginationCtrls variable
                    $paginationCtrls = '';
                    // If there is more than 1 page worth of results
                    if($last != 1){
                    /* First we check if we are on page one. If we are then we don't need a link to
                        the previous page or the first page so we do nothing. If we aren't then we
                        generate links to the first page, and to the previous page. */
                        if ($pagenum > 1) {
                            $first = $pagenum * 0 ;
                            $paginationCtrls .= '<li class="btn-pagnation">'.'<a href="'.$_SERVER['PHP_SELF'].'?pn='.$first.'" class="page-link-pagnation">الاول</a></li>';
                        }
                        if ($pagenum > 1) {
                            $previous = $pagenum - 1;
                            $paginationCtrls .= '<li class="btn-pagnation">'.'<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" class="page-link-pagnation">السابق</a></li>';
                            // Render clickable number links that should appear on the left of the target page number
                            for($i = $pagenum-4; $i < $pagenum; $i++){
                                if($i > 0){
                                    $paginationCtrls .= '<li class="btn-pagnation">'.'<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" class="page-link-pagnation">'.$i.'</a></li>';
                                }
                            }
                        }
                        // Render the target page number, but without it being a link
                        $paginationCtrls .= '<div class="btn-pagnation active12">'.''.$pagenum.'</div>';
                        // Render clickable number links that should appear on the right of the target page number
                        for($i = $pagenum+1; $i <= $last; $i++){
                            $paginationCtrls .= '<li class="btn-pagnation">'.'<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" class="page-link-pagnation">'.$i.'</a></li>';
                            if($i >= $pagenum+4){
                                break;
                            }
                        }
                        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                        if ($pagenum != $last) {
                            $next = $pagenum + 1;
                            $paginationCtrls .= '<li class="btn-pagnation">'.'<a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'" class="page-link-pagnation">التالي</a></li>';
                            $paginationCtrls .= '<li class="btn-pagnation">'.'<a href="'.$_SERVER['PHP_SELF'].'?pn='.$last.'" class="page-link-pagnation">الاخير</a></li>';
                        }
                        }
                        ?>
            </section>
            <div class="container pagnation1 mb-5">
                    <span class="font-bold-pagnation" aria-hidden="true">&laquo;</span>
                            <?php echo $paginationCtrls; ?>
                    <span class="font-bold-pagnation" aria-hidden="true">&raquo;</span>
                    <?php
                    echo "<div>";
                    echo '<p class="total-record-search text-center mt-2">عدد السجلات = </span>' . $rows  . '<span class="total-record-search"> الصفحة =' .  $pagenum . '</p> ' ;
                    echo "</div>";
                    ?>
            </div>
            <!-- Section: Blog v.3 -->
                </div>
            <?php
                }

        } else {
            header('Location: index.php');
            exit();
        }
            
		} elseif ($do == 'Add') {?>
            <section class="ganiralform">
                <div class="container ganiralform1" dir="rtl">
                    <h2 class="text-center">تم اغلاق الاستمارة ...مع تحيات مديرية شؤون الشهداء والجرحى</h2>
                </div>
        </section>
        <?php		
		} elseif ($do == 'Insert') {

		} elseif ($do == 'TaqudPDF') {
            if(isset($_SESSION['user'])){

            //============================================================+
            // START OF FILE
            //============================================================+
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
                $this->Cell(0, 15, 'استمارة الراغبين للتعاقد في وزارة الداخلية', 'B', false, 'C', 0, '', 0, false, 'M', 'M');
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
            $pdf->SetTitle('أستمارة الراغبين للتعاقد في وزارة الداخلية');
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
            $Taqud_ID=isset($_GET['Taqud_ID']) && is_numeric($_GET['Taqud_ID']) ? intval($_GET['Taqud_ID']) : 0;
            $stmt=$con->prepare('SELECT
                            *
                        FROM
                        taqudform

                        WHERE	
                            Taqud_ID=?
                        ORDER BY
                            `taqudform`.`Taqud_ID`
                        DESC');
            $stmt->execute(array($Taqud_ID));
            $taquditems=$stmt->fetchall();
            foreach ($taquditems as $item) {
            // -------------------------------------------------------//
            // set font
            $pdf->SetFont('freeserif',0, 16);

            // add a page
            $pdf->AddPage();

            // set some text to print
            $txt = <<<EOD
            بيانات استمارة المتعاقدين - 2023
            EOD;

            // print a block of text using Write()
            $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
            // ---------------------------------------------------------
            //--- التسلسل ---------
            $pdf->SetFont('freeserif',0, 13);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(160, 10,'',0,0);
            $pdf->Cell(10, 10,$item['Taqud_ID'],0,0);
            $pdf->Cell(20, 10,'العدد:',0,1);
            $pdf->Cell(128, 10,'',0,0);
            $pdf->Cell(42, 10,$item['Taqud_AddDate'],0,0);
            $pdf->Cell(20, 10,'التاريخ:',0,1);

            //---  الرتبة واسم الجريح الرباعي ---------
            $txt5='الحالة';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(40, 10,'',0,0,'C');
            $pdf->Cell(100,10,$txt5,1,0,'C');
            $pdf->Cell(40, 10,'',0,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(40, 10,'',0,0,'C');
            $pdf->Cell(100, 10, $item['Taqud_NameMutaqid'],1,0,'C');
            $pdf->Cell(40, 10,'',0,1,'C');
            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            $txt1='الجنس';
            $txt2='التولد';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(140, 10,$txt2,1,0,'C');
            $pdf->Cell(40, 10,$txt1,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(140, 10, $item['Taqud_Tawilid'],1,0,'C');
            $pdf->Cell(40, 10, $item['Taqud_Jins'],1,1,'C');
            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            $txt3='اسم ام المتعاقد';
            $txt4='صلة القرابة';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt4,1,0,'C');
            $pdf->Cell(90, 10,$txt3,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['Taqud_Qaraba'],1,0,'C');
            $pdf->Cell(90, 10, $item['Taqud_NameMotherM'],1,1,'C');
                
            // ---------------------------------------------------------
            //---  رقم وتاريخ الامر مع الملبلغ الكلي---------
            $txt6='محافظة السكن';
            $txt7='العنوان';
            
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);

            $pdf->Cell(90, 10,$txt7,1,0,'C');
            $pdf->Cell(90, 10,$txt6,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['Taqud_Adress'],1,0,'C');
            $pdf->Cell(90, 10, $item['Taqud_Mohaftha'],1,1,'C');

            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            $txt8='التحصيل الدراسي';
            $txt9='رقم الهاتف';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt9,1,0,'C');
            $pdf->Cell(90, 10,$txt8,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['Taqud_PhonNu'],1,0,'C');
            $pdf->Cell(90, 10, $item['Taqud_Dirasy'],1,1,'C');
                
            // ---------------------------------------------------------

            //---  اماكن التواقيع ---------
            $txt11='اسم الشهيد الرباعي';
            $txt12='رقم وتاريخ الامر الاداري بالاستشهاد';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt12,1,0,'C');
            $pdf->Cell(90, 10,$txt11,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['Taqud_OmorDateIdary'],1,0,'C');
            $pdf->Cell(90, 10, $item['Taqud_NameMartyr'],1,1,'C');

            // ---------------------------------------------------------
            //---  اماكن التواقيع ---------
            $txt11='مكان العمل';
            $txt12='تاريخ الاضافة';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt12,1,0,'C');
            $pdf->Cell(90, 10,$txt11,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['Taqud_AddDate'],1,0,'C');
            $pdf->Cell(90, 10, $item['Taqud_Workplace'],1,1,'C');

            // ---------------------------------------------------------
            
            //---  اماكن التواقيع ---------
            $txt11='هامش المكتب';
            $txt12='هامش السيد المدير';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(187,28,39);
            // Title
            $pdf->Cell(180, 10,'',0,1,'C');
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
            $pdf->AddPage();    
            $img_file = K_PATH_IMAGES.'/taqud/'.$item['Taqud_OmorIdary_img'];
            $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
            $pdf->AddPage();    
            $img_file = K_PATH_IMAGES.'/taqud/'.$item['Taqud_personID_img'];
            $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 1);
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
		} elseif ($do == 'taqudSearch') {
            if(isset($_SESSION['user'])){
                if ($_SERVER['REQUEST_METHOD']=='POST'){ 
                    $strKeyword = null;
            
            if(isset($_POST["taqudkeyword"]))
            {
                $strKeyword   =filter_var($_POST["taqudkeyword"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
            }
            if(isset($_GET["taqudkeyword"]))
            {
                $strKeyword   =filter_var($_GET["taqudkeyword"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
            }
            $sql = "SELECT COUNT(*)
                    FROM
                    taqudform 
                    WHERE
                    Taqud_NameMutaqid
                     LIKE
                     '%".$strKeyword."%'
                    OR
                    Taqud_NameMotherM
                    LIKE
                    '%".$strKeyword."%'
                    OR
                    Taqud_NameMartyr
                    LIKE
                    '%".$strKeyword."%'";
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $num_rows = $stmt->fetchColumn();
            $per_page = 50;   // Per Page
            $page1  = 1;
            
            if(isset($_GET["Page"]))
            {
              $page1 = $_GET["Page"];
            }
            
            $prev_page = $page1-1;
            $next_page = $page1+1;
            
            $row_start = (($per_page*$page1)-$per_page);
            if($num_rows<=$per_page)
            {
              $num_pages =1;
            }
            else if(($num_rows % $per_page)==0)
            {
              $num_pages =($num_rows/$per_page) ;
            }
            else
            {
              $num_pages =($num_rows/$per_page);
              $num_pages = (int)$num_pages;
            }
            $row_end = $per_page * $page1;
            if($row_end > $num_rows)
            {
              $row_end = $num_rows;
            }
            $sql =("SELECT
                        *
                    FROM
                    taqudform 
                    WHERE
                    Taqud_NameMutaqid
                     LIKE
                     '%".$strKeyword."%'
                    OR
                    Taqud_NameMotherM
                    LIKE
                    '%".$strKeyword."%'
                    OR
                    Taqud_NameMartyr
                    LIKE
                    '%".$strKeyword."%'
                    ORDER BY
                    `taqudform`.`Taqud_ID`
                    ASC");
                    $limit=" limit " .$row_start  . "," . $row_end;
                    $query = $sql.$limit;
                    $pdo_statement = $con->prepare($query);
                    $pdo_statement->execute();
                    $taqudDatas = $pdo_statement->fetchall();
                    $count=$pdo_statement->rowcount();
              if ($count>0){
                    ?>
                    <!-- section head -->
                    <section  class=" ganiral_admin my-5" dir="rtl">
                    <p id="hform" class="h1 text-center mb-4">الاستمارة  عن المعاملات غير المنجزة</p>
                        <!-- start creat the table to show all data -->
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 ganiral_datatable">
                                        <!--Table-->
                                    <table class="main-table table  text-center  align-middle table-bordered table-responsive">
                                        <!--Table head-->
                                        <thead class="mdb-color darken-3">
                                            <tr class="text-white ">
                                                <th>التسلسل</th>
                                                <th>الاسم الرباعي واللقب للمتعاقد</th>
                                                <th>التولد</th>
                                                <th>التحصيل الدراسة</th>
                                                <th>تاريخ الاضافة</th>
                                                <th> لوحة التحكم</th>
                                            </tr>
                                        </thead>
                                        <!--Table head-->

                                        <!--Table body-->
                                        <tbody>
                                            <?php
                                            foreach ($taqudDatas as $taqudData) {
                                                echo "<tr>";
                                                    echo "<td>" . $taqudData['Taqud_ID'] . "</td>";
                                                    echo "<td>" . $taqudData['Taqud_NameMutaqid'] . "</td>";
                                                    echo "<td>" . $taqudData['Taqud_Tawilid'] . "</td>";
                                                    echo "<td>" . $taqudData['Taqud_Dirasy'] . "</td>";
                                                    echo "<td>" . $taqudData['Taqud_AddDate'] . "</td>";
                                                    echo '<td>
                                                            <a href="aqidForm.php?do=TaqudPDF&Taqud_ID=' . $taqudData['Taqud_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
                                                            <a href="aqidForm.php?do=Delete&Taqud_ID=' . $taqudData['Taqud_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
                                                        </td>';
                                                echo "</tr>";
                                                }
                                            ?>
                                        </tbody>
                                        <!--Table body-->
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end creat the table to show all data -->
                    </section>
                <?php
                }
            
                    // echo our pagnation
                    echo "<div class='container pagnation1 mb-3'>";
                    echo '<span class="font-bold-pagnation" aria-hidden="true">&laquo;</span>';
                    if($prev_page)
                    {
                        echo '<li class="btn-pagnation">'."<a href='$_SERVER[SCRIPT_NAME]?Page=$prev_page&txtKeyword=$strKeyword' class='page-link-pagnation'>Back</a></li>";
                    }
            
                    for($i=1; $i<=$num_pages; $i++){
                        if($i != $page1)
                        {
                            echo '<li class="btn-pagnation">'."<a href='$_SERVER[PHP_SELF]?Page=$i&txtKeyword=$strKeyword' class='page-link-pagnation'>$i</a></li>";
                        }
                        else
                        {
                            echo"<b class='btn-pagnation active12'> $i </b>";
                        }
            
                    }
                    if($page1!=$num_pages)
                    {
                        echo '<li class="btn-pagnation">'."<a href ='$_SERVER[PHP_SELF]?Page=$next_page&txtKeyword=$strKeyword' class='page-link-pagnation'>Next</a></li> ";
                    }
                    $conn = null;
                    echo '<span class="font-bold-pagnation" aria-hidden="true">&raquo;</span>';
                    echo '</div>';
                    echo '<p class="total-record-search text-center mt=0">عدد السجلات = </span>' . $num_rows  . '<span class="total-record-search"> الصفحة =' .  $num_pages . '</p> ' ;
            
                }else{
                    $themsg = "<div class='my_denger'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
                    redirect($themsg,'');
                  }
            
            }else {
                header('location:index.php');
                exit();
            }            

		} elseif ($do == 'Delete') {
        if(isset($_SESSION['user'])){
            // check if get request itemid is numeric & get the integer value of it
            $Taqud_ID =isset($_GET['Taqud_ID']) && is_numeric($_GET['Taqud_ID']) ? intval($_GET['Taqud_ID']) : 0;
            // select all data depend on this id
            $chek=checkitem("Taqud_ID" , "taqudform" , $Taqud_ID);
            if ($chek > 0) {
                $stmt=$con->prepare("DELETE FROM taqudform WHERE Taqud_ID=:zaqud" );
                $stmt->bindparam(":zaqud",$Taqud_ID);
                $stmt->execute();
                $themsg = "<div class='my_succsuflly' role='alert'>تمت عملية الحذف بنجاح </div>" ;
                redirect($themsg,'');

            } else {
                echo "<div class='container'>";
                $themsg = "<div class='my_denger'> لا يوجد سجل بهذا التسلسل </div>";
                redirect($themsg,'');
                echo "</div>";
                }

            }
		} elseif ($do == 'Activate') {


		}

		include $tpl . 'footer.php';

	ob_end_flush(); // Release The Output

?>
