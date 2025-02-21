<?php
require('db/config.php');

$searchInput = $_GET['search'];

$limit = 8; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit; // Offset

$sql = "SELECT * FROM products WHERE name LIKE '%$searchInput%'";

$sql .= " LIMIT $start, $limit";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<div class="pro-container">';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="pro">';
        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="product-image">';
        echo '<div class="des">';
        echo '<span>' . htmlspecialchars($row['category']) . '</span>';
        echo '<h5>' . htmlspecialchars($row['name']) . '</h5>';

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

        echo '<h4>RM' . number_format($row['price'], 2) . '</h4>';
        echo '</div>';  
        echo '</div>';  
    }
    echo '</div>';  

    $query = "SELECT COUNT(*) AS total FROM products WHERE name LIKE '%$searchInput%'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $total_pages = ceil($row['total'] / $limit);

    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        $active_class = $page == $i ? 'active' : '';
        echo '<a class="' . $active_class . '" href="?search=' . urlencode($searchInput) . '&page=' . $i . '">' . $i . '</a>';
    }
    echo '</div>'; 
    
} else {
    echo 'No products found.';
}

mysqli_close($con);
?>
