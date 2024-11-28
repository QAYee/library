<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once("includes\header.php");
session_start();
?>
    <!-- Navbar -->
    <?php
require_once("includes\\navbar.php");
?>


   

    <!-- Search Section -->
    <section class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search for books..." aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Book Categories -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center">Book Categories</h2>
            <div class="row text-center mt-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Fiction</h5>
                            <p class="card-text">Explore our collection of fiction books.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Non-Fiction</h5>
                            <p class="card-text">Discover educational and insightful reads.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Science</h5>
                            <p class="card-text">Dive into the world of science and research.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">History</h5>
                            <p class="card-text">Learn about past events and historical figures.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Books -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center">Popular Books</h2>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 1</h5>
                            <p class="card-text">Short description of the book.</p>
                            <a href="#" class="btn btn-primary">Borrow</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 2</h5>
                            <p class="card-text">Short description of the book.</p>
                            <a href="#" class="btn btn-primary">Borrow</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 3</h5>
                            <p class="card-text">Short description of the book.</p>
                            <a href="#" class="btn btn-primary">Borrow</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Book Image">
                        <div class="card-body">
                            <h5 class="card-title">Book Title 4</h5>
                            <p class="card-text">Short description of the book.</p>
                            <a href="#" class="btn btn-primary">Borrow</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once(ROOT_DIR."/includes/footer.php")?>