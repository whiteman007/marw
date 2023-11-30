<?php


		include 'session.php';
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
			if(isset($_SESSION['user'])){
				// START OUR PAGNATION BOX
				$rows=countitem('Martyr_ID' , 'onlinform') ;
				$page_rows=50;
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
			 $stmt=$con->prepare("SELECT * FROM onlinform $limit1");
			 $stmt->execute();
			 $members=$stmt->fetchall();
			if (! empty($members)){
		   ?>
		   	<div class="container">

		   <div class="searchbox2">
      <!-- Search form -->
			<form name="frmSearch" class="form-inline mr-4" action="formsearch.php" method="POST">
				<input class="form-control search-box arabic-text-align" type="text" name="txtKeyword" placeholder="بحث" aria-label="Search" >
				<button class="btn  btn-lg  btn-danger"id="btnsearch" type="submit" value="Search"><i class="fa fa-search text-white" aria-hidden="true"></i></button>
			</form>

			</div>
			</div> 

		   <!-- section head -->
		   <section class="my-5 ganiral_admin" dir="rtl">
			<!-- start creat the table to show all data -->
			 <div class="container-flude">
				   <!--Table-->
				<div class="datatable">
				<p id="hform" class="h1 text-center mb-4">بيانات منحة ذوي الشهداء</p>
				   <table class="main-table table  text-center  align-middle table-bordered table-responsive">

					   <!--Table head-->
					   <thead class="mdb-color darken-3">
						   <tr class="text-white ">
							   <th>التسلسل</th>
							   <th>اسم الشهيد الرباعي</th>
							   <th>اسم المستفيد الرباعي</th>
							   <th>مكان العمل </th>
							   <th>رقم الهاتف</th>
							   <th>التاريخ </th>
							   <th>الوقت</th>
							   <th>ازرار التحكم</th>
						   </tr>
					   </thead>
					   <!--Table head-->

					   <!--Table body-->
					   <tbody>
							<?php
								 foreach ($members as $member) {
								   echo "<tr>";
									   echo "<td>" . $member['Martyr_ID']   . "</td>";
									   echo "<td>" . $member['martyr_name'] . "</td>";
									   echo "<td>" . $member['benefit_name'] . "</td>";
									   echo "<td>" . $member['work_place']    . "</td>";
									   echo "<td>" . $member['phon_cell'] . "</td>";
									   echo "<td>" . $member['Dateform'] . "</td>";
									   echo "<td>" . $member['Timeform']     . "</td>";
										echo '<td>
												<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
												<a href="martyrsform.php?do=Infoviwe2&MartyrID=' . $member['Martyr_ID'] . ' " target="_blank"><button type="button" class="btn btn-success btn-sm b0"><i class="fas fa-edit"></i> عرض </button></a>
												<a href="PDFfile.php?MartyrID=' . $member['Martyr_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>INFO </button></a>
												<a href="martyrsform.php?do=pdf&MartyrID=' . $member['Martyr_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>DOC </button></a>
												<a href="martyrsform.php?do=Delete&MartyrID=' . $member['Martyr_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
												</div>';
										echo'</td>';
								   echo "</tr>";
								 }

							 ?>
					   </tbody>
					   <!--Table body-->
				   </table>
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
				}else {
					header('location:index.php');
					exit();
				}


		} elseif ($do == 'Add') {?>
			<div class="importantnotice">
				<span class="not">ملاحظات مهمة جدا:</span>
				<p>1-نرجو مراعاه أدخال المعلومات الصحيحة في الاستمارة ليتم ادخالها في قاعدة بياناتنا بعد تدقيقها </p>
				<p>2- سيتم ارسال رسالة على رقم الهاتف واعلامكم موعد استلام المنحة </p>
				<p>3- المستمسكات المطلوبة (الأمر الأداري بالأستشهاد ,  قسام شرعي , حجة وصايا,صورة للماستر كارد او الكي كارد, مستمسكات الاربعة للمستفيد)</p>
				<p>4- يجب ان تكون المستمكات مرفقة بملف PDF واحد يتضمن جميع المستمسكات المطلوبة في فقرة (3) </p>
			</div>
            <h1 class="text-center"> أستمارة منحة ذوي الشهداء</h1>
						<div class="input-group mb-3">
						  <button
						    class="btn btn-primary dropdown-toggle"
						    type="button"
						    data-mdb-toggle="dropdown"
						    aria-expanded="false"
						  >
						    Dropdown
						  </button>
						  <ul class="dropdown-menu">
						    <li><a class="dropdown-item" href="#">Action</a></li>
						    <li><a class="dropdown-item" href="#">Another action</a></li>
						    <li><a class="dropdown-item" href="#">Something else here</a></li>
						    <li><hr class="dropdown-divider" /></li>
						    <li><a class="dropdown-item" href="#">Separated link</a></li>
						  </ul>
						  <input type="text" class="form-control" aria-label="Text input with dropdown button" />
						</div>

						<div class="input-group mb-3">
						  <input type="text" class="form-control" aria-label="Text input with dropdown button" />
						  <button
						    class="btn btn-primary dropdown-toggle"
						    type="button"
						    data-mdb-toggle="dropdown"
						    aria-expanded="false"
						  >
						    Dropdown
						  </button>
						  <ul class="dropdown-menu dropdown-menu-end">
						    <li><a class="dropdown-item" href="#">Action</a></li>
						    <li><a class="dropdown-item" href="#">Another action</a></li>
						    <li><a class="dropdown-item" href="#">Something else here</a></li>
						    <li><hr class="dropdown-divider" /></li>
						    <li><a class="dropdown-item" href="#">Separated link</a></li>
						  </ul>
						</div>

						<div class="input-group">
						  <button
						    class="btn btn-primary dropdown-toggle"
						    type="button"
						    data-mdb-toggle="dropdown"
						    aria-expanded="false"
						  >
						    Dropdown
						  </button>
						  <ul class="dropdown-menu">
						    <li><a class="dropdown-item" href="#">Action before</a></li>
						    <li><a class="dropdown-item" href="#">Another action before</a></li>
						    <li><a class="dropdown-item" href="#">Something else here</a></li>
						    <li><hr class="dropdown-divider" /></li>
						    <li><a class="dropdown-item" href="#">Separated link</a></li>
						  </ul>
						  <input
						    type="text"
						    class="form-control"
						    aria-label="Text input with 2 dropdown buttons"
						  />
						  <button
						    class="btn btn-primary dropdown-toggle"
						    type="button"
						    data-mdb-toggle="dropdown"
						    aria-expanded="false"
						  >
						    Dropdown
						  </button>
						  <ul class="dropdown-menu dropdown-menu-end">
						    <li><a class="dropdown-item" href="#">Action</a></li>
						    <li><a class="dropdown-item" href="#">Another action</a></li>
						    <li><a class="dropdown-item" href="#">Something else here</a></li>
						    <li><hr class="dropdown-divider" /></li>
						    <li><a class="dropdown-item" href="#">Separated link</a></li>
						  </ul>
						</div>

        <?php


		} elseif ($do == 'Insert') {
				if ($_SERVER['REQUEST_METHOD']=='POST') {
					// upload files image 1
					$FileName		= $_FILES['pdf1']['name'];
					$FileTmpName 	= $_FILES['pdf1']['tmp_name'];
					$FileSize		= $_FILES['pdf1']['size'];
					$FileError 		= $_FILES['pdf1']['error'];
					$FileType 		= $_FILES['pdf1']['type'];
					//list of allowed file type to upload
					$filellowedextension=array("pdf");
					// imag1
					$expoldefile=explode("." , $FileName);
					$filextension = strtolower(end($expoldefile));
					// get the varaible from the form

					$martyName      	=filter_var($_POST['marty_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$martyName 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $martyName);


					$workplace      	=filter_var($_POST['workplace'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$workplace 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $workplace);

					$mobilephon      	=filter_var($_POST['phone_cell'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$mobilephon	 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $mobilephon);

					$benefincyname      =filter_var($_POST['benefitName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$benefincyname 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $benefincyname);

					$uniq_user=$con->prepare("SELECT
								*
						FROM
								onlinform

						WHERE
								benefit_name='$benefincyname'");
					$uniq_user->execute();
					$count=$uniq_user->rowcount();


					$masterCardNo       =filter_var($_POST['mastercard'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$masterCardNo 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $masterCardNo);

					$adress      		=filter_var($_POST['adress_benefit'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$adress 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $adress);

					$marigestatuse      =filter_var($_POST['marig_statuse'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$marigestatuse 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $marigestatuse);

					$wifename      		=filter_var($_POST['wife_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$wifename 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $wifename);

					$clidren      		=filter_var($_POST['childNo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$clidren 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $clidren);


					$benefincyretir     =filter_var($_POST['retire_benefitNo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$benefincyretir 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $benefincyretir);


					//validat Errors
					$formerror=array();
					if (empty($martyName)) {
						$formerror[]='لايجب ان يكون حقل اسم الشهيد فارغا ';
					}
					if (empty($workplace)) {
						$formerror[]='يجب ذكر مكان العمل الشهيد وعدم ترك هذا الحقل فارغا';
					}
					if (empty($mobilephon)) {
						$formerror[]='لا يجب ان يكون حقل رقم الهاتف فارغا مع مراعاه صحة الرقم المكتوب';
					}
					if (empty($benefincyname)) {
						$formerror[]='يجب ذكر الشخص المستفيد من المنحة وعدم ترك حقل اسم المستفيد فارغا';
					}
					if ($count > 0) {
						$formerror[]='لقد تم التسجيل مسبقا لا يمكن التقديم اكثر من مرة';
					}

					if (empty($adress)) {
						$formerror[]='عدم ترك حقل عنوان السكن فارغا ';
					}
					//file
					if(empty($FileName)){
						$formerror[]='يجب ارفاق المستمسكات المطلوبة مع مراعاة الدقة والوضوح';
					}
					if($marigestatuse == 0) {
						$formerror[]='يجب اختيار الحالة الزوجية للشهيد';
					}
					if(($marigestatuse == 1 ) && (empty($wifename))) {
						$formerror[]='يجب كتابة اسم الزوجة اذا كانت الحالة الزوجية للشهيد متزوج  ';
					}
					if(($marigestatuse == 2 ) && (!empty($wifename))) {
						$formerror[]='اترك حقل زوجة الشهيد فارغ في حال تم اختيار الحالة الزوجية (أعزب) ';
					}

					if(! empty($filextension) && ! in_array($filextension,$filellowedextension)){
						$formerror[]='امتداد هذه الملف غير مسموح به ';
					}
					if($FileSize > 15242880){
						$formerror[]='حجم الملف جدا كبير يجب ان يكون اقل من 15 ميكا بايت';
					}
					if($FileSize < 50000){
						$formerror[]='حجم الملف جدا صغير يجب التاكد من المرفقات رجاءا ';
					}
					foreach ($formerror as $error) {
						echo '<div class="my_denger" role="alert">' . $error. '</div>';
					}

					if (empty($error)) {

					if(!empty($FileName)){
						$fileUploaded = rand(0,100000000) . '_' . $FileName;
						move_uploaded_file($FileTmpName,'PDF/' . $fileUploaded);
					}else {
						$imagUploaded=null;
					}
					$stmt=$con->prepare("INSERT INTO
											onlinform (martyr_name,work_place,phon_cell,benefit_name,Mastercard_no,benefit_adress,marige_statuse,wife_name,child_No,retirement_benefitNo,Dateform,Timeform,fileupload)
											VALUES (:zmart_name,:zwork,:zphon,:zbenename,:zmasterNo,:zbeneadress,:zmarystatuse,:zwifename,:zchild,:zretirbene,now(),now(),:zfile)");
								$stmt->execute(array(
									'zmart_name'     	=>$martyName,
									'zwork'     		=>$workplace,
									'zphon'     		=>$mobilephon,
									'zbenename'     	=>$benefincyname,
									'zmasterNo'     	=>$masterCardNo,
									'zbeneadress'       =>$adress,
									'zmarystatuse'      =>$marigestatuse,
									'zwifename'   		=>$wifename,
									'zchild'     		=>$clidren,
									'zretirbene'     	=>$benefincyretir,
									'zfile'     		=>$fileUploaded
								));

							//echo success message
							$themsg = "<div class='my_succsuflly' role='alert'>تمت الاضافة بنجاح </div>" ;
							redirect($themsg,'');

				}

			} else {
				$themsg = "<div class='my_denger'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}

		} elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {

		} elseif ($do == 'pdf') {
		    $MartyrID=isset($_GET['MartyrID']) && is_numeric($_GET['MartyrID']) ? intval($_GET['MartyrID']) : 0;
            $stmt=$con->prepare('SELECT
                                            *
                                    FROM
                                            onlinform

                                    WHERE	Martyr_ID=?');
            $stmt->execute(array($MartyrID));
            $members=$stmt->fetchall();
            foreach ($members as $member) {
			?>

			<div class="container">

			<iframe type="application/pdf" src="PDF/<?php echo $member['fileupload'];?>" style="width:100%; height:620px">hello</iframe>

			</div>
			<?php
        }
		} elseif ($do == 'Delete') {
		if(isset($_SESSION['user'])){
			// check if get request itemid is numeric & get the integer value of it
			$MartyrID=isset($_GET['MartyrID']) && is_numeric($_GET['MartyrID']) ? intval($_GET['MartyrID']) : 0;
			// select all data depend on this id
			$chek=checkitem("Martyr_ID" , "onlinform" , $MartyrID);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM onlinform WHERE Martyr_ID=:zmarty" );
				$stmt->bindparam(":zmarty",$MartyrID);
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


		} elseif ($do == 'Infoviwe') {

		} elseif ($do == 'Infoviwe2') {
			if(isset($_SESSION['user'])){
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
			foreach ($items as $item) {?>


            <h1 class="text-center"> أستمارة منحة ذوي الشهداء</h1>
            <form class="mrtyrsform" action="?do=Insert" method="POST" enctype="multipart/form-data">
            <div class="container">
                <div class="form-row">
					<div class="col-md-6 mb-3">
						<h3 for="validationServer01">أسم الشهيد الرباعي </h3>
						<p class="lead"><?php echo $item['martyr_name'] ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer02">أسم دائرة الشهيد </h3>
						<p class="lead"><?php echo $item['work_place'] ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer03">رقم الهاتف </h3>
						<p class="lead"><?php echo $item['phon_cell'] ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer04">أسم الرباعي للمستفيد </h3>
						<p class="lead"><?php echo $item['benefit_name'] ?></p>
                    </div>
					<div class="col-md-6 mb-3">
						<h3 for="validationServer11">رقم الماستر كارد او الكي كارد</h3>
						<p class="lead"><?php echo $item['Mastercard_no'] ?></p>
					</div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer05">عنوان السكن</h3>
						<p class="lead"><?php echo $item['benefit_adress'] ?></p>
                    </div>
                    <div class="col-md-12 mb-3 ">
						<h3 for="validationServer06" calss="wifstatuse">الحالة الزوجية </h3>
						<p class="lead">
							<?php
								if ($item['marige_statuse']== 0){ echo 'لم يتم الاختيار';}
								if ($item['marige_statuse']== 1){ echo 'متزوج';}
								if ($item['marige_statuse']== 2){ echo 'أعزب';}
							?></p>
						</div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer07">اسم الزوجة الثلاثي </h3>
						<p class="lead">
						<?php
							if ($item['marige_statuse']== 0){ echo $item['wife_name'];}
							if ($item['marige_statuse']== 1){ echo $item['wife_name'];}
							if ($item['marige_statuse']== 2){ echo 'أعزب';}
						 ?>
						</p>
                    </div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer08">عدد الاولاد </h3>
						<p class="lead"><?php echo $item['child_No'] ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                    </div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer010">الرقم التقاعدي </h3>
						<p class="lead"><?php echo $item['retirement_benefitNo'] ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
						<h3 for="validationServer010">وقت التسجيل</h3>
						<p class="lead"><?php echo $item['Timeform'] ?></p>
                    </div>
					<div class="col-md-6 mb-3">
						<h3 for="validationServer010">تاريخ التسجيل </h3>
						<p class="lead"><?php echo $item['Dateform'] ?></p>
                    </div>
                </div>
                </div>
                </div>
								<!-- star our Image Upload  -->
			<div class="container mt-3 mb-4 arabic-text-align">
				<h3>الملف المرفق</h3>
			<!-- start Image1 box -->
				<div class="file-field">
					<div class="img-butt">
						<div class="mb-1">
							<?php
						    	echo '<a href="martyrsform.php?do=pdf&MartyrID=' . $item['Martyr_ID'] . ' " target="_blank"><img src="images/defult2.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar"></a>';
							?>
						</div>
					</div>
				<!-- end Image1 box -->
				</div>
				<!-- start submait  -->
				<div class="text-center mt-4">
				<?php
				echo	'<a href="PDFfile.php?MartyrID=' . $item['Martyr_ID'] . ' " target="_blank"><button type="button" class="btn btn-info printbtn">أطبع </button></a>'	;
				?>
				</div>
			</div>
            </form>
<?php

			}
		}else {
			header('location:index.php');
			exit();
		}

	} elseif ($do == 'pdf') {

}

		include $tpl . 'footer.php';


	ob_end_flush(); // Release The Output

?>
