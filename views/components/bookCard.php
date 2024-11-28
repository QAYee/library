<div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo BASE_URL. $books["img_url"]; ?>" class="card-img-top mx-auto" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $books["bookTitle"]?></h5>
                        <p class="card-text">Description <?php echo $books["description"]?></p>
                        <p class="card-text">Author: <?php echo $books["author"]?></p>
                        <p class="card-text">Category: <?php echo $books["category"]?></p>
                        <p class="card-text">ISBN: <?php echo $books["ISBN"]?></p>
                        <a  href="<?php echo BASE_URL;?>views/admin/products/edit.php?id=<?php echo $books["id"]; ?>" class="btn btn-primary">Edit Product</a>   
                        
                        <form action="<?php echo BASE_URL;?>app/product/delete_product.php?" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $books["id"]; ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete Product</a>
                        </form> 
                        
                    </div>
                </div>
            </div>          