<?php
ob_start();
session_start();
require_once("db.php");
$db = new Db();
$output_result = ""; $coupons = array();
$sqlQuery = $db -> select(" CALL `coupons_get`() ");
if($sqlQuery === false)
{
  $output_result = "failed";
}else{
  foreach($sqlQuery as $row)
  {
    if($row['result'] == "failed" )
    {
      $output_result = "failed";
    }else{				
      $output_result = "success";
      if($row['dataset'] == 'coupons'){
        $coupons[] = $row;
      }
    }
  }		
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <title>Coupons dashboard - ITCAN</title>

    <style type="text/css">
        .shadow {
            box-shadow: 0 .375rem 1.5rem 0 rgba(140,152,164,.125)!important;
        }
    </style>
  </head>
  <body>
    
    <nav class="navbar navbar-white bg-white shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">ITCAN Admin</span>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12 text-end">
                <button class="btn btn-primary" id="btnUploadModal">Upload coupons</button>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sn</th>
                                <th scope="col">App Name</th>
                                <th scope="col">Clinet Name</th>
                                <th scope="col">Title EN</th>
                                <th scope="col">Title AR</th>
                                <th scope="col">Coupon Code</th>
                                <th scope="col">Status</th>
                                <th scope="col">Coupon Type</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Category</th>
                                <th scope="col">Tags</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row = 0;
                            foreach($coupons as $coupon){
                                echo "<tr>";
                                echo "<td>".++$row."</td>";
                                echo "<td>".$coupon['app_name']."</td>";
                                echo "<td>".$coupon['client_name']."</td>";
                                echo "<td>".$coupon['title_en']."</td>";
                                echo "<td>".$coupon['title_ar']."</td>";
                                echo "<td>".$coupon['code']."</td>";
                                echo "<td>".$coupon['status']."</td>";
                                echo "<td>".$coupon['tyoe']."</td>";
                                echo "<td>".$coupon['discount']."</td>";
                                echo "<td>".$coupon['category']."</td>";
                                echo "<td>".$coupon['tag']."</td>";
                                echo "</tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="csvUploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="" type="multipart/encoded">
                    <div class="row">
                        <div class="col-lg-12 mb-5">
                            <input type="file" name="csvfile" id="csvfile" />
                        </div>
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-primary mb-2" id="btnUpload">UPLOAD</button>
                            <div class="uploadStatus"></div>
                            <div class="totalRows text-primary"></div>
                            <div class="successRows text-warning"></div>
                            <div class="failedRows text-danger"></div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    
    <script type="text/javascript">
        $(document).ready(function(e){
            var csvUploadModal = new bootstrap.Modal(document.getElementById('csvUploadModal'), {
                keyboard: false
            });
            
            $("#btnUploadModal").click(function(e){
                csvUploadModal.show();
            });

            $('#csvUploadModal').on('hidden.bs.modal', function (e) {
                location.reload();
            });

            $("#btnUpload").click(function(e){
                var btn = $(this);
                var btnText = "UPLOAD";
                var btnLoadingText = "Please wait...";
                if( btn.html() == btnText ){
                    var file = document.getElementById('csvfile').files[0];
                    var hasError = false;
                    if(file !=undefined){
                        var file_name = file.name;
                        var file_extension = file_name.split('.').pop().toLowerCase();
                        if(file_extension != "csv"){
                            hasError = true;
                            $(".uploadStatus").html("<span class='text-danger'>CSV files only allowed</div>");
                        }
                    }else{
                        hasError = true;
                        $(".uploadStatus").html("<span class='text-danger'>Please select the file</div>");
                    }
                    if( !hasError ){
                        btn.html(btnLoadingText);
                        var form_data = new FormData();
                        form_data.append("file",file);
                        $.ajax({
                            url:'api/admin/coupon_upload.php',
                            method:'POST',
                            data:form_data,
                            contentType:false,
                            cache:false,
                            processData:false,
                            success:function(data){
                                btn.html(btnText);
                                $(".uploadStatus").html("<span class='text-success'>File processed successfully.</div>");
                                $(".totalRows").html("Total Coupons: "+data.totalRows);
                                $(".successRows").html("Successfully processed: "+data.successfulRows);
                                $(".failedRows").html("Failed: "+data.failedRows);
                            },
                            error: function (xhr, status) {  
                                btn.html(btnText);
                                $(".recoveryResultDiv").html("<div class='text-danger'>Something went wrong. Please try again.</div>");		
                            }
                        });
                    }
                }
                

                

            });
        });
    </script>


  </body>
</html>
