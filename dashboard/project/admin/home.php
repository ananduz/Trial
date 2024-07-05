<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Fetch total comments
$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

// Fetch uploaded documents
$select_documents = $conn->prepare("SELECT * FROM `content` WHERE user_id = ?");
$select_documents->execute([$user_id]);
$total_documents = $select_documents->rowCount();

// Fetch student reviews
$select_reviews = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_reviews->execute([$user_id]);
$total_reviews = $select_reviews->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- User dashboard section starts  -->

<section class="user-dashboard">

   <h1 class="heading">Your Dashboard</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Comments</h3>
         <p>Total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">View Comments</a>
      </div>

      <div class="box">
         <h3 class="title">Uploaded Documents</h3>
         <p>Total documents : <span><?= $total_documents; ?></span></p>
         <a href="content.php" class="inline-btn">View Documents</a>
      </div>

      <div class="box">
         <h3 class="title">Student Reviews</h3>
         <p>Total reviews : <span><?= $total_reviews; ?></span></p>
         <a href="comments.php" class="inline-btn">View Reviews</a>
      </div>

      <?php
         } else { 
      ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">Please login or register</h3>
         <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
         </div>
      </div>
      <?php
      }
      ?>

   </div>

</section>

<!-- User dashboard section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
