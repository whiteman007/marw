<?php


include 'session.php';
include 'init.php';
ini_set('display_errors', 1);
error_reporting(~0);

$strKeyword = null;

if(isset($_POST["txtKeyword"])) 
{
  $strKeyword = $_POST["txtKeyword"];
}
if(isset($_GET["txtKeyword"]))
{
  $strKeyword = $_GET["txtKeyword"];
}
$sql = "SELECT COUNT(*) 
        FROM onlinform 
        WHERE 
        martyr_name
         LIKE 
         '%".$strKeyword."%'
         OR
         benefit_name
        LIKE
        '%".$strKeyword."%'
        OR
        wife_name
        LIKE
        '%".$strKeyword."%'";
$stmt = $con->prepare($sql);
$stmt->execute();
$num_rows = $stmt->fetchColumn();

$per_page = 5;   // Per Page
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
        onlinform
        WHERE
        martyr_name
        LIKE
        '%".$strKeyword."%'
        OR
        benefit_name
        LIKE
        '%".$strKeyword."%'
        OR
        wife_name
        LIKE
        '%".$strKeyword."%'
        ORDER BY
        `onlinform`.`Martyr_ID`
        DESC");
        $limit=" limit " .$row_start  . "," . $row_end;
        $query = $sql.$limit;
        $pdo_statement = $con->prepare($query);
        $pdo_statement->execute();
        $members = $pdo_statement->fetchall();
        $count=$pdo_statement->rowcount();
  if ($count>0){
        ?>
		   <!-- section head -->
		   <section class="my-5">
			<!-- start creat the table to show all data -->
			 <div class="container">
				   <!--Table-->
				<div class="datatable">
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
							echo '<p id="hform" class="h1 text-center mb-4">الاعضاء</p>';
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

		include $tpl . 'footer.php';


    ob_end_flush(); // Release The Output

 ?>
