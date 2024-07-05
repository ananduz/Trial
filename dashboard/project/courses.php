<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Uploaded Documents</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- documents section starts  -->

<section class="documents">

   <h1 class="heading">Uploaded Documents</h1>

   <div class="box-container">

      <?php
         $select_documents = $conn->prepare("SELECT * FROM `content` WHERE user_id = ? ORDER BY date DESC");
         $select_documents->execute([$user_id]);
         if($select_documents->rowCount() > 0){
            while($fetch_document = $select_documents->fetch(PDO::FETCH_ASSOC)){
               $document_id = $fetch_document['id'];
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fetch_document['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_document['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_document['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_document['date']; ?></span></div>
         </div>
         <h3 class="title"><?= $fetch_document['title']; ?></h3>
         <p><?= $fetch_document['description']; ?></p>
         <a href="view_document.php?get_id=<?= $document_id; ?>" class="inline-btn">View Document</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No documents uploaded yet!</p>';
      }
      ?>

   </div>

</section>

<!-- documents section ends -->

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
