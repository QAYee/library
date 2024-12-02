<?php

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");

require_once(ROOT_DIR."includes\header.php");

if (isset($_SESSION["mali"])) {
    $messErr = $_SESSION["mali"];
    unset($_SESSION["mali"]);
}

if (isset($_SESSION["tama"])) {
    $messSuc = $_SESSION["tama"];
    unset($_SESSION["tama"]);
}

include(ROOT_DIR. "app/books/getBook.php");

?>
<?php
require_once(ROOT_DIR."includes/navbar.php");
?>
<body>

    <!-- Page Header -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Book List</h2>
            <!-- Add New Book Button -->
            <a href="<?php echo BASE_URL; ?>views/admin/add.php" class="btn btn-success">Add New Book</a>
        </div>

        <!-- Success Message (Conditional) -->
        <?php if (isset($messSuc)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $messSuc; ?></strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Error Message (Conditional) -->
        <?php if (isset($messErr)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $messErr; ?></strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <p class="text-center">Manage all books in the catalog</p>
        <hr>
    </div>

    <!-- Book Cards Container -->
    <div class="container content mt-3">
        <div class="row">
            <!-- Loop through each book and include the book card -->
            <?php 
            foreach ($bookList as $books) {
                include(ROOT_DIR."views/components/bookCard.php"); 
            }
            ?>  
        </div>
    </div>

    <?php require_once(ROOT_DIR."/includes/footer.php")?>
