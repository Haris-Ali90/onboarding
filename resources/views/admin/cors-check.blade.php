<!DOCTYPE html>
<html>
<body>

<h1>The XMLHttpRequest Object</h1>

<button type="button" onclick="loadDoc2()">Request data</button>

<p id="demo"></p>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>
function loadDoc2(){
      $.ajax({
          url: "https://api2.joeyco.com/api/merchant/orders/csv/get-vendor-details/NDc1NTgw",
          type: 'GET',
          //dataType: 'jsonp',
          cors: false ,
          headers: {
            'Cross-origin-token' : 'NWZhZmRjZmRkMDI5MjkuMzEzNDEzNTA='
          },
          success: function (data){
            console.log(data);
            $("#demo").text(data);
          }
      })

      
}
</script>

</body>
</html>
