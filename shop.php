<?php
require('db/config.php');

$query = "SELECT DISTINCT category FROM products";
$result = mysqli_query($con, $query);

if (!$result) {
    die('Error fetching categories: ' . mysqli_error($con));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shop | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="shop.css">
</head>
<body>
    <section id="page-header" class="text-center" style="padding: 80px 0";>
        <h2 style="font-size: 3em; margin-bottom: 10px;">Discover CuddleBug Shop</h2>
        <p style="font-size: 17px; max-width: 500px; margin-bottom: 20px; font-weight:300;">Explore our wide range of premium baby care products, curated with love and care.</p>
    </section>

    <div class="shop-container">
        <div class="product-grid">
            <div class="search-sort-container">
                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="row gx-3 align-items-center">
                    <div class="col-md-12">
                        <div class="search mb-3">
                            <label class="form-label me-2" style="font-weight: bold;">Search By:</label>
                            <input type="text" class="form-control" id="live_search" name="search" autocomplete="off" placeholder="Search...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sort">
                            <label for="sort_by" class="form-label me-2">Sort By:</label>
                            <select id="sort_by" class="form-select" name="sort_by" onchange="this.form.submit()">
                                <option value="name_asc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                                <option value="name_desc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                                <option value="price_asc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_asc' ? 'selected' : ''; ?>>Price (Low to High)</option>
                                <option value="price_desc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_desc' ? 'selected' : ''; ?>>Price (High to Low)</option>
                                <option value="ratings_desc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'ratings_desc' ? 'selected' : ''; ?>>Ratings (High to Low)</option>
                                <option value="ratings_asc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'ratings_asc' ? 'selected' : ''; ?>>Ratings (Low to High)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter">
                            <label for="category" class="form-label me-2">Filter By Category:</label>
                            <select id="category" class="form-select" name="category" onchange="this.form.submit()">
                                <option value="" <?php echo empty($_GET['category']) ? 'selected' : ''; ?>>All Categories</option>
                                <?php
                                mysqli_data_seek($result, 0);  
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = isset($_GET['category']) && $_GET['category'] == $row['category'] ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['category']) . '" ' . $selected . '>' . htmlspecialchars($row['category']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <section id="product1">
            <?php
                $sql = "SELECT * FROM products WHERE 1";

                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    $category = mysqli_real_escape_string($con, $_GET['category']);
                    $sql .= " AND category = '$category'";
                }
                
                $sort_option = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'name_asc';
                switch ($sort_option) {
                    case 'name_asc':
                        $sql .= " ORDER BY name ASC";
                        break;
                    case 'name_desc':
                        $sql .= " ORDER BY name DESC";
                        break;
                    case 'price_asc':
                        $sql .= " ORDER BY price ASC";
                        break;
                    case 'price_desc':
                        $sql .= " ORDER BY price DESC";
                        break;
                    case 'ratings_asc':
                        $sql .= " ORDER BY ratings ASC";
                        break;
                    case 'ratings_desc':
                        $sql .= " ORDER BY ratings DESC";
                        break;
                    default:
                        $sql .= " ORDER BY name ASC";
                }

                $limit = 12; 
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
                $start = ($page - 1) * $limit; 

                $sql .= " LIMIT $start, $limit";
                $result = mysqli_query($con, $sql);

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

                    $query = "SELECT COUNT(*) AS total FROM products";
                    if (isset($_GET['category']) && !empty($_GET['category'])) {
                        $category = mysqli_real_escape_string($con, $_GET['category']);
                        $query .= " WHERE category = '$category'";
                    }
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $total_pages = ceil($row['total'] / $limit);

                    echo '<br><br><div class="pagination">';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = $page == $i ? 'active' : '';
                        $category_param = isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : '';
                        $sort_param = isset($_GET['sort_by']) ? '&sort_by=' . urlencode($_GET['sort_by']) : '';
                        echo '<a class="' . $active_class . '" href="?page=' . $i . $category_param . $sort_param . '">' . $i . '</a>';
                    }
                    echo '</div>'; 
                } else {
                    echo 'No products found.';
                }
                ?>                                  
        
            </section>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>

    <script>
        function liveSearch() {
            var searchInput = document.getElementById('live_search').value.trim().toLowerCase();
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("product1").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "search_products.php?search=" + searchInput, true);
            xhttp.send();
        }

        document.getElementById('live_search').addEventListener('input', function() {
            liveSearch();
        });
    </script>
</body>
</html>
