                  <?php
                    include 'init.php';
                  ?>

              <div class="bodyofsite">
                       <!-- start news our websites -->
                     <section class="martyrs-dep" dir="rtl">
                        <div class="row">
                          <h1 class="text-center">صفحة اضافة ونشر أخر أخبار المديرية</h1>

                        <div class="add_item">
                            <!-- Default form group -->
                            <form>
                            <!-- Default input -->
                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="عنوان المنشور">
                            </div>
                            <!-- Default input -->
                            <div class="form-group">
                                <label>موجز عن المنشور</label>
                                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="يتم كتابة موجز عن المنشور يوضح للقاريء تفاصيل المنشور">
                            </div>
                            <div class="form-group">
                                <label>تفاصيل المنشور</label>
                                <textarea class="form-control" id="exampleFormControlTextarea3" rows="20"></textarea>
                            </div>
                            <div class="container mt-3 mb-4">
                                      <h1>تحميل الصور للمنشور </h1>
                                      <!-- start Image1 box -->
                                          <div class="file-field ">
                                            <div class="img-butt">
                                              <div class="mb-1">
                                                  <img src="images/Defult.jpg" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                              </div>
                                              <span class="btn btn-danger btn-file">
                                                  img1...<input type="file" name="img1"  id="aa" onchange="pressed()">
                                              </span>
                                            </div>
                                              <div class="fileLabel2">
                                                <p id="fileLabel">أختار صورة</p>
                                              </div>
                                          </div>
                                          <!-- end Image1 box -->
                                          <!-- start Image2 box -->
                                          <div class="file-field ">
                                            <div class="img-butt">
                                              <div class="mb-1">
                                                  <img src="images/Defult.jpg" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                              </div>
                                              <span class="btn btn-danger btn-file">
                                                  img2...<input type="file" name="img2"  id="aa2" onchange="pressed2()">
                                              </span>
                                            </div>
                                              <div class="fileLabel2">
                                                <p id="fileLabel2">أختار صورة</p>
                                              </div>
                                          </div>
                                          <!-- end Image2 box -->
                                          <!-- start Image3 box -->
                                          <div class="file-field ">
                                            <div class="img-butt">
                                              <div class="mb-1">
                                                  <img src="images/Defult.jpg" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                              </div>
                                              <span class="btn btn-danger btn-file">
                                                  img3...<input type="file" name="img3"  id="aa3" onchange="pressed3()">
                                              </span>
                                            </div>
                                              <div class="fileLabel2">
                                                <p id="fileLabel3">أختار صورة</p>
                                              </div>
                                          </div>
                                          <!-- end Image3 box -->
                                          <!-- start Image4 box -->
                                          <div class="file-field ">
                                            <div class="img-butt">
                                              <div class="mb-1">
                                                  <img src="images/Defult.jpg" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                              </div>
                                              <span class="btn btn-danger btn-file">
                                                  img4...<input type="file" name="img4"  id="aa4" onchange="pressed4()">
                                              </span>
                                            </div>
                                              <div class="fileLabel2">
                                                <p id="fileLabel4">أختار صورة</p>
                                              </div>
                                          </div>
                                          <!-- end Image4 box -->
                                          <!-- start Image5 box -->
                                          <div class="file-field ">
                                            <div class="img-butt">
                                              <div class="mb-1">
                                                  <img src="images/Defult.jpg" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                              </div>
                                              <span class="btn btn-danger btn-file">
                                                  img5... <input type="file" name="img5"  id="aa5" onchange="pressed5()">
                                              </span>
                                            </div>
                                              <div class="fileLabel2">
                                                <p id="fileLabel5">أختار صورة</p>
                                              </div>
                                          </div>
                                          <!-- end Image5 box -->
                                          <!-- start Image6 box -->
                                          <div class="file-field">
                                            <div class="img-butt">
                                              <div class="mb-1">
                                                  <img src="images/Defult.jpg" class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                              </div>
                                              <span class="btn btn-danger btn-file">
                                                  img6...<input type="file" name="img6"  id="aa6" onchange="pressed6()">
                                              </span>
                                            </div>
                                              <div class="fileLabel2">
                                                <p id="fileLabel6">أختار صورة</p>
                                              </div>
                                          </div>
                                          <!-- end Image6 box -->
                                    </div>
                                      <!-- end our Image Upload  -->
                                      <!-- start submait  -->
                                      <div class="text-center mt-4">
                                          <input class="btn btn-outline-primary" type="submit" value="نشر" >
                                      </div>
                                      <!-- end submait  -->
                            </form>
                            <!-- Default form group -->
                            </div>
                        </div>
                    </section>
                       <!-- end defin our websites -->
              </div>

                       <!-- start our footer section-->
                  <?php
                  include $tpl . 'footer.php';
                  ?>
