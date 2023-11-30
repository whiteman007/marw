                  <?php
                    include 'init.php';
                  ?>

              <div class="bodyofsite">
                       <!-- start news our websites -->
                     <section class="martyrs-dep" dir="rtl">
                        <div class="row">
                          <h1 class="text-center">أخبار مديرية شؤون الشهداء والجرحى  </h1>

                        </div>
                    </section>
                       <!-- end defin our websites -->
              </div>

                       <!-- start our footer section-->
                  <?php
                  include $tpl . 'footer.php';
                  ?>


$themsg = "<div class='my_succsuflly' role='alert'>تمت الاضافة بنجاح </div>" ;

if(isset($_SESSION['user'])){
								echo '<div class="group-button">';
								echo '<a href="index.php?do=Edit2&sliderid=' . $slider['ID_Slider'] . ' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
								echo '<a href="#"><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
								echo '</div>';
								} 


					         <!-- start slider section -->
									<!--Carousel Wrapper-->
                  <div id="carousel-example-1z" class="carousel slide carousel-fade mb-2 " data-ride="carousel">
								<!--Indicators-->
								<ol class="carousel-indicators">
										<?php if($item['img1']!=null){ ?>
										<li data-target="#carousel-example-1z" data-slide-to="0" class="active"></li>
										<?php } ?>
										<?php if($item['img2']!=null){ ?>
										<li data-target="#carousel-example-1z" data-slide-to="1"></li>
										<?php } ?>
										<?php if($item['img3']!=null){ ?>
										<li data-target="#carousel-example-1z" data-slide-to="2"></li>
										<?php } ?>
										<?php if($item['img4']!=null){ ?>
										<li data-target="#carousel-example-1z" data-slide-to="3"></li>
										<?php } ?>
										<?php if($item['img5']!=null){ ?>
										<li data-target="#carousel-example-1z" data-slide-to="4"></li>
										<?php } ?>
										<?php if($item['img6']!=null){ ?>
										<li data-target="#carousel-example-1z" data-slide-to="5"></li>
										<?php } ?>
								</ol>
								<!--/.Indicators-->
								<!--Slides-->
								<div class="carousel-inner image-iteminfo" role="listbox">
										<!--First slide-->
											<div class="carousel-item active">
													<img class="d-block w-100 img-item-infoitem" src="layout\images\logo1.png" alt="First slide">
											</div>
											<?php if(!empty($item['img1'])){ ?>
											<div class="carousel-item ">
													<img class="d-block w-100 img-item-infoitem" src="<?php echo 'uploads/item_img/'.$item['img1'] ?>" alt="First slide">
											</div>
											<?php }?>
											<!--/First slide-->
											<!--Second slide-->
											<?php if(!empty($item['img2'])){ ?>
											<div class="carousel-item" >
													<img class="d-block w-100 img-item-infoitem" src="<?php echo 'uploads/item_img/'.$item['img2'] ?>" alt="Second slide">
											</div>
											<?php } ?>
											<!--/Second slide-->
											<!--Third slide-->
											<?php if(!empty($item['img3'])){ ?>
											<div class="carousel-item">
													<img class="d-block w-100 img-item-infoitem" src="<?php echo 'uploads/item_img/'.$item['img3'] ?>" alt="Third slide">
											</div>
											<?php } ?>
											<!--/Third slide-->
											<!--Fourth slide-->
											<?php if(!empty($item['img4'])){ ?>
											<div class="carousel-item">
													<img class="d-block w-100 img-item-infoitem" src="<?php echo 'uploads/item_img/'.$item['img4'] ?>" alt="Fourth slide">
											</div>
											<?php } ?>
											<!--/Fourth slide-->
											<!--Fifth slide-->
											<?php if(!empty($item['img5'])){ ?>
											<div class="carousel-item">
													<img class="d-block w-100 img-item-infoitem" src="<?php echo 'uploads/item_img/'.$item['img5'] ?>" alt="Fifth slide">
											</div>
											<?php } ?>
											<!--/Fifth slide-->
											<!--Sexth slide-->
											<?php if(!empty($item['img6'])){ ?>
											<div class="carousel-item">
													<img class="d-block w-100 img-item-infoitem" src="<?php echo 'uploads/item_img/'.$item['img6'] ?>" alt="Sexth slide">
											</div>
										<!--/Sexth slide-->
								</div>
								<!--/.Slides-->
								<?php if (!empty($item['img1']||$item['img2']||$item['img3']||$item['img4']||$item['img5']||$item['img6'])){?>
								<!--Controls-->
								<a class="carousel-control-prev" href="#carousel-example-1z" role="button" data-slide="prev">
										<span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<span class="sr-only">Previous</span>
								</a>
								<a class="carousel-control-next" href="#carousel-example-1z" role="button" data-slide="next">
										<span class="carousel-control-next-icon" aria-hidden="true"></span>
										<span class="sr-only">Next</span>
								</a>
								<!--/.Controls-->
							<?php } ?>
							</div>
							<!--/.Carousel Wrapper-->
					</div>
						<!-- end slider section -->
						<?php
							if(isset($_SESSION['user'])){
								echo '<div class="group-button">';
								echo '<a href="index.php?do=Edit2&sliderid=' . $slider['ID_Slider'] . ' "><button type="button" class="btn btn-success btn-sm b1"><i class="far fa-edit"></i> تعديل </button></a>';
								echo '<a href="index.php?do=Delete2&sliderid=' . $slider['ID_Slider'] . '"><button type="button" class="btn btn-danger btn-sm  b1 confirm"><i class="fas fa-times"></i> حذف</button></a>';
								echo '</div>';
								} 
								$desc		=str_ireplace(" ",,$desc);
								$desc		=str_ireplace("\n","nl2br",$desc);
								$desc		=str_ireplace(" ",'&nbsp;',$desc);
								$desc		=str_ireplace("\n","<br>",$desc);
						#ffc107
							?>
                  <input type="hidden" name="userid" value="<?php echo $userid ?>" />
				  value="<?php echo $row['UserName']; ?>"



				  
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


.container .md-form .form-control  {
      margin-left: 25px !important;
    }
.md-form #fig {
  color: #871a90  !important;
  display: block;
  right: -25px;
}
#hform{
   color: #871a90  !important;

 }


.btn-outline-info {
    border: 2px solid #871a90 !important;
    color: #871a90 !important;
    font-size: 16px;
    font-weight: bold;
  }
  .btn-outline-info.active, .btn-outline-info:active,
  .btn-outline-info:active:focus, .btn-outline-info:focus,
  .btn-outline-info:hover {
    border-color: #ffc107!important;
    color: #ffc107!important;
  }

/* start start astresik to explain any feild required and eye to show th pass*/
.form-control{
  position: relative;
}
.astresik{
  position: absolute;
  right:507px;
  top: 19px;
  font-size: 18px;
  color: #dc3545;
}
.astresik1{
  position: absolute;
  left:298px;
  top: 19px;
  font-size: 18px;
  color: #dc3545;
}
.astresik2{
  position: absolute;
  left:503px;
  top: 19px;
  font-size: 18px;
  color: #dc3545;
}
.astresik3{
  position: absolute;
  left:-145px;
  top: 19px;
  font-size: 18px;
  color: #dc3545;
}

#show_password{
  position: absolute;
  top: -20px;
  left:-149px;
  font-size: 16px;
  color: #dc3545;
/* start table*/
}
.datatable{
  margin-top: 10px;
}
.mdb-color.darken-3{
  background-color: #546de5 !important;
}

/* start table*/
.datatable{
  margin-bottom: 10px;
}
.main-table{
box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
margin-bottom: 5px;
}
.main-table td{
  background-color: #fff;
  padding: 3px 10px;
}
.btn-group-sm .b0{
  font-size: 10px;
  padding: 8px 20px;
  border-radius: 5px;
  margin: 3px;
}
.main-table  .b0{
  font-size: 10px;
  padding: 8px 10px;
  border-radius: 5px;
}
table.table td, table.table th{
  padding-top: 15px;
  padding-bottom: 10px;
}
.add-item .add-mamber{
  margin-top: 50px;
}

.add-item .add-mamber .input11{
  width: 500px;
  color: #000;
}
.table-responsive{
  display: inline-table;
}
