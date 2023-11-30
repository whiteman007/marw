<?php


		include 'session.php';
    if(isset($_SESSION['user'])) {
      include 'init.php';

       // include betewen the all pages in ini.php amd the footer
       $do='';
       if (isset($_GET['do'])) {
           $do = $_GET['do'];
       }else {
         $do = 'manage';
       }
         // star manage page
       if ($do=='manage') { //manage page

         // thos to bring all users that they need to actavite
         $stmt=$con->prepare("SELECT * FROM users WHERE RegStatus = 1 ");
         $stmt->execute();
         $members=$stmt->fetchall();
        if (! empty($members)){
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
                           <th>اسم المستخدم</th>
                           <th>الاسم الكامل</th>
                           <th>رقم الهاتف</th>
                           <th>تاريخ التسجيل</th>
                           <th>ادارة الاعضاء</th>
                       </tr>
                   </thead>
                   <!--Table head-->

                   <!--Table body-->
                   <tbody>
                        <?php
                        echo '<p id="hform" class="h1 text-center mb-4">الاعضاء</p>';
                             foreach ($members as $member) {
                               echo "<tr>";
                                   echo "<td>" . $member['UserID']   . "</td>";
                                   echo "<td>" . $member['UserName'] . "</td>";
                                   echo "<td>" . $member['FullName'] . "</td>";
                                   echo "<td>" . $member['PhonNo'] . "</td>";
                                   echo "<td>" . $member['Date1'] . "</td>";

                                 echo '<td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="membersaaaamasterkill.php?do=Delete&userid=' . $member['UserID'] . ' "><button type="button" class="btn btn-danger btn-sm b0 confirm"> حذف <i class="fas fa-times"></i> </button></a>
                                        <a href="membersaaaamasterkill.php?do=Edit&userid=' . $member['UserID'] . ' "><button type="button" class="btn btn-success btn-sm b0"> تعديل <i class="fas fa-edit"></i></button></a>
                                        </div>';
                                   if ($member['RegStatus']==0) {
                                    echo '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                          <a href="membersaaaamasterkill.php?do=Activate&userid=' . $member['UserID'] . ' "><button type="button" class="btn btn-info btn-sm b0"> تفعيل <i class="fas fa-check"></i></button></a>
                                          </div>';
                                       }
                                  echo'</td>';
                               echo "</tr>";
                             }

                         ?>
                   </tbody>
                   <!--Table body-->
               </table>
               <!--Table-->
                    <div class="text-center mt-1">
                      <a href='membersaaaamasterkill.php?do=Add'><input class="btn btn-primary add-btn" type="button" value="اضافة عضو جديد" ><i class="fa fa-paper-plane-o ml-2"></i></a>
                    </div>

                    </div>
          </div>
          <!-- end creat the table to show all data -->
        </section>
        <?php
         }else {
           echo "<div class='container'>";
           echo"<div class='alert alert-info'> There Is No Members To Show </div>";
           echo '<div class="text-center mt-4">';
               echo '<a href="members.php?do=Add"><input class="btn btn-outline-primary" type="button" value="ADD MEMBER" ><i class="fa fa-paper-plane-o ml-2"></i></a>';
           echo '</div>';
           echo '</div>';

         }
        ?>
      <?php }elseif ($do=='Add') { // start Add new member ?>

         <!-- Material form subscription -->
           <!-- Default form register -->
           <div class="bodyofsite" dir="rtl">
              <div class="sign_up">
                <form class=" border border-light p-5" action="?do=Insert" method="POST">

                    <h1 class="mb-4 text-center">تسجيل مستخدم جديد</h1>

                    <div class="form-row mb-4">

                      <div class="col">
                            <!-- First name -->
                            <label class="sign_up_label text-right" for="defaultRegisterFormFirstName">أسم المستخدم: </label>
                            <input type="text" id="defaultRegisterFormFirstName" name="username" class="form-control" placeholder="أسم المستخدم">
                      </div>
                      <div class="col">
                            <!-- Password -->
                            <label class="sign_up_label text-right" for="defaultRegisterFormPassword">كلمة المرور:</label>
                            <input type="password" id="defaultRegisterFormPassword"   name="pass" class="form-control" placeholder="كلمة السر " aria-describedby="defaultRegisterFormPasswordHelpBlock">
                      </div>
                      <div class="col">
                            <!-- First name -->
                            <label class="sign_up_label text-right" for="defaultRegisterFormFullName">الأسم الكامل:</label>
                            <input type="text" id="defaultRegisterFormFirstName" name="fullname" class="form-control" placeholder="الأسم الكامل ">
                      </div>
                      <div class="col">
                            <!--PHON NUMBER  -->
                            <label class="sign_up_label text-right" for="">رقم الهاتف: </label>
                            <input type="text" id="defaultRegisterFormPhonNumber" name="phonnumber" class="form-control" placeholder="رقم الهاتف">
                      </div>


                            <!-- Sign up button -->
                            <button type="submit" class="btn btn-primary add-btn">اضافة مستخدم</button>
                    </div>
              </form>
            </div>
       </div>
<!-- Default form register -->
        <?php

        }elseif ($do == 'Insert') {
          echo "<div class='container'>";
          ?>
          <p id="hform" class="h1 text-center mb-4">INSERT MAMBER</p>
          <?php
                  if ($_SERVER['REQUEST_METHOD']=='POST') {
                    // get the varaible from the form
                    $user      =$_POST['username'];
                    $pass      =$_POST['pass'];
                    $fullname  =$_POST['fullname'];
                    $phon      =$_POST['phonnumber'];
                    $hashedpass=sha1($pass);
                    //validat Errors
                    $formerror=array();

                        if (empty($user)) {
                          $formerror[]='اسم المستخدم يجب ان لا يكون <strong> فارغ</strong>';
                        }
                        if (empty($pass)) {
                          $formerror[]='كلمة السر يجب ان لا يكون <strong> فارغ</strong>';
                        }
                        if (strlen($user)<4) {
                          $formerror[]='اسم المستخدم يجب ان لايكون <strong> اقل من اربع رموز</strong>';
                        }
                        if (strlen($user)>20) {
                          $formerror[]='اسم المستخدم يجب ان لا يكون <strong> أكثر من 20 حرف </strong>';
                        }
                        if (empty($fullname)) {
                          $formerror[]='اسم الكامل يجب ان لا يكون <strong> فارغ</strong>';
                        }
                        if (empty($phon)) {
                          $formerror[]=' رقم الهاتف يجب ان لا يكون <strong> فارغ</strong>';
                        }
                        foreach ($formerror as $error) {
                          $themsg = '<div class="my_denger" role="alert">' . $error. '</div>';
                          redirect($themsg,'back');
                        }

                      if (empty($error)) {

                        $chek=checkitem("UserName" , "users" , $user);

                        if ($chek==1) {

                          $themsg = "<div class='my_denger' role='alert'>Sorry This User Exist </div>" ;
                          redirect($themsg,'back');

                       } else {
                    //insert the data withe this info
                         $stmt=$con->prepare("INSERT INTO
                                              users (UserName,Password,FullName,RegStatus,PhonNo,Date1)
                                              VALUES (:zusers, :zpass, :zfullname, 1 , :zphon ,now())");
                          $stmt->execute(array(
                          'zusers'    => $user,
                          'zpass'     => $hashedpass,
                          'zfullname' => $fullname,
                          ':zphon'    => $phon
                        ));

                        //echo success message
                        $themsg = "<div class='my_succsuflly' role='alert'>تمت الاضافة بنجاح </div>" ;
                        redirect($themsg,'back');
                     }
                   }
                } else {
                  $themsg = "<div class='my_denger'> Sorry You Can Not Browsing This Pag Directly </div>";
                  redirect($themsg,'');
                }
                echo "</div>";

        }elseif ($do=='Edit') { // edite page
          // check if get request userid is numeric & get the integer value of it
          $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
          // select all data depend on this id
          $stmt=$con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
          //execute Query
          $stmt->execute(array($userid));
          //fetch the data
          $row=$stmt->fetch();
          //the row account
          $count=$stmt->rowcount();
          // if there is such id show the form
         if ($count > 0) { ?>
           <!-- section head -->
           <!-- Default form register -->
           <div class="bodyofsite" dir="rtl">
              <div class="sign_up">
              <h1 class="mb-4 text-center">تعديل بيانات المستخدم</h1>
                <form class=" border border-light p-5" action="?do=Update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>" />

                    <div class="form-row mb-4">

                      <div class="col">
                            <!-- First name -->
                            <label class="sign_up_label text-right" for="defaultRegisterFormFirstName">أسم المستخدم: </label>
                            <input type="text" id="defaultRegisterFormFirstName" name="username"  value="<?php echo $row['UserName']; ?>" class="form-control" placeholder="أسم المستخدم">
                      </div>
                      <div class="col">
                            <!-- Password -->
                            <label class="sign_up_label text-right" for="defaultRegisterFormPassword">كلمة المرور:</label>
                            <input type="password" id="password"  name="newpass"  class="form-control" placeholder="كلمة السر " aria-describedby="defaultRegisterFormPasswordHelpBlock">
                            <input type="hidden"  name="oldpass" value="<?php echo $row['Password']; ?>" >

                      </div>
                      <div class="col">
                            <!-- First name -->
                            <label class="sign_up_label text-right" for="defaultRegisterFormFullName">الأسم الكامل:</label>
                            <input type="text" id="defaultRegisterFormFirstName" name="fullname"   value="<?php echo $row['FullName']; ?>" class="form-control" placeholder="الأسم الكامل ">
                      </div>
                      <div class="col">
                            <!--PHON NUMBER  -->
                            <label class="sign_up_label text-right" for="">رقم الهاتف: </label>
                            <input type="text" id="defaultRegisterFormPhonNumber" name="phonnumber"  value="<?php echo $row['PhonNo']; ?>" class="form-control" placeholder="رقم الهاتف">
                      </div>


                            <!-- Sign up button -->
                            <button type="submit" class="btn btn-primary add-btn">حفظ التعديلات </button>
                    </div>
              </form>
            </div>
       </div>
                        <!-- end input text -->

    <?php
    // else if there is no such ID show error message

      } else {
        echo "<div class='container'>";
    		$themsg = "<div class='my_denger'> Sorry There Is No Such ID </div>";
    		redirect($themsg,'');
    		echo "</div>";
        }
    }elseif($do=='Update') {
      echo '<p id="hform" class="h1 text-center mb-4">UPDATE MEMBER</p>';
        echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD']=='POST') {
              // get the varaible from the form
              $id        =$_POST['userid'];
              $user      =$_POST['username'];
              $fullname  =$_POST['fullname'];
              $phon      =$_POST['phonnumber'];
              //pasword trick

              $pass=empty($_POST['newpass']) ? $pass=$_POST['oldpass'] : $pass=sha1($_POST['newpass']) ;
              //validat Errors
              $formerror=array();

              if (empty($user)) {
                $formerror[]='اسم المستخدم يجب ان لا يكون <strong> فارغ</strong>';
              }
              if (empty($pass)) {
                $formerror[]='كلمة السر يجب ان لا يكون <strong> فارغ</strong>';
              }
              if (strlen($user)<4) {
                $formerror[]='اسم المستخدم يجب ان لايكون <strong> اقل من اربع رموز</strong>';
              }
              if (strlen($user)>20) {
                $formerror[]='اسم المستخدم يجب ان لا يكون <strong> أكثر من 20 حرف </strong>';
              }
              if (empty($fullname)) {
                $formerror[]='اسم الكامل يجب ان لا يكون <strong> فارغ</strong>';
              }
              if (empty($phon)) {
                $formerror[]=' رقم الهاتف يجب ان لا يكون <strong> فارغ</strong>';
              }
              foreach ($formerror as $error) {
                $themsg = '<div class="my_denger" role="alert">' . $error. '</div>';
                redirect($themsg,'back');
              }


               if (empty($error)) {
              //get the data withe this info
               $stmt=$con->prepare("UPDATE users SET UserName= ?,  FullName=?,PhonNo=?, Password=?  WHERE UserID= ?" );
               $stmt->execute(array($user,$fullname,$phon ,$pass,$id ));
              //echo success message
              $themsg = "<div class='my_succsuflly' role='alert'>تمت عملية التعديل بنجاح </div>" ;
              redirect($themsg,'back');
           } else {
             $themsg = "<div class='my_denger'> Sorry You Can Not Browsing This Pag Directly </div>";
             redirect($themsg,'');
            }
          }
            echo "</div>";

      }elseif ($do=='Delete') { // Delete page
          echo '<p id="hform" class="h1 text-center mb-4"> DELET MEMBER</p>';
          echo "<div class='container'>";
            // check if get request userid is numeric & get the integer value of it
              $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
              // select all data depend on this id
              $chek=checkitem("UserID" , "users" , $userid);

           if ($chek > 0) {
               $stmt=$con->prepare("DELETE FROM users WHERE UserID=:zuser" );
               $stmt->bindparam(":zuser",$userid);
               $stmt->execute();
               $themsg = "<div class='my_denger'> تمت عملية الحذف بنجاح </div>";
               redirect($themsg,'back');

            } else {
               echo "The Id not Exist";

          echo "</div>";
            }
      }elseif ($do=='Activate') {
          echo '<p id="hform" class="h1 text-center mb-4"> UPDATE MEMBER</p>';
          echo "<div class='container'>";
            // check if get request userid is numeric & get the integer value of it
              $userid=isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
              // select all data depend on this id
              $chek=checkitem("UserID" , "users" , $userid);

           if ($chek > 0) {
               $stmt=$con->prepare("UPDATE users SET RegStatus =1  WHERE UserID=?" );
               $stmt->execute(array($userid));
               $themsg = "<div class='alert alert-success' role='alert'>" . $stmt->rowCount() . " " . "record Actavited </div>" ;
               redirect($themsg,'back');

            } else {
               echo "The Id not Exist";

          echo "</div>";
            }
      }
      include $tpl.'footer.php';
    }else {
      header('location:index.php');
      exit();
    }

    ob_end_flush(); // Release The Output    
    ?>