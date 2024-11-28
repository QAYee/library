<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once("includes\header.php");
session_start();
?>

    <!-- Navbar -->
    <?php
require_once("includes\\navbar.php");
?>


    <!-- Header Section -->
    <header class="bg-light py-5">
        <div class="container text-center">
            <h1>Fiction Books</h1>
            <p class="lead">Browse our selection of fiction books</p>
        </div>
    </header>

    <!-- Books Grid -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Book 1 -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x400" class="card-img-top" alt="Book Cover">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 1</h5>
                            <p class="card-text">Author: John Doe</p>
                            <p class="card-text">A brief description of the book goes here, summarizing the storyline or key points.</p>
                            <a href="book-details.html" class="btn btn-primary">Read More</a>
                            <a href="#" class="btn btn-success">Borrow</a>
                        </div>
                    </div>
                </div>

                <!-- Book 2 -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x400" class="card-img-top" alt="Book Cover">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 2</h5>
                            <p class="card-text">Author: Jane Smith</p>
                            <p class="card-text">A short synopsis of the book, providing readers with an enticing look into the story.</p>
                            <a href="book-details.html" class="btn btn-primary">Read More</a>
                            <a href="#" class="btn btn-success">Borrow</a>
                        </div>
                    </div>
                </div>

                <!-- Book 3 -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x400" class="card-img-top" alt="Book Cover">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 3</h5>
                            <p class="card-text">Author: Michael Lee</p>
                            <p class="card-text">A quick overview of the book, touching on themes or plot points that engage readers.</p>
                            <a href="book-details.html" class="btn btn-primary">Read More</a>
                            <a href="#" class="btn btn-success">Borrow</a>
                        </div>
                    </div>
                </div>

                <!-- Additional books... -->
            </div>
        </div>
    </section>

    <?php require_once("includes\\footer.php");?>