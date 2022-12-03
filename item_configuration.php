<?php 
include 'includes/session_company.php';
$msg = "";
$class="";
$brand_no = '';
		
		if(isset($_GET['status']) && $_GET['status']=='not-added'){
		// echo '<script>alert("This supplier already Exist, Kindly add another supplier")</script>';
		$msg = "This item already Exist, Kindly add another item";
  		$class = "w3-red w3-left w3-col l4 w3-margin-top w3-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='sup-added'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Item Added Successfully!!";
  		$class = "w3-green w3-left w3-col l4 w3-margin-top w3-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='not-updated'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Item Not Updated, something went Wrong!!";
  		$class = "w3-red w3-left w3-col l4 w3-margin-top w3-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='item-updated'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Item Updated Successfully!!";
  		$class = "w3-orange w3-left w3-col l4 w3-margin-top w3-small";
	}
	
	include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$sql1 = "SELECT LOOKUP_PK, LOOKUP_VALUE, LOOKUP_TYPE FROM POPS_LOOKUP WHERE LOOKUP_TYPE = 'LIQUOR_CATAGORY'";
	$stmt1 = sqlsrv_query($conn, $sql1);

	$sql2 = "SELECT LOOKUP_PK, LOOKUP_VALUE, LOOKUP_TYPE FROM POPS_LOOKUP WHERE LOOKUP_TYPE = 'LIQUOR_TYPE'";
	$stmt2 = sqlsrv_query($conn, $sql2);



	$sql3 = "SELECT SUPPLIER_NAME as SUPPLIER_NAME  FROM POPS_PRICE_MASTER_ORISSA group by SUPPLIER_NAME order by SUPPLIER_NAME asc";
	$stmt3 = sqlsrv_query($conn, $sql3);
	$sqlBrand = "select MAX(SKU_FK)+1 as BRAND_NUM from POPS_PRICE_MASTER_ORISSA";
	$stmt_brand = sqlsrv_query($conn, $sqlBrand);

	while($row = sqlsrv_fetch_array($stmt_brand, SQLSRV_FETCH_ASSOC))
		$brand_no = $row['BRAND_NUM'];
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Brand Configuration</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		input{
			height: 15px;
		}
		select{
			height: 18px;
		}
	</style>
</head>
<body>
	<?php
	include 'includes/header_company.php';
	?>
	<div class="body-content w3-white w3-small">
		<div class="w3-container w3-margin-bottom">

           <div class="w3-row">
				<div class="w3-col l1">&nbsp;</div>
				<div class="w3-col l10">
                   	<h3><span class="w3-col l3">Brand Details</span> <span class="<?php echo $class; ?>">
						<?php echo $msg; ?>
					</span></h3>
                 <div class="w3-row">
				<div class="w3-margin-bottom">					
				</div>

			</div>
		
			<div class="container w3-col l12">
				<form name="adduser" action="add_item.php" method="POST">
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">


						<div class="w3-col l2 w3-padding-small">
							<label>Brand Code<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" id="brand_id" onchange="getBrandDetails(this.value)" type="text" name="brand_id" required="" maxlength="20" placeholder="Enter Brand Code" value="">
						</div>
						<input  placeholder="Enter Gtin Number" onchange="checkGtinNumber(this)" autocomplete="off"class="w3-input w3-border" type="hidden" id="gtin_no" name="gtin_no" required="" minlength="14">
						<!-- <input type="hidden" name="brand_table_pk" id="brand_table_pk" value=""> -->
						
						<!-- <div class="w3-col l2 w3-padding-small" >
							<label>Gtin Number<span class="w3-text-red">*</span></label>
							
						</div> -->


						<div class="w3-col l4 w3-padding-small">
							<label>Brand Name<span class="w3-text-red">*</span></label>
							<input  placeholder="Enter Brand Name" onkeyup="this.value =this.value.replace(/[^a-zA-Z0-9&\-_ ]/g, '').toUpperCase()" autocomplete="off"class="w3-input w3-border" type="text" id="brand_name" name="brand_name" required="" maxlength="250">
						</div>
						
						<div class="w3-col l1 w3-padding-small">
							<label>Size Value<span class="w3-text-red">*</span></label>
							<input  placeholder="Size Value" autocomplete="off" class="w3-input w3-border" type="number" name="size_value" required="" maxlength="20" id="size_value">
						</div>
						<div class="w3-col l1 w3-padding-small">
							<label>Pack Size<span class="w3-text-red">*</span></label>
							<input  placeholder=" Pack Size" autocomplete="off" class="w3-input w3-border" type="number" id="pack_size" name="pack_size" required="" maxlength="10" >
						</div>
						<div class="w3-col l2 w3-padding-small">
							<label>Category<span class="w3-text-red">*</span></label>
							<!-- <input class="w3-input w3-border" type="text" name="sup_name" required="" maxlength="30"> -->
							<select  class="w3-select w3-border" name="category" required="" id="category">
							    <option value="" selected>Choose category</option>
							    <?php while($row1 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){

							    	?>
								<option value="<?php echo $row1['LOOKUP_PK']; ?>"><?php echo $row1['LOOKUP_VALUE']; ?></option>
							<?php } ?>
							    
							  </select>
						</div>
						<div class="w3-col l2 w3-padding-small">
							<label>Sub Category<span class="w3-text-red">*</span></label>
							<!-- <input class="w3-input w3-border" type="text" name="sup_name" required="" maxlength="30"> -->
							<select class="w3-select w3-border" name="sub_category" required="" id="sub_category">
							    <option value=""  selected>Choose sub category</option>
							    <?php while($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){

							    	?>
								<option value="<?php echo $row1['LOOKUP_PK']; ?>"><?php echo $row1['LOOKUP_VALUE']; ?>
							</option>
							<?php } ?>
							    
							    
							  </select>
						</div>
						
					</div>
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">
						
						<div class="w3-col l1 w3-padding-small">
							<label>W.S.P<span class="w3-text-red">*</span></label>
							<input   placeholder="Enter W.s Price" min="1" autocomplete="off"class="w3-input w3-border" value="0" type="number" name="ws_price" required="" id="ws_price" step=".01" >
						</div>
						<div class="w3-col l2 w3-padding-small">
							<label>Custom Duty</label>
							<input class="w3-input w3-border" value="0" min="0" required=""  type="number" name="custom_duty"  placeholder="Enter Custom Duty" autocomplete="off" id="custom_duty"  step=".01">
						</div>
						<div class="w3-col l2 w3-padding-small">
							<label>Excise Price<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Excise Price" min="0.01" autocomplete="off" id="excise_price" value="0"  type="number" name="excise_price" required="" step=".01" >
						</div>
						<input   placeholder="Enter Cost Price" autocomplete="off" class="w3-input w3-border" value="0"  type="hidden" name="cost_price" maxlength="20" step=".01" id="cost_price"  required="">
						<!-- <div class="w3-col l3 w3-padding-small">
							<label>Cost Price<span class="w3-text-red">*</span></label>
							
						</div> -->
						<div class="w3-col l1 w3-padding-small">
							<label>VAT</label>
							<input  placeholder="Enter Sale Tax" autocomplete="off" min="0.1" class="w3-input w3-border" value="0"  type="number" name="sale_tax" required="" maxlength="30" id="sale_tax" step=".01" >
						</div>
						<input  placeholder="Enter TCS" autocomplete="off" class="w3-input w3-border" value="0" step=".01"  type="hidden" name="tcs" required="" maxlength="20" id="tcs">
						<!-- <div class="w3-col l3 w3-padding-small">
							<label>TCS</label>
							
						</div> -->
						<div class="w3-col l2 w3-padding-small">
							<label>Retail Profit</label>
							<input  placeholder="Enter Retail Profit" min="1" autocomplete="off" class="w3-input w3-border" value="0"  type="number" name="retail_profit"  step=".01" maxlength="10" id="retail_profit">
						</div>
						<div class="w3-col l1 w3-padding-small">
							<label>MRP<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" id="mrp" value="0" min="1"  type="number"  placeholder="Enter MRP" autocomplete="off"name="retail_price" required="" step=".01">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Supp Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" id="supp_name"   type="text"  placeholder="Enter Supp Name" autocomplete="off"name="supp_name" required="" >
						</div>
				
					<div class="w3-container w3-center">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit">Submit</button>
						<!-- <button class="w3-button w3-round w3-red tohide" id="update" type="Submit" name="update" >Update</button> -->
						<!-- <button class="w3-button w3-round w3-red">Delete</button> -->
						<button class="w3-button w3-round w3-red tohide" type="Reset" onclick="reSetFormdata()" >Reset</button>
					</div>
						</div>

				</form>
                   <div class="w3-col l12  w3-center" >
    
                          <label style="display:inline-block ; width:auto !important;">Type<span class="w3-text-red"></span></label>
                                       <input type="radio" id="bottle" name="qty" value="bottle" checked="true" onclick="checktype(this.value)"> Bottle
                          <input type="radio" name="qty" id="case_id" value="case" onclick="checktype(this.value)" > Case
                          <i class="w3-right">Download  List <img src="images/microsoft-excel.png" alt="amrit" style="height: 20px;" onclick="downloadExcel()">  </i>

</div>
             <div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">



                   <div class="w3-border w3-border-grey" style="max-height: 280px;  overflow:auto;" id="div_table">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1" id='table_id' style="width: 1300px">
					
				</table>
				
				</div>

				<form id="div_form" method="post" action="price_list.php">
					<input type="hidden" id="input_form" name="table_data" value="">					
				</form>


</div>
			</div>
			
		</div>


					</div>
				<div class="w3-col l1">&nbsp;</div>
			</div>

		</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
		// getBrandNameOrissa();
		// location.href='price_list.php'
		// var search_brand = document.getElementById('bnd_id');
		// search_brand.addEventListener("change",getBrandSizeOrissa);

		const getBrandDetails = (brand_id) =>{
			// console.log(brand_id)
			// obj.value = obj.value.toUpperCase();
			if(brand_id == ""){
				return false;
			}
			brand_id= brand_id.replace(/[^a-zA-Z0-9 .&\-]/g, '').toUpperCase();
			document.getElementById('brand_id').value=brand_id;
			var url= 'add_item.php?brand_id='+brand_id+'&fun=getBrandDetails';
			// console.log(url);
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				// console.log();
				if(this.responseText.length==0){
					return false;
				}
				var response = JSON.parse(this.responseText);
				// console.log(response);
				document.getElementById('gtin_no').value = response.GTIN_NO;
				document.getElementById('category').value = response.LIQUOR_TYPE_CD;
				document.getElementById('mrp').value = response.MRP;
				document.getElementById('brand_name').value = response.BRAND_NAME;
				document.getElementById('sub_category').value = response.CATEGORY_CD;
				document.getElementById('custom_duty').value = response.CUSTOM_DUTY;
				document.getElementById('excise_price').value = response.EXCISE_PRICE;
				document.getElementById('pack_size').value = response.PACK_SIZE;
				document.getElementById('size_value').value = response.SIZE_VALUE;
				document.getElementById('ws_price').value = response.WSP;
				document.getElementById('sale_tax').value = response.SALE_TAX;
				document.getElementById('retail_profit').value = response.RETAIL_PROFIT;
				document.getElementById('tcs').value = response.TCS;
				document.getElementById('supp_name').value = response.SUPP_NAME;


				}
			xhttp.open("GET", url, true);
			xhttp.send();
			
		}
		function downloadExcel(){
			var data = document.getElementById('div_table');
			document.getElementById('input_form').value=data.innerHTML;
			document.getElementById('div_form').submit();
		}
		const checkGtinNumber = (gtin) =>{
			if(gtin.value.length<14){
				alert('Gtin number must be at least 14 characters');
				gtin.focus();
			}
		}
         
          bottle=document.getElementById('bottle').value;
          case_id=document.getElementById('case_id').value;

          const checktype=(type)=>{

          var url = 'update-brand.php?fun=checkcase&type='+type;

          fetch(url).then(data=>data.text()).then(data=>{
          	document.getElementById('table_id').innerHTML = data;
          })

        

}

	checktype('bottle');

	</script>
</body>
</html>

<?php 

 ?>