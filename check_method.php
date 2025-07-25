<?php
session_start();
include 'config.php';


$name = $_POST['name'];
$grand_total = $_POST['grand_total'];

$user_id = $_SESSION['user_id'];
$uname =$_POST['uname'];
 $email =$_POST['email'] ; 
   $number =$_POST['number'] ;
   $name = $_POST['name']; 

$conn=mysqli_connect('localhost','root','','book_shop');
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    if ($method == 'phonepay') {
      // Redirect to PayUMoney form
      $_SESSION['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
      // $_SESSION['amount'] = $grand_total;
       $_SESSION['grand_total'] = $grand_total;
       $_SESSION['uname'] = $uname;
       $_SESSION['email'] = $email; 
       $_SESSION['number'] = $number;
         $_SESSION['name']=$name;
         
      //header('Location: payumoney_form.php?txnid='.$txnid.'&amount='.$amount);
      header('Location: PayUMoney_form.php');
      exit();
  }
  else{
    
if(isset($_POST['order_btn'])){

  
    $db_name = "mysql:host=localhost;dbname=book_shop";
    $username = "root";
    $password = "";
    
    $conn = new PDO($db_name, $username, $password);
    
       
    
       $name = $_POST['uname'];
       $name = filter_var($name, FILTER_SANITIZE_STRING);
       $number = $_POST['number'];
       $number = filter_var($number, FILTER_SANITIZE_STRING);
       $email = $_POST['email'];
       $email = filter_var($email, FILTER_SANITIZE_STRING);
       $method = $_POST['method'];
       $method = filter_var($method, FILTER_SANITIZE_STRING);
       $address = 'flat no. '. $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];
       $address = filter_var($address, FILTER_SANITIZE_STRING);
       $placed_on = date('d-M-Y');
    
    
       $cart_total = 0;
       $cart_products[] = '';
    
       $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
       $cart_query->execute([$user_id]);
       if($cart_query->rowCount() > 0){
          while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
             $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
             $sub_total = ($cart_item['price'] * $cart_item['quantity']);
             $cart_total += $sub_total;
          };
       };
    
       $total_products = implode(', ', $cart_products);
    
       $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
       $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);
    
       if($cart_total == 0){
          $message[] = 'your cart is empty';
       }elseif($order_query->rowCount() > 0){
          $message[] = 'order placed already!';
       }else{
          $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
          $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
          $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
          $cart_query->execute([$user_id]);
          if($cart_query->rowCount() > 0){
             while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
                $update_product = $conn->prepare("UPDATE `products` SET in_stock = in_stock - ?  WHERE  id = ?");
                $update_product->execute([$cart_item['quantity'],$cart_item['pid']]);
    
             };
          };
          $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
          $delete_cart->execute([$user_id]);
    
          echo "Deleted";
       // Store necessary data in session variables
      
        // Check the selected payment method
        
     
    }
    }
  }
  ?>
  