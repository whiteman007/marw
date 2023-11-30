<?php


		include 'session.php';
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
			if(isset($_SESSION['user'])){
				// START OUR PAGNATION BOX
				$rows=countitem('martyrInfo_ID' , 'martyrinfo') ;
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
			 $stmt=$con->prepare("SELECT * FROM martyrinfo $limit1");
			 $stmt->execute(array('martyrInfo_ID'));
			 $martyrinfos=$stmt->fetchall();
			if (! empty($martyrinfos)){
		   ?>
			 <section class=" woundeddatabas_admin my-5" dir="rtl">
		   <div class="container">
				 <div class="row">
					 <!-- Search form -->
					 <div class="col-12">
				   <div class="woundeddatabase_searchbox">
						 <form name="frmSearch" class="form-inline mr-4" action="martyrinfosearch.php" method="POST">
			 				<input class="form-control search-box arabic-text-align" type="text" name="txtKeyword25" placeholder="بحث" aria-label="Search" >
			 				<button class="btn  btn-lg  btn-danger"id="btnsearch"  type="submit" value="Search"><i class="fa fa-search text-white" aria-hidden="true"></i></button>
			 			</form>
						</div>
					</div>
				</div>
			</div>
		   <!-- section head -->
		   <section class="my-5">
				 <h2  class="text-center mb-4">أستمارة تحديث بيانات ذوي الشهداء </h2>
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
							   <th>الرتبة</th>
							   <th>الاسم الرباعي واللقب</th>
							   <th> أسم الام الثلاثي</th>
							   <th>رقم الهاتق </th>
							   <th> التاريخ</th>
							   <th> الوقت</th>
							   <th>لوحة التحكم</th>
						   </tr>
					   </thead>
					   <!--Table head-->

					   <!--Table body-->
					   <tbody>
							<?php
							foreach ($martyrinfos as $martyrinfo) {
								echo "<tr>";
									 echo "<td>" . $martyrinfo['martyrInfo_ID']   . "</td>";
									 echo "<td>" . $martyrinfo['martyr_ritba']    . "</td>";
									 echo "<td>" . $martyrinfo['martyr_name'] . "</td>";
									 echo "<td>" . $martyrinfo['martyr_mother'] . "</td>";
									 echo "<td>" . $martyrinfo['martyr_phon'] . "</td>";
									 echo "<td>" . $martyrinfo['Date_enter']     . "</td>";
									 echo "<td>" . $martyrinfo['Time_enter']     . "</td>";
								 echo '<td>
										 <a href="martyrinfo.php?do=Infoviwe2&martyrinfo=' . $martyrinfo['martyrInfo_ID'] . ' " target="_blank"><button type="button" class="btn btn-success btn-sm b0"><i class="fas fa-edit"></i> عرض </button></a>
										 <a href="martyrinfoPDF.php?martyrinfoID=' . $martyrinfo['martyrInfo_ID'] . ' " target="_blank"><button type="button" class="btn btn-info btn-sm b0"><i class="fas fa-check"></i>PDF </button></a>
										 <a href="martyrinfo.php?do=Delete&martyrinfID=' . $martyrinfo['martyrInfo_ID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"><i class="fas fa-times"></i> حذف </button></a>
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
            <section class="ganiralform">
                <div class="container ganiralform1" dir="rtl">
                    <h2 class="text-center">تم اغلاق الاستمارة ...مع تحيات مديرية شؤون الشهداء والجرحى</h2>
                </div>
        </section>
        <?php
		} elseif ($do == 'Insert') {

		} elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {

		} elseif ($do == 'Delete') {
		if(isset($_SESSION['user'])){
			// check if get request itemid is numeric & get the integer value of it
			$martyrinfID=isset($_GET['martyrinfID']) && is_numeric($_GET['martyrinfID']) ? intval($_GET['martyrinfID']) : 0;
			// select all data depend on this id
			$chek=checkitem("martyrInfo_ID" , "martyrinfo" , $martyrinfID);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM martyrinfo WHERE martyrInfo_ID =:zmrtinfo" );
				$stmt->bindparam(":zmrtinfo",$martyrinfID);
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
