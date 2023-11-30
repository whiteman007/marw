<?php


		include 'session.php';
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		// the main Page (index.php)

		if ($do == 'Manage') { 
			// START OUR PAGNATION BOX
			$rows=countitem('items_ID' , 'items');
			$page_rows=7;
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
			if (isset($_GET['Pending'])) {
			$query =' WHERE Approve = 0 ';
			}
			//start our marquee section
			$stmt=$con->prepare("SELECT * FROM items  ORDER BY items_ID ASC");
			$stmt->execute();
			$newss=$stmt->fetchall();
			if (!empty($newss)){?>
				<div class="container">
					<div class="marquee_news">
						<span class="last_news">:اخر الاخبار <i class="far fa-newspaper"></i></span>
						<div class="marquee_news">
							<marquee   direction="right" height="60%" onmouseover="this.stop();" onmouseout="this.start();">
								<?php foreach ($newss as $news) {?>
									<a <?php echo 'href= "index.php?do=infoitems&itemid=' . $news['items_ID'] . ' "' ?>><span class="marquee_item"><?php echo $news['Subjuct']; ?> </span></a><i class="fas fa-grip-lines-vertical"></i>
								<?php } ?>
							</marquee>
						</div>
					</div>
	    		</div>
					</div>
				<?php
				}
			//end our marquee section
			$stmt=$con->prepare('SELECT
							shohda_slider.*,users.UserName,users.FullName
						FROM
							shohda_slider
					INNER JOIN
							users
					ON
							users.UserID = shohda_slider.User_ID
					ORDER BY
							`shohda_slider`.`shohda_ID`
					DESC');
			$stmt->execute();
			$shahids=$stmt->fetchall();
			if (! empty($shahids)){
			?>
						<div class="container mt-3">
							<h1  class="text-center mb-4">صفحة أخر الاخبار ونشاطات المديرية</h1>
							<div class="row">
								<!-- our slider of activites -->
							<?php } ?>
								<?php $stmt=$con->prepare('SELECT
																solder.*,users.UserName,users.FullName
															FROM
																solder
														INNER JOIN
																users
														ON
																users.UserID = solder.User_ID2
														ORDER BY
																`solder`.`ID_Slider`
														DESC');
								$stmt->execute();
								$sliders=$stmt->fetchall();
								if (! empty($sliders)){
								?>
								<div class="col-8 slider_activity" dir="rtl">
									<h2 class="text-right mb-2">نشاطات المديرية</h2>
												<!-- Carousel wrapper -->
												<div
													id="carouselBasicExample"
													class="carousel slide carousel-fade"
													data-mdb-ride="carousel"
												>
													<!-- Inner -->
													<div class="carousel-inner">
														<?php
														$i=0;
														foreach ($sliders as $slider) {
															$actives='';
															if($i==0){
																$actives='active';}?>

														<!-- Single item -->
														<div class="carousel-item <?=$actives?>">
															<img
																<?php echo 'src="uploads/item_img/'.$slider['Photo_slider'].'"';?>
																class="d-block w-100"
																alt="..."
															/>
															<div class="carousel-caption1">
																<h5 class="me-2 mt-2">نشاطات المديرية</h5>
																<p class="me-2 ms-2"><a <?php echo 'href= "index.php?do=infoslider&sliderid=' . $slider['ID_Slider'] . ' "' ?>><?php echo $slider['summry_slider']; ?></a></p>

															<?php
															echo '<div class="User_Name">';
															if(isset($_SESSION['user'])){
															echo '<span>' . $slider['FullName'].  ' </span>';
															}
															echo 'التاريخ : <i class="fas fa-calendar-alt"></i> <span>' . $slider['Add_date2'].  '   </span>';
															echo 'الوقت : <i class="far fa-clock"></i> <span>' . $slider['Time_item2'].  ' </span>';
															echo '</div>';
															if(isset($_SESSION['user'])){
															echo '<div class="group-button group_slider">';
																echo '<a href="index.php?do=Edit2&sliderid=' . $slider['ID_Slider'] . ' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
																echo '<a href="index.php?do=Delete2&sliderid=' . $slider['ID_Slider'] . '"><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
															echo '</div>';
														}
															?>
															</div>

														</div>
														<?php $i++; }?>
													</div>

													<!-- Inner -->

													<!-- Controls -->
													<a
														class="carousel-control-prev"
														href="#carouselBasicExample"
														role="button"
														data-mdb-slide="prev"
													>
														<span class="carousel-control-prev-icon" aria-hidden="true"></span>
														<span class="visually-hidden">Previous</span>
													</a>
													<a
														class="carousel-control-next"
														href="#carouselBasicExample"
														role="button"
														data-mdb-slide="next"
													>
														<span class="carousel-control-next-icon" aria-hidden="true"></span>
														<span class="visually-hidden">Next</span>
													</a>
												</div>
												<!-- Carousel wrapper -->
											</div>
											<!-- end slider section -->
											
								<div class="col-4 shohda_slider">
									<h2 class="text-right mb-2">شهدائنا الابطال</h2>
						<!-- start slider section -->
										<div id="carouselExampleCaptions" class="carousel slide" data-mdb-ride="carousel">
									<div class="carousel-inner">
										<?php
										$i=0;
										foreach ($shahids as $shahid) {
											$actives='';
											if($i==0){
											$actives='active';}?>

									<div class="carousel-item <?=$actives?>">
											<a <?php echo 'href= "index.php?do=shohda_info&shodaid=' . $shahid['shohda_ID'] . ' "' ?>>
												<img
														<?php echo 'src="uploads/shahid/'.$shahid['shahid_photo'].'"';?>
												class="d-block w-100"
												alt="..."
												/>
											</a>
										<div class="carousel-caption1">
												<h5 class="me-2 mt-2"><a <?php echo 'href= "index.php?do=shohda_info&shodaid=' . $shahid['shohda_ID'] . ' "' ?>> <?php echo $shahid['Name_shahid']; ?></a></h5>
												<p class="me-2 ms-2"><a <?php echo 'href= "index.php?do=shohda_info&shodaid=' . $shahid['shohda_ID'] . ' "' ?>><?php echo $shahid['shahid_summry']; ?></a></p>
												<?php
												echo '<div class="User_Name">';
												if(isset($_SESSION['user'])){
												echo '<span>' . $shahid['FullName'].  ' </span>';
												}
												echo 'التاريخ : <i class="fas fa-calendar-alt"></i> <span>' . $shahid['shahid_Date'].  '   </span>';
												echo 'الوقت : <i class="far fa-clock"></i> <span>' . $shahid['shahid_Time'].  ' </span>';
												echo '</div>';
												if(isset($_SESSION['user'])){
												echo '<div class="group-button group_shahid">';
													echo '<a href="index.php?do=Edit3&shodaid=' . $shahid['shohda_ID'] .' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
													echo '<a href="index.php?do=Delete3&shodaid=' . $shahid['shohda_ID'] . '"><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
												echo '</div>';
											}
												?>
										</div>
									</div>
										<?php $i++; }?>
									</div>
									<a
									class="carousel-control-prev"
									href="#carouselExampleCaptions"
									role="button"
									data-mdb-slide="prev"
									>
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Previous</span>
									</a>
									<a
									class="carousel-control-next"
									href="#carouselExampleCaptions"
									role="button"
									data-mdb-slide="next"
									>
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Next</span>
									</a>
							</div>
						</div>
					</div>
				</div>
				<?php

						}
						$stmt=$con->prepare("SELECT
													items.*,users.UserName,users.FullName
											FROM
													items
											INNER JOIN
													users
											ON
													users.UserID = items.User_ID
											ORDER BY
													`items`.`items_ID`
											DESC
											$limit1");
						$stmt->execute();
						$items=$stmt->fetchall();
						if (! empty($items)){?>

                       <!-- start defin our websites -->
                     <section class="martyrs-dep" dir="rtl">
					<div class="container">
					<div class="row">
						<div class="col-4">
						<h1 class='text-center mt-5'>استمارات</h1>
						<div class="istmarat">
								<div class="jarha">
									<a href="ganeralform.php?do=Add">
										<img src="images/form1.png" alt="our istimarat img">
									</a>
									<a href="ganeralform.php?do=Add">
										<h5> استمارة الطلبات</h5>
									</a>
								</div>
								<div class="shohad">
									<a href="haj.php?do=Add">
										<img src="images/form1.png" alt="our istimarat img">
									</a>
									<a href="Silaf_Form.php?do=Add">
										<h5>أستمارة أطفاء السلف</h5> 
									</a>
								</div>
							</div>
							<div class="tube_frame mt-2">
								<h5>مقابلات الاستاذ زامل</h5>
								<div class="istmarat2">
									<embed width="100%" height="234" src="https://www.youtube.com/embed/watch?v=uCBv4NA4NZE&list=UUGS73FPxyE-honKaCrDj5rw&index=1&t=75s&ab_channel=zamilalsady" type="">
								</div>
							</div>
							<div class="guder mt-2">
								<h5> دليل تبسيط الاجراءات</h5>
								<a href="guider.php"><img style="width: 400px;" src="images/guder.jpg" alt="our guder img"></a>
							</div>
						</div>
						<div class="col-8">
							<h1 class='text-center mt-5'>أخر الاخبار</h1>
							<?php foreach ($items as $item) { ?>
							<div class='news_section'>
									<div class="title-news">
										<p class="lead text-center">
										<a <?php echo 'href= "index.php?do=infoitems&itemid=' . $item['items_ID'] . ' "' ?>  rel="noopener noreferrer"><?php echo $item['Subjuct']; ?> </a>
										</p>
									</div>
									<div class="news-1">
									<p class="text-justify"><?php echo $item['summry']; ?>
										<a <?php echo 'href= "index.php?do=infoitems&itemid=' . $item['items_ID'] . ' "' ?>  rel="noopener noreferrer">... أقرأ المزيد </a>
									</p>
									<?php
									// our button Group
									if(isset($_SESSION['user'])){
										echo '<div class="group-button">';
											echo '<a href="index.php?do=Edit&itemid=' . $item['items_ID'] . ' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
											echo '<a href="index.php?do=Delete&itemid=' . $item['items_ID'] . ' "><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
											echo '</div>';
											}
											echo '<div class="User_Name">';
											if(isset($_SESSION['user'])){
											echo '<span>' . $item['FullName'].  ' </span>';
											}
											echo 'التاريخ : <i class="fas fa-calendar-alt"></i> <span>' . $item['Add_date'].  '   </span>';
											echo 'الوقت : <i class="far fa-clock"></i> <span class="ms-2">' . $item['Time_item'].  ' </span>';
											echo '</div>';

										?>
								</div>
							</div>
							<hr class="line0">

							<?php  } }  ?>
							<!-- end defin our websites -->
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
							if (isset($_GET['Pending'])) {
						}else {
							?>
				<div class="container pagnation1 mb-3">
						<span class="font-bold-pagnation" aria-hidden="true">&laquo;</span>
								<?php echo $paginationCtrls; ?>
						<span class="font-bold-pagnation" aria-hidden="true">&raquo;</span>
						<?php
						echo "<div class='total-record-search'>";
						echo '<p class="text-center mt-2">عدد السجلات = </span>' . $rows  . '<span class="total-record-search"> الصفحة =' .  $pagenum . '</p> ' ;
						echo "</div>";
						?>
				</div>
				<!-- Section: Blog v.3 -->
				<?php
					}
					?>
							</div>
					</div>
				</div>
		</section>

		<?php
   		// start our ADD news item page
		}elseif ($do == 'Add') {
			if(isset($_SESSION['user'])){?>
				<div class="bodyofsite">
					<!-- start news our websites -->
					<section class="martyrs-dep" dir="rtl">
						<div class="container">
						<div class="row">
							<h2 class="text-center mt-4">صفحة اضافة ونشر أخر أخبار المديرية</h2>
							<div class="add_news">
								<!-- Default form group -->
								<form action="?do=Insert" method="POST" enctype="multipart/form-data">
								<?php
								// ganerate CSRF token to protact Form 
								if(isset($_POST['CSRF_token_news'])){
									if($_POST['CSRF_token_news'] == $_SESSION['CSRF_token_news']){
									}else{
									echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
									}
									$max_time = 60*60*24;
									if(isset($_SESSION['CSRF_token_time'])){
									$token_time=$_SESSION['CSRF_token_time'];
									if(($token_time + $max_time) >= time()){
									}else{
										unset($_SESSION['CSRF_token_news']);
										unset($_SESSION['CSRF_token_time']);
										echo "CSRF TOKEN EXPIRED";
									}
									}
								}
								$token = md5(uniqid(mt_rand(), true));
								$_SESSION['CSRF_token_news'] = $token;
								$_SESSION['CSRF_token_time'] = time();
								// ganerate CSRF token to protact Form 
								?>
								<input type="hidden" name="CSRF_token_news" value="<?php echo $token; ?>">
								<!-- Default input -->
								<div class="form-group">
									<label>العنوان</label>
									<input type="text" name="subject" class="form-control" id="formGroupExampleInput" placeholder="عنوان المنشور">
								</div>
								<!-- Default input -->
								<div class="form-group">
									<label>موجز عن المنشور</label>
									<textarea name="summry_news" class="form-control" id="formGroupExampleInput2" placeholder="يتم كتابة موجز عن المنشور يوضح للقاريء تفاصيل المنشور" rows="5"></textarea>
								</div>
								<div class="form-group">
									<label>تفاصيل المنشور</label>
									<textarea class="form-control" name="dital_news" id="exampleFormControlTextarea3" placeholder="تفاصيل المنشور" rows="20"></textarea>
								</div>
									<!-- star our Image Upload  -->
									<div class="container mt-3 mb-4 arabic-text-align">
									<h2>تحميل صورة </h2>
									<!-- start Image1 box -->
											<div class="file-field">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة 1<input type="file" name="img1"  id="aa"  >
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel">اختر صورة</p>
													</div>
											</div>
											<!-- end Image1 box -->
											<!-- start Image2 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة2<input type="file" name="img2"  id="aa2" onchange="pressed2()" >
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel2">أختر صورة</p>
													</div>
											</div>
											<!-- end Image2 box -->
											<!-- start Image3 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة3<input type="file" name="img3"  id="aa3" onchange="pressed3()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel3">أختر صورة</p>
													</div>
											</div>
											<!-- end Image3 box -->
											<!-- start Image4 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة4<input type="file" name="img4"  id="aa4" onchange="pressed4()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel4">أختر صورة</p>
													</div>
											</div>
											<!-- end Image4 box -->
											<!-- start Image5 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة5 <input type="file" name="img5"  id="aa5" onchange="pressed5()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel5">أختر صورة</p>
													</div>
											</div>
											<!-- end Image5 box -->
											<!-- start Image6 box -->
											<div class="file-field">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة6<input type="file" name="img6"  id="aa6" onchange="pressed6()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel6">أختر صورة</p>
													</div>
											</div>
											<!-- end Image6 box -->
								</div>
									<!-- end our Image Upload  -->
										<!-- end our Image Upload  -->
										<!-- start submait  -->
										<div class="text-center mt-4 ">
											<input class="btn btn-outline-primary nashir " type="submit" value="نشـــر" >
										</div>
										<!-- end submait  -->
								</form>
								<!-- Default form group -->
								</div>
							</div>
						</div>
					</section>
					<!-- end defin our websites -->
			</div>

		   <?php
		}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}

		} elseif ($do == 'Insert') {
			if(isset($_SESSION['user'])){
				if ($_SERVER['REQUEST_METHOD']=='POST') {
					// upload files image 1
					$img1name	=$_FILES['img1']['name'];
					$img1size	=$_FILES['img1']['size'];
					$img1tmp	=$_FILES['img1']['tmp_name'];
					$img1type	=$_FILES['img1']['type'];
					// upload files image 2
					$img2name	=$_FILES['img2']['name'];
					$img2size	=$_FILES['img2']['size'];
					$img2tmp	=$_FILES['img2']['tmp_name'];
					$img2type	=$_FILES['img2']['type'];
					// upload files image 3
					$img3name	=$_FILES['img3']['name'];
					$img3size	=$_FILES['img3']['size'];
					$img3tmp	=$_FILES['img3']['tmp_name'];
					$img3type	=$_FILES['img3']['type'];
					// upload files image 4
					$img4name	=$_FILES['img4']['name'];
					$img4size	=$_FILES['img4']['size'];
					$img4tmp	=$_FILES['img4']['tmp_name'];
					$img4type	=$_FILES['img4']['type'];
					// upload files image 5
					$img5name	=$_FILES['img5']['name'];
					$img5size	=$_FILES['img5']['size'];
					$img5tmp	=$_FILES['img5']['tmp_name'];
					$img5type	=$_FILES['img5']['type'];
					// upload files image 6
					$img6name	=$_FILES['img6']['name'];
					$img6size	=$_FILES['img6']['size'];
					$img6tmp	=$_FILES['img6']['tmp_name'];
					$img6type	=$_FILES['img6']['type'];

					//list of allowed type to upload
					$imgallowedextension=array("jpeg","jpg","JPG","png","gif");
					// imag1
					$expoldefile=explode("." , $img1name);
					$imgextension = strtolower(end($expoldefile));
					// imag2
					$expoldefile2=explode("." , $img2name);
					$imgextension2 = strtolower(end($expoldefile2));
					// imag3
					$expoldefile3=explode("." , $img3name);
					$imgextension3 = strtolower(end($expoldefile3));
					// imag4
					$expoldefile4=explode("." , $img4name);
					$imgextension4 = strtolower(end($expoldefile4));
					// imag5
					$expoldefile5=explode("." , $img5name);
					$imgextension5 = strtolower(end($expoldefile5));
					// imag6
					$expoldefile6=explode("." , $img6name);
					$imgextension6 = strtolower(end($expoldefile6));

					// get the varaible from the form
					$subject    =$_POST['subject'];
					$summry     =$_POST['summry_news'];
					$desc       =$_POST['dital_news'];
					$desc		=str_ireplace("\n","<br>\n",$desc);
					$user		=$_SESSION['uid'];
					//validat Errors
					$formerror=array();

							if (empty($subject)) {
								$formerror[]='لا يجب ان يكون حقل الموضوع فارغا';
							}
							if (empty($summry)) {
								$formerror[]='لا يجب ان يكون حقل الموجز فارغا ';
							}
							if (empty($desc)) {
								$formerror[]='لا يجب ان يكون حقل تفاصيل المنشور فارغا';
							}

							//img1
							if(! empty($imgextension) && ! in_array($imgextension,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img1size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img2
							if(! empty($imgextension2) && ! in_array($imgextension2,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img2size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img3
							if(! empty($imgextension3) && ! in_array($imgextension3,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img3size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img4
							if(! empty($imgextension4) && ! in_array($imgextension4,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img4size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img5
							if(! empty($imgextension5) && ! in_array($imgextension5,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img5size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img6
							if(! empty($imgextension6) && ! in_array($imgextension6,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img6size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}

							foreach ($formerror as $error) {
								echo '<div class="my_denger" role="alert">' . $error. '</div>';
							}

						if (empty($error)) {

							if(!empty($img1name)){
								$imagUploaded1 = rand(0,100000000) . '_' . $img1name;
								move_uploaded_file($img1tmp,'uploads/item_img/' . $imagUploaded1);
							}else {
								$imagUploaded1=null;
							}
							if(!empty($img2name)){
								$imagUploaded2 = rand(0,100000000) . '_' . $img2name;
								move_uploaded_file($img2tmp,'uploads/item_img/' . $imagUploaded2);
							}else {
								$imagUploaded2=null;
							}
							if(!empty($img3name)){
								$imagUploaded3 = rand(0,100000000) . '_' . $img3name;
								move_uploaded_file($img3tmp,'uploads/item_img/' . $imagUploaded3);
							}else {
								$imagUploaded3=null;
							}
							if(!empty($img4name)){
								$imagUploaded4 = rand(0,100000000) . '_' . $img4name;
								move_uploaded_file($img4tmp,'uploads/item_img/' . $imagUploaded4);
							}else {
								$imagUploaded4=null;
							}
							if(!empty($img5name)){
								$imagUploaded5 = rand(0,100000000) . '_' . $img5name;
								move_uploaded_file($img5tmp,'uploads/item_img/' . $imagUploaded5);
							}else {
								$imagUploaded5=null;
							}
							if(!empty($img6name)){
								$imagUploaded6 = rand(0,100000000) . '_' . $img6name;
								move_uploaded_file($img6tmp,'uploads/item_img/' . $imagUploaded6);
							}else {
								$imagUploaded6=null;
							}
											//insert the data withe this info
							 $stmt=$con->prepare("INSERT INTO
											items (Subjuct,summry,Description,Add_date,Time_item,img1,img2,img3,img4,img5,img6,User_ID)
											VALUES (:zsub, :zsummry, :zdesc,now(),now(),:zimg1,:zimg2,:zimg3,:zimg4,:zimg5,:zimg6,:zuser)");
								$stmt->execute(array(
								'zsub'     => $subject,
								'zsummry'  => $summry,
								'zdesc'    => $desc,
								'zuser'   	=>$user,
								'zimg1'    => $imagUploaded1,
								'zimg2'    => $imagUploaded2,
								'zimg3'    => $imagUploaded3,
								'zimg4'    => $imagUploaded4,
								'zimg5'    => $imagUploaded5,
								'zimg6'    => $imagUploaded6
							));

							//echo success message
							$themsg = "<div class='my_succsuflly container' role='alert'>تمت الاضافة بنجاح </div>" ;
							redirect($themsg,'');
				 }
			} else {
				$themsg = "<div class='my_denger container'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}
			echo "</div>";
			}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}

		// start our ADD slider Item page
		 }elseif ($do == 'Add2') {
			 if(isset($_SESSION['user'])){?>
				 <div class="bodyofsite">
								 <!-- start news our websites -->
								 <section class="martyrs-dep" dir="rtl">
									 <div class="container">
									 <div class="row">
									 <h2 class="text-center mt-4"> صفحة اضافة سلايدر (نشاطات)  </h2>

									 <div class="add_news">
										 <!-- Default form group -->
										 <form action="?do=Insert2" method="POST" enctype="multipart/form-data">
										 <?php
										// ganerate CSRF token to protact Form 
										if(isset($_POST['CSRF_token_slide'])){
											if($_POST['CSRF_token_slide'] == $_SESSION['CSRF_token_slide']){
											}else{
											echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
											}
											$max_time = 60*60*24;
											if(isset($_SESSION['CSRF_token_time'])){
											$token_time=$_SESSION['CSRF_token_time'];
											if(($token_time + $max_time) >= time()){
											}else{
												unset($_SESSION['CSRF_token_slide']);
												unset($_SESSION['CSRF_token_time']);
												echo "CSRF TOKEN EXPIRED";
											}
											}
										}
										$token = md5(uniqid(mt_rand(), true));
										$_SESSION['CSRF_token_slide'] = $token;
										$_SESSION['CSRF_token_time'] = time();
										// ganerate CSRF token to protact Form 
										?>
										<input type="hidden" name="CSRF_token_slide" value="<?php echo $token; ?>">
										 <!-- Default input -->
										 <div class="form-group">
										<label>العنوان</label>
											<input type="text" name="subject_slider"  class="form-control" id="formGroupExampleInput" placeholder="عنوان المنشور">
										</div>

										 <div class="form-group">
											 <label>موجز عن السلايدر</label>
											 <textarea type="text" class="form-control" name="summery2" rows="3" maxlength="110
											 " id="formGroupExampleInput2" placeholder="يتم كتابة موجز عن السلايدر يوضح للقاريء تفاصيل السلايدر"></textarea>
										 </div>
												<!-- start our Image Upload  -->
												<div class="container mt-3 mb-4">
												 <h2>تحميل صورة السلايد  </h2>
												 <!-- start Image7 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img7...<input type="file" name="img7"  id="aa7" onchange="pressed()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image7 box -->
												 </div>
												 <!-- end our Image Upload  -->

										 <div class="form-group">
											 <label>تفاصيل المنشور</label>
											 <textarea class="form-control" name="dital2" id="exampleFormControlTextarea3" rows="20"></textarea>
										 </div>

										 <!-- start our Image Upload  -->
										 <div class="container mt-3 mb-4">
												 <h1>تحميل الصور للمنشور </h1>
												 <!-- start Image1 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img1...<input type="file" name="img1"  id="aa" onchange="pressed()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image1 box -->
													 <!-- start Image2 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img2...<input type="file" name="img2"  id="aa2" onchange="pressed2()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel2">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image2 box -->
													 <!-- start Image3 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img3...<input type="file" name="img3"  id="aa3" onchange="pressed3()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel3">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image3 box -->
													 <!-- start Image4 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img4...<input type="file" name="img4"  id="aa4" onchange="pressed4()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel4">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image4 box -->
													 <!-- start Image5 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img5... <input type="file" name="img5"  id="aa5" onchange="pressed5()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel5">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image5 box -->
													 <!-- start Image6 box -->
													 <div class="file-field">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img6...<input type="file" name="img6"  id="aa6" onchange="pressed6()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel6">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image6 box -->
												 </div>
												 <!-- end our Image Upload  -->
												 <!-- start submait  -->
												 <div class="text-center mt-4">
													 <input class="btn btn-outline-primary nashir" type="submit" value="نشر" >
												 </div>
												 <!-- end submait  -->
										 </form>
										 <!-- Default form group -->
										 </div>
									 </div>
								 </div>
								 </section>
								 <!-- end defin our websites -->
						 </div>

			<?php
		 }else {
			 echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			 header('location:index.php');
			 exit();
			 }
		 // add the slider news
		} elseif ($do == 'Insert2') {
			if(isset($_SESSION['user'])){
				if ($_SERVER['REQUEST_METHOD']=='POST') {
					// upload files image 1
					$img1name	=$_FILES['img1']['name'];
					$img1size	=$_FILES['img1']['size'];
					$img1tmp	=$_FILES['img1']['tmp_name'];
					$img1type	=$_FILES['img1']['type'];
					// upload files image 2
					$img2name	=$_FILES['img2']['name'];
					$img2size	=$_FILES['img2']['size'];
					$img2tmp	=$_FILES['img2']['tmp_name'];
					$img2type	=$_FILES['img2']['type'];
					// upload files image 3
					$img3name	=$_FILES['img3']['name'];
					$img3size	=$_FILES['img3']['size'];
					$img3tmp	=$_FILES['img3']['tmp_name'];
					$img3type	=$_FILES['img3']['type'];
					// upload files image 4
					$img4name	=$_FILES['img4']['name'];
					$img4size	=$_FILES['img4']['size'];
					$img4tmp	=$_FILES['img4']['tmp_name'];
					$img4type	=$_FILES['img4']['type'];
					// upload files image 5
					$img5name	=$_FILES['img5']['name'];
					$img5size	=$_FILES['img5']['size'];
					$img5tmp	=$_FILES['img5']['tmp_name'];
					$img5type	=$_FILES['img5']['type'];
					// upload files image 6
					$img6name	=$_FILES['img6']['name'];
					$img6size	=$_FILES['img6']['size'];
					$img6tmp	=$_FILES['img6']['tmp_name'];
					$img6type	=$_FILES['img6']['type'];
					// upload files image 7
					$img7name	=$_FILES['img7']['name'];
					$img7size	=$_FILES['img7']['size'];
					$img7tmp	=$_FILES['img7']['tmp_name'];
					$img7type	=$_FILES['img7']['type'];

					//list of allowed type to upload
					$imgallowedextension=array("jpeg","jpg","JPG","png","gif");
					// imag1
					$expoldefile=explode("." , $img1name);
					$imgextension = strtolower(end($expoldefile));
					// imag2
					$expoldefile2=explode("." , $img2name);
					$imgextension2 = strtolower(end($expoldefile2));
					// imag3
					$expoldefile3=explode("." , $img3name);
					$imgextension3 = strtolower(end($expoldefile3));
					// imag4
					$expoldefile4=explode("." , $img4name);
					$imgextension4 = strtolower(end($expoldefile4));
					// imag5
					$expoldefile5=explode("." , $img5name);
					$imgextension5 = strtolower(end($expoldefile5));
					// imag6
					$expoldefile6=explode("." , $img6name);
					$imgextension6 = strtolower(end($expoldefile6));
					// imag7
					$expoldefile7=explode("." , $img7name);
					$imgextension7 = strtolower(end($expoldefile7));

					// get the varaible from the form
					$sub    	=$_POST['subject_slider'];
					$summry    	=$_POST['summery2'];
					$desc       =$_POST['dital2'];
					$desc		=str_ireplace("\n","<br>\n",$desc);
					$user		=$_SESSION['uid'];
					//validat Errors
					$formerror=array();

							if (empty($summry)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا ';
							}
							if (empty($desc)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا';
							}

							//img1
							if(! empty($imgextension) && ! in_array($imgextension,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img1size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img2
							if(! empty($imgextension2) && ! in_array($imgextension2,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img2size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img3
							if(! empty($imgextension3) && ! in_array($imgextension3,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img3size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img4
							if(! empty($imgextension4) && ! in_array($imgextension4,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img4size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img5
							if(! empty($imgextension5) && ! in_array($imgextension5,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img5size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img6
							if(! empty($imgextension6) && ! in_array($imgextension6,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img6size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}
							//img7
							if(! empty($imgextension7) && ! in_array($imgextension7,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img7size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}

							foreach ($formerror as $error) {
								echo '<div class="alert alert-danger" role="alert">' . $error. '</div>';
							}

						if (empty($error)) {

							if(!empty($img1name)){
								$imagUploaded1 = rand(0,100000000) . '_' . $img1name;
								move_uploaded_file($img1tmp,'uploads/item_img/' . $imagUploaded1);
							}else {
								$imagUploaded1=null;
							}
							if(!empty($img2name)){
								$imagUploaded2 = rand(0,100000000) . '_' . $img2name;
								move_uploaded_file($img2tmp,'uploads/item_img/' . $imagUploaded2);
							}else {
								$imagUploaded2=null;
							}
							if(!empty($img3name)){
								$imagUploaded3 = rand(0,100000000) . '_' . $img3name;
								move_uploaded_file($img3tmp,'uploads/item_img/' . $imagUploaded3);
							}else {
								$imagUploaded3=null;
							}
							if(!empty($img4name)){
								$imagUploaded4 = rand(0,100000000) . '_' . $img4name;
								move_uploaded_file($img4tmp,'uploads/item_img/' . $imagUploaded4);
							}else {
								$imagUploaded4=null;
							}
							if(!empty($img5name)){
								$imagUploaded5 = rand(0,100000000) . '_' . $img5name;
								move_uploaded_file($img5tmp,'uploads/item_img/' . $imagUploaded5);
							}else {
								$imagUploaded5=null;
							}
							if(!empty($img6name)){
								$imagUploaded6 = rand(0,100000000) . '_' . $img6name;
								move_uploaded_file($img6tmp,'uploads/item_img/' . $imagUploaded6);
							}else {
								$imagUploaded6=null;
							}
							if(!empty($img7name)){
								$imagUploaded7 = rand(0,100000000) . '_' . $img7name;
								move_uploaded_file($img7tmp,'uploads/item_img/' . $imagUploaded7);
							}else {
								$imagUploaded7=null;
							}
											//insert the data withe this info
							 $stmt=$con->prepare("INSERT INTO
											solder (subject_slider,summry_slider,Photo_slider,Detl_slider,Add_date2,Time_item2,img1_slider,img2_slider,img3_slider,img4_slider,img5_slider,img6_slider,User_ID2)
											VALUES (:zsub,:zsummry, :zimg7, :zdesc,now(),now(),:zimg1,:zimg2,:zimg3,:zimg4,:zimg5,:zimg6,:zuser)");
								$stmt->execute(array(
								'zsub'  	=> $sub,
								'zsummry'  => $summry,
								'zimg7'    => $imagUploaded7,
								'zdesc'    => $desc,
								'zuser'   	=>$user,
								'zimg1'    => $imagUploaded1,
								'zimg2'    => $imagUploaded2,
								'zimg3'    => $imagUploaded3,
								'zimg4'    => $imagUploaded4,
								'zimg5'    => $imagUploaded5,
								'zimg6'    => $imagUploaded6
							));

							//echo success message
							$themsg = "<div class='my_succsuflly container' role='alert'>تمت الاضافة بنجاح </div>" ;
							redirect($themsg,'');
				 }
			} else {
				$themsg = "<div class='my_denger container'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}
			echo "</div>";
			}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}


			// start our ADD slider of shohda Item page

		} elseif ($do == 'Add3') {
			if(isset($_SESSION['user'])){?>
						<div class="bodyofsite">
										<!-- start news our websites -->
										<section class="martyrs-dep" dir="rtl">
											<div class="container">
											<div class="row">
											<h1 class="text-center mt-4"> صفحة اضافة شهيد</h1>

											<div class="add_news">
												<!-- Default form group -->
											<form action="?do=Insert3" method="POST" enctype="multipart/form-data">
											<?php
											// ganerate CSRF token to protact Form 
											if(isset($_POST['CSRF_token_shahid'])){
											if($_POST['CSRF_token_shahid'] == $_SESSION['CSRF_token_shahid']){
											}else{
												echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
											}
											$max_time = 60*60*24;
											if(isset($_SESSION['CSRF_token_time'])){
												$token_time=$_SESSION['CSRF_token_time'];
												if(($token_time + $max_time) >= time()){
												}else{
												unset($_SESSION['CSRF_token_shahid']);
												unset($_SESSION['CSRF_token_time']);
												echo "CSRF TOKEN EXPIRED";
												}
											}
											}
											$token = md5(uniqid(mt_rand(), true));
											$_SESSION['CSRF_token_shahid'] = $token;
											$_SESSION['CSRF_token_time'] = time();
											// ganerate CSRF token to protact Form 
											?>
											<input type="hidden" name="CSRF_token_shahid" value="<?php echo $token; ?>">

												<!-- Default input -->
												<div class="form-group">
											 	<label>اسم الشهيد الكامل</label>
												 <input type="text" name="ShahidName"  class="form-control" id="formGroupExampleInput" placeholder="اسم الشهيد الكامل">

											 </div>

												<div class="form-group">
													<label>موجز عن الشهيد</label>
													<textarea class="form-control" name="Shahid_summary" id="exampleFormControlTextarea3" rows="3" placeholder="موجز عن الشهيد" maxlength = "60"></textarea>
												</div>
													 <!-- start our Image Upload  -->
													 <div class="container mt-3 mb-4">
														<h1>تحميل صورة الشهيد  </h1>
														<!-- start Image7 box -->
															<div class="file-field ">
																<div class="img-butt">
																<div class="mb-1">
																	<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
																</div>
																<span class="btn btn-danger btn-file">
																	img7...<input type="file" name="img_shahid"  id="aa7" onchange="pressed()">
																</span>
																</div>
																<div class="fileLabel2">
																	<p id="fileLabel">أختار صورة</p>
																</div>
															</div>
															<!-- end Image7 box -->
														</div>
														<!-- end our Image Upload  -->

												<div class="form-group">
													<label>تفاصيل عن الشهيد</label>
													<textarea class="form-control" name="dital_shahid" id="exampleFormControlTextarea3" rows="20"></textarea>
												</div>

												<!-- start submait  -->
												<div class="text-center mt-4">
													<input class="btn btn-outline-primary nashir" type="submit" value="نشر" >
												</div>
												<!-- end submait  -->
										</form>
										<!-- Default form group -->
										</div>
									</div>
								</div>
								</section>
								<!-- end defin our websites -->
						</div>

		 <?php
		}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}

		} elseif ($do == 'Insert3') {
			if(isset($_SESSION['user'])){
				if ($_SERVER['REQUEST_METHOD']=='POST') {
					// upload files image 1
					$img1name	=$_FILES['img_shahid']['name'];
					$img1size	=$_FILES['img_shahid']['size'];
					$img1tmp	=$_FILES['img_shahid']['tmp_name'];
					$img1type	=$_FILES['img_shahid']['type'];

					//list of allowed type to upload
					$imgallowedextension=array("jpeg","jpg","JPG","png","gif");
					// imag1
					$expoldefile=explode("." , $img1name);
					$imgextension = strtolower(end($expoldefile));
					// imag2

					// get the varaible from the form
					$nameshahid    	=$_POST['ShahidName'];
					$summry    	=$_POST['Shahid_summary'];
					$desc       =$_POST['dital_shahid'];
					$desc		=str_ireplace("\n","<br>\n",$desc);
					$user		=$_SESSION['uid'];
					//validat Errors
					$formerror=array();

							if (empty($nameshahid)) {
								$formerror[]='يجب أدخال اسم الشهيد الكامل';
							}
							if (empty($summry)) {
								$formerror[]='يجب كتابة وصف وجيز عن الشهيد';
							}
							if (empty($desc)) {
								$formerror[]='يجب أدخال تفاصيل الشهيد ';
							}

							//img1
							if(! empty($imgextension) && ! in_array($imgextension,$imgallowedextension)){
								$formerror[]='امتداد هذه الصورة غير مسموح بها ';
							}
							if($img1size > 15242880){
								$formerror[]='حجم الصورة جدا كبير يجب ان يكون اقل من 10 ميكا بايت';
							}

							foreach ($formerror as $error) {
								echo '<div class="alert alert-danger" role="alert">' . $error. '</div>';
							}

						if (empty($error)) {

							if(!empty($img1name)){
								$imagUploaded1 = rand(0,100000000) . '_' . $img1name;
								move_uploaded_file($img1tmp,'uploads/shahid/' . $imagUploaded1);
							}else {
								$imagUploaded1=null;
							}
											//insert the data withe this info
							 $stmt=$con->prepare("INSERT INTO
																		shohda_slider (Name_shahid,shahid_summry,shahid_detl,shahid_photo,shahid_Date,shahid_Time,User_ID)
																		VALUES (:zname,:zsummry,:zdesc,:zimg_shahid,now(),now(),:zuser)");
								$stmt->execute(array(
								'zname'  				=> $nameshahid,
								'zsummry' 		 	=> $summry,
								'zdesc'    			=> $desc,
								'zimg_shahid'   => $imagUploaded1,
								'zuser'   			=>$user
							));

							//echo success message
							$themsg = "<div class='my_succsuflly container' role='alert'>تمت الاضافة بنجاح </div>" ;
							redirect($themsg,'');
				 }
			} else {
				$themsg = "<div class='my_denger container'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}
			echo "</div>";
			}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}

		} elseif ($do == 'Edit') {
		if(isset($_SESSION['user'])){
		// check if get request item is numeric & get the integer value of it
		$itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
		// select all data depend on this id
		$stmt=$con->prepare("SELECT * FROM items WHERE items_ID = ?");
		//execute Query
		$stmt->execute(array($itemid));
		//fetch the data
		$item=$stmt->fetch();
		//the row account
		$count=$stmt->rowcount();
		// if there is such id show the form
		if ($count > 0) {?>
				<div class="bodyofsite container">
					<!-- start news our websites -->
					<section class="martyrs-dep" dir="rtl">
						<div class="row">
							<h1 class="text-center">صفحة تعديل المنشور الحالي المديرية</h1>
							<div class="add_news">
								<!-- Default form group -->
								<form action="?do=Update" method="POST" >
								<?php
								// ganerate CSRF token to protact Form 
								if(isset($_POST['CSRF_token_edite_news'])){
									if($_POST['CSRF_token_edite_news'] == $_SESSION['CSRF_token_edite_news']){
									}else{
									echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
									}
									$max_time = 60*60*24;
									if(isset($_SESSION['CSRF_token_time'])){
									$token_time=$_SESSION['CSRF_token_time'];
									if(($token_time + $max_time) >= time()){
									}else{
										unset($_SESSION['CSRF_token_edite_news']);
										unset($_SESSION['CSRF_token_time']);
										echo "CSRF TOKEN EXPIRED";
									}
									}
								}
								$token = md5(uniqid(mt_rand(), true));
								$_SESSION['CSRF_token_edite_news'] = $token;
								$_SESSION['CSRF_token_time'] = time();
								// ganerate CSRF token to protact Form 
								?>
								<input type="hidden" name="CSRF_token_edite_news" value="<?php echo $token; ?>">
								<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
								<!-- Default input -->
								<div class="form-group">
									<label>العنوان</label>
									<input type="text" name="subject" value="<?php echo $item['Subjuct'] ?>" class="form-control" id="formGroupExampleInput" placeholder="عنوان المنشور">
								</div>
								<!-- Default input -->
								<div class="form-group">
									<label>موجز عن المنشور</label>
									<textarea name="summry_news"  class="form-control" id="formGroupExampleInput2" placeholder="يتم كتابة موجز عن المنشور يوضح للقاريء تفاصيل المنشور" rows="5"><?php echo $item['summry'] ?></textarea>
								</div>
								<div class="form-group">
									<label>تفاصيل المنشور</label>
									<textarea class="form-control" name="dital_news"   id="exampleFormControlTextarea3" placeholder="تفاصيل المنشور" rows="20"><?php echo $item['Description'] ?></textarea>
								</div>
									<!-- star our Image Upload  -->
									<div class="container mt-3 mb-4 arabic-text-align">
									<h3>تحميل صورة </h3>
									<!-- start Image1 box -->
											<div class="file-field">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة 1<input type="file" name="img1"  id="aa" onchange="pressed()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel">اختر صورة</p>
													</div>
											</div>
											<!-- end Image1 box -->
											<!-- start Image2 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة2<input type="file" name="img2"  id="aa2" onchange="pressed2()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel2">أختر صورة</p>
													</div>
											</div>
											<!-- end Image2 box -->
											<!-- start Image3 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة3<input type="file" name="img3"  id="aa3" onchange="pressed3()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel3">أختر صورة</p>
													</div>
											</div>
											<!-- end Image3 box -->
											<!-- start Image4 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة4<input type="file" name="img4"  id="aa4" onchange="pressed4()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel4">أختر صورة</p>
													</div>
											</div>
											<!-- end Image4 box -->
											<!-- start Image5 box -->
											<div class="file-field ">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة5 <input type="file" name="img5"  id="aa5" onchange="pressed5()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel5">أختر صورة</p>
													</div>
											</div>
											<!-- end Image5 box -->
											<!-- start Image6 box -->
											<div class="file-field">
												<div class="img-butt">
													<div class="mb-1">
															<img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
													</div>
													<span class="btn btn-danger btn-file">
															صورة6<input type="file" name="img6"  id="aa6" onchange="pressed6()">
													</span>
												</div>
													<div class="fileLabel2">
														<p id="fileLabel6">أختر صورة</p>
													</div>
											</div>
											<!-- end Image6 box -->
								</div>
									<!-- end our Image Upload  -->
										<!-- end our Image Upload  -->
										<!-- start submait  -->
										<div class="text-center mt-4 ">
											<input class="btn btn-outline-primary nashir " type="submit" value="حـــفـــظ" >
										</div>
										<!-- end submait  -->
								</form>
								<!-- Default form group -->
								</div>
						</div>
					</section>
					<!-- end defin our websites -->
			</div>

			<?php
		}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}
		}


		} elseif ($do == 'Edit2') {
			if(isset($_SESSION['user'])){
				// check if get request item is numeric & get the integer value of it
				$sliderid=isset($_GET['sliderid']) && is_numeric($_GET['sliderid']) ? intval($_GET['sliderid']) : 0;
				// select all data depend on this id
				$stmt=$con->prepare("SELECT * FROM solder WHERE ID_Slider = ?");
				//execute Query
				$stmt->execute(array($sliderid));
				//fetch the data
				$slider=$stmt->fetch();
				//the row account
				$count=$stmt->rowcount();
				// if there is such id show the form
				if ($count > 0) {?>
								 <div class="bodyofsite container">
								 <!-- start news our websites -->
								 <section class="martyrs-dep" dir="rtl">
									 <div class="row">
									 <h1 class="text-center"> صفحة اضافة ونشر اخر نشاطات  وفعاليات السيد المدير  </h1>

									 <div class="add_news">
										 <!-- Default form group -->
										 <form action="?do=Update2" method="POST" enctype="multipart/form-data">
										 <?php
										// ganerate CSRF token to protact Form 
										if(isset($_POST['CSRF_token_edite_slide'])){
											if($_POST['CSRF_token_edite_slide'] == $_SESSION['CSRF_token_edite_slide']){
											}else{
											echo 'PROBLEM WITHE CSRF TOKEN VERFICATION';
											}
											$max_time = 60*60*24;
											if(isset($_SESSION['CSRF_token_time'])){
											$token_time=$_SESSION['CSRF_token_time'];
											if(($token_time + $max_time) >= time()){
											}else{
												unset($_SESSION['CSRF_token_edite_slide']);
												unset($_SESSION['CSRF_token_time']);
												echo "CSRF TOKEN EXPIRED";
											}
											}
										}
										$token = md5(uniqid(mt_rand(), true));
										$_SESSION['CSRF_token_edite_slide'] = $token;
										$_SESSION['CSRF_token_time'] = time();
										// ganerate CSRF token to protact Form 
										?>
										<input type="hidden" name="CSRF_token_edite_slide" value="<?php echo $token; ?>">
										 <input type="hidden" name="sliderid" value="<?php echo $sliderid ?>" />
										 <!-- Default input -->
										 <div class="form-group">
										<label>العنوان</label>
											<input type="text" name="subject_slider" value="<?php echo  $slider['subject_slider'] ?>" class="form-control" id="formGroupExampleInput" placeholder="عنوان المنشور">
										</div>

										 <div class="form-group">
											 <label>موجز عن السلايدر</label>
											 <textarea type="text" class="form-control" name="summery2" rows="3" maxlength="110" id="formGroupExampleInput2" placeholder="يتم كتابة موجز عن السلايدر يوضح للقاريء تفاصيل السلايدر"><?php echo $slider['summry_slider'] ?>"</textarea>
										 </div>
												<!-- start our Image Upload  -->
												<div class="container mt-3 mb-4">
												 <h1>تحميل صورة السلايد  </h1>
												 <!-- start Image7 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img7...<input type="file" name="img7"  id="aa7" onchange="pressed()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image7 box -->
												 </div>
												 <!-- end our Image Upload  -->

										 <div class="form-group">
											 <label>تفاصيل المنشور</label>
											 <textarea class="form-control" name="dital2" id="exampleFormControlTextarea3" rows="20"><?php echo $slider['Detl_slider'] ?></textarea>
										 </div>

										 <!-- start our Image Upload  -->
										 <div class="container mt-3 mb-4">
												 <h1>تحميل الصور للمنشور </h1>
												 <!-- start Image1 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img1...<input type="file" name="img1"  id="aa" onchange="pressed()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image1 box -->
													 <!-- start Image2 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img2...<input type="file" name="img2"  id="aa2" onchange="pressed2()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel2">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image2 box -->
													 <!-- start Image3 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img3...<input type="file" name="img3"  id="aa3" onchange="pressed3()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel3">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image3 box -->
													 <!-- start Image4 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img4...<input type="file" name="img4"  id="aa4" onchange="pressed4()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel4">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image4 box -->
													 <!-- start Image5 box -->
													 <div class="file-field ">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img5... <input type="file" name="img5"  id="aa5" onchange="pressed5()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel5">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image5 box -->
													 <!-- start Image6 box -->
													 <div class="file-field">
														 <div class="img-butt">
														 <div class="mb-1">
															 <img src="images/defult.png" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
														 </div>
														 <span class="btn btn-danger btn-file">
															 img6...<input type="file" name="img6"  id="aa6" onchange="pressed6()">
														 </span>
														 </div>
														 <div class="fileLabel2">
															 <p id="fileLabel6">أختار صورة</p>
														 </div>
													 </div>
													 <!-- end Image6 box -->
												 </div>
												 <!-- end our Image Upload  -->
												 <!-- start submait  -->
												 <div class="text-center mt-4">
													 <input class="btn btn-outline-primary nashir" type="submit" value="نشر" >
												 </div>
												 <!-- end submait  -->
										 </form>
										 <!-- Default form group -->
										 </div>
									 </div>
								 </section>
								 <!-- end defin our websites -->
						 </div>
					<?php
				}else {
					echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
					header('location:index.php');
					exit();
					}
				}


		} elseif ($do == 'Update') {
			if(isset($_SESSION['user'])){
				if ($_SERVER['REQUEST_METHOD']=='POST') {

					// get the varaible from the form
					$id      	=$_POST['itemid'];
					$subject    =$_POST['subject'];
					$summry     =$_POST['summry_news'];
					$desc       =$_POST['dital_news'];
					//validat Errors
					$formerror=array();

							if (empty($subject)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا ';
							}
							if (empty($summry)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا ';
							}
							if (empty($desc)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا';
							}
											//insert the data withe this info
								              //get the data withe this info
											  $stmt=$con->prepare("UPDATE
																		items
																	SET
																		Subjuct=?,
																		summry=?,
																		Description=?,
																		Add_date=now(),
																		Time_item=now()
																	WHERE items_ID=?" );
							$stmt->execute(array($subject,$summry,$desc,$id));

							//echo success message
							$themsg = "<div class='my_succsuflly container ' role='alert'>تمت التعديل بنجاح </div>" ;
							redirect($themsg,'');
			} else {
				$themsg = "<div class='my_denger container'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}
			echo "</div>";
			}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}
		} elseif ($do == 'Update2') {
			if(isset($_SESSION['user'])){
				if ($_SERVER['REQUEST_METHOD']=='POST') {

					// get the varaible from the form
					$id      	=$_POST['sliderid'];
					$sub    	=$_POST['subject_slider'];
					$summry    	=$_POST['summery2'];
					$desc       =$_POST['dital2'];
					//validat Errors
					$formerror=array();

							if (empty($subject)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا ';
							}
							if (empty($summry)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا ';
							}
							if (empty($desc)) {
								$formerror[]='لا يجب ان يكون هذا الحقل فارغا';
							}
											//insert the data withe this info
								              //get the data withe this info
											  $stmt=$con->prepare("UPDATE
																		solder
																	SET
																		subject_slider=?,
																		summry_slider=?,
																		Detl_slider=?,
																		Add_date2=now(),
																		Time_item2=now()
																	WHERE ID_Slider=?" );
							$stmt->execute(array($sub,$summry,$desc,$id));

							//echo success message
							$themsg = "<div class='my_succsuflly container' role='alert'>تمت التعديل بنجاح </div>" ;
							redirect($themsg,'');
			} else {
				$themsg = "<div class='my_denger container'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
				redirect($themsg,'');
			}
			echo "</div>";
			}else {
			echo "انت غير مسموح لك بالدخول الى هذه الصفحة  ";
			header('location:index.php');
			exit();
			}


		} elseif ($do == 'Delete') {
			if(isset($_SESSION['user'])){
			// check if get request itemid is numeric & get the integer value of it
			$itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			// select all data depend on this id
			$chek=checkitem("items_ID" , "items" , $itemid);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM items WHERE items_ID=:zitem" );
				$stmt->bindparam(":zitem",$itemid);
				$stmt->execute();
				$themsg = "<div class='my_succsuflly container' role='alert'>تمت عملية الحذف بنجاح </div>" ;
				redirect($themsg,'');

			} else {
				echo "<div class='container container'>";
				$themsg = "<div class='my_denger'> Sorry There Is No Such ID </div>";
				redirect($themsg,'');
				echo "</div>";
				}

			}

		} elseif ($do == 'Delete2') {
			if(isset($_SESSION['user'])){
			// check if get request sliderid is numeric & get the integer value of it
			$sliderid=isset($_GET['sliderid']) && is_numeric($_GET['sliderid']) ? intval($_GET['sliderid']) : 0;
			// select all data depend on this id
			$chek=checkitem("ID_Slider" , "solder" , $sliderid);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM solder WHERE ID_Slider=:zslider" );
				$stmt->bindparam(":zslider",$sliderid);
				$stmt->execute();
				$themsg = "<div class='my_succsuflly container' role='alert'>تمت عملية الحذف بنجاح </div>" ;
				redirect($themsg,' ');

			} else {
				echo "<div class='container'>";
				$themsg = "<div class='my_denger'> Sorry There Is No Such ID </div>";
				redirect($themsg,'');
				echo "</div>";
				}

			}

		} elseif ($do == 'Delete3') {
			if(isset($_SESSION['user'])){
			// check if get request sliderid is numeric & get the integer value of it
			$shodaid=isset($_GET['shodaid']) && is_numeric($_GET['shodaid']) ? intval($_GET['shodaid']) : 0;
			// select all data depend on this id
			$chek=checkitem("shohda_ID" , "shohda_slider" , $shodaid);

			if ($chek > 0) {
				$stmt=$con->prepare("DELETE FROM shohda_slider WHERE shohda_ID=:zshohda" );
				$stmt->bindparam(":zshohda",$shodaid);
				$stmt->execute();
				$themsg = "<div class='my_succsuflly container' role='alert'>تمت عملية الحذف بنجاح </div>" ;
				redirect($themsg,' ');

			} else {
				echo "<div class='container'>";
				$themsg = "<div class='my_denger'> Sorry There Is No Such ID </div>";
				redirect($themsg,'');
				echo "</div>";
				}

			}


		} elseif ($do == 'infoitems') {
			$itemid=isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			$itemid=filter_var($itemid,FILTER_SANITIZE_NUMBER_INT);
			$stmt=$con->prepare('SELECT
											items.*,users.UserName,users.FullName
									FROM
											items
									INNER JOIN
											users
									ON
											users.UserID = items.User_ID

									WHERE	items_ID=?

									ORDER BY
											`items`.`items_ID`
									DESC');
			$stmt->execute(array($itemid));
			$items=$stmt->fetchall();
			foreach ($items as $item) {?>
				<div class="slider_main_info" dir="rtl">
					<div class="container">
					<div class="slider_info">
					<h1 class="text-center mt-4 mb-3">أخبار مديرية شؤون الشهداء والجرحى  </h1>
					<?php if (!empty($item['img1']||$item['img2']||$item['img3']||$item['img4']||$item['img5']||$item['img6'])){?>
					<h2 class="text-right mb-4">مجموعة من الصور</h2>
					<?php }?>
					<!-- start slider section -->
					<!-- Carousel wrapper -->
					<div class="container">
						<div
							id="carouselExampleInterval"
							class="carousel slide"
							data-mdb-ride="carousel"
						>
							<div class="carousel-inner">
								<?php if(!empty($item['img1'])){ ?>
								<div class="carousel-item active" data-mdb-interval="10000">
									<img
										src="<?php echo 'uploads/item_img/'.$item['img1'] ?>"
										class="d-block w-100"
										alt="news img"
									/>
								</div>
							<?php }?>
							<?php if(!empty($item['img2'])){ ?>
							<div class="carousel-item" data-mdb-interval="10000">
								<img
									src="<?php echo 'uploads/item_img/'.$item['img2'] ?>"
									class="d-block w-100"
									alt="news img"
								/>
							</div>
							<?php }?>
							<?php if(!empty($item['img3'])){ ?>
							<div class="carousel-item" data-mdb-interval="10000">
								<img
									src="<?php echo 'uploads/item_img/'.$item['img3'] ?>"
									class="d-block w-100"
									alt="news img"
								/>
							</div>
							<?php }?>
							<?php if(!empty($item['img4'])){ ?>
							<div class="carousel-item" data-mdb-interval="10000">
								<img
									src="<?php echo 'uploads/item_img/'.$item['img4'] ?>"
									class="d-block w-100"
									alt="news img"
								/>
							</div>
							<?php }?>
							<?php if(!empty($item['img5'])){ ?>
							<div class="carousel-item" data-mdb-interval="10000">
								<img
									src="<?php echo 'uploads/item_img/'.$item['img5'] ?>"
									class="d-block w-100"
									alt="news img"
								/>
							</div>
							<?php }?>
							<?php if(!empty($item['img6'])){ ?>
							<div class="carousel-item" data-mdb-interval="10000">
								<img
									src="<?php echo 'uploads/item_img/'.$item['img6'] ?>"
									class="d-block w-100"
									alt="news img"
								/>
							</div>
						<?php }?>
							</div>
							<?php if (!empty($item['img1']||$item['img2']||$item['img3']||$item['img4']||$item['img5']||$item['img6'])){?>
							<button
								class="carousel-control-prev"
								data-mdb-target="#carouselExampleInterval"
								type="button"
								data-mdb-slide="prev"
							>
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button
								class="carousel-control-next"
								data-mdb-target="#carouselExampleInterval"
								type="button"
								data-mdb-slide="next"
							>
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						<?php }?>
						</div>
					</div>

						<!-- Carousel wrapper -->
						<!-- end slider section -->
						<!-- start news our websites -->
						<section  dir="rtl">
							<div class="row">
								<div class="col-sm-12">
									<h2><?php echo $item['Subjuct'] ?></h2>
									<p class="lead text-justify"> <?php echo $item['Description'] ?></p>
											<!--our button Group-->
											<?php
											echo '<div class="User_Name">';
											if(isset($_SESSION['user'])){
											echo '<span>' . $item['FullName'].  ' </span>';
											}
											echo 'التاريخ : <i class="fas fa-calendar-alt"></i> <span>' . $item['Add_date'].  '   </span>';
											echo 'الوقت : <i class="far fa-clock"></i> <span>' . $item['Time_item'].  ' </span>';
											echo '</div>';
											if(isset($_SESSION['user'])){
											echo '<div class="group-button group_slider mt-3">';
											echo '<a href="index.php?do=Edit&itemid=' . $item['items_ID'] . ' "><button type="button" class="btn btn-success btn-lg b1"><i class="far fa-edit"></i> تعديل </button></a>';
											echo '<a href="index.php?do=Delete&itemid=' . $item['items_ID'] . ' "><button type="button" class="btn btn-danger btn-lg  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
											echo '</div>';
										}
											?>
								</div>
							</div>
						</section>
				<!-- end defin our websites -->
				</div>
			</div>
		</div>

				<?php
			}
		} elseif ($do == 'infoslider') {
			$sliderid=isset($_GET['sliderid']) && is_numeric($_GET['sliderid']) ? intval($_GET['sliderid']) : 0;
			$stmt=$con->prepare('SELECT
											solder.*,users.UserName,users.FullName
									FROM
											solder
									INNER JOIN
											users
									ON
											users.UserID = solder.User_ID2

									WHERE	ID_Slider=?

									ORDER BY
											`solder`.`ID_Slider`
									DESC');
			$stmt->execute(array($sliderid));
			$sliders=$stmt->fetchall();
			foreach ($sliders as $slider) {?>

			<div class="slider_main_info" dir="rtl">
				<div class="container">
				<div class="slider_info">
				<h1 class="text-center mt-4 mb-3">أخبار مديرية شؤون الشهداء والجرحى  </h1>
				<?php if (!empty($slider['img1_slider']||$slider['img2_slider']||$slider['img3_slider']||$slider['img4_slider']||$slider['img5_slider']||$slider['img6_slider'])){?>
				<h2 class="text-right mb-4">مجموعة من الصور</h2>
				<?php }?>
				<!-- start slider section -->
				<!-- Carousel wrapper -->
				<div class="container">
					<div
					  id="carouselExampleInterval"
					  class="carousel slide"
					  data-mdb-ride="carousel"
					>
					  <div class="carousel-inner">
							<?php if(!empty($slider['img1_slider'])){ ?>
					    <div class="carousel-item active" data-mdb-interval="10000">
					      <img
									src="<?php echo 'uploads/item_img/'.$slider['img1_slider'] ?>"
					        class="d-block w-100"
					        alt="our slider img"
					      />
					    </div>
						<?php }?>
						<?php if(!empty($slider['img2_slider'])){ ?>
						<div class="carousel-item" data-mdb-interval="10000">
							<img
								src="<?php echo 'uploads/item_img/'.$slider['img2_slider'] ?>"
								class="d-block w-100"
								alt="our slider img"
							/>
						</div>
						<?php }?>
						<?php if(!empty($slider['img3_slider'])){ ?>
						<div class="carousel-item" data-mdb-interval="10000">
							<img
								src="<?php echo 'uploads/item_img/'.$slider['img3_slider'] ?>"
								class="d-block w-100"
								alt="our slider img"
							/>
						</div>
						<?php }?>
						<?php if(!empty($slider['img4_slider'])){ ?>
						<div class="carousel-item" data-mdb-interval="10000">
							<img
								src="<?php echo 'uploads/item_img/'.$slider['img4_slider'] ?>"
								class="d-block w-100"
								alt="our slider img"
							/>
						</div>
						<?php }?>
						<?php if(!empty($slider['img5_slider'])){ ?>
						<div class="carousel-item" data-mdb-interval="10000">
							<img
								src="<?php echo 'uploads/item_img/'.$slider['img5_slider'] ?>"
								class="d-block w-100"
								alt="our slider img"
							/>
						</div>
						<?php }?>
						<?php if(!empty($slider['img6_slider'])){ ?>
						<div class="carousel-item" data-mdb-interval="10000">
							<img
								src="<?php echo 'uploads/item_img/'.$slider['img6_slider'] ?>"
								class="d-block w-100"
								alt="our slider img"
							/>
						</div>
					<?php }?>
					  </div>
						<?php if (!empty($slider['img1_slider']||$slider['img2_slider']||$slider['img3_slider']||$slider['img4_slider']||$slider['img5_slider']||$slider['img6_slider'])){?>
					  <button
					    class="carousel-control-prev"
					    data-mdb-target="#carouselExampleInterval"
					    type="button"
					    data-mdb-slide="prev"
					  >
					    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
					    <span class="visually-hidden">Previous</span>
					  </button>
					  <button
					    class="carousel-control-next"
					    data-mdb-target="#carouselExampleInterval"
					    type="button"
					    data-mdb-slide="next"
					  >
					    <span class="carousel-control-next-icon" aria-hidden="true"></span>
					    <span class="visually-hidden">Next</span>
					  </button>
					<?php }?>
					</div>
				</div>

						<!-- Carousel wrapper -->
      <!-- end slider section -->

						<!-- start news our websites -->
						<div class="row">
							<div class="col-sm-12">
								<h2 class="mt-4 mb-3"><?php echo $slider['subject_slider'] ?></h2>
								<p class="lead text-justify"><?php echo $slider['Detl_slider'] ?></p>
								<?php
								echo '<div class="User_Name">';
								if(isset($_SESSION['user'])){
								echo '<span>' . $slider['FullName'].  ' </span>';
								}
								echo 'التاريخ : <i class="fas fa-calendar-alt"></i> <span>' . $slider['Add_date2'].  '   </span>';
								echo 'الوقت : <i class="far fa-clock"></i> <span>' . $slider['Time_item2'].  ' </span>';
								echo '</div>';
								if(isset($_SESSION['user'])){
								echo '<div class="group-button group_slider mt-3">';
									echo '<a href="index.php?do=Edit2&sliderid=' . $slider['ID_Slider'] . ' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
									echo '<a href="index.php?do=Delete2&sliderid=' . $slider['ID_Slider'] . '"><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
								echo '</div>';
							}
								?>
							</div>
						</div>
				<!-- end defin our websites -->
			</div>
		</div>
	</div>


				<?php
			}

		} elseif ($do == 'shohda_info') {
			$shodaid=isset($_GET['shodaid']) && is_numeric($_GET['shodaid']) ? intval($_GET['shodaid']) : 0;
			$stmt=$con->prepare('SELECT
											shohda_slider.*,users.UserName,users.FullName
									FROM
											shohda_slider
									INNER JOIN
											users
									ON
											users.UserID = shohda_slider.User_ID

									WHERE	shohda_ID=?

									ORDER BY
											`shohda_slider`.`shohda_ID`
									DESC');
			$stmt->execute(array($shodaid));
			$shahids=$stmt->fetchall();
			foreach ($shahids as $shahid) {?>
				<div class="shohda_info" dir="rtl">
					<div class="container shohda_info2 mb-5">
						<div class="row">
							<div class="col-12">
								<h1 class="text-center mt-4 mb-3">شهداء الداخلية الابطال</h1>
								<h2 class="mt-3 mb-5"><?php echo $shahid['Name_shahid'] ?></h2>
								<img src="<?php echo 'uploads/shahid/'.$shahid['shahid_photo'] ?>" alt="shahid">
								<p class="lead text-justify mt-3"><?php echo $shahid['shahid_detl'] ?></p>
								<?php
								echo '<div class="User_Name">';
								if(isset($_SESSION['user'])){
								echo '<span>' . $shahid['FullName'].  ' </span>';
								}
								echo 'التاريخ : <i class="fas fa-calendar-alt"></i> <span>' . $shahid['shahid_Date'].  '   </span>';
								echo 'الوقت : <i class="far fa-clock"></i> <span>' . $shahid['shahid_Time'].  ' </span>';
								echo '</div>';
								if(isset($_SESSION['user'])){
								echo '<div class="group-button group_slider mt-3">';
								echo '<a href="index.php?do=Edit3&shodaid=' . $shahid['shohda_ID'] .' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
								echo '<a href="index.php?do=Delete3&shodaid=' . $shahid['shohda_ID'] . '"><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
								echo '</div>';
							}
								?>

							</div>
						</div>
					</div>
				</div>

				<?php
		}
}
		include $tpl . 'footer.php';



	ob_end_flush(); // Release The Output

?>
