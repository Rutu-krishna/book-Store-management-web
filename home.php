<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){
   
$pid = $_POST['pid'];
$pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $author_name = $_POST['author_name']; // Add author name
   $category_name = $_POST['category_name']; // Add category name

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
     
      mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name,price, quantity, image, author_name, category_name) VALUES('$user_id', '$pid', '$product_name', '$product_price', '$product_quantity', '$product_image','$author_name', '$category_name')") or die('query failed');
        $message[] = 'product added to cart!';

   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
  <style>
   
.home-category .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 27rem);
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.home-category .box-container .box{
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   border-radius: 1.5rem;

   box-shadow: 5px 5px 5px rgb(106 106 117);
}

.home-category .box-container .box img{
   width: 100%;
   height:150px;
   margin-bottom: 1rem;
}

.home-category .box-container .box h3{
   text-transform: uppercase;
   color:var(--black);
   padding:1rem 0;
   font-size: 2rem;
}

.home-category .box-container .box p{
   line-height: 2;
   font-size: 1.5rem;
   color:var(--light-color);
   padding:.5rem 0;
}

.home-category{
   padding-bottom: 0;
}
.products .box-container .box{
   
   box-shadow: 5px 5px 5px rgb(148, 148, 152);
 
}

  </style>
  
</head>
<body>
   
<?php include 'header.php'; ?>
<section class="body">
<section class="home">
   <div class="content">
      <h3 id="changeTitle">Hand Picked Book to your door.</h3>
      <p id="changeText"> At Bookly our mission is simple — to connect readers with the stories that matter. We strive to:
Provide an extensive and diverse collection of books across genres. Create a seamless and enjoyable shopping experience for our customers. 
Foster a community of readers by promoting literary discussions and events. </p>
      <a href="about.php" class="white-btn" class="">discover more</a>
   </div>
</section>

<section class="home-category">

   <h1 class="title">Shop By category</h1>

   <div class="box-container">

      

      <div class="box">
         <img src="images\sci.webp" alt="" width="223" height="103">
         <h3>Science</h3>
         <p>Unlock the mysteries of the universe with this comprehensive science book.</p>
         <a href="category.php?category=Science" class="btn">Science</a>
      </div>
      
      <div class="box">
         <img src="images\fiction.jpg" alt="" width="223" height="103">
         <h3>Fiction</h3>
         <p>"A mysterious circus that arrives without warning, captivating all who enter." </p>
         <a href="category.php?category=Fiction" class="btn">Fiction</a>
      </div>

      <div class="box">
         <img src="images/psycho.jpg" alt="" width="223" height="103">
         <h3>Psychology</h3>
         <p>Explore the depths of the human mind in this illuminating psychology book.</p>
         <a href="category.php?category=Psychology" class="btn">Psychology</a>
      </div>
      <div class="box">
         <img src="images\nov.jpg" alt="" width="223" height="103">
         <h3>Novel</h3>
         <p>A gripping tale of love and betrayal in a world on the brink of collapse</p>
         <a href="category.php?category=Novel" class="btn">Novel</a>
      </div>
   </div>

</section>
<div style="background-color:rgb (87, 86, 86,0.5)">
   <section class="products">
      <h1 class="title">latest products</h1>
      <div class="box-container" id="productcontainer">
         <?php  
            $select_products = mysqli_query($conn, "SELECT p.*, p.category AS category_name, p.author AS author_name FROM `products` p") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
         ?>
                  <form action="" method="post" class="box">
                     <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                     <div class="name"><?php echo $fetch_products['name']; ?></div>
                     <div class="category">Category: <?php echo $fetch_products['category_name']; ?></div>
                     <div class="author">Author: <?php echo $fetch_products['author_name']; ?></div>
                     <div class="in_stock"><span><?= $fetch_products['in_stock']; ?></span></div>
                     <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
                     <input type="number" min="0" max="<?= $fetch_products['in_stock']; ?>" value="1" name="product_quantity" class="qty">
                     <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                     <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                     <input type="hidden" name="author_name" value="<?php echo $fetch_products['author_name']; ?>"> <!-- Add hidden input for author name -->
                     <input type="hidden" name="category_name" value="<?php echo $fetch_products['category_name']; ?>"> <!-- Add hidden input for category name -->
                     <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                  </form>
         <?php
               }
            }else{
               echo '<p class="empty">no products added yet!</p>';
            }
         ?>
      </div>
      <button class="prev-btn" onclick="scrollproducts(-1)"><i class="fas fa-chevron-left"></i></button>
   <button class="next-btn" onclick="scrollproducts(1)"><i class="fas fa-chevron-right"></i></button>
 
      <div class="load-more" style="margin-top: 2rem; text-align:center">
         <a href="shop.php" class="option-btn">load more</a>
      </div>
   </section>

   <section class="about">
      <div class="flex">
         <div class="image">
             <img src="images\fiction.jpg" alt=""> 
         </div>
         <div class="content">
            <h3>about us</h3>
            <p>At Bookly, we believe in the transformative power of literature. Our passion for books and the written word drives us to provide you with an exceptional online bookstore experience. </p>
            <a href="about.php" class="btn">read more</a>
         </div>
      </div>
   </section>
</div>

<!--<section class="reviews">
   <h1 class="title">client's reviews</h1>
   <div id="reviewsContainer" class="  box-container reviews-scroll">
      <?php
      $selectReview = mysqli_query($conn, "SELECT * FROM `review`") or die('query failed');
      $number_of_reviews = mysqli_num_rows($selectReview);
      if ($number_of_reviews > 0) {
         while ($fetch_message = mysqli_fetch_assoc($selectReview)) {
      ?>
            <div class="box" style="height: 220px; width:300px;padding-bottom:10px;">
               <p> Name : <span><?php echo $fetch_message['name']; ?></span> </p>
               <p> Number : <span><?php echo $fetch_message['number']; ?></span> </p>
               <p> Review : <span><?php echo $fetch_message['review']; ?></span> </p>
               Display star ratings 
               <div class="stars">
                  <?php
                  $rating = $fetch_message['star_rating'];
                  for ($i = 1; $i <= 5; $i++) {
                     if ($i <= $rating) {
                        echo '<i class="fas fa-star"></i>';
                     } else {
                        echo '<i class="far fa-star"></i>';
                     }
                  }
                  ?>
               </div>
            </div>
      <?php
         }
      } else {
         echo '<p class="empty">No reviews available!</p>';
      }
      ?>
   </div>
</section>-->
<div style="background-color:rgb (87, 86, 86,0.5)">
<section class="reviews">
   <h1 class="title">client's reviews</h1>
   <button class="prev-btn" onclick="scrollReviews(-1)"><i class="fas fa-chevron-left"></i></button>
   <button class="next-btn" onclick="scrollReviews(1)"><i class="fas fa-chevron-right"></i></button>
 
   <div id="reviewsContainer" class="  reviews-scroll" >
    
  <?php
      $selectReview = mysqli_query($conn, "SELECT * FROM `review` ") or die('query failed');
      $number_of_reviews = mysqli_num_rows($selectReview);
      if ($number_of_reviews > 0) {
         while ($fetch_message = mysqli_fetch_assoc($selectReview)) {
      ?>
            <div class="box"  >
               <p> Name : <span><?php echo $fetch_message['name']; ?></span> </p>
               <p> Number : <span><?php echo $fetch_message['number']; ?></span> </p>
               <p> Review : <span><?php echo $fetch_message['review']; ?></span> </p>
               <!-- Display star ratings -->
               <div class="stars">
                  <?php
                  $rating = $fetch_message['star_rating'];
                  for ($i = 1; $i <= 5; $i++) {
                     if ($i <= $rating) {
                        echo '<i class="fas fa-star"></i>';
                     } else {
                        echo '<i class="far fa-star"></i>';
                     }
                  }
                  ?>
               </div>
            </div>
           
      <?php
         }
      } else {
         echo '<p class="empty">No reviews available!</p>';
      }
      ?>
   </div>     </div>
</section>
</div>
<section class="home-contact">
   <div class="content">
      <h3>have any questions?</h3>
      <p>GPK,Karad<br>contactbookly@gmail.com<br>Phone Number: 8830734118 <br>
      Thank you for choosing Bookly as your destination for literary exploration. Happy reading! </p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>
</section>
</section>

<?php include 'footer.php'; ?>

<script>
   // Define an array of titles and texts to cycle through
   const titles = ["Hand Picked Book to your door.", "New Arrivals Just for You!", "Explore Exciting Reads Today!"];
   const texts = [
      "At Bookly our mission is simple — to connect readers with the stories that matter. We strive to: Provide an extensive and diverse collection of books across genres. Create a seamless and enjoyable shopping experience for our customers. Foster a community of readers by promoting literary discussions and events.",
      "Discover the latest additions to our catalog! From bestsellers to hidden gems, find your next favorite book here.",
      "Expand your literary horizons with Bookly! Explore our curated selection of books and dive into new worlds of imagination and knowledge."
   ];

   let index = 0;

   // Function to change the content after a certain delay
   function changeContent() {
      document.getElementById('changeTitle').innerText = titles[index];
      document.getElementById('changeText').innerText = texts[index];
      index = (index + 1) % titles.length; // Increment index and reset to 0 if it exceeds the length of the array
   }

   // Call the function initially
   changeContent();

   // Set a timer to call the function periodically (every 5 seconds in this example)
   setInterval(changeContent, 4000); // Change content every 5 seconds (5000 milliseconds)
  
</script>
<script>
   
 //For Reviews
   // Function to scroll reviews horizontally
  function scrollReviews() {
      const container = document.getElementById('reviewsContainer'); 
      const boxWidth = container.firstElementChild.offsetWidth; // Get width of each review card
      const numCards = Math.floor(container.offsetWidth / boxWidth); // Calculate number of cards visible at once
      const scrollAmount = numCards * boxWidth; // Calculate the scroll amount
      container.scrollBy({
         left: scrollAmount,
         behavior: 'smooth'
      });
     // container.scrollLeft += container.offsetWidth; // Scroll one container width at a time
      if (container.scrollLeft + container.offsetWidth >= container.scrollWidth) {
         container.scrollLeft = 0; // Reset to the beginning if reached the end
      }
   }
   
   // Set interval to scroll reviews periodically (every 5 seconds in this example)
   
   </script>
   <script>
   
   //For Reviews
     // Function to scroll reviews horizontally
    function scrollproducts() {
        const container = document.getElementById('productcontainer'); 
        const boxWidth = container.firstElementChild.offsetWidth; // Get width of each review card
        const numCards = Math.floor(container.offsetWidth / boxWidth); // Calculate number of cards visible at once
        const scrollAmount = numCards * boxWidth; // Calculate the scroll amount
  
        container.scrollBy({
           left: scrollAmount,
           behavior: 'smooth'
        });
  
       // container.scrollLeft += container.offsetWidth; // Scroll one container width at a time
        if (container.scrollLeft + container.offsetWidth >= container.scrollWidth) {
           container.scrollLeft = 0; // Reset to the beginning if reached the end
        }
     }
  
     // Set interval to scroll reviews periodically (every 5 seconds in this example)
     
     </script>
</body>
<style>
      
      body{ background:linear-gradient(80deg,rgb(156,133,177), rgb(255,255,255), rgb(156,133,177));
         /* background:linear-gradient(180deg, rgb(255,255,255), rgb(156,133,177));*/
      }
      :root{
         --main-color:#443;
    --border-radius:95% 4% 97% 5%/4% 94% 3% 95%;
    --border-radius-hover:4% 95% 6% 95%/95% 4% 92% 5%;
    --border:.2rem solid var(--main-color);
    --border-hover:.2rem dashed var(--main-color);
      }
      .about .flex{
    display:flex;
    align-items: center;
    flex-wrap:wrap;
    gap:1.5rem;
}
      
.about .flex .image{
    flex:1 1 42rem;
}

.about .flex .image img{
    width: 100%;
    animation:aboutImage 4s linear infinite;

}

@keyframes aboutImage {
    0%, 100%{
        transform:scale(.9);
        border-radius: var(--border-radius-hover);

    }
    50%{
        transform:scale(.8);
        border-radius: var(--border-radius);
        
    }

}
     
      .products .box-container .box {
         border-radius: 1.5rem;
         background-color: var(--white);
         box-shadow: 5px 5px 5px rgb(148, 148, 152);
        
         padding: 2rem;
         text-align: center;
         border: var(--border);
         position: relative;
      }
      
      .products .box-container .box .qty {
         width: 80px;
         padding: 1.2rem 1.4rem;
         border-radius: 1.5rem;
      
         margin: 1rem 0;
         font-size: 2rem;
      }

      .in_stock {
   position: absolute;
   top:1rem; right:1rem;
   border-radius: .5rem;
   height: 4.5rem;
   line-height: 4.3rem;
   width: 5rem;
   border:var(--border);
   color:var(--black);
   font-size: 2rem;
   background-color: var(--white);
}
      .box {
         height:auto;
         padding:10px;
         text-align: center;
         box-shadow: 5px 5px 5px rgb(148, 148, 152);
         transition: transform 0.3s;
         margin: 1rem;
         border-radius: 15px;
      }

      .box img {
         max-width: 100%;
         max-height: 200px;
         height: auto;
      }

      .reviews-scroll .box {
         flex: 0 0 auto;
         margin-right: 10px;width: 80px;
         padding: 1.2rem 1.4rem;
         border-radius: 1.5rem;
         border: var(--border);
         
         font-size: 2rem;
      }

      .reviews-scroll {

         
         -webkit-overflow-scrolling: touch;
         padding-bottom: 10px;
         margin-bottom: -10px;  width: auto;
      }
      .reviews .reviews-scroll {
    display: flex;
    align-items: center;
    position: relative;  
         flex-wrap: nowrap;
         overflow-x: auto;
}

      .reviews {
         background-color: rgb(156,133,177,0.1);
      }
      .reviews .box {
  background-color: #fff;
  border-radius: 0px 30px 0px 30px;
  box-shadow: 5px 5px 5px rgba(148,148,152);
  padding: 20px;
  margin: 10px;
  width: 400px;
}

.reviews .box p {
  margin: 10px 0;
}

.reviews .box .stars {
  display: flex;
  justify-content: center;
}

.reviews .box .stars i {
  color: #ffd700; /* Gold color for star */
  margin: 0 2px;
}

.prev-btn,
.next-btn {
    position: absolute;
    
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    font-size: 24px;
    color: #555;
}

.prev-btn {
    left: 0;
}

.next-btn {
    right: 0;
}
   </style>
</html>
