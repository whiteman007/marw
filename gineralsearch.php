<?php


include 'session.php';
include 'init.php';
ini_set('display_errors', 1);
error_reporting(~0);
if(isset($_SESSION['user'])){
	if ($_SERVER['REQUEST_METHOD']=='POST'){ 
		$strKeyword = null;

if(isset($_POST["txtKeyword22"]))
{
	$strKeyword   =filter_var($_POST["txtKeyword22"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
}
if(isset($_GET["txtKeyword22"]))
{
	$strKeyword   =filter_var($_GET["txtKeyword22"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
}
$sql = "SELECT COUNT(*)
        FROM
        ganiralform
        WHERE
        Gineral_Name
         LIKE
         '%".$strKeyword."%'
         OR
         Status_Death
         LIKE
        '%".$strKeyword."%'
        OR
        Mother_name
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
        ganiralform
        WHERE
        Gineral_Name
         LIKE
         '%".$strKeyword."%'
         OR
         Status_Death
         LIKE
        '%".$strKeyword."%'
        OR
        Mother_name
         LIKE
        '%".$strKeyword."%'
        ORDER BY
        `ganiralform`.`Gineral_ID`
        ASC");
        $limit=" limit " .$row_start  . "," . $row_end;
        $query = $sql.$limit;
        $pdo_statement = $con->prepare($query);
        $pdo_statement->execute();
        $ginerals = $pdo_statement->fetchall();
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

		include $tpl . 'footer.php';


    ob_end_flush(); // Release The Output

 ?>
