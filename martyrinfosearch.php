<?php


include 'session.php';
include 'init.php';
ini_set('display_errors', 1);
error_reporting(~0);

$strKeyword = null;

if(isset($_POST["txtKeyword25"]))
{
  $strKeyword = $_POST["txtKeyword25"];
}
if(isset($_GET["txtKeyword25"]))
{
  $strKeyword = $_GET["txtKeyword25"];
}
$sql = "SELECT COUNT(*) 
        FROM 
        martyrinfo 
        WHERE 
        martyr_name
         LIKE 
         '%".$strKeyword."%'
         OR
         martyr_mother
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
        martyrinfo
        WHERE
        martyr_name
         LIKE 
         '%".$strKeyword."%'
        ORDER BY
        `martyrinfo`.`martyrInfo_ID`
        ASC");
        $limit=" limit " .$row_start  . "," . $row_end;
        $query = $sql.$limit;
        $pdo_statement = $con->prepare($query);
        $pdo_statement->execute();
        $martyrinfos = $pdo_statement->fetchall();
        $count=$pdo_statement->rowcount();
  if ($count>0){
        ?>
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
                                    <tr class="text-white">
                                        <th>التسلسل</th>
                                        <th>الرتبة</th>
                                        <th>الاسم الرباعي واللقب</th>
                                        <th> أسم الام الثلاثي</th>
                                        <th>رقم الهاتف </th>
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
