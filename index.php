<?php

include 'config.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['register'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass'] );
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `user` WHERE name = ? AND email = ?");
   $select_user->execute([$name, $email]);

   if($select_user->rowCount() > 0){
      $message[] = 'username or email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `user`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
      }
   }

}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete_cart_item'])){
   $delete_cart_id = $_GET['delete_cart_item'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$delete_cart_id]);
   header('location:index.php');
}

if(isset($_GET['logout'])){
   session_unset();
   session_destroy();
   header('location:index.php');
}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      $message[] = 'please login first!';
   }else{

      $pid = $_POST['pid'];
      $name = $_POST['name'];
      $price = $_POST['price'];
      $image = $_POST['image'];
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND name = ?");
      $select_cart->execute([$user_id, $name]);

      if($select_cart->rowCount() > 0){
         $message[] = 'already added to cart';
      }else{
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'added to cart!';
      }

   }

}

if(isset($_POST['order'])){

   if($user_id == ''){
      $message[] = 'please login first!';
   }else{
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $number = $_POST['number'];
      $number = filter_var($number, FILTER_SANITIZE_STRING);
      $address = 'flat no.'.$_POST['flat'].', '.$_POST['street'].' - '.$_POST['pin_code'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $method = $_POST['method'];
      $method = filter_var($method, FILTER_SANITIZE_STRING);
      $total_price = $_POST['total_price'];
      $total_products = $_POST['total_products'];

      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);

      if($select_cart->rowCount() > 0){
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $method, $address, $total_products, $total_price]);
         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);
         $message[] = 'order placed successfully!';
      }else{
         $message[] = 'your cart empty!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>The Gallery Cafe</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/web.css">


<style>

 .reservation form{
   border: var(--border);
   padding: 2rem;
}

  .reservation form .flex{
   display: flex;
   flex-wrap: wrap;
   gap: 1.5rem;
}

.reservation form h3{
   background-color: var(--sub-color);
   color: var(--main-color);
   font-size: 2.5rem;
   margin-bottom: 2rem;
   border-radius: .5rem;
   padding: 1.2rem;
   text-align: center;
   text-transform: capitalize;
}

.reservation form .flex .box{
   flex: 1 1 40rem;
   color:var(--black);
}

.reservation form .flex .box p{
   font-size: 1.8rem;
   color: var(--sub-color);
}

.reservation form .flex .box .input{
   padding: 1rem 0;
   margin: 1rem 0;
   border-bottom: var(--border);
   background: var(--main-color);
   color:var(--black);
   font-size: 1.8rem;
   width: 100%;
}

.reservation form .flex .box .input::placeholder{
   color: rgba(220, 198, 156, .6);
}

.reservation form .flex .box input[type="date"]::-webkit-calendar-picker-indicator{
   filter: invert(1);
}
</style>




</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!-- header section starts  -->

<header class="header">

   <section class="flex">

      <a href="#home" class="logo"><span>Gallery</span>Cafe</a>

      <nav class="navbar">
         <a href="#home">home</a>
         <a href="#about">about</a>
         <a href="#menu">menu</a>
         <a href="#order">order</a>
         
         <a href="#reservation">Reservation</a>

      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="order-btn" class="fas fa-box"></div>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <div id="cart-btn" class="fas fa-shopping-cart"><span>(<?= $total_cart_items; ?>)</span></div>
      </div>

   </section>

</header>

<!-- header section ends -->

<div class="user-account">

   <section>

      <div id="close-account"><span>close</span></div>

      <div class="user">
         <?php
            $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_user->execute([$user_id]);
            if($select_user->rowCount() > 0){
               while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                  echo '<p>welcome ! <span>'.$fetch_user['name'].'</span></p>';
                  echo '<a href="index.php?logout" class="btn">logout</a>';
               }
            }else{
               echo '<p><span>you are not logged in now!</span></p>';
            }
         ?>
      </div>

      <div class="display-orders">
         <?php
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  echo '<p>'.$fetch_cart['name'].' <span>('.$fetch_cart['price'].' x '.$fetch_cart['quantity'].')</span></p>';
               }
            }else{
               echo '<p><span>your cart is empty!</span></p>';
            }
         ?>
      </div>

      <div class="flex">

         <form action="user_login.php" method="post">
            <h3>login now</h3>
            <input type="email" name="email" required class="box" placeholder="enter your email" maxlength="50">
            <input type="password" name="pass" required class="box" placeholder="enter your password" maxlength="20">
            <input type="submit" value="login now" name="login" class="btn">
         </form>

         <form action="" method="post">
            <h3>register now</h3>
            <input type="text" name="name" oninput="this.value = this.value.replace(/\s/g, '')" required class="box" placeholder="enter your username" maxlength="20">
            <input type="email" name="email" required class="box" placeholder="enter your email" maxlength="50">
            <input type="password" name="pass" required class="box" placeholder="enter your password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" required class="box" placeholder="confirm your password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="register now" name="register" class="btn">
         </form>

      </div>

   </section>

</div>

<div class="my-orders">

   <section>

      <div id="close-orders"><span>close</span></div>

      <h3 class="title"> my orders </h3>

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){   
      ?>
      <div class="box">
         <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
         <p> total_orders : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> total price : <span>Rs<?= $fetch_orders['total_price']; ?>/-</span> </p>
         <p> payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">nothing ordered yet!</p>';
      }
      ?>

   </section>

</div>

<div class="shopping-cart">

   <section>

      <div id="close-cart"><span>close</span></div>

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
              $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
              $grand_total += $sub_total; 
      ?>
      <div class="box">
         <a href="index.php?delete_cart_item=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this cart item?');"></a>
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <div class="content">
          <p> <?= $fetch_cart['name']; ?> <span>(<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?>)</span></p>
          <form action="" method="post">
             <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
             <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" onkeypress="if(this.value.length == 2) return false;">
               <button type="submit" class="fas fa-edit" name="update_qty"></button>
          </form>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty"><span>your cart is empty!</span></p>';
      }
      ?>

      <div class="cart-total"> grand total : <span>Rs<?= $grand_total; ?>/-</span></div>

      <a href="#order" class="btn">order now</a>

   </section>

</div>

<div class="home-bg">

   <section class="home" id="home">

      <div class="slide-container">

         <div class="slide active">
            <div class="image">
               <img src="images/restaurant.jpg" alt="">
            </div>
            <div class="content">
               <h3>Welcome </h3> 
               <h3> Gallery Cafe Restaurant</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

         <div class="slide active">
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
            <div class="content">
               <h3>Sri Lankan Foods</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

         <div class="slide">
            <div class="image">
               <img src="images/home-img-4.png" alt="">
            </div>
            <div class="content">
               <h3>Chinese Foods</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>


         <div class="slide">
            <div class="image">
               <img src="images/home-img-5..png" alt="">
            </div>
            <div class="content">
               <h3>Australian Foods</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

      </div>

   </section>

</div>

<!-- about section starts  -->

<section class="about" id="about">

   <h1 class="heading">about us</h1>
       
     <div class="box-container">

      <div class="box">
         <img src="images/about-1.svg" alt="">
         <h3>Why you Choose Us</h3>
         <p>Our menu features a diverse array of dishes, carefully crafted using the freshest, locally-sourced ingredients. Whether you’re in the mood for classic comfort food or innovative culinary creations,
         our chefs ensure every plate is a masterpiece.</p>
         
      </div>

      <div class="box">
         <img src="images/meals2.jpg" alt="">
         <h3>Types of Meals</h3>
         <p> Breakfast<br>
             Brunch <br>
             Elevenses<br>
             Lunch<br>
             Dinner<br>
             Banquet<br>
            Desserts<br>
            Beverages<br></p>
         
      </div>

      <div class="box">
         <img src="images/meals.jpg" alt="">
         <h3>Also have the foods various Cousin Types</h3>
         <p>Sri Lankan<br>
            Chinese<br>
            Ithalian<br>
            Australian..etc</p>
         
      </div>

   </div>


   <center>
 <div class="containerevents">

   <h1>Upcoming Events</h1>

   <div class="events-grid">
       <div class="event">
           <img src="images/wine.jpg" alt="Event 1">
           <h2>Wine and Cheese Tasting</h2>
           <p>Join us for an evening of wine and cheese tasting, featuring a selection of fine wines and artisanal cheeses.</p>
           <p><strong>Date:</strong> Friday, March 19th</p>
           <p><strong>Time:</strong> 6:00 PM - 8:00 PM</p>
           <p><strong>Price:</strong> $50 per person</p>
           
       </div>
       <div class="event">
           <img src="images/night.jpeg" alt="Event 2">
           <h3>Live Music Night</h3>
           <p>Enjoy an evening of live music with our featured artist, performing a selection of jazz and blues classics.</p>
           <p><strong>Date:</strong> Saturday, March 20th</p>
           <p><strong>Time:</strong> 8:00 PM - 10:00 PM</p>
           <p><strong>Price:</strong> Free admission</p>
         
       </div>
       <div class="event">
           <img src="images/brunch.jpg" alt="Event 3">
           <h3>Brunch Buffet</h3>
           <p>Join us for our weekly brunch buffet, featuring a wide selection of breakfast and lunch items.</p>
           <p><strong>Date:</strong> Sunday, March 21st</p>
           <p><strong>Time:</strong> 10:00 AM - 2:00 PM</p>
           <p><strong>Price:</strong> $25 per person</p>
           
   </div>
</div>
</center>



<center>

<div class="container7">
   <h1>Promotions</h1>
   <div class="promotions-grid">
       <div class="promotion">
           <img src="images/happy.jpeg" alt="Promotion 1">
           <h3>Happy Hour</h3>
           <p>Enjoy 50% off all drinks and appetizers during our happy hour, every Friday from 5:00 PM - 7:00 PM.</p>
           <p><strong>Valid:</strong> Every Friday</p>
           <p><strong>Time:</strong> 5:00 PM - 7:00 PM</p>
           
       </div>
       <div class="promotion">
           <img src="images/buy.jpeg" alt="Promotion 2">
           <h3>Buy One Get One Free</h3>
           <p>Buy one entree, get one free on all weekdays, excluding holidays.</p>
           <p><strong>Valid:</strong> Weekdays, excluding holidays</p>
           <p><strong>Time:</strong> All day</p>
           
       </div>
       <div class="promotion">
           <img src="images/kids.jpeg" alt="Promotion 3">
           <h3>Kids Eat Free</h3>
           <p>Kids eat free with the purchase of an adult entree, every Sunday.</p>
           <p><strong>Valid:</strong> Every Sunday</p>
           <p><strong>Time:</strong> All day</p>
           
       </div>
       <div class="promotion">
           <img src="images/loyalty.jpeg" alt="Promotion 4">
           <h3>Loyalty Program</h3>
           <p>Earn points for every purchase and redeem them for free menu items and discounts.</p>
           <p><strong>Valid:</strong> Ongoing</p>
           <p><strong>Time:</strong> All day</p>
           
       </div>
   </div>
</div>

</center>

</section>

<!-- about section ends -->

<!-- menu section starts  -->

<section id="menu" class="menu">

   <h1 class="heading">our menu</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){    
      ?>
      <div class="box">
         <div class="price">Rs <?= $fetch_products['price'] ?>/-</div>
         <img src="uploaded_img/<?= $fetch_products['image'] ?>" alt="">
         <div class="name"><?= $fetch_products['name'] ?></div>
         <form action="" method="post">
            <input type="hidden" name="pid" value="<?= $fetch_products['id'] ?>">
            <input type="hidden" name="name" value="<?= $fetch_products['name'] ?>">
            <input type="hidden" name="price" value="<?= $fetch_products['price'] ?>">
            <input type="hidden" name="image" value="<?= $fetch_products['image'] ?>">
            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            <input type="submit" class="btn" name="add_to_cart" value="add to cart">
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- menu section ends -->

<!-- order section starts  -->

<section class="order" id="order">

   <h1 class="heading">order now</h1>

   <form action="" method="post">

   <div class="display-orders">

   <?php
         $grand_total = 0;
         $cart_item[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
              $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
              $grand_total += $sub_total; 
              $cart_item[] = $fetch_cart['name'].' ( '.$fetch_cart['price'].' x '.$fetch_cart['quantity'].' ) - ';
              $total_products = implode($cart_item);
              echo '<p>'.$fetch_cart['name'].' <span>('.$fetch_cart['price'].' x '.$fetch_cart['quantity'].')</span></p>';
            }
         }else{
            echo '<p class="empty"><span>your cart is empty!</span></p>';
         }
      ?>

   </div>

      <div class="grand-total"> grand total : <span>Rs<?= $grand_total; ?>/-</span></div>

      <input type="hidden" name="total_products" value="<?= $total_products; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" class="box" required placeholder="enter your name" maxlength="20">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" class="box" required placeholder="enter your number" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;">
           
    
    
         </div>
         <div class="inputBox">
            <span>payment method</span>
            <select name="method" class="box">
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
               <option value="paytm">paytm</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="flat" class="box" required placeholder="e.g. flat no." maxlength="50">
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street" class="box" required placeholder="e.g. street name." maxlength="50">
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" name="pin_code" class="box" required placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;">
         </div>
      </div>

      <input type="submit" value="order now" class="btn" name="order">

   </form>

</section>

<!-- order section ends -->

<!-- faq section starts  -->

<section class="faq" id="faq">

   <h1 class="heading">Customer Reviews</h1>

   <div class="accordion-container">

      <div class="accordion active">
         <div class="accordion-heading">
            <span>Sarah </span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         "Best dining experience we've had in years. The menu offers a great variety, and everything we tried was superb. We'll definitely be back!"
         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>Mark</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         "Absolutely fantastic! The food was delicious, the service was impeccable, and the atmosphere was perfect for a night out. Highly recommend!"
         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>Jorn</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         "Delightful dining experience! Fresh ingredients, creative dishes, and a beautiful setting. A must-visit restaurant in town."

         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>James</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         "Exceptional in every way! From the exquisite food to the welcoming staff, this place has it all. Perfect for any occasion."
         </p>
      </div>


      <div class="accordion">
         <div class="accordion-heading">
            <span>Olivia</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         "Amazing food and wonderful service! The ambiance made our night special. Highly recommend!"

         </p>
      </div>

   </div>

</section>


<section class="reservation" id="reservation">

<?php

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
};

?>

   <form action="reservation_process.php" method="post">
      <h3>Make a reservation</h3>
      <div class="flex">
         <div class="box">
            <p>your name <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
         </div>
         <div class="box">
            <p>your email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="input">
         </div>
         
         <div class="box">
            <p>Tables <span>*</span></p>
            <select name="tables" class="input" required>
               <option value="1" selected>1 Table</option>
               <option value="2">2 Tables </option>
               <option value="3">3 Tables</option>
               <option value="4">4 Tables</option>
               
               
            </select>
         </div>


         <div class="box">
            <p>Parking<span>*</span></p>
            <select name="parking" class="input" required>
               <option value="1" selected>Yes</option>
               <option value="2">No</option>
               
               
               
            </select>
         </div>
         <div class="box">
            <p>Date <span>*</span></p>
            <input type="date" name="date" class="input"required>
         </div>
         
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         
      </div>
      <input type="submit" value="book now" name="book" class="btn">
   </form>

</section>


<!-- faq section ends -->

<!-- footer section starts  -->

<section class="footer">

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>phone number</h3>
         <p>071 2234562</p>
         
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>our address</h3>
         <p>Cinnamon grrand path,<br>Colombo 10</p>
      </div>

      <div class="box">
         <i class="fas fa-clock"></i>
         <h3>opening hours</h3>
         <p>07:00 am to 10:00 pm</p>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>email address</h3>
         <p>dinajirajapaksha08@gmail.com</p>
         
      </div>

   </div>

   <div class="credit">
      &copy; copyright @ 2024 by <span>Dinaji Imesha</span> | all rights reserved!
   </div>

</section>

<!-- footer section ends -->



















<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>