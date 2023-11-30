<?php
ob_start(); // Output Buffering Start

session_start();

include 'init.php';
ini_set('display_errors', 1);
error_reporting(~0);

if ($_SERVER['REQUEST_METHOD']=='POST'){
  $strKeyword = null;

  if(isset($_POST["searchItem"]))
  {
    $strKeyword   =filter_var($_POST["searchItem"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
  }
  if(isset($_GET["searchItem"]))
  {
    $strKeyword   =filter_var($_GET["searchItem"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $strKeyword 	= preg_replace('/[^\p{L}\p{N}\s]/u', '', $strKeyword);
  }
  $sql = "SELECT COUNT(*) 
          FROM items 
          WHERE 
               Subjuct
           LIKE 
           '%".$strKeyword."%'
           OR
              summry
          LIKE
          '%".$strKeyword."%'
          OR
              Description
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
          items.*,users.UserName,users.FullName
          FROM
          items	
          INNER JOIN
          users
          ON
          users.UserID = items.User_ID
          WHERE
              Subjuct
          LIKE
          '%".$strKeyword."%'
          OR
              summry
          LIKE
          '%".$strKeyword."%'
          OR
              Description
          LIKE
          '%".$strKeyword."%'
          ORDER BY
          `items`.`items_ID`
          DESC");
          $limit=" limit " .$row_start  . "," . $row_end;
          $query = $sql.$limit;
          $pdo_statement = $con->prepare($query);
          $pdo_statement->execute();
          $items = $pdo_statement->fetchall();
          $count=$pdo_statement->rowcount();
          if ($count>0){
          ?>
          <div class="container bodyofsite">
          <h1  class="text-center">أخر اخبار ونشاطات مديرية شؤون الشهداء والجرحى  </h1>
          <?php
      echo $strKeyword;

          ?>
          <h2 style="text-align: right;">هنالك <?php echo $count ?> من النتائج </h2>
          <?php foreach ($items as $item) { ?>
            <section class="container martyrs-dep" dir="rtl">
              <div class="row">
                <div class="col-md-12">
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
                          echo '<span>' . $item['Add_date'].  '   </span>';
                          echo '<span>' . $item['Time_item'].  ' </span>';
                          echo '</div>';
                            
                        ?>
                    </div>
                    </div>
                  </div>
                </div>
              </section>
              <hr class="line0">
              
               <!-- end defin our websites -->
               <?php  } ?>
                 </div>
  
          <?php
        }
       
          // echo our pagnation
          echo "<div class='container pagnation1 mb-3'>";
          echo '<span class="font-bold-pagnation" aria-hidden="true">&laquo;</span>';
          if($prev_page)
          {
            echo '<li class="btn-pagnation">'."<a href='$_SERVER[SCRIPT_NAME]?Page=$prev_page&searchItem=$strKeyword' class='page-link-pagnation'>Back</a></li>";
          }
  
          for($i=1; $i<=$num_pages; $i++){
            if($i != $page1)
            {
              echo '<li class="btn-pagnation">'."<a href='$_SERVER[PHP_SELF]?Page=$i&searchItem=$strKeyword' class='page-link-pagnation'>$i</a></li>";
            }
            else
            {
              echo"<b class='btn-pagnation active12'> $i </b>";
            }
  
          }
          if($page1!=$num_pages)
          {
            echo '<li class="btn-pagnation">'."<a href ='$_SERVER[PHP_SELF]?Page=$next_page&searchItem=$strKeyword' class='page-link-pagnation'>Next</a></li> ";
          }
          $conn = null;
          echo '<span class="font-bold-pagnation" aria-hidden="true">&raquo;</span>';
          echo '</div>';
          echo '<p class="total-record-search text-center mt=0">عدد السجلات = </span>' . $num_rows  . '<span class="total-record-search"> الصفحة =' .  $num_pages . '</p> ' ;
  
    }else{
      $themsg = "<div class='my_denger'> لا تستطيع الوصول لهذه الصفحة مباشرة </div>";
      redirect($themsg,'');
    }

		include $tpl . 'footer.php';


    ob_end_flush(); // Release The Output

 ?>
