<?php
require('db/config.php');

$product = [];
$product_images = [];
$reviews = [];
$recommended_products = [];

// Check if 'id' is set and numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);

    // Fetch product details
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Fetch product images
        $query_images = "SELECT image_url FROM product_images WHERE product_id = '$id'";
        $result_images = mysqli_query($con, $query_images);

        if ($result_images && mysqli_num_rows($result_images) > 0) {
            while ($row = mysqli_fetch_assoc($result_images)) {
                $product_images[] = $row['image_url'];
            }
        }

        // Fetch reviews
        $reviews_sql = "SELECT product_reviews.*, users.username 
                        FROM product_reviews 
                        INNER JOIN users ON product_reviews.user_id = users.id 
                        WHERE product_reviews.product_id = '$id'";
        $reviews_result = mysqli_query($con, $reviews_sql);

        if ($reviews_result && mysqli_num_rows($reviews_result) > 0) {
            while ($row = mysqli_fetch_assoc($reviews_result)) {
                $reviews[] = $row;
            }
        }

        // Fetch recommended products
        if (!empty($product['category'])) {
            $category = mysqli_real_escape_string($con, $product['category']);
            $current_product_id = $product['id'];
            $query_recommended = "SELECT * FROM products WHERE category = '$category' AND id != '$current_product_id' LIMIT 4";
            $result_recommended = mysqli_query($con, $query_recommended);
            
            if ($result_recommended && mysqli_num_rows($result_recommended) > 0) {
                while ($row = mysqli_fetch_assoc($result_recommended)) {
                    $recommended_products[] = $row;
                }
            }
        }

    } else {
        echo '<p class="alert alert-warning">Product not found.</p>';
    }
} else {
    echo '<p class="alert alert-danger">Invalid product ID.</p>';
}

    // Check if user is logged in
    $user_logged_in = isset($_SESSION['user_id']);
    $user_id = $user_logged_in ? $_SESSION['user_id'] : null;

    // Calculate average rating
    $average_rating = 0;
    $total_ratings = count($reviews);
    if ($total_ratings > 0) {
        $sum_ratings = array_sum(array_column($reviews, 'rating'));
        $average_rating = round($sum_ratings / $total_ratings, 1);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($product['name']); ?> - Shop | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="product_description.css">
</head>
<body>

    <?php if (!empty($product)): ?>
        <div class="container mt-4">
            <!-- Product details -->
            <div class="product-container">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb pr-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                        <li class="breadcrumb-item"><a href="shop.php?search=&sort_by=name_asc&category=<?php echo urlencode($product['category']); ?>"><?php echo htmlspecialchars($product['category']); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
                    </ol>
                </nav>
                <div class="row">
                    <!-- Product image column -->
                    <div class="col-md-6">
                        <!-- Main product image with zoom -->
                        <div class="zoomable-image-container">
                            <img id="main-product-image" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image img-fluid">
                            <div class="zoomed-image-container"></div>
                        </div>
                        <!-- Additional product images -->
                        <div class="product-images mt-3">
                            <!-- Main product image (small version) -->
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-small" onclick="changeMainImage(this)">
                            <!-- Additional product images -->
                            <?php foreach ($product_images as $image_url): ?>
                                <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-small" onclick="changeMainImage(this)">
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Product details column -->
                    <div class="col-md-6 product-details">
                        <div class="inline-block text-white py-2 px-6 rounded-lg mb-4" style="background-color: #E91E63;">
                            <p class="text-1xl" style="text-transform: uppercase;"><?php echo htmlspecialchars($product['category']); ?></p>
                        </div>
                        <h2><?php echo htmlspecialchars($product['name']); ?></h2>

                        <p><?php echo htmlspecialchars($product['description']); ?></p>

                        <p class="price">RM<?php echo number_format($product['price'], 2); ?></p>
                        <div class="ratings">
                            <?php
                            $ratings = $product['ratings'];
                            $fullStars = floor($ratings);
                            $halfStar = $ratings - $fullStars;
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $fullStars) {
                                    echo '<i class="fa fa-star checked"></i>';  
                                } elseif ($i == $fullStars + 1 && $halfStar >= 0.5) {
                                    echo '<i class="fa fa-star-half-o checked"></i>';  
                                } else {
                                    echo '<i class="fa fa-star"></i>';  
                                }
                            }
                            echo '<span class="rating-value">(' . number_format($ratings, 1) . ')</span>';
                            ?>
                        </div>
                        <div class="add-to-cart-container">
                            <div class="quantity-input">
                                <button class="quantity-btn minus">-</button>
                                <input type="number" id="quantity" name="quantity" value="0" min="0">
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="add-to-cart" id="add-to-cart" disabled>Add to cart</button>
                        </div>
                        <div id="stock-message" class="mt-2 text-s text-green-500"></div>
                    </div>
                </div>

                <!-- Review section -->
                <div class="review-section">
                    <h3 class="mt-5 mb-4">Customer Reviews</h3>

                    <?php if (empty($reviews)) : ?>
                        <div class="no-review-img">
                            <img src="images/no-review.gif" alt="no review" />
                        </div>
                    <?php endif; ?>

                    <?php foreach ($reviews as $review) : ?>
                        <div class="review-item mb-4">
                            <div class="review-rating">
                                <?php
                                $reviewRating = $review['rating'];
                                for ($i = 1; $i <= 5; $i++) {
                                    $starClass = ($i <= $reviewRating) ? 'fa fa-star checked' : 'fa fa-star';
                                    echo '<i class="' . $starClass . '"></i>';
                                }
                                ?>
                            </div>
                            <p class="review-text">
                                <strong class="review-title"><?php echo htmlspecialchars($review['title']); ?></strong><br><br>
                                <strong class="review-username"><?php echo htmlspecialchars($review['username']); ?></strong><br>
                                "<?php echo htmlspecialchars($review['comment']); ?>"
                            </p>
                        </div>
                    <?php endforeach; ?>

                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <div class="review-form mt-5">
                            <h5>Add Your Review</h5>
                            <form action="submit_review.php" method="post">
                                <div class="mb-3">
                                    <label for="reviewRating" class="form-label">Rating:</label>
                                    <div class="rating-stars" id="ratingStars">
                                        <i class="fa fa-star" data-rating="1"></i>
                                        <i class="fa fa-star" data-rating="2"></i>
                                        <i class="fa fa-star" data-rating="3"></i>
                                        <i class="fa fa-star" data-rating="4"></i>
                                        <i class="fa fa-star" data-rating="5"></i>
                                    </div>
                                    <input type="hidden" name="reviewRating" id="reviewRating" value="5">  
                                </div>
                                <div class="mb-3">
                                    <label for="reviewTitle" class="form-label">Review Title:</label>
                                    <input type="text" class="form-title" id="reviewTitle" name="reviewTitle" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reviewText" class="form-label">Your Review:</label>
                                    <textarea class="form-control" id="reviewText" name="reviewText" rows="3" required></textarea>
                                </div>
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    <?php else : ?>
                        <p class="mt-4"><a href="login.php" style="color:#E91E63;">Log in</a> to submit a review.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Horizontal line -->
            <hr class="my-5">

            <div class="product-container">
                <!-- Recommended products -->
                <?php if (!empty($recommended_products)): ?>
                    <div class="recommended-products">
                        <h3 class="mb-4">You may also like</h3>
                        <div class="row">
                            <?php foreach ($recommended_products as $rec_product): ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <img src="<?php echo htmlspecialchars($rec_product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($rec_product['name']); ?>">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?php echo htmlspecialchars($rec_product['name']); ?></h5>
                                            <p class="card-text">RM<?php echo number_format($rec_product['price'], 2); ?></p>
                                            <a href="product_description.php?id=<?php echo $rec_product['id']; ?>" class="btn btn-primary mt-auto">View Product</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Popup message container -->
    <div id="popup-message" class="popup-message"></div>


    <?php include 'inc/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainProductImage = document.getElementById('main-product-image');
            const productImages = document.querySelectorAll('.product-image-small');
            const zoomableImageContainer = document.querySelector('.zoomable-image-container');
            const zoomedImageContainer = document.querySelector('.zoomed-image-container');
            // Function to change main product image on click of additional images
            productImages.forEach(function(image) {
                image.addEventListener('click', function() {
                    mainProductImage.src = image.src;
                });
            });

            // Zoom functionality
            let isZoomed = false;

            zoomableImageContainer.addEventListener('click', function(e) {
                if (!isZoomed) {
                    zoomedImageContainer.style.backgroundImage = `url(${mainProductImage.src})`;
                    zoomedImageContainer.style.display = 'block';
                    updateZoom(e);
                    isZoomed = true;
                } else {
                    zoomedImageContainer.style.display = 'none';
                    isZoomed = false;
                }
            });

            zoomableImageContainer.addEventListener('mousemove', function(e) {
                if (isZoomed) {
                    updateZoom(e);
                }
            });

            function updateZoom(e) {
                const zoomer = e.currentTarget;
                const offsetX = e.offsetX;
                const offsetY = e.offsetY;
                const x = offsetX / zoomer.offsetWidth * 100;
                const y = offsetY / zoomer.offsetHeight * 100;
                zoomedImageContainer.style.backgroundPosition = x + '% ' + y + '%';
                zoomedImageContainer.style.backgroundSize = '250%';
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const reviewForm = document.getElementById('review-form');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(reviewForm);
                    
                    fetch('submit_review.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Review submitted successfully!');
                            location.reload();  
                        } else {
                            alert('Error submitting review: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while submitting the review.');
                    });
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.querySelectorAll(".rating-stars i");

            stars.forEach(function(star) {
                star.addEventListener("click", function() {
                    const rating = this.getAttribute("data-rating");
                    document.getElementById("reviewRating").value = rating;

                    stars.forEach(function(s) {
                        s.classList.remove("checked");
                    });

                    for (let i = 0; i < rating; i++) {
                        stars[i].classList.add("checked");
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const minusBtn = document.querySelector('.minus');
            const plusBtn = document.querySelector('.plus');
            const quantityInput = document.getElementById('quantity');
            const addToCartBtn = document.querySelector('.add-to-cart');            
            const popupMessage = document.getElementById('popup-message');


            minusBtn.addEventListener('click', () => {
                let value = parseInt(quantityInput.value);
                if (value > 0) {
                    quantityInput.value = value - 1;
                }
            });

            plusBtn.addEventListener('click', () => {
                let value = parseInt(quantityInput.value);
                quantityInput.value = value + 1;
            });

            function showPopupMessage(message) {
                popupMessage.textContent = message;
                popupMessage.style.display = 'block';
                setTimeout(() => {
                    popupMessage.style.display = 'none';
                }, 3000); // Hide the popup after 3 seconds
            }

            addToCartBtn.addEventListener('click', () => {
                const quantity = parseInt(quantityInput.value);
                if (quantity > 0) {
                    // Send an AJAX request to add the item to the cart
                    fetch('add_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: <?php echo $product['id']; ?>,
                            quantity: quantity
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showPopupMessage(`Added ${quantity} item(s) to cart`);
                            updateCartIcon(data.cartTotal);
                        } else {
                            showPopupMessage('Error adding item to cart');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        showPopupMessage('An error occurred while adding to cart');
                    });
                } else {
                    showPopupMessage('Please select at least one item');
                }
            });

            function updateCartIcon(cartTotal) {
                const cartIcon = document.querySelector('.fa-shopping-cart');
                if (cartIcon) {
                    cartIcon.setAttribute('data-count', cartTotal);
                }
            }
        });
    </script>

<script>
const productId = <?php echo json_encode($id); ?>;
document.addEventListener('DOMContentLoaded', function () {
            const plusButton = document.querySelector('.quantity-btn.plus');
            const minusButton = document.querySelector('.quantity-btn.minus');
            const quantityInput = document.getElementById('quantity');
            const stockMessage = document.getElementById('stock-message');
            const addToCartButton = document.getElementById('add-to-cart');

            function checkStockAvailability(quantity) {
                fetch(`check_stock.php?id=${productId}&quantity=${quantity}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            stockMessage.innerHTML = 'Stock Availability: <i class="fa-solid fa-circle-check"></i>';
                            addToCartButton.disabled = false;                            
                            stockMessage.classList.add("text-green-500");
                            stockMessage.classList.remove("text-red-500");
                        } else {
                            stockMessage.textContent = 'Out of Stock';
                            stockMessage.classList.remove("text-green-500");
                            stockMessage.classList.add("text-red-500");
                            addToCartButton.disabled = true;
                        }
                    });
            }

            // Check stock availability on page load
            checkStockAvailability(quantityInput.value);

            // Check stock availability on plus button click
            plusButton.addEventListener('click', function () {
                const quantity = parseInt(quantityInput.value);
                checkStockAvailability(quantity);
            });

            // Check stock availability on minus button click
            minusButton.addEventListener('click', function () {
                let quantity = parseInt(quantityInput.value);
                if (quantity > 0) {
                    quantityInput.value = quantity;
                    checkStockAvailability(quantity);
                }
            });

            // Check stock availability on quantity input change
            quantityInput.addEventListener('input', function () {
                const quantity = parseInt(quantityInput.value);
                checkStockAvailability(quantity);
            });
        });
</script>

</body>
</html>
