<?php


include 'session.php';
include 'init.php';

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if ($do == 'Manage') {
	if(isset($_SESSION['user'])){
		// START OUR PAGNATION BOX
		$rows=countitem('Gineral_ID ' , 'ganiralform') ;
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
		$stmt=$con->prepare("SELECT * FROM ganiralform $limit1");
		$stmt->execute(array());
		$ginerals=$stmt->fetchall();
	if (! empty($ginerals)){
	?>
	<!-- Search form -->
	<section class=" ganiral_admin my-5" dir="rtl">
	<div class="container">
			<div class="row">
				<div class="col-12">
			<div class="ganira_searchbox">
				<form name="frmSearch" class="form-inline mr-4" action="gineralsearch.php" method="POST">
					<?php
					// ganerate CSRF token to protact Form 
					if(isset($_POST['CSRF_token_gsearchform'])){
						if($_POST['CSRF_token_gsearchform'] == $_SESSION['CSRF_token_gsearchform']){
						}else{
							echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
						}
						$max_time = 60*60*24;
						if(isset($_SESSION['CSRF_token_time'])){
							$token_time=$_SESSION['CSRF_token_time'];
							if(($token_time + $max_time) >= time()){
							}else{
								unset($_SESSION['CSRF_token_gsearchform']);
								unset($_SESSION['CSRF_token_time']);
								echo "CSRF TOKEN EXPIRED";
							}
						}
					}
					$token = md5(uniqid(mt_rand(), true));
					$_SESSION['CSRF_token_gsearchform'] = $token;
					$_SESSION['CSRF_token_time'] = time();
					// ganerate CSRF token to protact Form 
					?>
					<input type="hidden" name="CSRF_token_gsearchform" value="<?php echo $token; ?>">
					<input class="form-control search-box arabic-text-align" type="text" name="txtKeyword22"   placeholder="بحث" aria-label="Search" >
					<button class="btn  btn-lg  btn-danger"id="btnsearch"  type="submit" value="Search"><i class="fa fa-search text-white" aria-hidden="true"></i> بحث</button>
				</form>
				</div>
			</div> 
		</div>
	</div>
	<!-- section head -->
	<h2 class=" text-center mb-4 mt-4">الاستمارة الشاملة للمعاملات الغير منجزة</h2>

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
							<th>اسم صاحب الطلب </th>
							<th> التاريخ</th>
								<th> الوقت</th>
								<th> لوحة التحكم</th>
						</tr>
					</thead>
					<!--Table head-->

					<!--Table body-->
					<tbody>
							<?php
								foreach ($ginerals as $gineral) {
								echo "<tr>";
										echo "<td>" . $gineral['Gineral_ID']   . "</td>";
										if ($gineral['Status_Death']== 1){
										echo "<td>" ."شهيد" . "</td>";
										}
										if ($gineral['Status_Death']== 2){
										echo "<td>" ."متوفي" . "</td>";
										}
										if ($gineral['Status_Death']== 3){
										echo "<td>" ."مفقود" . "</td>";
										}
										if ($gineral['Status_Death']== 4){
										echo "<td>" ."جريح" . "</td>";
										}
										echo "<td>" . $gineral['Gineral_Name'] . "</td>";
										echo "<td>" . $gineral['Name_Of_Form'] . "</td>";
										echo "<td>" . $gineral['gineral_Date'] . "</td>";
										echo "<td>" . $gineral['gineral_Time']     . "</td>";
										echo '<td>
													<a href="ganeralform.php?do=Infoviwe2&gineralformID=' . $gineral['Gineral_ID'] . ' " target="_blank"><button type="button" class="btn btn-success btn-sm b0"><i class="fas fa-edit"></i> عرض </button></a>
													<a href="gineralPDF.php?gineralformID=' . $gineral['Gineral_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
													<a href="ganeralform.php?do=Delete&gineralformID=' . $gineral['Gineral_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
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
		}else {
			header('location:index.php');
			exit();
		}
		} elseif ($do == 'Add') {?>
			<section class="ganiralform">
			<div class="container ganiralform1" dir="rtl">
					<div class="row">
						<div class="col-md-12 importantnotice">
							<span style="color: red;font-weight:bold;font-size:20px">ملاحظات مهمة جدا:</span>
							<p>1-نرجو مراعاه أدخال المعلومات باللغة العربية حصرا وبصورة صحيحة في الاستمارة ليتم ادخالها في قاعدة بياناتنا   </p>
							<p>2- سيتم ارسال رسالة على رقم الهاتف واعلامكم الاجراء لذا يرجى ادخال رقم هاتف يعمل </p>
							<p>3- يرجى املاء جميع الحقول لضرورة المعلومات المطلوبة </p>
							<p>4- يجب ارفاق صورة من الامر الاداري والمستمسكات الثبوتية اسفل الاستمارة </p>
						</div>
					</div>
				<h2 class="text-center">أستمارة الطلبات الخاصة بذوي (الشهداء\المتوفي\مفقود\جريح)</h2>
				<form class="mrtyrsform" name="ginerlform" action="?do=Insert"  method="POST" enctype="multipart/form-data">
				<?php
				// ganerate CSRF token to protact Form 
				if(isset($_POST['CSRF_token'])){
					if($_POST['CSRF_token'] == $_SESSION['CSRF_token']){
					}else{
						echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
					}
					$max_time = 60*60*24;
					if(isset($_SESSION['CSRF_token_time'])){
						$token_time=$_SESSION['CSRF_token_time'];
						if(($token_time + $max_time) >= time()){
						}else{
							unset($_SESSION['CSRF_token']);
							unset($_SESSION['CSRF_token_time']);
							echo "CSRF TOKEN EXPIRED";
						}
					}
				}
				$token = md5(uniqid(mt_rand(), true));
				$_SESSION['CSRF_token'] = $token;
				$_SESSION['CSRF_token_time'] = time();
				// ganerate CSRF token to protact Form 
				?>
				<input type="hidden" name="CSRF_token" value="<?php echo $token; ?>">
				<div class="container">
					<div class="row">
                    <div class="col-12 mb-3">
                        <label for="person_statuse">(شهيد\متوفي\مفقود\جريح)<span class="not2">*</span></label>
                        <select class="custom-select gineralselect mt-2" name="person_statuse" onchange="getddl()" id="person_statuse">
                            <option value="0" selected>...</option>
                            <option value="1">شهيد </option>
                            <option value="2">متوفي </option>
                            <option value="3">مفقود </option>
                            <option value="4">جريح </option>
                        </select>
                    </div>
					<div class="col-md-6 mb-3 4 box">
					<label for="status_wound">حالة الجريح متقاعد/مستمر<span class="not2">*</span> </label>
						<select class="custom-select mt-1" name="status_wound" id="status_wound">
								<option selected value="0">...</option>
								<option value="1">متــقاعد </option>
								<option value="2">مستــــمر </option>
						</select>
					</div>
					<div class="col-md-6 mb-3">
						<label for="Name_fourth" id="getlabel">الاسم الرباعي واللقب /<span class="not2">*</span> </label>
						<input type="text" name="Name_fourth" class="form-control mt-2 arabic-input"  id="Name_fourth"  placeholder=" من فضلك ادخل اسم الرباعي واللقب">
					</div>
					<div class="col-md-6 mb-3 4 box">
						<label for="percentofwounded">نسبة العجز ان وجدت</label>
						<input type="text" name="percentofwounded" class="form-control mt-2 arabic-input"  id="percentofwounded"  placeholder="يرجى ادخال نسبة العجز ان وجدت ">
					</div>
					<div class="col-md-6 mb-3">
						<label for="Date_ofdeath" id="getlabel2">تاريخ (الاستشهاد / الوفاه / الفقدان /الاصابة)<span class="not2">*</span> </label>
						<input type="text" name="Date_ofdeath" class="form-control mt-2 arabic-input"  id="Date_ofdeath"   placeholder="يرجى ادخال التاريخ يوم شهر سنة بدون رموز"  >
					</div>
					<div class="col-md-6 mb-3">
						<label for="Mother_name"  id="getlabel3">أسم ألام الثلاثي<span class="not2">*</span> </label>
						<input type="text" name="Mother_name" class="form-control mt-2 arabic-input"  id="Mother_name"   placeholder="يرجى ادخال اسم الام الثلاثي"  >
					</div>
					<div class="col-md-6 mb-3">
						<label for="Directry_name"  id="getlabel4">أسم الدائرة التي يعمل بها<span class="not2">*</span> </label>
						<input type="text" name="Directry_name" class="form-control mt-2 arabic-input"  id="Directry_name"  placeholder="يرجى ادخال اسم الدائرة  "  >
					</div>
					<div class="col-md-6 mb-3">
						<label for="amor_idary" id="getlabel5">هل صدر أمر أداري؟ <span class="not2">*</span></label>
						<select class="custom-select omorIdaribox mt-2" name="amor_idary" id="amor_idary">
							<option selected value="0">...</option>
							<option value="7">نــعم </option>
							<option value="8">لا </option>
						</select>
					</div>
					<div class="col-md-6 7 idari mb-3">
						<label for="number_data_book">رقم وتاريخ الامر الاداري<span class="not2">*</span> </label>
						<input type="text" name="number_data_book" class="form-control mt-2 arabic-input"  id="number_data_book"   placeholder="يرجى ادخال رقم وتاريخ الامر الاداري"  >
								</div>
					<div class="col-md-6 7 idari mb-3">
						<label for="number_taqaud">الرقم التقاعدي</label>
						<input type="text" name="number_taqaud" class="form-control mt-2 arabic-input"  id="number_taqaud"   placeholder=" يرجى ادخال رقم وتاريخ الامر الاداري بدون رموز"  >
					</div>
					<div class="col-md-6 8 idari mb-3">
						<label for="investy_board" id="getlabel6">هل أنجز المجلس التحقيقي؟<span class="not2">*</span></label>
						<select class="custom-select mt-2" name="investy_board" id="investy_board">
							<option selected value="0">...</option>
							<option value="5">نــعم</option>
							<option value="6">لا </option>
						</select>
					</div>
					<div class="col-md-6 mb-3">
						<label for="name_of_form">أسم صاحب الطلب<span class="not2">*</span> </label>
						<input type="text" name="name_of_form" class="form-control arabic-input"  id="name_of_form"   placeholder="يرجى ادخال اسم مقدم الطلب"  >
					</div>
						<div class="col-md-6 mb-3">
							<label for="relative_relation" id="getlabel7">صلة القرابة<span class="not2">*</span></label>
							<select class="custom-select mt-2" name="relative_relation" id="relative_relation">
								<option selected value="0">...</option>
								<option value="9"> أب</option>
								<option value="10"> أم</option>
								<option value="111"> ابن</option>
								<option value="112"> ابنة</option>
								<option value="11"> أخ</option>
								<option value="12"> أخت</option>
								<option value="13"> زوجة</option>
								<option class="4 box" value="113"> نفسة</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label for="Number_phon">رقم الهاتف <span class="not2">*</span> </label>
							<input type="text" name="Number_phon" class="form-control arabic-input"  id="Number_phon"   placeholder="يرجى ادخال رقم هاتف يعمل"  >
						</div>
						<div class="col-md-6 mb-3">
							<label for="Adress_gform">العنوان<span class="not2">*</span> </label>
							<input type="text" name="Adress_gform" class="form-control arabic-input"  id="Adress_gform"   placeholder="يرجى ادخال العنوان كامل محافظة / محلة / زقاق / دار"  >
						</div>
						<div class="col-md-6  1 2 3 box mb-3">
							<label for="kafin">هل تم استلام الكفن والدفن؟<span class="not2">*</span></label>
							<select class="custom-select" name="kafin" id="kafin">
								<option selected value="0">...</option>
								<option value="14"> نـعم </option>
								<option value="15"> لا</option>
							</select>
						</div>
						<div class="col-md-6  1 2 3 4 box mb-3">
							<label for="vication">هل تم استلام الاجازات المتراكمة؟<span class="not2">*</span></label>
							<select class="custom-select" name="vication" id="vication">
								<option selected value="0">...</option>
								<option value="16"> نـعم</option>
								<option value="17"> لا</option>
							</select>
						</div>
							<div class="col-md-6  1 2 3 4 box mb-3">
							<label for="salary">هل تم استلام راتب التقاعد؟<span class="not2">*</span></label>
							<select class="custom-select" name="salary" id="salary">
								<option selected value="0">...</option>
								<option value="18"> نـعم</option>
								<option value="19"> لا </option>
							</select>
						</div>
						<div class="col-md-6  4 box mb-3">
							<label for="isaba">هل تم استلام منحة الاصابة ؟<span class="not2">*</span></label>
							<select class="custom-select" name="isaba" id="isaba">
								<option selected value="0">...</option>
								<option value="20"> نـعم</option>
								<option value="21"> لا</option>
							</select>
						</div>
						<div class="col-md-12 mb-3">
							<label for="desc">توضيح صاحب الطلب وعرض المشكلة<span class="not2">*</span> </label>
							<textarea class="form-control arabic-input" name="desc" id="desc"    placeholder="يرجى ادخال توضيح مختصر وعرض المشكلة " rows="6" maxlength = "600"></textarea>
						</div>
																	<!-- star our Image Upload  -->
						<div class="container mt-3 mb-4" style="direction: rtl;">
							<h3>تحميل صورة </h3>
									<!-- start Image3 box -->
									<div class="file-field2 1 box">
										<div class="img-butt">
											<div class="mb-1">
													<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic2" alt="example avatar">
											</div>
											<span class="btn btn-danger btn-file2">
											امرأستشهاد<input type="file" name="ganiralform_omor"  id="aa3" onchange="pressed3()">
											</span>
										</div>
											<div class="fileLabel2">
												<p id="fileLabel3">الامر الاداري بالاستشهاد</p>
											</div>
									</div>
									<!-- end Image3 box -->
									<!-- start Image3 box -->
									<div class="file-field2 4 box">
										<div class="img-butt">
											<div class="mb-1">
													<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic2" alt="example avatar">
											</div>
											<span class="btn btn-danger btn-file2">
											امر الاصابة <input type="file" name="ganiralform_isaba"  id="aa3" onchange="pressed3()">
											</span>
										</div>
											<div class="fileLabel2">
												<p id="fileLabel3"> الامر الاداري بالاصابة</p>
											</div>
									</div>
									<!-- end Image3 box -->
									<!-- start Image3 box -->
									<div class="file-field2 4 box ">
										<div class="img-butt">
											<div class="mb-1">
													<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic2" alt="example avatar">
											</div>
											<span class="btn btn-danger btn-file2">
												نسبة العجز <input type="file" name="ganiral_ajaz"  id="aa3" onchange="pressed3()">
											</span>
										</div>
											<div class="fileLabel2">
												<p id="fileLabel3"> نسبة العجز</p>
											</div>
									</div>
									<!-- end Image3 box -->

									<!-- start Image3 box -->
									<div class="file-field2 2 box ">
										<div class="img-butt">
											<div class="mb-1">
													<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic2" alt="example avatar">
											</div>
											<span class="btn btn-danger btn-file2">
											امر الوفاه <input type="file" name="ganiralform_wafa"  id="aa3" onchange="pressed3()">
											</span>
										</div>
											<div class="fileLabel2">
												<p id="fileLabel3"> الامر الاداري بالوفاه</p>
											</div>
									</div>
									<!-- end Image3 box -->
									<!-- start Image3 box -->
									<div class="file-field2 3 box ">
										<div class="img-butt">
											<div class="mb-1">
													<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic2" alt="example avatar">
											</div>
											<span class="btn btn-danger btn-file2">
											امر الفقدان <input type="file" name="ganiralform_fugdan"  id="aa3" onchange="pressed3()">
											</span>
										</div>
											<div class="fileLabel2">
												<p id="fileLabel3"> الامر الاداري بالفقدان</p>
											</div>
									</div>
									<!-- end Image3 box -->
									<!-- start Image1 box -->
									<div class="file-field2 0 1 2 3 4 box">
										<div class="img-butt">
											<div class="mb-1">
													<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic2" alt="example avatar">
											</div>
											<span class="btn btn-danger btn-file2">
												هوية ثبوتية<input type="file" name="ganiralform_jnsia"  id="aa2" onchange="pressed1()" >
											</span>
										</div>
											<div class="fileLabel2">
												<p id="fileLabel">جنسية صاحب الطلب </p>
											</div>
										</div>
									<!-- end Image1 box -->
							</div>
						</div>
						<!-- start submait  -->
					<div class="text-center mt-10 ">
						<input class="btn btn-danger nashir " type="submit" value="ارسال" >
					</div>
			</form>
		</div>
	</section>
        <?php
		} elseif ($do == 'Insert') {
				if ($_SERVER['REQUEST_METHOD']=='POST') {
					// get the varaible from the form
					// upload files omoristishad image 1
					$img1name	=$_FILES['ganiralform_omor']['name'];
					$img1size	=$_FILES['ganiralform_omor']['size'];
					$img1tmp	=$_FILES['ganiralform_omor']['tmp_name'];
					$img1type	=$_FILES['ganiralform_omor']['type'];
					// upload files omorisaba image 2
					$img2name	=$_FILES['ganiralform_isaba']['name'];
					$img2size	=$_FILES['ganiralform_isaba']['size'];
					$img2tmp	=$_FILES['ganiralform_isaba']['tmp_name'];
					$img2type	=$_FILES['ganiralform_isaba']['type'];
					// upload files omor ajaz image 3
					$img3name	=$_FILES['ganiral_ajaz']['name'];
					$img3size	=$_FILES['ganiral_ajaz']['size'];
					$img3tmp	=$_FILES['ganiral_ajaz']['tmp_name'];
					$img3type	=$_FILES['ganiral_ajaz']['type'];
					// upload files omor wafa image 4
					$img4name	=$_FILES['ganiralform_wafa']['name'];
					$img4size	=$_FILES['ganiralform_wafa']['size'];
					$img4tmp	=$_FILES['ganiralform_wafa']['tmp_name'];
					$img4type	=$_FILES['ganiralform_wafa']['type'];
					// upload files omorfuqdan image 5
					$img5name	=$_FILES['ganiralform_fugdan']['name'];
					$img5size	=$_FILES['ganiralform_fugdan']['size'];
					$img5tmp	=$_FILES['ganiralform_fugdan']['tmp_name'];
					$img5type	=$_FILES['ganiralform_fugdan']['type'];
					// upload files jnsia image 6
					$img6name	=$_FILES['ganiralform_jnsia']['name'];
					$img6size	=$_FILES['ganiralform_jnsia']['size'];
					$img6tmp	=$_FILES['ganiralform_jnsia']['tmp_name'];
					$img6type	=$_FILES['ganiralform_jnsia']['type'];

					$imgallowedextension=array("jpeg","jpg","JPG","png","gif");
					// imag1
					$expoldefileistshad=explode("." , $img1name);
					$imgextensionistshad = strtolower(end($expoldefileistshad));
					// imag2
					$expoldefileisaba=explode("." , $img2name);
					$imgextensionisaba = strtolower(end($expoldefileisaba));
					// imag3
					$expoldefileajaz=explode("." , $img3name);
					$imgextensionajaz = strtolower(end($expoldefileajaz));
					// imag4
					$expoldefilewafa=explode("." , $img4name);
					$imgextensionwafa = strtolower(end($expoldefilewafa));
					// imag5
					$expoldefilefuqdan=explode("." , $img5name);
					$imgextensionfuqdan = strtolower(end($expoldefilefuqdan));
					// imag6
					$expoldefilejinsia=explode("." , $img6name);
					$imgextensionjnsia = strtolower(end($expoldefilejinsia));
	

					$person_statuse     	=filter_var($_POST['person_statuse'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$person_statuse 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $person_statuse);

					$Namefourth      		=filter_var($_POST['Name_fourth'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$Namefourth 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $Namefourth);

					$Statuswounded      	=filter_var($_POST['status_wound'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$Statuswounded 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $Statuswounded);

					$Percent      			=filter_var($_POST['percentofwounded'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$Percent 				= preg_replace('/[^\p{L}\p{N}\s]/u', '', $Percent);

					$DateOFdeat     		=filter_var($_POST['Date_ofdeath'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$DateOFdeat 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $DateOFdeat);


					$MOthername      		=filter_var($_POST['Mother_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$MOthername	 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $MOthername);

					$uniq_user=$con->prepare("SELECT
								*
						FROM
							ganiralform

						WHERE
							Mother_name='$MOthername'");

					$uniq_user->execute();
					$count=$uniq_user->rowcount();

					$orginazation      	=filter_var($_POST['Directry_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$orginazation 		= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $orginazation);

					$investyboard       =filter_var($_POST['investy_board'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$investyboard 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $investyboard);

					$IdARY   			=filter_var($_POST['amor_idary'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$IdARY 				= preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $IdARY);

					$DATE_NU_BOOK     	=filter_var($_POST['number_data_book'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$DATE_NU_BOOK 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $DATE_NU_BOOK);

					$NUMBER_TAQAUD     	=filter_var($_POST['number_taqaud'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$NUMBER_TAQAUD 		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $NUMBER_TAQAUD);

					$OWNFORM      		=filter_var($_POST['name_of_form'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$OWNFORM 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $OWNFORM);


					$RELATION_OF_RELETIVE     =filter_var($_POST['relative_relation'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$RELATION_OF_RELETIVE 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $RELATION_OF_RELETIVE);


					$NU_PHON     			=filter_var($_POST['Number_phon'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$NU_PHON 				= preg_replace('/[^\p{L}\p{N}\s]/u', '', $NU_PHON);

					$ADRESS_OF_OWN     		=filter_var($_POST['Adress_gform'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$ADRESS_OF_OWN 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $ADRESS_OF_OWN);

					$KAFAN     				=filter_var($_POST['kafin'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$KAFAN 					= preg_replace('/[^\p{L}\p{N}\s]/u', '', $KAFAN);

					$VICATION     			=filter_var($_POST['vication'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$VICATION 				= preg_replace('/[^\p{L}\p{N}\s]/u', '', $VICATION);

					$SALARY     			=filter_var($_POST['salary'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$SALARY 				= preg_replace('/[^\p{L}\p{N}\s]/u', '', $SALARY);

					$ISABA     				=filter_var($_POST['isaba'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$ISABA 					= preg_replace('/[^\p{L}\p{N}\s]/u', '', $ISABA);

					$DESECRAPION     		=filter_var($_POST['desc'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$DESECRAPION 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $DESECRAPION);

					 

					//validat Errors
					$formerror=array();
					//img1 istshad
					
					if(! empty($imgextensionistshad) && ! in_array($imgextensionistshad,$imgallowedextension)){
						$formerror[]='امتداد هذه الصورة غير مسموح بها ';
					}
					if($img1size > 15242880){
						$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
					}
					if ($person_statuse == 1 && empty($img1name)) {
						$formerror[]=' يجب ارفاق صورة من الامر الادري بالاستشهاد';
					}

					//img2 asaba
					if(! empty($imgextensionisaba) && ! in_array($imgextensionisaba,$imgallowedextension)){
						$formerror[]='امتداد هذه الصورة غير مسموح بها ';
					}
					if($img2size > 15242880){
						$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
					}
					if ($person_statuse == 4 && empty($img2name)) {
						$formerror[]=' يجب ارفاق صورة من الامر الادري بالاصابة';
					}
					//img3 ajaz
					if(! empty($imgextensionajaz) && ! in_array($imgextensionajaz,$imgallowedextension)){
						$formerror[]='امتداد هذه الصورة غير مسموح بها ';
					}
					if($img3size > 15242880){
						$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
					}
					if ($person_statuse == 4 && empty($img3name)) {
						$formerror[]=' يجب ارفاق صورة من نسبة العجز';
					}
					//img4 wafa
					if(! empty($imgextensionwafa) && ! in_array($imgextensionwafa,$imgallowedextension)){
						$formerror[]='امتداد هذه الصورة غير مسموح بها ';
					}
					if($img4size > 15242880){
						$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
					}
					if ($person_statuse == 2 && empty($img4name)) {
						$formerror[]=' يجب ارفاق صورة من الامر الادري بالوفاه';
					}
					//img5 fuqdan
					if(! empty($imgextensionfuqdan) && ! in_array($imgextensionfuqdan,$imgallowedextension)){
						$formerror[]='امتداد هذه الصورة غير مسموح بها ';
					}
					if($img5size > 15242880){
						$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
					}
					if ($person_statuse == 3 && empty($img5name)) {
						$formerror[]=' يجب ارفاق صورة من الامر الادري بالفقدان';
					}
					//img6 jinsai
					if(! empty($imgextensionjnsia) && ! in_array($imgextensionjnsia,$imgallowedextension)){
						$formerror[]='امتداد هذه الصورة غير مسموح بها ';
					}
					if($img6size > 15242880){
						$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
					}
					if (empty($img6name)) {
						$formerror[]=' يجب ارفاق صورة من جنسية صاحب الطلب';
					}

					if($person_statuse == 0) {
						$formerror[]='يجب اختيار صاحب الصلة هل هو شهيد / متوفي / متقاعد / جريح  ';
					}

					if (empty($Namefourth)) {
						$formerror[]='لا يمكن ترك حقل اسم الرباعي واللقب فارغا';
					}
					if(($person_statuse == 4) && ($Statuswounded == 0)){
						$formerror[]='يجب بيان حالة الجريح متقاعد او مستمر';
					}

					if (empty($DateOFdeat)) {
						$formerror[]='لا يمكن ترك حقل تاريخ الاستشهاد /الوفاه /التقاعد/الجريح فارغا';
					}
					if (empty($MOthername)) {
						$formerror[]='لا يمكن ترك حقل اسم الام الثلاثي فارغا';
					}
					if (empty($orginazation)) {
						$formerror[]='لا يمكن ترك حقل اسم دائرة الشهيد/المتوفي/المتقاعد/الجريح فارغا';
					}
					if($IdARY == 0) {
						$formerror[]='لا يمكن ترك حقل هل صدر امر اداري بالاستشهاد/بالوفاه/بالتقاعد/بالاصابة؟';
					}
					if(($IdARY == 7) && (empty($DATE_NU_BOOK))){
						$formerror[]='لا يمكن ترك حقل رقم وتاريخ الامر الاداري فارغا؟';
					}
					if(($IdARY == 8) && (empty($investyboard))){
						$formerror[]='لا يمكن ترك حقل هل تم أنجاز المجلس التحقيقي؟';
					}
					if (empty($OWNFORM)) {
						$formerror[]='لا يمكن ترك حقل اسم صاحب الطلب فارغا';
					}
					if($RELATION_OF_RELETIVE == 0) {
						$formerror[]='لا يمكن ترك حقل صلة القرابة فارغ ';
					}

					if (empty($NU_PHON)) {
						$formerror[]='لا يمكن ترك حقل رقم الهاتف فارغا';
					}
					if (empty($ADRESS_OF_OWN)) {
						$formerror[]='لا يمكن ترك حقل العنوان فارغا';
					}
					if ($count > 0) {
						$formerror[]='لقد تم ارسال بياناتك مسبقا';
					}
					if(($person_statuse == 1 ) ^ ($person_statuse == 2 ) && ($KAFAN == 0 )) {
						$formerror[]='يجب الاجابة على حقل سوال هل تم استلام مبلغ الكفن والدفن  ؟';
					}
					if(($person_statuse == 4 ) && ($ISABA == 0 )) {
						$formerror[]='يجب الاجابة على حقل سوال هل تم استلام منحة الاصابة من الصندوق ؟';
					}

					if($VICATION == 0) {
						$formerror[]='يجب الاجابة على سوال هل تم استلام الاجازات المتراكمة؟';
					}
					if($SALARY == 0) {
						$formerror[]='يجب الاجابة على سوال هل تم استلام الراتب التقاعدي؟ ';
					}
					if (empty($DESECRAPION)) {
						$formerror[]='لا يمكن ترك حقل توضيح صاحب الطلب وعرض المشكلة فارغا';
					}

					foreach ($formerror as $error) {
						echo '<div class="my_denger" role="alert">' . $error. '</div>';
					}

					if (empty($error)) {
						if(!empty($img1name)){
							$imagUploaded1 = rand(0,100000000) . '_' . $img1name;
							move_uploaded_file($img1tmp,'TCPDF/images/ganiralForm/' . $imagUploaded1);
						}else {
							$imagUploaded1=null;
						}

						if(!empty($img2name)){
							$imagUploaded2 = rand(0,100000000) . '_' . $img2name;
							move_uploaded_file($img2tmp,'TCPDF/images/ganiralForm/' . $imagUploaded2);
						}else {
							$imagUploaded2=null;
						}

						if(!empty($img3name)){
							$imagUploaded3 = rand(0,100000000) . '_' . $img3name;
							move_uploaded_file($img3tmp,'TCPDF/images/ganiralForm/' . $imagUploaded3);
						}else {
							$imagUploaded3=null;
						}

						if(!empty($img4name)){
							$imagUploaded4 = rand(0,100000000) . '_' . $img4name;
							move_uploaded_file($img4tmp,'TCPDF/images/ganiralForm/' . $imagUploaded4);
						}else {
							$imagUploaded4=null;
						}

						if(!empty($img5name)){
							$imagUploaded5 = rand(0,100000000) . '_' . $img5name;
							move_uploaded_file($img5tmp,'TCPDF/images/ganiralForm/' . $imagUploaded5);
						}else {
							$imagUploaded5=null;
						}

						if(!empty($img6name)){
							$imagUploaded6 = rand(0,100000000) . '_' . $img6name;
							move_uploaded_file($img6tmp,'TCPDF/images/ganiralForm/' . $imagUploaded6);
						}else {
							$imagUploaded6=null;
						}


					$stmt=$con->prepare("INSERT INTO
											ganiralform (Status_Death,Gineral_Name,status_wounded,percentag_wounded,Date_of_Death,Mother_name,Derectry_name,Investy_board,
											Amor_idary,No_Data_Book,NO_taqaud,Name_Of_Form,
											Relative_relashion,Phon_Nu,Adress_gform,Kafan,Vaction,Salary,Asaba,gineral_Date,gineral_Time,Description_Of,ganiral_omorIstshad,ganiral_omorIsaba
											,ganiral_Ajaz,ganiral_omorWafa,ganiral_omorFuqdan,ganiral_Jinsai)
										VALUES
											(:zstat_death,:zgin_name,:zstatuswounded,:zpercent,:zdatedeath,:zmother_name,:zder_name,
											:zinvesty,:zamor,:znu_of_book,:zno_taqaud,:znameown,
											:zrelarive_relshn,:zphon,:zadress,:zkafan,:zvacation,:zsalary,:zasaba,now(),now(),:zDescr,:zIstshad,:zIsabs,:zAjaz,:zWafa,:zFuqdan,:zJinsai)");
								$stmt->execute(array(
									'zstat_death'     	=>$person_statuse,
									'zgin_name'     	=>$Namefourth,
									'zstatuswounded'    =>$Statuswounded,
									'zpercent'     		=>$Percent,
									'zdatedeath'     	=>$DateOFdeat,
									'zmother_name'     	=>$MOthername,
									'zder_name'   	  	=>$orginazation,
									'zinvesty'        	=>$investyboard,
									'zamor'     		=>$IdARY,
									'znu_of_book'   	=>$DATE_NU_BOOK,
									'zno_taqaud'	   	=>$NUMBER_TAQAUD,
									'znameown'     		=>$OWNFORM,
									'zrelarive_relshn'  =>$RELATION_OF_RELETIVE,
									'zphon'     		=>$NU_PHON,
									'zadress'     		=>$ADRESS_OF_OWN,
									'zkafan'     		=>$KAFAN,
									'zvacation'     	=>$VICATION,
									'zsalary'     		=>$SALARY,
									'zasaba'     		=>$ISABA,
									'zDescr'     		=>$DESECRAPION,
									'zIstshad'     		=>$imagUploaded1,
									'zIsabs'     		=>$imagUploaded2,
									'zAjaz'     		=>$imagUploaded3,
									'zWafa'     		=>$imagUploaded4,
									'zFuqdan'     		=>$imagUploaded5,
									'zJinsai'     		=>$imagUploaded6
								));

							//echo success message
							$themsg = "<div class='my_succsuflly' role='alert'>تمت ارسال طلبك بنجاح </div>" ;
							redirect($themsg,'');

				}

			} else {
				$themsg = "<div class='my_denger'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}

		} elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {

		} elseif ($do == 'Delete') {
		if(isset($_SESSION['user'])){
			// check if get request itemid is numeric & get the integer value of it
			$gineralformID=isset($_GET['gineralformID']) && is_numeric($_GET['gineralformID']) ? intval($_GET['gineralformID']) : 0;
			// select all data depend on this id
			$chek=checkitem("Gineral_ID " , "ganiralform" , $gineralformID);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM ganiralform WHERE Gineral_ID=:zgineral" );
				$stmt->bindparam(":zgineral",$gineralformID);
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

		}else {
			header('location:index.php');
			exit();
		}

		include $tpl . 'footer.php';


	ob_end_flush(); // Release The Output

?>
