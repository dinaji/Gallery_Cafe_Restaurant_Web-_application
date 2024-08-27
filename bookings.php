<?php

include 'config.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:staff_login.php');
}

if(isset($_POST['delete'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `bookings` WHERE id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){
      $delete_bookings = $conn->prepare("DELETE FROM `bookings` WHERE id = ?");
      $delete_bookings->execute([$delete_id]);
      $success_msg[] = 'Booking deleted!';
   }else{
      $warning_msg[] = 'Booking deleted already!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookings</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="staff_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'staff_header.php'; ?>
<!-- header section ends -->

<!-- bookings section starts  -->

<section class="grid">

   <h1 class="heading">bookings</h1>

   <div class="box-container">

   <?php
      $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
      $select_bookings->execute();
      if($select_bookings->rowCount() > 0){
         while($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      
      <p>your name: <span><?= $fetch_bookings['name']; ?></span></p>
      <p>your email  : <span><?= $fetch_bookings['email']; ?></span></p>
      <p>Tables : <span><?= $fetch_bookings['tables']; ?></span></p>
      <p>Parking : <span><?= $fetch_bookings['parking']; ?></span></p>
      <p>Date : <span><?= $fetch_bookings['date']; ?></span></p>
      <p>adults : <span><?= $fetch_bookings['adults']; ?></span></p>
      
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_bookings['id']; ?>">
         <input type="submit" value="delete booking" onclick="return confirm('delete this booking?');" name="delete" class="btn">
      </form>
   </div>
   <?php
      }
   }else{
   ?>
   <div class="box" style="text-align: center;">
      <p>no bookings found!</p>
      <a href="staff_dashboard.php" class="btn">go to home</a>
   </div>
   <?php
      }
   ?>

   </div>

</section>

<!-- bookings section ends -->
















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>



</body>
</html>