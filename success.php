<?php
session_start();
$action = '';
include 'config.php';

$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount =$_POST["amount"];
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$additionalCharges = isset($_POST["additionalCharges"]) ? $_POST["additionalCharges"] : '';
$posted_hash = $_POST["hash"];
$email=$_POST["email"];
//$salt="UkojH5TS";
$SALT = "UOssuafMZyn3LqkKnCwKyGBLG94uY6kj";


// Salt should be same Post Request 

If (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
  } else {
        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$txnid.'|'.$key;
         }
		 $hash = hash("sha512", $retHashSeq);
          echo $hash;
          
          

          echo "<h3>Thank You. Your order status is ". $status .".</h3>";
          echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";

         # echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";

          $query = "SELECT * FROM `orders`;"; 
  
  // FETCHING DATA FROM DATABASE 
  $result = $con->query($query); 
  
    if ($result->num_rows > 0)  
    { 
        // OUTPUT DATA OF EACH ROW 
        while($row = $result->fetch_assoc()) 
        { 
    
       
        //  $query=mysqli_query($con, "insert into transactions(user_id,uname,email,number,name,grand_total) value('".$row['parkingnumber']."','".$row['registrationnumber']."','".$row['ownername']."','".$row['amount']."')");
          $query = mysqli_query($conn, "INSERT INTO `transactions` (user_id, uname, email, number, name, grand_total,payment_method) 
            VALUES ('$user_id', '$uname', '$email', '$number', '$name', '$grand_total','$method')");
    
          
          if ($query) {
                echo "<script>alert('Transaction added successfully');</script>";
            
              }
              else
                {
                 
                   echo "<script>alert('Something Went Wrong. Please try again');</script>";
                }
        } 
    }  
    else { 
        echo "0 results"; 
    } 
        

		   
?>	
