<?php
include 'functions.php';
session_start();
if(!isset($_SESSION['UserName'])){
    header('location: login.php');
}
// Connect to MySQL
$db = mysqli_connect('localhost' , 'root' , '' , 'database') or die("Could not connect to database");
//Query command for all images for purpose of display
$query = "SELECT '*' FROM images ORDER BY uploaded_date DESC";
//Run query and fetch images
$results = mysqli_query($db, $query);
$images = mysqli_fetch_assoc($results);
?>

<?=template_header('Gallery')?>

<div class="content home">
	<h2>Gallery</h2>
	<p>Image Gallery</p>
    <!--Upload image, links to upload page-->
	<a href="upload.php" class="upload-image">Upload Image</a>
	<div class="images">
		<?php foreach ($images as $image): ?>
        <!--Setting up display of images that were fetched from database-->
		<?php if (file_exists($image['path'])): ?>
		<a href="#">
			<img src="<?=$image['path']?>" alt="<?=$image['description']?>" data-id="<?=$image['id']?>" data-title="<?=$image['title']?>" width="300" height="200">
			<span><?=$image['description']?></span>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
<div class="image-popup"></div>
<script>
//Popup for image access
let image_popup = document.querySelector('.image-popup');
document.querySelectorAll('.images a').forEach(img_link => {
	img_link.onclick = e => {
		e.preventDefault();
		let img_meta = img_link.querySelector('img');
		let img = new Image();
		img.onload = () => {
			//Create the pop out image
			image_popup.innerHTML = `
				<div class="con">
					<h3>${img_meta.dataset.title}</h3>
					<p>${img_meta.alt}</p>
					<img src="${img.src}" width="${img.width}" height="${img.height}">
				</div>
			`;
			image_popup.style.display = 'flex';
		};
		img.src = img_meta.src;
	};
});
//Hide the popup on click out
image_popup.onclick = e => {
	if (e.target.className == 'image-popup') {
		image_popup.style.display = "none";
	}
};
</script>
<?php 
mysqli_close($db);
?>
<?=template_footer()?>