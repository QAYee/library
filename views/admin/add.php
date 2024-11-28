<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."includes/header.php");
if(isset($_SESSION["mali"])){
    $messErr = $_SESSION["mali"];
    unset($_SESSION["mali"]);
}
if(isset($_SESSION["tama"])){
    $messSuc = $_SESSION["tama"];
    unset($_SESSION["tama"]);
}
?>
<?php
require_once(ROOT_DIR."includes/navbar.php");
?>

<!-- Page Guard -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Maintenance</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Book Maintenance Form -->
    <div class="container my-5">
        <h2>Book Maintenance</h2>
        <?php if(isset($messSuc)){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $messSuc; ?></strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <?php if(isset($messErr)){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $messErr; ?></strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <form action="<?php echo BASE_URL;?>app/books/add_books.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Left Column: Book Cover -->
                <div class="col-md-4 mb-3">
                    <label for="img_url" class="form-label">Book Cover</label>
                    <input type="file" class="form-control" id="img_url" accept="image/*" name="img_url" required>
                    <div class="mt-3">
                        <img id="imagePreview" src="" alt="Image Preview" class="img-fluid" style="display: none; max-height: 300px;">
                    </div>
                </div>

                <!-- Right Column: Book Details -->
                <div class="col-md-8">
                    <!-- Book Title -->
                    <div class="col-md-12 mb-3">
                        <label for="bookTitle" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="bookTitle" placeholder="Enter book title" name="bookTitle" required>
                    </div>

                    <!-- Author -->
                    <div class="col-md-12 mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" placeholder="Enter author name" name="author" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-12 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" class="form-select" name="category" required>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science">Science</option>
                            <option value="History">History</option>
                            <!-- Add more categories as needed -->
                        </select>
                    </div>

                    <!-- ISBN -->
                    <div class="col-md-12 mb-3">
                        <label for="ISBN" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="ISBN" placeholder="Enter ISBN" name="ISBN" required>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3" name="description" placeholder="Enter book description"></textarea>
                    </div>

                    <!-- Save Button -->
                    <div class="col-md-12 d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Save Book</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script>
        // Image Preview Functionality
        const fileInput = document.getElementById('img_url');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <!-- Bootstrap 5 JS Bundle -->
    <?php require_once(ROOT_DIR."/includes/footer.php"); ?>
</body>
</html>
