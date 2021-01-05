<?php
include 'functions.php';
session_start();
if(!isset($_SESSION['UserName'])){
    header('location: login.php');
}
$db = mysqli_connect('localhost' , 'root' , '' , 'database') or die("Could not connect to database");
//Creating variable for message outputs
$msg = '';
//Image check
if (isset($_FILES['image'], $_POST['title'], $_POST['description'])) {
	//Image directory for storage
	$target_dir = 'images/';
	//Directory path for uploaded image
	$image_path = $target_dir . basename($_FILES['image']['name']);
	//Check image for errors
	if (!empty($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name'])) {
		if (file_exists($image_path)) {
			$msg = 'Error: Image already exists.';
		} else {
			move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
			//Insert image info into the database table
			$t = $_POST['title'];
			$d = $_POST['description'];
			$query = "INSERT INTO images VALUES (NULL, '$t', '$d', '$imagepath', CURRENT_TIMESTAMP)";
			mysqli_query($db, $query);
			$msg = 'Image uploaded successfully.';
		}
	} else {
		//Image file error
		$msg = 'Please upload an image!';
	}
}
mysqli_close($db);
?>

<?=template_header('Upload Image')?>
<div class="content upload">
	<h2>Upload Image</h2>
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<label for="image">Choose Image</label>
		<input type="file" name="image" accept="image/*" id="image">
		<label for="title">Title</label>
		<input type="text" name="title" id="title">
		<label for="description">Description</label>
		<textarea name="description" id="description"></textarea>
	    <input type="submit" value="Upload Image" name="submit">
	</form>
	<p><?=$msg?></p>
</div>
<?=template_footer()?>