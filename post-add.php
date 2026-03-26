<?php
session_start();

$logged = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $logged = true;
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php?error=Please log in to create a blog post');
    exit;
}

include_once('admin/data/Post.php');
include_once('db_conn.php');
$categories = getAllCategories($conn);
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Create Blog</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM' crossorigin='anonymous'>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel='stylesheet' href='css/style.css'>
    <link rel='stylesheet' href='css/richtext.min.css'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='js/jquery.richtext.min.js'></script>
</head>

<body>
    <?php include 'inc/NavBar.php';
    ?>

    <div class='container mt-5'>
        <h3>Create New Blog</h3>
        <?php if (isset($_GET['error'])) {
        ?>
            <div class='alert alert-warning'>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php }
        ?>
        <?php if (isset($_GET['success'])) {
        ?>
            <div class='alert alert-success'>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php }
        ?>

        <form action='req/post-create.php' method='post' enctype='multipart/form-data' class='shadow p-4 bg-white rounded'>
            <div class='mb-3'>
                <label class='form-label'>Title</label>
                <input type='text' class='form-control' name='title' required>
            </div>
            <div class='mb-3'>
                <label class='form-label'>Cover Image</label>
                <input type='file' class='form-control' name='cover'>
            </div>
            <div class='mb-3'>
                <label class='form-label'>Content</label>
                <textarea class='form-control text' name='text' rows='8' required></textarea>
            </div>
            <div class='mb-3'>
                <label class='form-label'>Category</label>
                <select name="category" class="form-control">
    			<option value="">Select Category</option>
    			<?php if (!empty($categories)) { ?>
        			<?php foreach ($categories as $category) { ?>
						<option value="<?php echo $category['id']; ?>">
							<?php echo htmlspecialchars($category['category']); ?>
						</option>
        			<?php } ?>
    			<?php } else { ?>
        			<option disabled>No categories found</option>
    			<?php } ?>
			</select>
            </div>
            <button type='submit' class='btn btn-primary'>Create</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.text').richText();
        });
    </script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' integrity='sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz' crossorigin='anonymous'></script>
</body>

</html>