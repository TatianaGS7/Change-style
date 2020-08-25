<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Change style (DB)</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <style>
   .cont{
      height: 400px;
   }
  </style>
</head>

<body>

<nav id="menu" class="navbar navbar-expand-sm bg-primary navbar-dark">
  <a class="navbar-brand" href="#">Logo</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">Link 1</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link 2</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link 3</a>
      </li>    
    </ul>
  </div>  
</nav>

  <div id="accordion">
    <div class="card">
      <div class="card-header">
        <a class="card-link" data-toggle="collapse" href="#Style_panel">
          Style panel 
          <span id="arr" class='float-right mt-1 text-primary txt'>&#9660;</span>
        </a>
      </div>
      <div id="Style_panel" class="collapse show" data-parent="#accordion">
        <div class="card-body">
          <form class="form-inline" id="change_style">
            <label for="sel1">Select theme:</label>
            <div class="form-check-inline">
              <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" name="optradio" id="radio1" value="s_1">
                <label class="custom-control-label" for="radio1">Primary</label>
              </div>
            </div>
            <div class="form-check-inline">
              <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" name="optradio" id="radio2" value="s_2">
                <label class="custom-control-label" for="radio2">Danger</label>
              </div>
            </div>            

            <div class="form-check-inline">
              <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" name="optradio" id="radio3" value="s_3">
                <label class="custom-control-label" for="radio3">Success</label>
              </div>
            </div>
            <div class="form-check-inline">
              <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" name="optradio" id="radio4" value="s_4">
                <label class="custom-control-label" for="radio4">Warning</label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm float-right">ОК</button>
            </form>
        </div>
      </div>
    </div>
  </div>

<div class="alert alert-info d-none" id="alert_1">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Failed to get data about the custom color scheme.</strong> The theme is selected by default.
</div>

<div class="alert alert-danger d-none" id="alert_2">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="txt_alert_2"></span>
</div>


<div class="container mt-5">
  <div class="row">
    <div class="col-sm-12">
        <div class="container p-3 my-3 border border-primary cont"></div>
    </div>
  </div>
</div>

<div class="container mt-5">
  <div class="row">
    <div class="col-sm-12">
        <div class="container p-3 my-3 border border-primary cont"></div>
    </div>
  </div>
</div>

<div id="footer" class="container-fluid text-center mb-0 bg-primary p-1 text-white">
  <p>Footer</p>
</div>

<script>
$(document).ready(function(){
  
  document.getElementById("radio1").checked = true;

  // Arrow
  $(".collapse").on('show.bs.collapse', function(){
    $("#arr").css("transform", "rotateX(180deg)");
  })

  $(".collapse").on('hide.bs.collapse', function(){
    $("#arr").css("transform", "rotateX(0deg)");
  })

    // Changing color theme
    // s_1
    function S1(){
      // Menu, footer
        $("#menu, #footer").addClass("bg-primary");
      // border
        $(".cont").addClass("border-primary");
      // Text
        $(".card-link, .txt").addClass("text-primary");
    }
    // s_2
    function S2(){
          $("#menu, #footer").addClass("bg-danger");
          $(".cont").addClass("border-danger");
          $(".card-link, .txt").addClass("text-danger");
    }


    // Setting the site's color palette
    $.getJSON('style.php', function(result) {
            switch (result) {
                case "s_1":
                    S1();
                    break;

                case "s_2":
                    S2();
                    break;
            }
    })
    .fail(function() {
        S1();
        $("#alert_1").removeClass("d-none");
    });

    // Change the site's color palette
    $('#change_style').submit(function(event) {
        event.preventDefault();
        var style = $('input[name="style"]:checked').val();
        var formData = new FormData();
        formData.append('schema', style);
    // Save a selection
        $.ajax({
            type: "POST",
            url: "save_style.php",
            data: formData,
            contentType: false, 
            processData: false, 
            cache: false, 
            success: function(res){
                console.log(res);
                var $res =  JSON.parse(res);

                if ($res.result == 1) {

                    switch ($res.choice) {
                        case "s_1":
                            // Menu, footer
                            $("#menu, #footer").removeClass("bg-danger").removeClass("bg-success").removeClass("bg-warning");
                            $("#menu, #footer").addClass("bg-primary");
                            // border
                            $(".cont").removeClass("border-danger").removeClass("border-success").removeClass("border-warning");
                            $(".cont").addClass("border-primary");
                            // Text
                            $(".card-link, .txt").removeClass("text-danger").removeClass("text-success").removeClass("text-warning");
                            $(".card-link, .txt").addClass("text-primary");
                            break;

                        case "s_2":
                            $("#menu, #footer").addClass("bg-danger");
                            $(".cont").removeClass("border-primary").removeClass("border-success").removeClass("border-warning");       
                            $(".cont").addClass("border-danger");
                            $(".card-link, .txt").removeClass("text-primary").removeClass("text-success").removeClass("text-warning");
                            $(".card-link, .txt").addClass("text-danger");
                            break;
                    }

                }
                else {
                    if ($res.error) {
                        $("#alert_2").removeClass("d-none");
                        $('#txt_alert_2').text($res.mes);
                    }
                }
            },
            error: function (request) {
                $("#alert_2").removeClass("d-none");
                $('#txt_alert_2').text('An error occurred when changing the color scheme');
            }
        });

    });
  

});
</script>

</body>
</html>