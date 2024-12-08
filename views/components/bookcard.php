<div class="book-card">
            <img src="<?php echo $bookImage; ?>" alt="Book Image">
            <h3 class="book-title"><?php echo $bookTitle; ?></h3>
            <p><strong>Description:</strong> <?php echo $bookDescription; ?></p>
            <p><strong>Author:</strong> <?php echo $bookAuthor; ?></p>
            <p><strong>Category:</strong> <?php echo $bookCategory; ?></p>
            <p><strong>ISBN:</strong> <?php echo $bookIsbn; ?></p>
            <p><strong>Copies Available:</strong> <?php echo $bookCopies; ?></p>
            <p><strong>Total Borrows:</strong> <?php echo $bookTotalBorrows; ?></p>
            <div class="btn-container">
                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-delete">Delete</a>
                <a href="book_history.php?id=<?php echo $book['id']; ?>" class="btn btn-history">History</a>
            </div>
        </div>