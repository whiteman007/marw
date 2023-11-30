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

	$pagetitle = 'استمارة اطفاء السلف';



		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {
            if(isset($_SESSION['user'])){
                // START OUR PAGNATION BOX
                $rows=countitem('muqabalat_ID ' , 'muqabalat') ;
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
                $stmt=$con->prepare("SELECT * FROM muqabalat $limit1");
                $stmt->execute(array());
                $muqabalats=$stmt->fetchall();
            if (! empty($muqabalats)){
            ?>
            <!-- Search form -->
            <section class=" ganiral_admin my-5" dir="rtl">
            <div class="container">
                    <div class="row">
                        <div class="col-12">
                    <div class="ganira_searchbox">
                        <form name="frmSearch" class="form-inline mr-4" action="?do=muqabalatSearch" method="POST">
                            <?php
                            // ganerate CSRF token to protact Form 
                            if(isset($_POST['CSRF_token_muqabalatsearchform'])){
                                if($_POST['CSRF_token_muqabalatsearchform'] == $_SESSION['CSRF_token_muqabalatsearchform']){
                                }else{
                                    echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
                                }
                                $max_time = 60*60*24;
                                if(isset($_SESSION['CSRF_token_time'])){
                                    $token_time=$_SESSION['CSRF_token_time'];
                                    if(($token_time + $max_time) >= time()){
                                    }else{
                                        unset($_SESSION['CSRF_token_muqabalatsearchform']);
                                        unset($_SESSION['CSRF_token_time']);
                                        echo "CSRF TOKEN EXPIRED";
                                    }
                                }
                            }
                            $token = md5(uniqid(mt_rand(), true));
                            $_SESSION['CSRF_token_muqabalatsearchform'] = $token;
                            $_SESSION['CSRF_token_time'] = time();
                            // ganerate CSRF token to protact Form 
                            ?>
                            <input type="hidden" name="CSRF_token_muqabalatsearchform" value="<?php echo $token; ?>">
                            <input class="form-control search-box arabic-text-align" type="text" name="muqabalatkeyword" placeholder="بحث" aria-label="Search" >
                            <button class="btn  btn-lg  btn-danger"id="btnsearch"  type="submit" value="Search"><i class="fa fa-search text-white" aria-hidden="true"></i> بحث</button>
                        </form>
                        </div>
                    </div> 
                </div>
            </div>
            <!-- section head -->
            <h2 class=" text-center mb-4 mt-4">بيانات استمارة اطفاء السلف</h2>
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
                                    <th>الحالة</th>
                                    <th>الاسم الرباعي واللقب</th>
                                    <th>اسم صاحب الطلب</th>
                                    <th>صلة القرابة</th>
                                    <th>تاريخ الاضافة</th>
                                    <th> لوحة التحكم</th>
                                </tr>
                            </thead>
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                                <?php
                                foreach ($muqabalats as $muqabalat) {
                                echo "<tr>";
                                    echo "<td>" . $muqabalat['muqabalat_ID'] . "</td>";
                                    echo "<td>" . $muqabalat['muqabalat_personstatus'] . "</td>";
                                    echo "<td>" . $muqabalat['muqabalat_fullnam'] . "</td>";
                                    echo "<td>" . $muqabalat['muqabalat_namerequster'] . "</td>";
                                    echo "<td>" . $muqabalat['muqabalat_relation'] . "</td>";
                                    echo "<td>" . $muqabalat['muqabalat_date_add'] . "</td>";
                                    echo '<td>
                                        <a href="muqabalat.php?do=muqabalatPDF&muqabalatID=' . $muqabalat['muqabalat_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
                                        <a href="muqabalat.php?do=Delete&muqabalatID=' . $muqabalat['muqabalat_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
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
                        <div class="row">
                            <div class="col-md-12 importantnotice">
                                <span style="color: red;font-weight:bold;font-size:20px">ملاحظات مهمة جدا:</span>
                                <p>1-نرجو مراعاه أدخال المعلومات باللغة العربية حصرا وبصورة صحيحة في الاستمارة ليتم اجراء اللازم من قبلنا   </p>
                                <p>2- سيتم ارسال رسالة على رقم الهاتف واعلامكم الاجراء لذا يرجى ادخال رقم هاتف يعمل </p>
                                <p>3- يرجى املاء جميع الحقول لضرورة المعلومات المطلوبة </p>
                            </div>
                        </div>
                    <h2 class="text-center">استمارة المقابلات الخاصة بمديرية شؤون الشهداء والجرحى</h2>
                    <form class="mrtyrsform" name="Silaf_Form" action="?do=Insert"  method="POST" enctype="multipart/form-data">
                    <?php
                    // ganerate CSRF token to protact Form 
                    if(isset($_POST['CSRF_tokenmuqabalat'])){
                        if($_POST['CSRF_tokenmuqabalat'] == $_SESSION['CSRF_tokenmuqabalat']){
                        }else{
                            echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
                        }
                        $max_time = 60*60*24;
                        if(isset($_SESSION['CSRF_token_time'])){
                            $token_time=$_SESSION['CSRF_token_time'];
                            if(($token_time + $max_time) >= time()){
                            }else{
                                unset($_SESSION['CSRF_tokenmuqabalat']);
                                unset($_SESSION['CSRF_token_time']);
                                echo "CSRF TOKEN EXPIRED";
                            }
                        }
                    }
                    $token = md5(uniqid(mt_rand(), true));
                    $_SESSION['CSRF_tokenmuqabalat'] = $token;
                    $_SESSION['CSRF_token_time'] = time();
                    // ganerate CSRF token to protact Form 
                    ?>
                    <input type="hidden" name="CSRF_tokenmuqabalat" value="<?php echo $token; ?>">
                    <div class="row">
                        <h4>معلومات منسوب وزارة الداخلية</h4>
                        <div class="col-md-12 mb-2">
                            <label for="person_statuse2">(شهيد\متوفي\مفقود\جريح)<span class="not2">*</span></label>
                            <select class="custom-select gineralselect " name="person_statuse2">
                                <option value="empty" >...</option>
                                <option value="شهيد">شهيد </option>
                                <option value="متوفي">متوفي </option>
                                <option value="مفقود">مفقود </option>
                                <option value="جريح">جريح </option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2 جريح box">
                        <label for="status_wound2">حالة الجريح متقاعد/مستمر <span class="not2">*</span></label>
                            <select class="custom-select" name="status_wound2" id="status_wound2">
                                    <option value="empty" >...</option>
                                    <option value="متقاعد">متــقاعد </option>
                                    <option value="مستمر">مستــــمر </option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="mratba">الرتبة <span class="not2">*</span></label>
                            <select class="custom-select" name="mratba" id="mratba">
                                <option value="empty">...</option>
                                <option value="فريق اول ركن">|فريق اول ركن|</option>
                                <option value="فريق اول">|فريق اول|</option>
                                <option value="فريق">|فريق ركن|</option>
                                <option value="فريق">|فريق|</option>
                                <option value="لواء ركن">|لواء ركن|</option>
                                <option value="لواء">|لواء|</option>
                                <option value="عميد ركن">|عميد ركن|</option>
                                <option value="عميد">|عميد|</option>
                                <option value="عقيد ركن">|عقيد ركن|</option>
                                <option value="عقيد">|عقيد|</option>
                                <option value="مقدم ركن">|مقدم ركن|</option>
                                <option value="مقدم">|مقدم|</option>
                                <option value="رائد ركن">|رائد ركن|</option>
                                <option value="رائد">|رائد|</option>
                                <option value="نقيب">|نقيب|</option>
                                <option value="ملازم اول">|ملازم اول|</option>
                                <option value="ملازم">|ملازم|</option>
                                <option value="مفوض">|مفوض|</option> 
                                <option value="رئيس عرفاء">|رئيس عرفاء|</option>
                                <option value="عريف">|عريف|</option>
                                <option value="ن.ع">|ن.ع|</option>
                                <option value="شرطي اول">|شرطي اول|</option>
                                <option value="شرطي">|شرطي|</option>
                                <option value="حارس امني">|حارس امني|</option>
                                <option value="موظف">|موظف|</option>
                                <option value="لايوجد">|لايوجد|</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="Name_fourth2">الاسم الرباعي واللقب<span class="not2">*</span></label>
                            <input type="text" name="Name_fourth2" class="form-control arabic-input"  id="Name_fourth2" placeholder=" يرجى ادخال اسم الرباعي واللقب">
                        </div>
                        <div class="col-md-6 mb-2 جريح box">
                            <label for="percentofwounded2">نسبة العجز ان وجدت<span class="not2">*</span></label>
                            <input type="text" name="percentofwounded2" class="form-control arabic-input"  id="percentofwounded2" placeholder="يرجى ادخال نسبة العجز ان وجدت ">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="Date_ofdeath2">تاريخ (الاستشهاد / الوفاه / الفقدان /الاصابة)<span class="not2">*</span></label>
                            <input type="text" name="Date_ofdeath2" class="form-control  arabic-input"  id="Date_ofdeath2" placeholder=" يرجى ادخال التاريخ يوم شهر سنة بدون رموز"  >
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="Mother_name2">أسم ألام الثلاثي<span class="not2">*</span></label>
                            <input type="text" name="Mother_name2" class="form-control arabic-input"  id="Mother_name2" placeholder="يرجى ادخال اسم الام الثلاثي"  >
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="Directry_name2">أسم الدائرة التي يعمل بها<span class="not2">*</span></label>
                            <input type="text" name="Directry_name2" class="form-control arabic-input"  id="Directry_name2" placeholder="يرجى ادخال اسم الدائرة  "  >
                        </div>
                        <div class="col-md-6  mb-2">
                            <label for="number_data_book2" class="mb-1">رقم وتاريخ الامر الاداري</label>
                            <input type="text" name="number_data_book2" class="form-control arabic-input"  id="number_data_book2" placeholder=" يرجى ادخال رقم وتاريخ الامر الاداري بدون رموز ان وجد"  >
                        </div>
                        <div class="col-md-6  mb-2">
                            <label for="number_taqaud2" class="mb-1">الرقم التقاعدي</label>
                            <input type="text" name="number_taqaud2" class="form-control arabic-input"  id="number_taqaud2" placeholder="يرجى ادخال الرقم التقاعدي رقماً ان وجد"  >
                        </div>            
                        <div class="col-md-6 mb-2">
                            <label for="mrgstatus">الحالة الاجتماعية<span class="not2">*</span></label>
                            <select class="custom-select istilamat_mrgStatus " name="mrgstatus" id="mrgstatus">
                                <option value="empty" >...</option>
                                <option value="متزوج">متزوج </option>
                                <option value="مطلق">مطلق</option>
                                <option value="اعزب">اعزب</option>
                                <option value="لايوجد">لايوجد</option>
                            </select>
                        </div>
                        <div class="col-md-6 متزوج مطلق istilamatmarigbox mb-2">
                            <label for="wifeName">اسم الزوجة<span class="not2">*</span></label>
                            <input type="text" name="wifeName" class="form-control arabic-input"  id="wifeName" placeholder="يجب ادخال اسم الزوجة"  >
                        </div>            
                        <div class="col-md-6 متزوج مطلق  istilamatmarigbox mb-2">
                            <label for="childNumber">عدد الاطفال<span class="not2">*</span></label>
                            <input type="text" name="childNumber" class="form-control arabic-input"  id="childNumber" placeholder="يجب ادخال عدد الاطفال"  >
                        </div>            
                        <div class="col-md-6  شهيد متوفي مفقود box mb-2">
                            <label for="kafin2">هل تم استلام الكفن والدفن؟<span class="not2">*</span></label>
                            <select class="custom-select " name="kafin2" id="kafin2">
                                <option value="empty" >...</option>
                                <option value="نعم"> نـعم </option>
                                <option value="لا"> لا</option>
                            </select>
                        </div>
                        <div class="col-md-6  شهيد متوفي مفقود جريح box mb-2">
                            <label for="vication2">هل تم استلام الاجازات المتراكمة؟<span class="not2">*</span></label>
                            <select class="custom-select " name="vication2" id="vication2">
                                <option value="empty" selected value="0">...</option>
                                <option value="نعم"> نـعم</option>
                                <option value="لا"> لا</option>
                            </select>
                        </div>
                        <div class="col-md-6 شهيد مفقود متوفي جريح box mb-2">
                            <label for="serveses2">هل تم استلام مكافئة نهاية الخدمة ؟<span class="not2">*</span></label>
                            <select class="custom-select" name="serveses2" id="serveses2">
                                <option value="empty" >...</option>
                                <option value="نعم"> نـعم</option>
                                <option value="لا"> لا</option>
                            </select>
                        </div>
                        <div class="col-md-6 شهيد مفقود متوفي جريح box mb-2">
                            <label for="salary2">هل تم استلام راتب التقاعد؟<span class="not2">*</span></label>
                            <select class="custom-select " name="salary2" id="salary2">
                                <option value="empty" >...</option>
                                <option value="نعم"> نـعم</option>
                                <option value="لا"> لا </option>
                            </select>
                        </div>
                        <div class="col-md-6  جريح box mb-2">
                            <label for="isaba2">هل تم استلام مكافئة الاصابة ؟<span class="not2">*</span></label>
                            <select class="custom-select " name="isaba2" id="isaba2">
                                <option value="empty" >..</option>
                                <option value="نعم"> نـعم</option>
                                <option value="لا"> لا</option>
                            </select>
                        </div>
                        <h4> معلومات صاحب الطلب</h4>
                        <div class="col-md-6 mb-2">
                            <label for="name_of_form2">أسم صاحب الطلب<span class="not2">*</span></label>
                            <input type="text" name="name_of_form2" class="form-control arabic-input"  id="name_of_form2" placeholder="يرجى ادخال اسم مقدم الطلب"  >
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="relative_relation2">صلة القرابة<span class="not2">*</span></label>
                            <select class="custom-select " name="relative_relation2" id="relative_relation2">
                                <option value="empty" >...</option>
                                <option value="اب"> أب</option>
                                <option value="ام"> أم</option>
                                <option value="ابن"> ابن</option>
                                <option value="ابنة"> ابنة</option>
                                <option value="اخ"> أخ</option>
                                <option value="اخت"> أخت</option>
                                <option value="زوجة"> زوجة</option>
                                <option value="نفسة"> نفسة</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="Number_phon2">رقم الهاتف <span class="not2">*</span></label>
                            <input type="text" name="Number_phon2" class="form-control arabic-input"  id="Number_phon2" placeholder="يرجى ادخال رقم هاتف يعمل"  >
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="Adress_gform2">العنوان<span class="not2">*</span></label>
                            <input type="text" name="Adress_gform2" class="form-control arabic-input"  id="Adress_gform2" placeholder="يرجى ادخال العنوان كامل محافظة  محلة زقاق  دار بدون رموز"  >
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="desc2">توضيح صاحب الطلب وعرض المشكلة<span class="not2">*</span></label>
                            <textarea class="form-control arabic-input" name="desc2" id="desc2"  placeholder="يرجى ادخال توضيح مختصر وعرض المشكلة " rows="6" maxlength = "600"></textarea>
                        </div>
                    </div>
                    <!-- start submait  -->
                    <div class="text-center mt-2 mb-2 ">
                        <input class="btn btn-danger" type="submit" value="ارسال" >
                    </div>
                </form>
            </div>
        </section>
        <?php		
		} elseif ($do == 'Insert') {
            if ($_SERVER['REQUEST_METHOD']=='POST') {
                // get the varaible from the form
                $person_statuse     	=filter_var($_POST['person_statuse2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $person_statuse 		= preg_replace('/[^\p{L}\p{N}\s]/u',' ', $person_statuse);

                $Statuswounded      	=filter_var($_POST['status_wound2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Statuswounded 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $Statuswounded);

                $ritba      			=filter_var($_POST['mratba'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $ritba 				    = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $ritba);


                $Namefourth      		=filter_var($_POST['Name_fourth2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Namefourth 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $Namefourth);

                $uniq_user=$con->prepare("SELECT
                            *
                    FROM
                        muqabalat

                    WHERE
                        muqabalat_fullnam='$Namefourth'");

                $uniq_user->execute();
                $fullnamecount=$uniq_user->rowcount();

                $Percent      			=filter_var($_POST['percentofwounded2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Percent 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $Percent);


                $Date_ofdeath2   		=filter_var($_POST['Date_ofdeath2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Date_ofdeath2 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $Date_ofdeath2);
 

                $motherName   			=filter_var($_POST['Mother_name2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $motherName 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $motherName);

                $uniq_user=$con->prepare("SELECT
                            *
                    FROM
                        muqabalat

                    WHERE
                        muqabalat_mothername='$motherName'");

                $uniq_user->execute();
                $mothercount=$uniq_user->rowcount();

                $workplace   			=filter_var($_POST['Directry_name2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $workplace 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $workplace);

                $nnumDateOmor   		=filter_var($_POST['number_data_book2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $nnumDateOmor 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $nnumDateOmor);

                $nutaqaud   			=filter_var($_POST['number_taqaud2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $nutaqaud 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $nutaqaud);

                $mrgstatus   			=filter_var($_POST['mrgstatus'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $mrgstatus 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $mrgstatus);


                $wifename     			=filter_var($_POST['wifeName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $wifename 				= preg_replace('/[^\p{L}\p{N}\s]/u', '', $wifename);

                $nuchild     		    =filter_var($_POST['childNumber'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $nuchild 			    = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $nuchild);

                $kafin2     			=filter_var($_POST['kafin2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $kafin2 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $kafin2);

                $vication2     			=filter_var($_POST['vication2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $vication2 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $vication2);

                $serveses2     		    =filter_var($_POST['serveses2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $serveses2 			    = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $serveses2);

                $salary2     			=filter_var($_POST['salary2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $salary2 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $salary2);

                $isaba2     		    =filter_var($_POST['isaba2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $isaba2 			    = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $isaba2);

                $name_of_form2     		=filter_var($_POST['name_of_form2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $name_of_form2 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $name_of_form2);


                $relative_relation2     =filter_var($_POST['relative_relation2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $relative_relation2 	= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $relative_relation2);

                $Number_phon2     		=filter_var($_POST['Number_phon2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Number_phon2 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $Number_phon2);

                $Adress_gform2     		=filter_var($_POST['Adress_gform2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Adress_gform2 			= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $Adress_gform2);

                $desc2     		        =filter_var($_POST['desc2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $desc2 			        = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $desc2);

                 

                //validat Errors
                $formerror=array();
                //img1 istshad
                

                if($person_statuse == "empty"){
                    $formerror[]='يجب اختيار حالة الشخص  شهيد او جريح او متوفي او مفقود';
                }
                if ($mothercount > 0) {
                    $formerror[]='لقد تم ارسال بياناتك مسبقا';
                }
                if ($fullnamecount > 0) {
                    $formerror[]='لقد تم ارسال بياناتك مسبقا';
                }
                if(($person_statuse == "جريح") && ($Statuswounded == "empty")){
                    $formerror[]='يجب بيان حالة الجريح متقاعد او مستمر';
                }
                if($ritba == "empty"){
                    $formerror[]='لا يمكن ترك حقل الرتبة فارغاً';
                }
                if((empty($Namefourth))){
                    $formerror[]='لا يمكن ترك حقل اسم الرباعي واللقب فارغاً';
                }

                if(($person_statuse == "جريح") && (empty($Percent))){
                    $formerror[]='لا يمكن ترك حقل نسبة العجز فارغاً';
                }
                if((empty($Date_ofdeath2))){
                    $formerror[]='لا يمكن ترك حقل تاريخ الاستشهاد او الاصابة او الوفاه او الفقدان فارغاً';
                }

                if((empty($motherName))){
                    $formerror[]='لا يمكن ترك حقل الاسم الام  فارغاً';
                }
                if((empty($workplace))){
                    $formerror[]='لا يمكن ترك حقل مكان العمل فارغاً';
                }
                if($mrgstatus == "empty"){
                    $formerror[]='يجب اختيار الحالة الاجتماعية';
                }
                if(($mrgstatus == "متزوج") ^ ($mrgstatus == "مطلق")  && (empty($wifename))){
                    $formerror[]='لا يمكن ترك حقل اسم الزوجة فارغاً';
                }
                if(($mrgstatus == "متزوج") ^ ($mrgstatus == "مطلق")  && (empty($nuchild))){
                    $formerror[]='لا يمكن ترك حقل عدد الاطفال فارغاً';
                }
                if (($person_statuse == "شهيد") ^ ($person_statuse == "متوفي") ^ ($person_statuse == "مفقود") && ($kafin2 == "empty")){
                    $formerror[]='يجب الاجابة بنعم او لا لحقل الكفن والدفن';
                }
                if (($person_statuse == "شهيد") ^ ($person_statuse == "متوفي") ^ ($person_statuse == "مفقود") && ($vication2 == "empty")){
                    $formerror[]='يجب الاجابة بنعم او لا لحقل الاجازات التراكمة';
                }
                if (($person_statuse == "شهيد") ^ ($person_statuse == "متوفي") ^ ($person_statuse == "مفقود") && ($serveses2 == "empty")){
                    $formerror[]='يجب الاجابة بنعم او لا لحقل نهاية الخدمة';
                }
                if (($person_statuse == "شهيد") ^ ($person_statuse == "متوفي") ^ ($person_statuse == "مفقود") && ($salary2 == "empty")){
                    $formerror[]='يجب الاجابة بنعم او لا لحقل الراتب التقاعدي';
                }
                if(($person_statuse == "جريح") && ($isaba2 == "empty")){
                    $formerror[]='يجب الاجابة بنعم او لا لحقل مكافئة الاصابة';
                }
                if((empty($name_of_form2))){
                    $formerror[]='لا يمكن ترك حقل اسم صاحب الطلب فارغاً';
                }
                if($relative_relation2 == "empty"){
                    $formerror[]='يجب اختيار صلة القرابة';
                }
                if((empty($Number_phon2))){
                    $formerror[]='لا يمكن ترك حقل رقم الهاتف فارغاً';
                }
                if((empty($Adress_gform2))){
                    $formerror[]='لا يمكن ترك حقل العنوان فارغاً';
                }
                if((empty($desc2))){
                    $formerror[]='لا يمكن ترك حقل توضيح صاحب الطلب فارغاً';
                }


                
                foreach ($formerror as $error) {
                    echo '<div class="my_denger" role="alert">' . $error. '</div>';
                }

                if (empty($error)) {
                $stmt=$con->prepare("INSERT INTO
                                        muqabalat(muqabalat_personstatus,muqabalat_woundedstatus,muqabalat_ritba,muqabalat_fullnam,muqabalat_ajaz,muqabalat_dateofdeath,muqabalat_mothername,muqabalat_workplace,
                                        muqabalat_numdateidari,muqabalat_numtaqud,muqabalat_mrgstatus,muqabalat_wifename,muqabalat_childNum,muqabalat_kafan,muqabalat_mutraqim,muqabalat_serveses,
                                        muqabalat_salary,muqabalat_isaba,muqabalat_namerequster,muqabalat_relation,muqabalat_phonnumber,muqabalat_adress,muqabalat_desc,muqabalat_date_add)
                                     VALUES
                                        (:zperson,:zwoundstatus,:zritba,:zfullname,:zajaz,:zdeath,:zmother,:zworkpls,:znudatidari,:znudattaqud,:zmrgstatus,:zwifename,:zchild,:zkafan,:zmutrakim,:zserveces,
                                        :zsalary,:zisaba,:znamerequster,:zrelasion,:zphon,:zadress,:zdes,now())");
                            $stmt->execute(array(
                                'zperson'     	    =>$person_statuse,
                                'zwoundstatus'     	=>$Statuswounded,
                                'zritba'            =>$ritba,
                                'zfullname'     	=>$Namefourth,
                                'zajaz'     	    =>$Percent,
                                'zdeath'     	    =>$Date_ofdeath2,
                                'zmother'     	    =>$motherName,
                                'zworkpls'   	  	=>$workplace,
                                'znudatidari'       =>$nnumDateOmor,
                                'znudattaqud'     	=>$nutaqaud,
                                'zmrgstatus'   	    =>$mrgstatus,
                                'zwifename'	   	    =>$wifename,
                                'zchild'     		=>$nuchild,
                                'zkafan'     		=>$kafin2,
                                'zmutrakim'         =>$vication2,
                                'zserveces'         =>$serveses2,
                                'zsalary'     	    =>$salary2,
                                'zisaba'     	    =>$isaba2,
                                'znamerequster'     =>$name_of_form2,
                                'zrelasion'     	=>$relative_relation2,
                                'zphon'     	    =>$Number_phon2,
                                'zadress'     	    =>$Adress_gform2,
                                'zdes'     	        =>$desc2
                            ));

                        //echo success message
                        $themsg = "<div class='my_succsuflly' role='alert'>تمت ارسال طلبك بنجاح </div>" ;
                        redirect($themsg,'');

            }

        } else {
            $themsg = "<div class='my_denger'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
            redirect($themsg,'');
        }

		} elseif ($do == 'muqabalatPDF') {
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
                $this->Cell(0, 15, 'أستمارة المقابلات الخاصة بمديرية شؤون الشهداء والجرحى', 'B', false, 'C', 0, '', 0, false, 'M', 'M');
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
            $pdf->SetTitle('أستمارة المقابلات الخاصة بمديرية شؤون الشهداء والجرحى');
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
            $muqabalatID=isset($_GET['muqabalatID']) && is_numeric($_GET['muqabalatID']) ? intval($_GET['muqabalatID']) : 0;
            $stmt=$con->prepare('SELECT
                            *
                        FROM
                        muqabalat

                        WHERE	
                        muqabalat_ID=?
                        ORDER BY
                            `muqabalat`.`muqabalat_ID`
                        DESC');
            $stmt->execute(array($muqabalatID));
            $muqabalats=$stmt->fetchall();
            foreach ($muqabalats as $item) {
            // -------------------------------------------------------//
            // set font
            $pdf->SetFont('freeserif',0, 16);

            // add a page
            $pdf->AddPage();

            // set some text to print
            $txt = <<<EOD
            بيانات استمارات المقابلات - 2023
            EOD;

            // print a block of text using Write()
            $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
            // ---------------------------------------------------------
            //--- التسلسل ---------
            $pdf->SetFont('freeserif',0, 13);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(160, 10,'',0,0);
            $pdf->Cell(10, 10,$item['muqabalat_ID'],0,0);
            $pdf->Cell(20, 10,'العدد:',0,1);
            $pdf->Cell(128, 10,'',0,0);
            $pdf->Cell(42, 10,$item['muqabalat_date_add'],0,0);
            $pdf->Cell(20, 10,'التاريخ:',0,1);

            //---  الرتبة واسم الجريح الرباعي ---------
            $txt0='الحالة';
            $txt1='الرتبة';
            $txt2='الاسم الرباعي واللقب';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90,10,$txt2,1,0,'C');
            $pdf->Cell(45, 10,$txt1,1,0,'C');
            $pdf->Cell(45, 10,$txt0,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_fullnam'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_ritba'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_personstatus'],1,1,'C');
            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            if ($item['muqabalat_personstatus']== "جريح"){
            $txt3='حالة الجريح';
            $txt4='نسبة العجز';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt4,1,0,'C');
            $pdf->Cell(90, 10,$txt3,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_ajaz'],1,0,'C');
            $pdf->Cell(90, 10, $item['muqabalat_woundedstatus'],1,1,'C');
            }
            // ---------------------------------------------------------
            //---  رقم وتاريخ الامر مع الملبلغ الكلي---------
            $txt6='تاريخ الحادث';
            $txt7='رقم وتاريخ الامر الاداري';
            
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);

            $pdf->Cell(90, 10,$txt7,1,0,'C');
            $pdf->Cell(90, 10,$txt6,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_numdateidari'],1,0,'C');
            $pdf->Cell(90, 10, $item['muqabalat_dateofdeath'],1,1,'C');

            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            $txt8='مكان العمل';
            $txt9='اسم الام الثلاثي';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt9,1,0,'C');
            $pdf->Cell(90, 10,$txt8,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_mothername'],1,0,'C');
            $pdf->Cell(90, 10, $item['muqabalat_workplace'],1,1,'C');
                
            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            $txt10='الرقم التقاعدي';
            $txt11='الحالة الاجتماعية';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt11,1,0,'C');
            $pdf->Cell(90, 10,$txt10,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_mrgstatus'],1,0,'C');
            $pdf->Cell(90, 10, $item['muqabalat_numtaqud'],1,1,'C');
                
            // ---------------------------------------------------------
            //---  الرتبة واسم الجريح الرباعي ---------
            $txt12='اسم الزوجة';
            $txt13='عدد الاطفال';
            // Set font
            if($item ['muqabalat_mrgstatus']=='متزوج' ^ $item ['muqabalat_mrgstatus']=='مطلق'){

            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt13,1,0,'C');
            $pdf->Cell(90, 10,$txt12,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_childNum'],1,0,'C');
            $pdf->Cell(90, 10, $item['muqabalat_wifename'],1,1,'C');    
            }
            // ---------------------------------------------------------
            // ---------------------------------------------------------
            
            $txt14='كفن';
            $txt15='متراكم';
            $txt16='نهاية خدمة';
            $txt17='راتب تقاعدي';
            $txt117='مكافئة الاصابة';
            
            if($item ['muqabalat_personstatus']=='شهيد' ^  $item ['muqabalat_personstatus']=='متوفي' ^  $item ['muqabalat_personstatus']=='مفقود' ){

            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(45, 10,$txt17,1,0,'C');
            $pdf->Cell(45, 10,$txt16,1,0,'C');
            $pdf->Cell(45, 10,$txt15,1,0,'C');
            $pdf->Cell(45, 10,$txt14,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(45, 10, $item['muqabalat_salary'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_serveses'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_mutraqim'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_kafan'],1,1,'C');    
            }else{
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(45, 10,$txt17,1,0,'C');
            $pdf->Cell(45, 10,$txt16,1,0,'C');
            $pdf->Cell(45, 10,$txt15,1,0,'C');
            $pdf->Cell(45, 10,$txt117,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(45, 10, $item['muqabalat_salary'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_serveses'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_mutraqim'],1,0,'C');
            $pdf->Cell(45, 10, $item['muqabalat_isaba'],1,1,'C');    
            }

            // ---------------------------------------------------------
            //---  اماكن التواقيع ---------
            $txt18='اسم صاحب الطلب';
            $txt19='صلة القرابة';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(90, 10,$txt19,1,0,'C');
            $pdf->Cell(90, 10,$txt18,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(90, 10, $item['muqabalat_relation'],1,0,'C');
            $pdf->Cell(90, 10, $item['muqabalat_namerequster'],1,1,'C');

            // ---------------------------------------------------------
            //---  اماكن التواقيع ---------
            $txt20='رقم الهاتف';
            $txt21='العنوان';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            $pdf->Cell(120, 10,$txt21,1,0,'C');
            $pdf->Cell(60, 10,$txt20,1,1,'C');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(120, 10, $item['muqabalat_adress'],1,0,'C');
            $pdf->Cell(60, 10, $item['muqabalat_phonnumber'],1,1,'C');

            // ---------------------------------------------------------
            
            //---  اماكن التواقيع ---------
            $txt23='سبب المقابلة ';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(28,3,156);
            // Title
            $pdf->Cell(180, 10,$txt23,1,1,'R');
            $pdf->SetFont('freeserif',0, 12);
            $pdf->SetTextColor(0,0,0);
            $pdf->MultiCell(180, 30, $item['muqabalat_desc'],1,'R', false);
            // ---------------------------------------------------------
            //---  اماكن التواقيع ---------
            $txt24='الهامش ';
            // Set font
            $pdf->SetFont('freeserif','B', 13);
            // color
            $pdf->SetTextColor(187,28,39);
            // Title
            $pdf->Cell(160, 20,'',1,0,'C');
            $pdf->Cell(20, 20,$txt24,1,1,'C');
            // ---------------------------------------------------------

            // -- set new background ---
            // get the current page break margin
            $bMargin = $pdf->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $pdf->getAutoPageBreak();
            // disable auto-page-break
            $pdf->SetAutoPageBreak(false, 0);
            // set bacground image
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

		} elseif ($do == 'muqabalatSearch') {
            if(isset($_SESSION['user'])){
                if ($_SERVER['REQUEST_METHOD']=='POST'){ 
                    $strKeyword = null;
            
            if(isset($_POST["muqabalatkeyword"]))
            {
                $strKeyword   =filter_var($_POST["muqabalatkeyword"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
            }
            if(isset($_GET["muqabalatkeyword"]))
            {
                $strKeyword   =filter_var($_GET["muqabalatkeyword"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
            }
            $sql = "SELECT COUNT(*)
                    FROM
                    muqabalat
                    WHERE
                    muqabalat_fullnam
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
                    muqabalat
                    WHERE
                    muqabalat_fullnam
                     LIKE
                     '%".$strKeyword."%'
                    ORDER BY
                    `muqabalat`.`muqabalat_ID`
                    ASC");
                    $limit=" limit " .$row_start  . "," . $row_end;
                    $query = $sql.$limit;
                    $pdo_statement = $con->prepare($query);
                    $pdo_statement->execute();
                    $muqabalats = $pdo_statement->fetchall();
                    $count=$pdo_statement->rowcount();
              if ($count>0){
                    ?>
                    <!-- section head -->
                    <section  class=" ganiral_admin my-5" dir="rtl">
                    <p id="hform" class="h1 text-center mb-4">الاستمارة الشاملة عن المعاملات غير المنجزة</p>
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
                                                    <th>الحالة</th>
                                                    <th>الاسم الرباعي واللقب</th>
                                                    <th>اسم صاحب الطلب</th>
                                                    <th>صلة القرابة</th>
                                                    <th>تاريخ الاضافة</th>
                                                    <th> لوحة التحكم</th>
                                                </tr>
                                            </thead>
                                            <!--Table head-->

                                            <!--Table body-->
                                            <tbody>
                                                <?php
                                                foreach ($muqabalats as $muqabalat) {
                                                echo "<tr>";
                                                    echo "<td>" . $muqabalat['muqabalat_ID'] . "</td>";
                                                    echo "<td>" . $muqabalat['muqabalat_personstatus'] . "</td>";
                                                    echo "<td>" . $muqabalat['muqabalat_fullnam'] . "</td>";
                                                    echo "<td>" . $muqabalat['muqabalat_namerequster'] . "</td>";
                                                    echo "<td>" . $muqabalat['muqabalat_relation'] . "</td>";
                                                    echo "<td>" . $muqabalat['muqabalat_date_add'] . "</td>";
                                                    echo '<td>
                                                        <a href="muqabalat.php?do=muqabalatPDF&muqabalatID=' . $muqabalat['muqabalat_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
                                                        <a href="muqabalat.php?do=Delete&muqabalatID=' . $muqabalat['muqabalat_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
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
            $muqabalatID=isset($_GET['muqabalatID']) && is_numeric($_GET['muqabalatID']) ? intval($_GET['muqabalatID']) : 0;
            // select all data depend on this id
            $chek=checkitem("muqabalat_ID " , "muqabalat" , $muqabalatID);
            if ($chek > 0) {
                $stmt=$con->prepare("DELETE FROM muqabalat WHERE muqabalat_ID=:zmuqabalat" );
                $stmt->bindparam(":zmuqabalat",$muqabalatID);
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
