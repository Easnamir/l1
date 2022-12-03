<div class="w3-col l2 w3-padding-small">
					<label style="margin-top: 8px;">Select Zone</label>
					<select class="w3-select w3-border w3-border-black" name="zone_detail" style="width: 85%;" id="company_zone" onchange="setZoneIdCompany(this.value)">
						
						<option value="All">All</option>
						<?php $sqlc = "SELECT POPS_ZONE_PK, ZONE from POPS_ZONE where POPS_COMPANY_DETAILS_FK='".$_SESSION['COMPANY_id']."'";
						$stmtc = sqlsrv_query($conn,$sqlc);
						while($rowc = sqlsrv_fetch_array($stmtc,SQLSRV_FETCH_ASSOC)){

						 ?>
						 <option value="<?php echo $rowc['POPS_ZONE_PK'] ?>"><?php echo $rowc['ZONE'] ?></option>
						 <?php
						}
						?>
					</select>
						<label>Select Shop</label>
					<select class="w3-select w3-border w3-border-black" name="shop_details" style="width: 85%;" id="company_shop"  required="">
						<option value="All">All</option>
						<?php
							$zone_arr=[];
							$sqlz = "Select POPS_ZONE_PK from POPS_ZONE where POPS_COMPANY_DETAILS_FK='$COMPANY_id'";
							$stmtz = sqlsrv_query($conn,$sqlz);
							while($rowz = sqlsrv_fetch_array($stmtz,SQLSRV_FETCH_ASSOC)){
								$zone_arr[]=$rowz['POPS_ZONE_PK'];
							}
							$zones = implode(',',$zone_arr);

	 $sql1 = "Select SHOP_DETAILS_PK as ID,SHOP_NAME as NAME, SHOP_ADDRESS as ADDRESS, PIN_CODE as PIN from POPS_SHOP_DETAILS where zone in ($zones)";
	// exit;
	$stmt1 = sqlsrv_query($conn, $sql1);
	$html = "";
	while($row=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		$html .= "<option value='".json_encode($row)."'>".$row['NAME']."</option>";
	}	
	echo $html;

						 ?>
						
					</select>
				</div>