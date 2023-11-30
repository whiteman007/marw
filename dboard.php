<?php
    include 'session.php';
    if(isset($_SESSION['user'])) {
    include 'init.php';
      /* start our dashboard page */
    ?>
    <section class="my-5" dir="rtl">
        <div class="container homestat text-center">
          <h1>لوحة تحكم المسوؤلين</h1>
          <div class="row">
            <div class="col-md-6 col-lg-4">
              <a href="members.php">
                <div class="stat st-users mt-2">
                  <div>
                  <img src="images/dboard/user.png" alt="">
                  </div>
                  <div class="info">
                    <p class="text-center">أدارة المستخدمين</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="ganeralform.php">
              <div class="stat st-ganeralform mt-2">
                  <div>
                    <img src="images/dboard/ganiralform.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">الاستمارة الشاملة</p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="Silaf_Form.php">
              <div class="stat st-womdedform mt-2">
                  <div>
                    <img src="images/dboard/form.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">بيانات استمارة السلف </p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="aqidForm.php">
              <div class="stat st-womdedform mt-2">
                  <div>
                    <img src="images/dboard/ganiralform.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">بيانات استمارة المتعاقدين </p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
              <a href="muqabalat.php">
              <div class="stat st-omra mt-2">
              <div>
                    <img src="images/dboard/ganiralform.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">بيانات استمارة المقابلات</p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="index.php?do=Add2">
              <div class="stat st-slider mt-2">
                  <div>
                    <img src="images/dboard/slider.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">أضافة فعالية </p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="index.php?do=Add">
              <div class="stat st-news mt-2">
              <div>
                    <img src="images/dboard/news.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">أضــــــافة خــــبـــر</p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="index.php?do=Add3">
              <div class="stat st-addmartyr mt-2">
              <div>
                    <img src="images/dboard/addmartyr1.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">اضافة شهيد</p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="helperform.php">
              <div class="stat st-helper mt-2">
              <div>
                    <img src="images/dboard/helper.jpg" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">بيانات المعين</p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
              <a href="haj.php">
              <div class="stat st-omra mt-2">
              <div>
                    <img src="images/dboard/omra.jpg" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">بيانات مسجلي العمرة</p>
                  </div>
              </div>
              </a>
            </div>
            <div class="col-md-6 col-lg-4">
            <a href="logout.php">
              <div class="stat st-signout2 mt-2">
              <div>
                    <img src="images/dboard/shutdown.png" alt="">
                  </div>
                  <div class="info">
                      <p class="text-center">تسجيل خروج</p>
                  </div>
              </div>
              </a>
            </div>


          </div>
        </div>
        </section>
        <?php

        /* end our dashboard page */

        include $tpl.'footer.php';

      }else {
        echo "Sorry you not allow to enter this page ";
        header('location:index.php');
        exit();
      }
      ob_end_flush(); // Release The Output

 ?>

