<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require('db/config.php');

// Function to remove item from cart
if (isset($_POST['remove'])) {
    $id_to_remove = $_POST['remove'];
    if (isset($_SESSION['cart'][$id_to_remove])) {
        unset($_SESSION['cart'][$id_to_remove]);
    }
}

// Function to update quantity in cart
if (isset($_POST['update_quantity'])) {
    $id_to_update = $_POST['update_quantity'];
    $new_quantity = $_POST['quantity'];
    if (isset($_SESSION['cart'][$id_to_update])) {
        $_SESSION['cart'][$id_to_update] = $new_quantity;
    }
}

// Fetch cart items from database
$cart_items = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', $ids);
    $query = "SELECT * FROM products WHERE id IN ($ids_string)";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart - CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<?php include 'inc/header.php'; ?>

<main class="container mx-auto max-w-7xl mt-8 px-4">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Your Shopping Cart</h2>
    
    <?php if (empty($cart_items)): ?>
        <p class="text-xl text-gray-600">Your cart is empty. Continue <a href="shop.php" class="text-l text-pink-600">Shopping <i class="fas fa-shopping-basket "></i></a></p>
        
    <?php else: ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-20 w-20">
                                        <img class="h-20 w-20 object-cover rounded-md" src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">RM<?php echo number_format($item['price'], 2); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="post" class="flex items-center">
                                    <button type="button" class="minus text-gray-600 hover:text-gray-900 px-2 py-1">-</button>
                                    <input type="hidden" name="update_quantity" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input w-12 text-center border border-gray-300 rounded">
                                    <button type="button" class="plus text-gray-600 hover:text-gray-900 px-2 py-1">+</button>
                            </td>
                            <td class="px-10 py-6 whitespace-nowrap text-sm font-medium">
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">Update</button>
                                </form>
                                <form method="post" class="mt-2">
                                    <button type="submit" name="remove" value="<?php echo $item['id']; ?>" class="text-red-600 hover:text-red-900">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 flex justify-between items-center">
            <div class="text-2xl font-bold text-gray-800">
                Total: RM<?php echo number_format(array_sum(array_map(function ($item) {
                    return $item['price'] * $item['quantity'];
                }, $cart_items)), 2); ?>
            </div>
            <a href="checkout.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Proceed to Checkout
            </a>
        </div>
    <?php endif; ?>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.minus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.nextElementSibling.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                }
            });
        });

        document.querySelectorAll('.plus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                let value = parseInt(input.value);
                input.value = value + 1;
            });
        });
    });
</script>

</body>
</html>
