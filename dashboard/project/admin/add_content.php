<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $document = $_FILES['document']['name'];
   $document = filter_var($document, FILTER_SANITIZE_STRING);
   $document_ext = pathinfo($document, PATHINFO_EXTENSION);
   $rename_document = unique_id().'.'.$document_ext;
   $document_tmp_name = $_FILES['document']['tmp_name'];
   $document_folder = '../uploaded_files/'.$rename_document;

   if($document_size > 5000000){
      $message[] = 'Document size is too large!';
   } else{
      $add_content = $conn->prepare("INSERT INTO `content`(id, tutor_id, title, description, document, status) VALUES(?,?,?,?,?,?)");
      $add_content->execute([$id, $tutor_id, $title, $description, $rename_document, $status]);
      move_uploaded_file($document_tmp_name, $document_folder);
      $message[] = 'New document uploaded!';
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
   
<section class="video-form">

   <h1 class="heading">upload project</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Project status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>Project Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter project title" class="box">
      <p>Project Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <!-- <p>video playlist <span>*</span></p> 
      <select name="playlist" class="box" required>
         <option value="" disabled selected>--select playlist</option>
        
      </select>-->
      <p>Select document <span>*</span></p>
      <input type="file" name="document" accept=".pdf, .doc, .docx" class="box">
      <input type="submit" value="upload document" name="submit" class="btn">
</form>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>