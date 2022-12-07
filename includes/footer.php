	<div class="w3-row w3-margin-top footer-fix w3-pale-green w3-hide-small" style="position: fixed; z-index: 10; bottom: 0; width: 100%; font-size: 8px !important;">
	<div class="w3-container w3-padding-small ">
		<div class="w3-col l4">
			<p>Email: <a href="mailto:mail.loopintechies@gmail.com">mail.loopintechies@gmail.com</a></p>
			<p>Contact: 0120-4108457/37</p>

		</div>
		<div class="w3-col l4">&copy; <?php echo Date('Y') ?> LOOPINTechies. All Rights Reserved</div>
		<div class="w3-col l4"><span>Developed and Maintained By:<a href="https://www.loopintechies.com/" target="_blank">LOOPINTechies Services (India) PVT LTD</a></span></div>
	</div>
</div>

<div class="w3-row w3-margin-top footer-fix w3-pale-green w3-hide-large" style="width: 100%; font-size: 11px !important;">
	<div class="w3-container w3-padding-small ">
		<div class="w3-col l4">
			<p>Email: <a href="mailto:mail.loopintechies@gmail.com">mail.loopintechies@gmail.com</a></p>
			<p>Contact: 0120-410-8457/37</p>

		</div>
		<div class="w3-col l5">&copy; <?php echo Date('Y') ?> LOOPINTechies. All Rights Reserved</div>
		<div class="w3-col l4"><span>Developed and Maintained By:<a href="https://www.loopintechies.com/" target="_blank">LOOPINTechies Services (India) PVT LTD</a></span></div>
	</div>
</div>

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> -->
<script type="text/javascript" src="js/script.js"></script>

<script>
function myAccFunc() {
  var x = document.getElementById("demoAcc");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-green";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-green", "");
  }
}

function myDropFunc() {
  var x = document.getElementById("demoDrop");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-green";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-green", "");
  }
}
</script>
