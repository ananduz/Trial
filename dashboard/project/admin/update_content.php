<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
} else {
   $get_id = '';
   header('location:dashboard.php');
}

if(isset($_POST['update'])){

   $document_id = $_POST['document_id'];
   $document_id = filter_var($document_id, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $update_content = $conn->prepare("UPDATE `content` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_content->execute([$title, $description, $status, $document_id]);

   $message[] = 'content updated!';
}

if(isset($_POST['delete_document'])){

   $delete_id = $_POST['document_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $delete_document = $conn->prepare("SELECT document FROM `content` WHERE id = ? LIMIT 1");
   $delete_document->execute([$delete_id]);
   $fetch_document = $delete_document->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_document['document']);

   $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
   $delete_likes->execute([$delete_id]);
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
   $delete_comments->execute([$delete_id]);

   $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
   $delete_content->execute([$delete_id]);
   header('location:contents.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update document</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">Update Project</h1>

   <?php
      $select_documents = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_documents->execute([$get_id, $tutor_id]);
      if($select_documents->rowCount() > 0){
         while($fecth_documents = $select_documents->fetch(PDO::FETCH_ASSOC)){ 
            $document_id = $fecth_documents['id'];
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="document_id" value="<?= $fecth_documents['id']; ?>">
      <p>update status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fecth_documents['status']; ?>" selected><?= $fecth_documents['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>update title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter document title" class="box" value="<?= $fecth_documents['title']; ?>">
      <p>update description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fecth_documents['description']; ?></textarea>
      <p>update document</p>
      <input type="file" name="document" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="box">
      <input type="submit" value="update project" name="update" class="btn">
      <div class="flex-btn">
         <a href="view_content.php?get_id=<?= $document_id; ?>" class="option-btn">View Project</a>
         <input type="submit" value="delete project" name="delete_document" class="delete-btn">
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Document not found! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add documents</a></p>';
      }
   ?>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
