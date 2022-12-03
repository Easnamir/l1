const setSession = (id)=>{
  // console.log(id);
	var url = `setSession.php?sid=${encodeURIComponent(id)}`;
	const xhttp = new XMLHttpRequest();
  	xhttp.onload = function() {
    // console.log(this.responseText);
    }
  xhttp.open("GET", url, true);
  xhttp.send();
}

setSession('');

const setZoneId =(zid)=>{
            var url ="setZoneSession.php?zid="+zid;
            const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            // console.log(this.responseText);
           // Viewsupplier();
           getShopList();
            }
          xhttp.open("GET", url, true);
          xhttp.send();
      }
      const setZoneIdCompany =(zid)=>{
            var url ="setZoneSession.php?zid="+encodeURIComponent(zid);
            const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            // console.log(this.responseText);
            }
          xhttp.open("GET", url, true);
          xhttp.send();
      }
const getShopList = () =>{
  var select_shop = document.getElementById('company_shop');
  var select_zone = document.getElementById('company_zone');
  if(select_zone.value==''){
    select_shop.innerHTML = "<option value=''>Select Shop</option>";
    return false;
  }
  var url = "getShopList.php?fun=getShopList";
  const xhttp = new XMLHttpRequest();
      xhttp.onload = function() {
        select_shop.innerHTML=(this.responseText);
        }
      xhttp.open("GET", url, true);
      xhttp.send();

}

const getShopListZone = (zoneId) =>{
  var select_shop = document.getElementById('company_shop');
  var select_zone = document.getElementById('company_zone');
  if(select_zone.value==''){
    select_shop.innerHTML = "<option value=''>Select Shop</option>";
    return false;
  }
  // console.log(zoneId);
  var url = "getShopList.php?fun=getShopListZone&zoneId="+zoneId;
  const xhttp = new XMLHttpRequest();
      xhttp.onload = function() {
        select_shop.innerHTML=(this.responseText);
        // console.log(this.responseText);
        }
      xhttp.open("GET", url, true);
      xhttp.send();

}