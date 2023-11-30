<?php

	/*
	================================================
	== istilamat Page
	================================================
	*/
	ini_set('display_errors','on');
	error_reporting(E_ALL);
	include 'session.php';
	include 'dbcontact.php';
	// routs
	$func='includes/functions/';
	include $func . 'functions.php';

	$pagetitle = 'ISTILAMAT';

	if (isset($_SESSION['istilamatuser'])) {
	

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {
			?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>الاستعلامات الخارجية</title>
				<link rel="icon" type="image/png" href="images/logo1.png" />
				<!-- google font -->
				<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Indie+Flower" integrity="sha384-rC+v+bdn1IetDBW04I6cRL22KVDTmCIowV/6uNpKB9pPuJJzo4DGE1NvKtVtFHZK" crossorigin="anonymous">
				<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mada" integrity="sha384-W28imtiUlB4RsUwppNlJvPoYXNuEOQKjnjGag7dWwu6vqmymu8JhFsyT8JI+W68l" crossorigin="anonymous">
				<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Changa" integrity="sha384-sL46D/hRf+zxvmwgAs8gWgXC6xl9v98XONOw/Vw/pCie6f3EuFhZodSG2+4Ciwzd" crossorigin="anonymous">
				<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js" integrity="sha384-RIQuldGV8mnjGdob13cay/K1AJa+LR7VKHqSXrrB5DPGryn4pMUXRLh92Ev8KlGF" crossorigin="anonymous"></script>
				<!-- font MDB labrary   -->
				<link href="css/mdb.min.css" rel="stylesheet">
				<!-- bootstrap labrary -->
				<link href="css/bootstrap.min.css" rel="stylesheet">
				<!-- font awsom labrary   -->
				<link href="css/all.min.css" rel="stylesheet">
				<!-- our CSS file  -->
				<link href="css/style.css" rel="stylesheet">
			</head>
				<script>
					function loadXMLDoc() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							document.getElementById("istilamatTable").innerHTML =
							this.responseText;
						}
						};
						xhttp.open("GET", "istilamatShow.php?do=servertable", true);
						xhttp.send();
					}
					setInterval(function(){
						loadXMLDoc();
					},2000);
					window.onload = loadXMLDoc;
				</script>
				<body class="hold-transition sidebar-mini" style="background-color: white; padding:0px; margin:0px;">
				<div class="container-flude" dir="rtl">
					<div class="row">
						<div class="col-md-12" style="background-color: #3498db !important">
							<div class="row">
								<div class="col-3">
									<img src="images/MOI.png" alt="LOGO" 
									style="
										margin: 0 auto;
										display: block;
										height: 140px;
										width: auto;
										" >
								</div>
								<div class="col-6">
									<h1 class="text-center mt-4 mb-4" style="color: #fff" >
										وزارة الداخلية <br>
										مديرية شؤون الشهداء والجرحى
									</h1>        
								</div>
								<div class="col-3">
									<img src="images/logo46.png" alt="LOGO2" 
									style="
										margin: 0 auto;
										display: block;
										height: 140px;
										width: auto;
										" >
								</div>
							</div>
						</div> 
						<div class="col-md-12 mt-2  px-4">
							<h3 style="color: #dc3545"><i class="far fa-newspaper"></i></i> الية تقديم السلف لذوي الشهداء وجرحى متقاعدي وزارة الداخلية</h3>
							<div class="marquee ver" style="background-color:antiquewhite; border: 2px solid black; border-radius:10px;" data-direction='right' dir="ltr">
							<ul id="marqueesilaf">
								<i style="color:green;">الية تقديم السلف لذوي الشهداء :</i>
								<i>&ensp;&ensp;1- ملئ استمارة الطلب من استعلامات مديريتنا&ensp;&ensp;</i>
								<i>|$|&ensp;&ensp;2- ربط صورة من الامر الاداري بالاستشهاد &ensp;&ensp;</i>
								<i>|$|&ensp;&ensp;3- ربط صورة من هوية التقاعد لذوي الشهيد&ensp;&ensp;</i>
								<i>|$|&ensp;&ensp;4- ربط صورة من القسام الشرعي&ensp;&ensp;</i>
								<i style="color:green;">&ensp;&ensp; الية تقديم السلف لجرحى المتقاعدين :</i>
								<i>&ensp;&ensp; 1- ملئ استمارة الطلب من استعلامات مديريتنا &ensp;&ensp;</i>
								<i>|$|&ensp;&ensp; 2-ربط صورة من الامر الاداري بالتقاعد &ensp;&ensp;</i>
								<i>|$|&ensp;&ensp; 3- ربط صورة من نسبة العجز&ensp;&ensp;</i>
								<i>|$|&ensp;&ensp; 4-ربط نسخة من المستمسكات الثبوتية&ensp;&ensp;</i>
								<i>|$|&ensp;&ensp; 5- ربط صورة من هوية التقاعد للجريح&ensp;&ensp;</i>
								<i style="color:green;">&ensp;&ensp; ملاحــظـــة :&ensp;</i>
								<i>في حال استلام سلفة من الصندوق سابقا فيجب ان يمضي سنتان على اخر سلفة تم استلامها&ensp;&ensp;</i>
								<i style="color:green;">&ensp;&ensp; مــع تــحيات مــديرية شـــؤون الـــشــهداء والـــجــرحى&ensp;&ensp; &ensp;&ensp;</i>
							</ul>
							</div>
						</div>
						<div class="col-md-6 mt-2 px-4" id="#tbody">
							<div id="istilamatTable"></div>
						</div>
						<div class="col-md-6 mt-2 px-4">
							<div class="container-flude">
							<div class="row">
								<div class="col-md-12 mt-2">
									<h3 style="color: #dc3545"><i class="fas fa-spinner"></i> فديو مقابلات استاذ زامل الساعدي</h3>
									 <div class="ifram_vedio" style="height:400px">
										<iframe width="100%" height="100%" src="https://www.youtube.com/embed/watch?v=uCBv4NA4NZE&list=UUGS73FPxyE-honKaCrDj5rw&index=1&t=75s&ab_channel=zamilalsady" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
									 </div>
								</div>
								<div class="col-md-12 mt-2">
									<h3 style="color: #dc3545"><i class="far fa-newspaper"></i></i> الية تحديث بيانات الشهيد في مؤسسة الشهداء</h3>
									<div class="myWrapper"  id="istilamat_flip">
										<ul>
											<li id="istilamat_flip2">
												<span style="color:green;">المستمسكات المطلوبة بالنسبة للشهيد :</span></br>
												1- الامر الاداري بالاستشهاد&ensp;
												2- حجة الوصايا&ensp;
												3- القسام الشرعي&ensp;
												4- هوية ثبوتية للشهيد&ensp;
											</li>
											<li id="istilamat_flip2">
												<span style="color:green;">المستمسكات المطلوبة بالنسبة للجريح :</span></br>
												1- الامر الاداري بالتقاعد&ensp;
												2- هوية تقاعدية للجريح&ensp;
												3- اوراق ثبوتية للجريح&ensp;
												4- نسبة العجز&ensp;
											</li>
										</ul>
									</div>
								</div>
								<div class="col-md-12 mt-2">
									<div class="myWrapper"  id="istilamat_flip_contact">
										<ul>
											<li id="istilamat_flip2">
												<span style="color:#dc3545;border-bottom:1px solid #dc3545">للتواصل مع مديريتنا :</span>
											</li>
											<li id="istilamat_flip2">
												<span style="color:green;">هاتف المديرية :</span>
												07735382597
											</li>
											<li id="istilamat_flip2">
												<span style="color:green;">البريد الالكتروني :</span>
												martyrs_wounded@moi.gov.iq
											</li>
										</ul>
									</div>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
				</body>
				    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
					<script src="js/jquery-3.5.1.min.js"></script>
					<!-- Our mdb js -->
					<script src="js/mdb.min.js"></script>
					<!-- Our bootstrap js -->
					<script src="js/bootstrap.min.js"></script>
					<!-- Our fontwosom js -->
					<script src="js/all.min.js"></script>
					<!-- Our popper js -->
					<script src="js/popper.min.js"></script>
					<!--  -->
					<script src="js/moment.min.js"></script>
					<!--  -->
					<script src="js/marquee.js"></script>
					<!-- datetime picker -->
					<script src="js/easyTicker.js"></script>
					<!-- Include all compiled plugins (below), or include individual files as needed -->
					<script src="js/custom1.js"></script>
					<!-- our Font awsom js -->
			</html>
		<?php


		} elseif ($do == 'servertable') {
			
			include 'dbcontact.php';
		?>
		
		<h3 style="color: #dc3545"><i class="fas fa-spinner"></i> لوحة الاجراء الخاصة بالطلبات</h3>
		<table class="table table-striped table-bordered" id="istilamat_data" style="border: 2px solid #ececec ;" >
			<thead>
			<tr style="background-color: #3498db; color:#fff;font-size: 18px;">
				<th scope="col">التسلسل</th>
				<th scope="col">الاسم الكامل</th>
				<th scope="col">القسم المعني</th>
				<th scope="col">حالة الاجراء</th>
			</tr>
			</thead>
			<tbody id="tbody">
				<?php
					// thos to bring all users that they need to actavite
					$stmt=$con->prepare("SELECT * FROM istilamat ORDER BY istilamat_date_update DESC LIMIT 13 ");
					$stmt->execute(array());
					$istilamatdatas=$stmt->fetchall();
					if (! empty($istilamatdatas)){
					foreach ($istilamatdatas as $istilamatdata) {
					echo "<tr>";
							echo "<td>" . $istilamatdata['istilamat_ID']   . "</td>";
							echo "<td>" . $istilamatdata['istilamat_namerequster'] . "</td>";
							if ($istilamatdata['istilamat_department']== 1){echo "<td>" ."مكتب المدير" . "</td>";}
							if ($istilamatdata['istilamat_department']== 2){echo "<td>" ."قسم الشهداء" . "</td>";}
							if ($istilamatdata['istilamat_department']== 3){echo "<td>" ."قسم الجرحى" . "</td>";}
							if ($istilamatdata['istilamat_department']== 4){echo "<td>" ." قسم الاراضي والاسكان" . "</td>";}
							if ($istilamatdata['istilamat_department']== 5){echo "<td>" ."قسم القانونية" . "</td>";}
							if ($istilamatdata['istilamat_department']== 6){echo "<td>" ."قسم القانونية" . "</td>";}
							if ($istilamatdata['istilamat_department']== 7){echo "<td>" ."قسم تكنلوجيا المعلومات" . "</td>";}
							if ($istilamatdata['istilamat_department']== 8){echo "<td>" ."شعبة المجالس التحقيقية" . "</td>";}
							if ($istilamatdata['istilamat_department']== 9){echo "<td>" ."شعبة الهويات" . "</td>";}
							if ($istilamatdata['istilamat_department']== 10){echo "<td>" ."شعبة المتوفين والمفقودين" . "</td>";}
							if ($istilamatdata['istilamat_department']== 11){echo "<td>" ."شعبة العدالة الانتقالية" . "</td>";}
							if ($istilamatdata['istilamat_department']== 12){echo "<td>" ." قسم الشهداء + قسم ت م" . "</td>";}
							if ($istilamatdata['istilamat_department']== 13){echo "<td>" ." قسم الجرح + قسم ت م" . "</td>";}
							if ($istilamatdata['istilamat_department']== 14){echo "<td>" ."قسم الجرحى + شعبة الهويات" . "</td>";}
							if ($istilamatdata['istilamat_department']== 15){echo "<td>" ."مكتب المدير + الشهداء + ت م" . "</td>";}
							if ($istilamatdata['istilamat_department']== 16){echo "<td>" ."مكتب المدير + الجرحى + ت م" . "</td>";}
							echo "<td>" . $istilamatdata['istilamat_statusijra'] . "</td>";
					echo "</tr>";
					}
				}
				?>
			</tbody>
		</table>
		<?php
		
		} elseif ($do == 'Insert') {


		} elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {


		} elseif ($do == 'Delete') {


		} elseif ($do == 'Activate') {


		
		}
		} else {

			header('Location: index.php');

			exit();
		}

	ob_end_flush(); // Release The Output

?>
