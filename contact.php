<?php

// check if user coming from a requst

if ($_SERVER ['REQUEST_METHOD'] == 'POST')
{

    // assign vailible

    $user1 = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $cell = filter_var($_POST['cellphon'], FILTER_SANITIZE_NUMBER_INT);
    $msg = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // creating array of errors

    $formErrors = array();
    if (strlen($user1) < 4) {
        $formErrors[] = 'Username Can Not Be Less Than  <strong>3</strong> Characters';
    }


    if (strlen($msg) < 10){
        $formErrors[] = 'Message Can Not Be Less Than <strong>10</strong> Characters';

    }
    // if no error send mail
    $headers = 'From: ' . $mail;
    $myemail = 'martyrs_wounded@moi.gov.iq';
    $subject = $cell;

    if(empty($formErrors)){
        mail($myemail, $subject, $msg, $headers);

    $user1 = '' ;
    $mail = '' ;
    $cell = '' ;
    $msg =  '' ;

    }

}
include 'init.php';
?>


<!-- start our contat page -->

<section class="contact-us mt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6" dir="rtl">
            <h1>موقعنا على الخريطة</h1>
                <embed 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3647.607843911339!2d44.43128157664071!3d33.33978347613488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x155783751080e05f%3A0x49c0e1a7e321956!2z2YXYr9mK2LHZitmHINin2YTYtNmH2K_Yp9ihINmI2KfZhNis2LHYrdmJ!5e1!3m2!1sar!2siq!4v1576141777859!5m2!1sar!2siq" type="" width="600" height="280" frameborder="0" style="border:0;" allowfullscreen="">
            </div>
                <div class="col-md-6" dir="rtl">
                <h1>أتــــصل بـــــنا</h1>
                <P class="lead">لمعلومات اكثر يرجى الاتصال بأحد الأرقام التالية :-</P>
                <h2>رقم التلفون :- </h2>
                <p class="lead">9647735382597+</p>
                <h2>فايبر :-</h2>
                <p class="lead">9647735382597+</p>
                <h2>البريد الالكتروني :- </h2>
                <p class="lead">martyrs_wounded@moi.gov.iq</p>

                </div>
        </div>
    </div>
</section>


<!-- star defin contact us -->


<section class="contactus_depatment" dir="rtl">
    <div class="container contact-us1">
        <div class="row">
            <div class="col-12">
                <i fa fa-headphones fa-5x></i>
                <h1 class="text-center mt-3 mb-3">أكـــــتب لـــــنا </h1>
                <form class="contact-form1" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                    <?php if (! empty($_formErrors)) { ?>

                    <?php } ?>
                    <div class="form-group">
                        <input
                        type="text"
                        name="username"
                        value="<?php if(isset($user1)) { echo $user1 ; } ?>"
                        class="form-control input-lg username"
                        placeholder="يرجى كتابة أسم المستخدم"
                        >
                        <i class="fas fa-user lara"></i>
                        <span class="user">*</span>
                    <div class="alert alert-danger custom-alert" dir="rtl">
                        أسم المستخدم يجب ان يكون اكثر من  <strong>3</strong> حروف
                    </div>
                    </div>
                        <div class="form-group">
                        <input
                        type="email"
                        name="email"
                        value="<?php if(isset($mail)) { echo $mail ; } ?>"
                        class="form-control input-lg email"
                        placeholder="يرجى كتابة البريد الألكتروني"
                        >
                        <i class="fas fa-envelope lara"></i>
                        <span class="mail">*</span>
                        <div class="alert alert-danger custom-alert" dir="rtl">
                            خانة البريد الالكتروني يجب ان لا يكون   <strong>فارغ</strong>
                        </div>
                        </div>
                        <div class="form-group">
                        <input
                        type="text"
                        name="cellphon"
                        value="<?php if(isset($cell)) { echo $cell; } ?>"
                        class="form-control input-lg"
                        placeholder="رقم الهاتف"
                        >
                        <i class="fas fa-phone lara"></i>
                        </div>
                        <div class="form-group">
                        <textarea
                        class="form-control input-lg message"
                        name="message"
                        value="<?php if(isset($msg)) { echo $msg; } ?>"
                        placeholder="الـــــرســــالة!"></textarea>
                        <span class="msg">*</span>

                        <div class="alert alert-danger custom-alert" dir="rtl">
                            يجب أن لا يقل عن <strong>10</strong> رموز
                        </div>
                        <div>
                        <input
                        type="submit"
                        value="أرسال الرسالة"
                        class="btn btn-primary btn-lg btn-block"/>
                        <i class="fas fa-paper-plane lara"></i>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- end defin contact us -->



    <!-- start our footer section-->
    <?php
    include $tpl . 'footer.php';
    ?>
