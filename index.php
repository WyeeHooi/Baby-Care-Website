<?php
session_start();
require_once('db/config.php');

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $user_id = $_COOKIE['remember_me'];

    $stmt = $con->prepare("SELECT id, username FROM users WHERE id = ?");
    
    if (!$stmt) {
        die('Error in prepare statement: ' . $con->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username);
        $stmt->fetch();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="index.css">
</head>
<body class="bg-white"> 

<main class="container mx-auto max-w-7xl mt-2">
    <!-- Hero section after the menu -->
    <section class="relative rounded-lg mb-5 custom-height swiper-container">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="absolute inset-0 bg-cover bg-center rounded-lg" style="background-image: url('images/header3.avif');"></div>
                <div class="custom-height bg-opacity-60 bg-pink-200 rounded-lg p-8 flex flex-col justify-center items-start relative z-10">
                    <div class="mx-12 text-black">
                        <div class="inline-block text-white py-2 px-6 rounded-lg mb-10" style="background-color: #E91E63;"> 
                            <p class="text-1xl" style="text-transform: uppercase;">Welcome</p>
                        </div>
                        <h1 class="text-5xl font-bold mb-5" style="line-height: 1.2;">The Better For <br>Your Newborn</h1>
                        <p class="text-lg mb-10">CuddleBug: Your go-to for cozy baby essentials, <br>ensuring comfort and care at every cuddle.</p>
                        <a href="#" class="inline-block bg-gray-800 hover:bg-pink-600 text-white py-3 px-10 rounded-full">Shop Now</a>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="absolute inset-0 bg-cover bg-center rounded-lg" style="background-image: url('images/header7.jpg');"></div>
                <div class="custom-height bg-opacity-60 bg-pink-200 rounded-lg p-8 flex flex-col justify-center items-start relative z-10">
                    <div class="mx-12 text-black">
                        <div class="inline-block text-white py-2 px-6 rounded-lg mb-10" style="background-color: #E91E63;"> 
                            <p class="text-1xl" style="text-transform: uppercase;">New Arrivals</p>
                        </div>
                        <h1 class="text-5xl font-bold mb-5" style="line-height: 1.2;">Comfort <br> Meets Style</h1>
                        <p class="text-lg mb-10">Discover our latest collection of adorable <br>and practical baby clothing.</p>
                        <a href="#" class="inline-block bg-gray-800 hover:bg-pink-600 text-white py-3 px-10 rounded-full">View Collection</a>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <div class="absolute inset-0 bg-cover bg-center rounded-lg" style="background-image: url('images/header8.jpg');"></div>
                <div class="custom-height bg-opacity-60 bg-pink-200 rounded-lg p-8 flex flex-col justify-center items-start relative z-10">
                    <div class="mx-12 text-black">
                        <div class="inline-block text-white py-2 px-6 rounded-lg mb-10" style="background-color: #E91E63;"> 
                            <p class="text-1xl" style="text-transform: uppercase;">Special Offer</p>
                        </div>
                        <h1 class="text-5xl font-bold mb-5" style="line-height: 1.2;">Explore <br>Baby Care</h1>
                        <p class="text-lg mb-10">Discover our wide range of baby care essentials, including safe <br>sleep solutions, feeding essentials, skincare, and more with 30% off.</p>
                        <a href="#" class="inline-block bg-gray-800 hover:bg-pink-600 text-white py-3 px-10 rounded-full">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add pagination -->
        <div class="swiper-pagination"></div>
    </section>

    <div class="container mx-auto mt-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Diapers and Wipes -->
            <div class="relative rounded-lg overflow-hidden bg-gray-600"> 
                <img src="images/diapers.jpg" alt="Diapers and Wipes" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-blue-200 bg-opacity-50 flex flex-col justify-between p-4">
                    <p class="text-black text-sm font-semibold">CATEGORIES</p>
                    <div>
                        <h3 class="text-black text-xl font-semibold mb-2">Diapers and Wipes</h3>
                        <a href="shop.php?search=&sort_by=name_asc&category=Diapers+and+Wipes" class="bg-black text-white hover:bg-pink-600 py-2 px-4 rounded-full text-xs font-semibold">Go To Categories</a>
                    </div>
                </div>
            </div>

            <!-- Baby Food and Feeding -->
            <div class="relative rounded-lg overflow-hidden bg-gray-600">
                <img src="images/babyfood.jpg" alt="Baby Food and Feeding" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-green-200 bg-opacity-50 flex flex-col justify-between p-4">
                    <p class="text-black text-sm font-semibold">CATEGORIES</p>
                    <div>
                        <h3 class="text-black text-xl font-semibold mb-2">Baby Food and Feeding</h3>
                        <a href="shop.php?search=&sort_by=name_asc&category=Baby+Food+and+Feeding" class="bg-black text-white hover:bg-pink-600 py-2 px-4 rounded-full text-xs font-semibold">Go To Categories</a>
                    </div>
                </div>
            </div>

            <!-- Skincare and Bathing -->
            <div class="relative rounded-lg overflow-hidden bg-gray-600">
                <img src="images/skincare.webp" alt="Skincare and Bathing" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-yellow-200 bg-opacity-50 flex flex-col justify-between p-4">
                    <p class="text-black text-sm font-semibold">CATEGORIES</p>
                    <div>
                        <h3 class="text-black text-lg font-semibold mb-2">Skincare and Bathing</h3>
                        <a href="shop.php?search=&sort_by=name_asc&category=Skincare+and+Bathing" class="bg-black text-white hover:bg-pink-600 py-2 px-4 rounded-full text-xs font-semibold">Go To Categories</a>
                    </div>
                </div>
            </div>

            <!-- Health and Safety -->
            <div class="relative rounded-lg overflow-hidden bg-gray-600">
                <img src="images/safety.jpg" alt="Health and Safety" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-red-200 bg-opacity-50 flex flex-col justify-between p-4">
                    <p class="text-black text-sm font-semibold">CATEGORIES</p>
                    <div>
                        <h3 class="text-black text-xl font-semibold mb-2">Health and Safety</h3>
                        <a href="shop.php?search=&sort_by=name_asc&category=Health+and+Safety" class="bg-black text-white hover:bg-pink-600 py-2 px-4 rounded-full text-xs font-semibold">Go To Categories</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsorship Section -->
    <section class="mt-20 bg-white py-10">
        <h2 class="text-2xl font-bold mb-6 text-center">Our Sponsors</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 justify-items-center">
            <!-- Sponsor 1 -->
            <div class="overflow-hidden max-w-xs">
                <img src="images/brand1.jpg" alt="Sponsor 1" class="h-20 object-contain mx-auto grayscale">
            </div>
            <!-- Sponsor 2 -->
            <div class="overflow-hidden max-w-xs">
                <img src="images/brand2.jpg" alt="Sponsor 2" class="h-20 object-contain mx-auto grayscale">
            </div>
            <!-- Sponsor 3 -->
            <div class="overflow-hidden max-w-xs">
                <img src="images/brand3.jpg" alt="Sponsor 3" class="h-20 object-contain mx-auto grayscale">
            </div>
            <!-- Sponsor 4 -->
            <div class="overflow-hidden max-w-xs">
                <img src="images/brand4.jpg" alt="Sponsor 4" class="h-20 object-contain mx-auto grayscale">
            </div>
            <!-- Sponsor 5 -->
            <div class="overflow-hidden max-w-xs">
                <img src="images/brand5.jpg" alt="Sponsor 5" class="h-20 object-contain mx-auto grayscale">
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="px-20 py-16 mt-16 bg-gradient-to-br from-pink-50 to-blue-50">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col md:flex-row items-center gap-24">
                <div class="md:w-1/2 flex flex-col justify-between">
                    <div>
                        <p class="text-1xl mb-3 font-bold" style="text-transform: uppercase;">Our People</p>
                        <h2 class="text-5xl font-extrabold mb-8 text-gray-800 leading-tight">
                            About <span class="text-pink-600">CuddleBug</span>
                        </h2>
                        <p class="text-md mb-6 text-gray-600 leading-relaxed">
                            At CuddleBug, we're passionate about providing the best for your little ones. Founded by parents who understand the joys and challenges of raising children, we curate a selection of high-quality, safe, and adorable products for babies and toddlers.
                        </p>
                        <p class="text-md mb-6 text-gray-600 leading-relaxed">
                            Our mission is to make parenting easier and more enjoyable by offering trusted brands and innovative solutions for all your baby care needs. From gentle skincare to cozy sleepwear, nutritious foods to engaging toys, we've got everything to keep your bundle of joy happy and healthy.
                        </p>
                    </div>
                    <div>
                        <a href="#" class="inline-block bg-gray-800 hover:bg-pink-600 text-white py-3 px-10 rounded-full">Learn More</a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="h-full w-full">
                        <img src="images/about.jpg" alt="Happy baby with parents" class="rounded-2xl h-full w-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-20">
        <p class="trending text-3xl font-bold mb-2">Trending</p>
        <h1 class="text-5xl font-bold mb-12" style="line-height: 1.2; text-align:center;">Shop our popular <br>baby products</h1>
    </div>
        
    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 justify-items-center">
        <!-- Product 1 -->
        <div class="overflow-hidden max-w-xs transition-transform transform hover:scale-105">
            <img src="images/clothdiaper.png" alt="Cloth Diapers" class="w-full h-60">
        </div>
            
        <!-- Product 2 -->
        <div class="overflow-hidden max-w-xs transition-transform transform hover:scale-105">
            <img src="images/cereal.webp" alt="Baby Cereal" class="w-full h-60">
        </div>
            
        <!-- Product 3 -->
        <div class="overflow-hidden max-w-xs transition-transform transform hover:scale-105">
            <img src="images/shampoo.png" alt="Striped Jacket" class="w-full h-60">
        </div>
            
        <!-- Product 4 -->
        <div class="overflow-hidden max-w-xs transition-transform transform hover:scale-105">
            <img src="images/toy.png" alt="Striped Shorts" class="w-full h-60">
        </div>
    </div>

    <div class="mt-20">
        <br><p class="trending text-3xl font-bold mb-2">CuddleBug</p>
        <h1 class="text-5xl font-bold mb-2" style="line-height: 1.2; text-align:center;">Our Products</h1>
    </div>

    <div class="container mb-20">
        <div class="row">
            <?php
            require_once('db/config.php');

            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT * FROM products GROUP BY category ORDER BY RAND() LIMIT 4";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo '<div class="pro-container">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="pro">';
                    echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="product-image">';
                    echo '<div class="des">';
                    echo '<span>' . htmlspecialchars($row['category']) . '</span>';
                    echo '<h5>' . htmlspecialchars($row['name']) . '</h5>';

                    // Display star ratings
                    $ratings = $row['ratings'];
                    $fullStars = floor($ratings);
                    $halfStar = $ratings - $fullStars;
                    echo '<div class="ratings">';
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
                    echo '</div>';

                    echo '<br><h4>RM' . number_format($row['price'], 2) . '</h4>';

                    echo '<a href="product_description.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-primary">More Details</a>';

                    echo '</div>';  
                    echo '</div>';  
                }
                echo '</div>';  
            }

            mysqli_close($con);
            ?>
        </div>
    </div>
</main>

<?php if (isset($_SESSION['user_id'])): ?>
     <!-- Newsletter Subscription Section -->
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto max-w-4xl text-center">
            <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-lg mb-6">Stay updated on the latest offers and parenting tips.</p>
            <!-- Newsletter Form -->
            <form action="subscribe.php" method="POST" class="flex justify-center">
                <input type="email" name="email" placeholder="Enter your email" class="px-5 py-3 mr-4 focus:outline-none focus:ring-2 focus:ring-pink-600 rounded-lg">
                <button type="submit" class="bg-black hover:bg-pink-600 text-white px-6 py-3 rounded-lg">Subscribe</button>
            </form>
        </div>
    </section>
<?php endif; ?>

<?php include 'inc/footer.php'; ?>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            slidesPerView: 1,
            spaceBetween: 0,
            effect: 'fade',   
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(form);
            fetch('subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    form.reset();  
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    });

</script>

</body>
</html>
