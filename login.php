<?php 
$msg = "";
$class="";
if(isset($_GET['err']) && $_GET['err']=='not-reg'){
  $msg = "The license key is not valid";
  $class = " w3-text-red w3-center";
}

elseif(isset($_GET['err']) && $_GET['err']=='registered'){
	$msg = "This license key has been used, kindly user another key.";
	$class = " w3-text-red w3-center";
}
elseif(isset($_GET['err']) && $_GET['err']=='wrong_uname'){
  $msg = "Wrong Username or Password";
  $class = "w3-text-red w3-center";
}
elseif(isset($_GET['success']) && $_GET['success']=='shop-added'){
  // echo '<script type="text/javascript">alert("Your Registeration is complete, Kindly Login")</script>';
  $msg = "Your shop has added, Kindly check your registered Email id and login";
  $class = "w3-text-red w3-center";
}
else if(isset($_GET['error_zone'])){
  $msg = "Wrong Password";
  $class = "w3-text-red w3-center";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

 <link rel="stylesheet" href="css/w3.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link rel="stylesheet" type="text/css" href="css/style.css">
 <style>
    body {font-family: Arial, Helvetica, sans-serif;}

    label{display: block;}

    /* Set a style for all buttons */
    button {
      background-color: #ff0000;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      opacity: 0.9;

    }

    button:hover {
      opacity: 0.8;
    }

    /* Extra styles for the cancel button */
    .cancelbtn {
      width: auto;
      padding: 10px 18px;
      background-color: #f44336;
    }

    /* Center the image and position the close button */
    .imgcontainer {
      text-align: center;
      margin: 24px 0 12px 0;
      position: relative;
    }

    img.avatar {
      width: 40%;
      border-radius: 50%;
    }

    .container {
      padding: 16px;
    }

    span.psw {
      float: right;
      padding-top: 16px;
    }

    /* The Modal (background) */
    .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
      padding-top: 60px;
    }

    /* Modal Content/Box */
    .modal-content {
      background-color: #fefefe;
      margin: 1% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
      border: 1px solid #888;
      width: 50%; /* Could be more or less, depending on screen size */
    }

    /* The Close Button (x) */
    .close {
      position: absolute;
      right: 25px;
      top: 0;
      color: #000;
      font-size: 35px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: red;
      cursor: pointer;
    }

    /* Add Zoom Animation */
    .animate {
      -webkit-animation: animatezoom 0.6s;
      animation: animatezoom 0.6s
    }

    @-webkit-keyframes animatezoom {
      from {-webkit-transform: scale(0)} 
      to {-webkit-transform: scale(1)}
    }

    @keyframes animatezoom {
      from {transform: scale(0)} 
      to {transform: scale(1)}
    }

    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
      span.psw {
       display: block;
       float: none;
     }
     .cancelbtn {
       width: 100%;
     }
   }
   
    #form_div{
      font-size: 12px;
    }
    #form_div .w3-input, #form_div .w3-select{
      height: 20px;
      font-size: 8px;
    }
    #vend{
      display: none;
    }
    .w3-small{
      font-size: 12px !important;;
    }
    .white-back{
      background-color: white;
    }
    ul{
      list-style-position: inside;
      list-style-type: "=>  ";
      font-weight: 100 !important;
      font-size: 12px;
      color: lightblue;
     font-style: italic;
     margin-top: 5px;
    }
    h3{
      /*text-decoration: underline;*/
      font-size:100%;
      /*text-indent: initial;*/
      margin-top: 10px;
      /*line-height: 25px;*/
      text-transform: none;

    }
    h3 span{
      border-bottom: 1px solid white;
      padding-bottom: 1px;
    }
    h3 + p{
      color: lightpink;
      font-style: italic;
      margin-top: 5px
    }
    #txt{
      display: none;
    }
    input:focus{
      outline: none !important;
    }

    .modal-content{
      width: 30%;
      height: 50vh;
    }
    #id03 .modal-content{
      height: 76vh;
    }

    

 </style>

</head>
<body>
  <?php
		//include 'includes/header.php'
  ?>
  <div class="w3-row body-content w3-white" style="width:100%; height: 100%; overflow: hidden">

    <div class="w3-col w3-card-4 white-back w3-center">
      <!-- <a href="https://loopintechies.com/" target="_blank">
      <img src="images/logo.png" height="70" />
    </a> -->
    <h2 style="margin: 0"><em>Liquor Management System</em></h2>
      <!-- <p><em>Internet Point of Sale(iPOS)</em></p> -->
      <!-- <marquee scrollamount="10" onmouseover="this.stop();" onmouseout="this.start();" ><span class="w3-text-red">For support contact on: 0120-4108475</span></marquee> -->
    </div>

<span id="txt"></span>
    
      <div class="w3-col l12" style="min-height: 95vh;" >
        <div class="w3-col l7 w3-hide-small w3-padding-large" style="min-height: 95vh; background-color: #005792;">
          <div class="w3-col l12">
          <div class="w3-col l3">&nbsp;</div>
          <div class="w3-center white-back w3-round-large w3-col l6 w3-margin-top">
            <img src="images/logoipos.png" />
          </div>
          <div class="w3-col l3">&nbsp;</div>
        </div>

          <div class="w3-col l12 w3-text-white">
            <h3><span>What is iPOS ?</span></h3>
            <ul>
              <li>iPOS stands for Internet Point of Sale.</li>
              <li>It is an online portal that manages your Purchase, Inventory, Sale and Reports.</li>
              <li>It is mainly designed for Liquor Shop Inventory Management.</li>
              <li>It takes care of all the Taxes, Purchase, Retail Profit, Whole Sale Price, Excise Duty, VAT etc.</li>
              <!-- <li>iPOS stands for Internet Point of Sale</li> -->

            </ul>


            <h3><span>What iPOS does ?</span></h3>
            <p>Using iPOS you can:</p>
            <ul>
              <li>Manage your purchases and stock (daily and monthly).</li>
              <li>Keep track of sale (daily and monthly).</li>
              <li>Keep track of Inventory(daily and monthly)</li>
              <li>Generate report of Purchase, Sale, Issue (Store receive), Sale Tax report.</li>
              <li>View and download all report in excel and PDF format.</li>

              <li>Sale items by scanning and manually</li>
              <li>Generate receipt from thermal printer/normal printer.</li>

            </ul>

          </div>

        </div>
        <div class="w3-col l5 w3-padding-large" style="background-image: linear-gradient(to bottom, #eee, white,white,white); min-height: 95vh;">

          <div class="w3-col l12 w3-padding w3-card-4" style='min-height: 88vh'>
            <div class="<?php echo $class; ?>" style="font-size: 11px; height: 20px" > <?php echo $msg; ?>  </div>
            <div class="w3-col l10">
              <h4 class='w3-center'>User Login</h4>
              <form class="w3-container" action="admin-login.php" method="post">
                <p>      
             <label class="w3-small w3-margin-top"><b>Username </b> <span class="w3-text-red">*</span></label>
             <input class="w3-input w3-border  w3-card" name="username" onkeyup="this.value=this.value.toLowerCase()" type="text" placeholder="Enter Username" autocomplete="off" maxlength="25" required></p>

             <p>      
               <label class="w3-margin-top w3-small"><b>Password </b><span class="w3-text-red">*</span></label>
               <input class="w3-input w3-border  w3-card" name="password" type="password" maxlength="25" placeholder="Enter Password" autocomplete="off" required></p>
               
              <p>      
             <!-- <label class=" w3-small w3-margin-top w3-b"><b>Login As</b> <span class="w3-text-red">*</span></label> -->
             <!-- <input class="w3-input w3-border  w3-card" name="vend_id" onkeyup="this.value=this.value.toUpperCase()" placeholder="Enter Vend ID" autocomplete="off" id="vend_val" type="text" maxlength="25" required> -->
             <input type="hidden" name="user_type" value="COMPANY_ADMIN">
            
            </p>
            <div id="demo">
            <p>      
             <!-- <label class=" w3-small w3-margin-top w3-b"><b>Vend ID</b> <span class="w3-text-red">*</span></label> -->
             <input class="w3-input w3-border  w3-card" name="company_code" onkeyup="this.value=this.value.toUpperCase()" placeholder="Enter Vend ID" autocomplete="off" id="vend_val" type="hidden" value="1LOOP" >
            </p>
          </div>
          <p>
            <label class=" w3-small w3-margin-top">Select Company</label>
            <select class="w3-select w3-border" style="height: 32px;" name="security_code">
              <option value="">Select Company</option>
              <option value="demo">Loopintechies Services PVT. LTD.</option>
		           <!-- <option value="beam">BEAM GLOBAL SPIRITS AND WINE (I) PVT. LTD.</option>
		           <option value="gwalior">GWALIOR ALCOBREW PVT. LTD.</option>
	            <option value="ALLIED">ALLIED BLENDERS AND DISTILLERS PVT. LTD.</option>
		          <option value="GRANO69 BEVERAGES PRIVATE LIMITED">GRANO69 BEVERAGES PRIVATE LIMITED</option>
              <option value="SULA">SULA VINEYARDS LIMITED</option>
              <option value="Inbev">Anheuser Busch Inbev India Ltd</option> -->

             

            </select>
          </p>
          <p>
            <button class="w3-btn w3-blue w3-round" style="width: 100%">Login</button>
          </p>
           <!-- <a href="#" class="w3-btn w3-hover-blue w3-border w3-round" onclick="document.getElementById('id01').style.display='block'" style="width: 100%">New User Register</a> -->
             </form>
             <br />
            
            </div>
            <div class="w3-col l2">&nbsp;</div>
          </div>


        </div>
        
      </div>
        
   <?php
	include 'includes/footer.php';
   ?>
   <div id="id01" class="modal">

    <form class="modal-content animate" action="validate-licence-code.php" method="post">
      <div class="imgcontainer">

        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        <!-- <img src="img_avatar2.png" alt="Avatar" class="avatar"> -->
      </div>

      <div class="container w3-padding">
        <div class="w3-col l12 w3-padding">
       <h4 class="w3-text-green w3-center">Enter License Code</h4>
       <label for="uname"><b>Licence Key</b><span class="w3-text-red">*</span></label>
       <input type="text" class="w3-card" style="width: 100%" placeholder="Enter Licence Key" name="licence-key" maxlength="50" required autocomplete="off"> <br>
       <button type="submit" class="w3-small w3-btn w3-blue w3-margin-top" style="width: 100%">Register</button>
    </div>
  </div>
    <!-- <div class="container"  style="background-color:#f1f1f1">
    </div> -->
  </form>

</div>
<!-- Forgot password Modal -->




<!-- Group Admin Login -->

<script type="text/javascript">
  const showUsersCode = (code) =>{
    // console.log(code);
    var url="get_user_code_block.php?code_id="+code;
    const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    document.getElementById("demo").innerHTML = this.responseText;
    }
  xhttp.open("GET", url, true);
  xhttp.send();
  }
</script>


</body>
</html>
