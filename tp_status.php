<?php
	include 'includes/session_company.php';
	$COMPANY_id = $_SESSION['COMPANY_id'];
	include 'includes/autoload.inc.php';
	include 'includes/connect.php';
  require 'vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
	$USER = $_SESSION['username'];
  $schdeules=[];
  $arr_size=count($schdeules);
  if(isset($_POST['upload'])){
    // var_dump($_FILES);
    // exit;
    $filename = $_FILES['tp_file']['name'];
    // var_dump($filename);
    // exit;
		$target_dir = "excels/";
		$file_break = explode('.',$filename);
		$extension =  end($file_break);
    // var_dump($extension);
    // exit;
		if(!($extension=='xls' || $extension=='xlsx' || $extension=='csv')){
			?>
			<script type="text/javascript">
				alert("Please Select properly formated Excel only");
			</script>
			<?php
		}
		else{
			move_uploaded_file($_FILES["tp_file"]["tmp_name"], $target_dir . $filename);
			$inputFileName = "./excels/$filename";
			
			// var_dump($inputFileName);
			$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

			/**  Create a new Reader of the type that has been identified  **/
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

			/**  Load $inputFileName to a Spreadsheet Object  **/
			$spreadsheet = $reader->load($inputFileName);
			$schdeules = $spreadsheet->getActiveSheet()->toArray();
			$arr_size=count($schdeules);
			// $row_num = count($schdeules[0]);
      $tp_array = [];
      for($i=10;$i<$arr_size-1;$i++){
        // var_dump($schdeules[$i]);
        // echo '<br>';
        $tp_num = $schdeules[$i][7];
        $po_num = $schdeules[$i][6];
        $issue_date = $schdeules[$i][11];
        $dispatch_date = $schdeules[$i][19];
        $receive_date = $schdeules[$i][21];
        $tp_status = $schdeules[$i][17];
        $tp_array["{$tp_num}"] = (array($tp_num,$po_num,$issue_date,$dispatch_date,$receive_date,$tp_status));
      }
      // var_dump($tp_array);
      // exit;
      $count=0;
      foreach($tp_array as $tp){
        $tp_num = $tp[0];
        $po_num = $tp[1];
        $issue_arr = explode('-',$tp[2]);
        $issue_date = $tp[2]?$issue_arr[2].'-'.$issue_arr[1].'-'.$issue_arr[0]:'';
        $dispatch_arr =explode('-',$tp[3]);
        $dispatch_date = $tp[3]?$dispatch_arr[2].'-'.$dispatch_arr[1].'-'.$dispatch_arr[0]:'';
        $receive_arr = explode('-',$tp[4]);
        $receive_date = $tp[4]?$receive_arr[2].'-'.$receive_arr[1].'-'.$receive_arr[0]:'';
        $tp_status = $tp[5];
        $sql ="if exists (
        select * from POPS_TP_STATUS_DETAILS where TP_NUMBER='$tp_num'
        ) 
        begin
        update POPS_TP_STATUS_DETAILS set tp_status='$tp_status',dispatch_date='$dispatch_date',receive_date='$receive_date',updated_date=getdate(),update_by='$USER' where TP_NUMBER='$tp_num'
        end
        else
        begin
        INSERT INTO [dbo].[POPS_TP_STATUS_DETAILS]
                   ([TP_NUMBER]
                   ,[PO_NUMBER]
                   ,[ISSUE_DATE]
                   ,[DISPATCH_DATE]
                   ,[RECEIVE_DATE]
                   ,[TP_STATUS]
                   ,[CREATED_DATE]
                   ,[CREATED_BY]
                   ,[UPDATE_BY]
                   ,[UPDATED_DATE])
             VALUES ('$tp_num','$po_num','$issue_date','$dispatch_date','$receive_date','$tp_status',getdate(),'$USER','$USER',getdate())
        end";
        // exit;
        $stmt = sqlsrv_query($conn,$sql);
        if($stmt != false){
          $count++;
        }
      }

      if($count == count($tp_array)){
        ?>
  <script>

    alert('TP Status update successfully');
    window.location.href='tp_status.php';
  </script>
        <?php
      }
      else{
        ?>
  <script>
    alert('Something went wrong!!');
    window.location.href='tp_status.php';
  </script>
        <?php
      }

    }
  }
  // exit;
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>TP status Portal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		i{
			cursor: pointer;
		}

		select{
			width: 80%;
		}
		input,select {
			height: 25px;
		}
	
	</style>
</head>
<body>
	<?php
		include 'includes/header_company.php';	
	?>
	<div class="w3-container">
<div class="body-content w3-white w3-small">
	<div class="w3-container w3-margin-bottom">
		<div class="w3-row">
			<div class="w3-col">
				<div class="w3-col l8">
          
					<h3>TP Status Portal</h3>
				</div>
        <div class="w3-col l4" style="padding-top: 5px">
          <form action="" method="post" enctype="multipart/form-data">
            <input type="file" style="height: 28px;" required name="tp_file">
            <button class="w3-button w3-red" name="upload">Upload</button>
          </form>
        </div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
					<!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                       
						
                   
                   <div class="w3-col l2 w3-padding-small">
						<label> Start Date </label>
						<input class="w3-input w3-border" type="date" name="startdate" id="startdate">
					</div>
					<div class="w3-col l2 w3-padding-small">
						<label> End Date </label>
						<input class="w3-input w3-border" type="date" name="enddate" id="enddate">
					</div>

					 <div class="w3-col l2 w3-padding-small">
							<label>Department<span class="w3-text-red">*</span></label>
              <select name="Department" class="w3-select" id="Department">
              		<option value="ALL">ALL</option>
								<option value="('DSIIDC','DCCWS','DTTDC','DSCSC')">Corporation</option>
									<option value="('RESTAURANT','CLUB','HOTEL')">HCR</option>
									<option value="('Private')">Private</option>
								
								<
							</select>
						</div>
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="tp_Status()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12 w3-border w3-border-black" style="min-height:200px;max-height: 200px;   overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
					<thead>
						<tr><th>SNo</th><th>Department</th><th width=20%>Vend Name</th><th>TP No</th><th>Issue Date</th><th>Dispatch Date</th><th>Recevied Date</th><th>Portal Status</th> <th>Manual Status</th><th>Updated By</th><th>Updated Date</th><th>Action</th></tr>	
					</thead>
						<tbody id="tp_Status"  >
								</tbody>
				</table>
				</div>
				</div>


                     



				
			</div>
		</div>
		
						

	</div>
</div>
	</div>


	<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:450px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-green w3-display-topright" title="Close Modal">&times;</span>
      </div>
			<div class="w3-section">
      <form class="w3-container" id="status_form" action="">
        
          <label><b>Change Status </b></label>
          <select id="statusId" name="tp_status" class="w3-select w3-border w3-margin-bottom" style="width:80% ">
          	<option value="">Change Status</option>
          	<option value="TP Received">TP Received</option>
          	<option value="Dispatched">Dispatched</option>
          	<option value="Expired">Expired</option>

            <option value="Printed">Printed</option>
          	<option value="Generated">Generated</option>
          	<option value="Apply for Revalidation">Apply for Revalidation</option>
            <option value="Generate in last Month Dispatch this Month">Generate in last Month Dispatch this Month</option>
          	<option value="Stock Send Not Rec.on Portal">Stock Send Not Rec.on Portal</option>
          	<option value="TP for Surrender">TP for Surrender</option>
            <option value="TP on Hold">TP on Hold</option>
          	
          </select>       
        <!-- <div >
						<label> Remark </label>
						<input class="w3-input w3-border" style="width:80%; margin-bottom: 10px " type="text" name="remark" id="remark">
					</div> -->
					<!-- <input  id='tp_id' class="w3-input w3-border w3-margin-bottom" type="hidden" name="tp_id"> -->
					
					
       <input  id='tp_no' class="w3-input w3-border w3-margin-bottom" type="hidden" name="tp_no">
			 <div>
          <button class="w3-button w3-red" type="button" onclick="changeStatusTp()">SAVE</button>
			 </div>
			 </form>
      </div>
      

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-orange w3-padding-small">Cancel</button>
      </div>

    </div>
  </div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
 var enddate = document.getElementById('enddate');
		enddate.value= getTodaysDate();

		var startdate =document.getElementById('startdate');
	startdate.value = getFirstDay();
	 
const tp_Status = () =>{
 var Department=document.getElementById('Department');
	if(Department.value==''){
		alert("Please select Department ");
		return false;
	}
	else{
			var url = 'challan_statusdata.php?tpstatusdata=tpstatusdata&&startdate='+startdate.value+'&enddate='+enddate.value+'&Department='+Department.value;


			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('tp_Status').innerHTML=data;
			});
}
}
const updatechallanStatus = (id) =>{
			console.log(id);
			document.getElementById('id01').style.display='block';
			document.getElementById('tp_no').value=id;
			let checkele = document.getElementsByName(id)[0];
			let chalan_no = checkele.getAttribute('data-id');
			let vend_id = checkele.getAttribute('data-vend');
			let challan_date = checkele.getAttribute('data-challandate');
			document.getElementById('pk_id').value=chalan_no;
			document.getElementById('vend_id').value=vend_id;
			document.getElementById('challan_date').value=challan_date;
		}
		const changeStatus = ()=>{
			let status = document.getElementById('statusId');
			let remark = document.getElementById('remark');
			if(status.value==''){
				alert("Please change status");
				status.focus();
				return false;
			}
			if(remark.value==''){
				alert("Please Enter remark");
				remark.focus();
				return false;
			}
			let form = document.getElementById('status_form');
			let formData = new FormData(form);
			var challan_no=document.getElementById('pk_id').value;			
			var url="challan_statusdata.php?fun=changeStatus";
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				document.getElementById('id01').style.display='none';
				alert(this.responseText);
				challan_Status();
				}
			};
			xhttp.open("POST", url, true);
			xhttp.send(formData);
		}
    const updateTpStatus = (tp) => {
      console.log(tp);
      document.getElementById('tp_no').value=tp;
      document.getElementById('id01').style.display='block';
    }
  function changeStatusTp(){
    let form = document.getElementById('status_form');
    let status = document.getElementById('statusId');
			// let remark = document.getElementById('remark');
			if(status.value==''){
				alert("Please change status");
				status.focus();
				return false;
			}
    formData= new FormData(form);
    var url="challan_statusdata.php?fun=changeStatusTp";
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				document.getElementById('id01').style.display='none';
				alert(this.responseText);
				tp_Status();
				}
			};
			xhttp.open("POST", url, true);
			xhttp.send(formData);

  }
	</script>
</body>
</html>

