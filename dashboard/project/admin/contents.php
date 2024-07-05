<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['delete_document'])){
   $delete_id = $_POST['document_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_document = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_document->execute([$delete_id]);
   if($verify_document->rowCount() > 0){
      $delete_document = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_document->execute([$delete_id]);
      $fetch_document = $delete_document->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_document['document']);

      // Delete likes and comments associated with the document
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);

      $delete_documents = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_documents->execute([$delete_id]);
      $message[] = 'Document and associated data deleted!';
   } else {
      $message[] = 'Document already deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="contents">

   <h1 class="heading">your project</h1>

   <div class="box-container">

      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">Create new Project</h3>
         <a href="add_content.php" class="btn">Add Project</a>
      </div>

      <?php
      $select_documents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? ORDER BY date DESC");
      $select_documents->execute([$tutor_id]);
      if($select_documents->rowCount() > 0){
         while($fecth_documents = $select_documents->fetch(PDO::FETCH_ASSOC)){ 
            $document_id = $fecth_documents['id'];
      ?>
         <div class="box">
            <div class="flex">
               <div><i class="fas fa-dot-circle" style="<?php if($fecth_documents['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_documents['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_documents['status']; ?></span></div>
               <div><i class="fas fa-calendar"></i><span><?= $fecth_documents['date']; ?></span></div>
            </div>
            <h3 class="title"><?= $fecth_documents['title']; ?></h3>
            <form action="" method="post" class="flex-btn">
               <input type="hidden" name="document_id" value="<?= $document_id; ?>">
               <a href="update_content.php?get_id=<?= $document_id; ?>" class="option-btn">Update</a>
               <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this document?');" name="delete_document">
            </form>
            <a href="view_content.php?get_id=<?= $document_id; ?>" class="btn">View Project</a>

            

         </div>
      <?php
         }
      } else {
         echo '<p class="empty">no contents added yet!</p>';
      }
      ?>

   </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>