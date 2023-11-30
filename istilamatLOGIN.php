<?php

include 'session.php';
if(isset($_SESSION['istilamatuser']))
{
  header('location:istilamatShow.php');
}

include 'init.php';
// form login start
// Check if the User Comming from http request

    if ($_SERVER ['REQUEST_METHOD'] == 'POST'){
        $user= $_POST['istilamatusername'];
        $pass= $_POST['istilamatpassword'];
        $hashedpass=sha1($pass);

        //check if the user name exict

        $stmt=$con->prepare("SELECT
                                UserID,UserName,Password
                             FROM
                                users
                             WHERE
                                UserName=?
                             AND
                                Password=?
                             LIMIT 1");
        $stmt->execute(array($user,$hashedpass));
        $get = $stmt->fetch();
        $count=$stmt->rowcount();
        // IF count > 0 that mean the data base containt recorde obout the user name
        if ($count>0) {
          $_SESSION['istilamatuser']=$user; // Register Session Name
          $_SESSION['istilamatuid'] = $get['UserID']; // Register User ID in Session
          header('location:istilamatShow.php');
		  exit(); 
        }
    }

  ?>
     <div class="bodyofsite mb-5 mt-5">
          <!-- Material form login -->
					<div class="container">
					<div class="rows">
						<div class="col-12">

		          <div class="card login-front">
		            <h5 class="card-header danger-color white-text text-center py-4">لوحة تسجيل دخول الاستعلامات</h5>

		            <!--Card content-->
		            <div class="card-body px-lg-5 pt-0">

		              <!-- Form -->
		              <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" class="text-center" style="color: #757575;">
		                <!-- Email -->
		                <div class="md-form">
		                <label for="materialLoginFormEmail" class="mb-2 mt-3">أسم المستخدم</label>
		                  <input type="text" id="materialLoginFormEmail" name="istilamatusername" class="form-control" required="required">
		                </div>

		                <!-- Password -->
		                <div class="md-form mt-2">
		                <label for="materialLoginFormPassword" class="mb-2 mt-3">كلمة السر</label>
		                  <input type="password" id="materialLoginFormPassword" name="istilamatpassword" class="form-control" required="required">
		                </div>

		                <!-- Sign in button -->
		                <button class="btn btn-danger btn-login mt-3 " type="submit">تسجيل دخول</button>

		                <!-- Social login -->
		              </form>
		              <!-- Form -->
		            </div>
		          </div>
		          <!-- Material form login -->
						</div>
					</div>
	    </div>
		</div>
<?php

include $tpl.'footer.php';
ob_end_flush(); // Release The Output
?>
