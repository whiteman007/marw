<?php


		include 'session.php';
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
			if(isset($_SESSION['user'])){
				// START OUR PAGNATION BOX
				$rows=countitem('haj_ID ' , 'haj_form') ;
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
			 $stmt=$con->prepare("SELECT * FROM haj_form $limit1");
			 $stmt->execute(array());
			 $hajs=$stmt->fetchall();
			if (! empty($hajs)){
		   ?>
			 <section class=" woundeddatabas_admin my-5" dir="rtl">
		   <div class="container">
				 <div class="row">
					 <!-- Search form -->
					 <div class="col-12">
				   <div class="woundeddatabase_searchbox">
						 <form name="frmSearch" class="form-inline mr-4" action="?do=Search" method="POST">
			 				<input class="form-control search-box arabic-text-align" type="text" name="keywordhaj" placeholder="بحث" aria-label="Search" >
			 				<button class="btn  btn-lg  btn-danger"id="btnsearch"  type="submit" value="Search"><i class="fa fa-search text-white" aria-hidden="true"></i></button>
			 			</form>
						</div>
					</div>
				</div>
			</div>
		   <!-- section head -->
		   <section class="my-5">
				 <h2  class="text-center mb-4">أستمارة التسجيل على العمرة </h2>
				 <!-- start creat the table to show all data -->
		 <div class="container-flude">
			 <div class="row">
				 <div class="col-12 woundeddatabas_datatable">
					 <!--Table-->
				   <table class="main-table table  text-center  align-middle table-bordered table-responsive">

					   <!--Table head-->
					   <thead class="mdb-color darken-3">
						   <tr class="text-white ">
								<th>التسلسل</th>
							   <th>أسم الشهيد الرباعي</th>
							   <th> مكان العمل</th>
							   <th> اسم صاحب الطلب</th>
							   <th> عمر صاحب الطلب</th>
                               <th> رقم التلفون</th>
							   <th> التاريخ</th>
							   <th> الوقت</th>
						   </tr>
					   </thead>
					   <!--Table head-->

					   <!--Table body-->
					   <tbody>
							<?php
							foreach ($hajs as $haj) {
								echo "<tr>";
									 echo "<td>" . $haj['haj_ID']   . "</td>";
									 echo "<td>" . $haj['martyr_name_haj']    . "</td>";
									 echo "<td>" . $haj['work_place_haj'] . "</td>";
									 echo "<td>" . $haj['requst_name_haj']. "</td>";
									 echo "<td>" . $haj['age']. "</td>";
									 echo "<td>" . $haj['telphon_haj']. "</td>";
									 echo "<td>" . $haj['haj_Data']. "</td>";
									 echo "<td>" . $haj['haj_Time']. "</td>";
								 echo '<td>
										 <a href="haj.php?do=Infoviwe2&martyrinfo=' . $haj['haj_ID'] . ' " target="_blank"><button type="button" class="btn btn-success btn-sm b0"><i class="fas fa-edit"></i> عرض </button></a>
										 <a href="hajPDF.php?hajID=' . $haj['haj_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
										 <a href="haj.php?do=Delete&hajID=' . $haj['haj_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
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
			<section class="woundeddatabase_form">
                <h2 class="text-center mt-4 mb-3">أستمارة  التسجيل على العمرة</h2>
                <div class="container flex-column-reverse">
					<h1 class="text-center" style="background-color: white;">انتهت فترة التقديم على العمرة </h1>
				</div>
			</section>

	<?php


		} elseif ($do == 'Insert') {
			if ($_SERVER['REQUEST_METHOD']=='POST') {

				$number_shahid    		=filter_var($_POST['num_shahid'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$number_shahid 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $number_shahid);


				$MartyrName    		    =filter_var($_POST['martyrName'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$MartyrName 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $MartyrName);

				$workPlace      		=filter_var($_POST['workPlace'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$workPlace 			    = preg_replace('/[^\p{L}\p{N}\s]/u', '', $workPlace);

				$MartyrName2    		    =filter_var($_POST['martyrName2'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$MartyrName2 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $MartyrName2);

				$workPlace2      		=filter_var($_POST['workPlace2'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$workPlace2 			    = preg_replace('/[^\p{L}\p{N}\s]/u', '', $workPlace2);

				$MartyrName3    		    =filter_var($_POST['martyrName3'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$MartyrName3 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $MartyrName3);

				$workPlace3      		=filter_var($_POST['workPlace3'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$workPlace3 			    = preg_replace('/[^\p{L}\p{N}\s]/u', '', $workPlace3);

				$data      	            =filter_var($_POST['martData'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$data 				    = preg_replace('/[^\p{L}\p{N}\s]/u', '',$data);

				$requstName      		=filter_var($_POST['Ownreq'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$requstName 			= preg_replace('/[^\p{L}\p{N}\s]/u', '',$requstName);

				$nativRelashin     		=filter_var($_POST['relativRelashion'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$nativRelashin 			= preg_replace('/[^\p{L}\p{N}\s]/u', '',$nativRelashin);


				$AGE      		        =filter_var($_POST['Age'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$AGE	 		    	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $AGE);

				$telphon      		    =filter_var($_POST['telPhon'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$telphon	 			= preg_replace('/[^\p{L}\p{N}\s]/u', '', $telphon);


				$Passport      		    =filter_var($_POST['PassPort'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$Passport 			    = preg_replace('/[^\p{L}\p{N}\s]/u', '', $Passport);

				$health       		    =filter_var($_POST['heathStatus'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$health 			    = preg_replace('/[^\p{L}\p{N}\s]/u', '', $health);

				$loqah       		    =filter_var($_POST['loqah'],  FILTER_SANITIZE_SPECIAL_CHARS);
				$loqah 			  		= preg_replace('/[^\p{L}\p{N}\s]/u', '', $loqah);



				//validat Errors
				$formerror=array();
				if(($number_shahid == 2) && (empty($MartyrName2))){
					$formerror[]='يجب كتابة اسم الشهيد الثاني ';
				}
				if(($number_shahid == 2) && (empty($workPlace2))){
					$formerror[]='يجب كتابة اسم دائرة الشهيد الثاني ';
				}
				if(($number_shahid == 3) && (empty($MartyrName3))){
					$formerror[]='يجب كتابة اسم الشهيد الثالث ';
				}
				if(($number_shahid == 3) && (empty($workPlace3))){
					$formerror[]='يجب كتابة اسم دائرة الشهيد الثالث ';
				}
				if (empty($MartyrName)) {
					$formerror[]=' لا يمكن ترك حقل اسم الشهيد فارغا';
				}
				if (empty($workPlace)) {
					$formerror[]='لا يمكن ترك حقل اسم الدائرى فارغا ';
				}
				if (empty($data)) {
					$formerror[]='لا يمكن ترك حقل تاريخ الاستشهاد فارغا ';
				}
				if (empty($requstName)) {
					$formerror[]='لا يمكن ترك حقل صاحب الطلب فارغا ';
				}
				if (empty($nativRelashin)) {
					$formerror[]='لا يمكن ترك حقل صلة القرابة  ';
				}
				if (empty($AGE)) {
					$formerror[]='لا يمكن ترك حقل عمر صاحب الطلب فارغا ';
				}
				if (empty($telphon)) {
					$formerror[]=' لا يمكن ترك حقل الهاتف فراغا';
				}
				if (empty($Passport)) {
					$formerror[]=' لا يمكن ترك حقل تاريخ نفاذية الجواز فارغا';
				}
				if (empty($health)) {
					$formerror[]=' لا يمكن ترك حقل  هل صاحب الطلب لديه القدرة على السفر؟ فارغا ';
				}
				if (empty($loqah)) {
					$formerror[]=' لا يمكن ترك حقل هل لدى صاحب الطلب بطاقة التلقيح الدولية؟  فارغا ';
				}
				foreach ($formerror as $error) {
					echo '<div class="my_denger" role="alert">' . $error. '</div>';
				}

				if (empty($error)) {
				$stmt=$con->prepare("INSERT INTO
										haj_form 
										(martyr_name_haj,work_place_haj,martyr_name_haj2,work_place_haj2,martyr_name_haj3,
										work_place_haj3,date_haj,requst_name_haj,qaraba,
                                        age,healt_status,telphon_haj,passport,LoQah,haj_Data,haj_Time)
									VALUES
										(:zmartname,:zwork,:zmartname2,:zwork2,:zmartname3,:zwork3,:zmartdata,
										:zrequst,:zqaraba,:zage,:zhealth,
                                        :ztelephon,:zpassport,:zloqah,now(),now())");
							$stmt->execute(array(
                                'zmartname'     		=>$MartyrName,
								'zwork'     			=>$workPlace,
                                'zmartname2'     		=>$MartyrName2,
								'zwork2'     			=>$workPlace2,
                                'zmartname3'     		=>$MartyrName3,
								'zwork3'     			=>$workPlace3,
								'zmartdata'    			=>$data,
								'zrequst'     			=>$requstName,
								'zqaraba'  				=>$nativRelashin,
								'zage'     			    =>$AGE,
								'zhealth'     			=>$health,
								'ztelephon'    			=>$telphon,
								'zpassport'    		    =>$Passport,
								'zloqah'    		    =>$loqah
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

		} elseif ($do == 'Delete') {
		if(isset($_SESSION['user'])){
			// check if get request itemid is numeric & get the integer value of it
			$HajID=isset($_GET['hajID']) && is_numeric($_GET['hajID']) ? intval($_GET['hajID']) : 0;
			// select all data depend on this id
			$chek=checkitem("haj_ID","haj_form", $HajID);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM haj_form WHERE haj_ID =:zhaj" );
				$stmt->bindparam(":zhaj",$HajID);
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


		} elseif ($do == 'Search') {
            $strKeyword = null;

			if(isset($_POST["keywordhaj"]))
			{
			  $strKeyword = $_POST["keywordhaj"];
			}
			
			if(isset($_GET["keywordhaj"]))
			{
			$strKeyword = $_GET["keywordhaj"];
			}
			$sql = "SELECT COUNT(*) 
					FROM 
					haj_form 
					WHERE 
					martyr_name_haj
					LIKE 
					'%".$strKeyword."%'
					OR
					requst_name_haj
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
					haj_form
					WHERE
					martyr_name_haj
					LIKE 
					'%".$strKeyword."%'
                    OR
                    requst_name_haj
					LIKE 
					'%".$strKeyword."%'
					ORDER BY
					`haj_form`.`haj_ID`
					ASC");
					$limit=" limit " .$row_start  . "," . $row_end;
					$query = $sql.$limit;
					$pdo_statement = $con->prepare($query);
					$pdo_statement->execute();
					$hajs = $pdo_statement->fetchall();
					$count=$pdo_statement->rowcount();
			if ($count>0){
					?>
		   <!-- section head -->
		   <section class="my-5">
				 <h2  class="text-center mb-4">أستمارة التسجيل على العمرة </h2>
				 <!-- start creat the table to show all data -->
		 <div class="container-flude">
			 <div class="row">
				 <div class="col-12 woundeddatabas_datatable">
					 <!--Table-->
				   <table class="main-table table  text-center  align-middle table-bordered table-responsive">

					   <!--Table head-->
					   <thead class="mdb-color darken-3">
						   <tr class="text-white ">
								<th>التسلسل</th>
							   <th>أسم الشهيد الرباعي</th>
							   <th> مكان العمل</th>
							   <th> اسم صاحب الطلب</th>
							   <th> عمر صاحب الطلب</th>
                               <th> رقم التلفون</th>
							   <th> التاريخ</th>
							   <th> الوقت</th>
						   </tr>
					   </thead>
					   <!--Table head-->

					   <!--Table body-->
					   <tbody>
							<?php
							foreach ($hajs as $haj) {
								echo "<tr>";
									 echo "<td>" . $haj['haj_ID']   . "</td>";
									 echo "<td>" . $haj['martyr_name_haj']    . "</td>";
									 echo "<td>" . $haj['work_place_haj'] . "</td>";
									 echo "<td>" . $haj['requst_name_haj']. "</td>";
									 echo "<td>" . $haj['age']. "</td>";
									 echo "<td>" . $haj['telphon_haj']. "</td>";
									 echo "<td>" . $haj['haj_Data']. "</td>";
									 echo "<td>" . $haj['haj_Time']. "</td>";
								 echo '<td>
										 <a href="haj.php?do=Infoviwe2&martyrinfo=' . $haj['haj_ID'] . ' " target="_blank"><button type="button" class="btn btn-success btn-sm b0"><i class="fas fa-edit"></i> عرض </button></a>
										 <a href="hajPDF.php?hajID=' . $haj['haj_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
										 <a href="haj.php?do=Delete&hajID=' . $haj['haj_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
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
        
        }else {
            header('location:index.php');
            exit();
        }

		include $tpl . 'footer.php';


	ob_end_flush(); // Release The Output

?>
