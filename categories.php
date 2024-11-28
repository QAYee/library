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
            <h1>Book Categories</h1>
            <p class="lead">Browse books by category and explore new genres</p>
        </div>
    </header>

    <!-- Book Categories Grid -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Fiction Category -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Fiction">
                        <div class="card-body text-center">
                            <h5 class="card-title">Fiction</h5>
                            <p class="card-text">Dive into worlds of imagination and creativity.</p>
                            <a href="fiction.html" class="btn btn-primary">Explore Fiction</a>
                        </div>
                    </div>
                </div>

                <!-- Non-Fiction Category -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Non-Fiction">
                        <div class="card-body text-center">
                            <h5 class="card-title">Non-Fiction</h5>
                            <p class="card-text">Learn from real-life stories and factual content.</p>
                            <a href="non-fiction.html" class="btn btn-primary">Explore Non-Fiction</a>
                        </div>
                    </div>
                </div>

                <!-- Science Category -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Science">
                        <div class="card-body text-center">
                            <h5 class="card-title">Science</h5>
                            <p class="card-text">Discover the wonders of science and research.</p>
                            <a href="science.html" class="btn btn-primary">Explore Science</a>
                        </div>
                    </div>
                </div>

                <!-- History Category -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="History">
                        <div class="card-body text-center">
                            <h5 class="card-title">History</h5>
                            <p class="card-text">Explore past events and historical insights.</p>
                            <a href="history.html" class="btn btn-primary">Explore History</a>
                        </div>
                    </div>
                </div>

                <!-- Art Category -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Art">
                        <div class="card-body text-center">
                            <h5 class="card-title">Art</h5>
                            <p class="card-text">Admire creativity in art, photography, and design.</p>
                            <a href="art.html" class="btn btn-primary">Explore Art</a>
                        </div>
                    </div>
                </div>

                <!-- Technology Category -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Technology">
                        <div class="card-body text-center">
                            <h5 class="card-title">Technology</h5>
                            <p class="card-text">Stay updated with the latest in technology.</p>
                            <a href="technology.html" class="btn btn-primary">Explore Technology</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once("includes\\footer.php");?>