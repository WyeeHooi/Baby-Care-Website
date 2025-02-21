<?php

    // Check if user is logged in
    session_start();
    $user_logged_in = isset($_SESSION['user_id']);
    $user_id = $user_logged_in ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FAQs | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="faq.css">
</head>
<body class="bg-white"> 

<section id="page-header" class="text-center" style="padding: 80px 0";>
    <h2 style="font-size: 3em; margin-bottom: 10px;">How can we help?</h2>
    <div class="search-box input-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Ask a question...">
        <div class="input-group-append">
            <button class="btn btn-primary" onclick="searchFAQs()">Search</button>
        </div>
    </div>
    <p style="color:gray"> or choose a category to quickly find the help you need</p>
</section>

<div class="faq-category">
    <button class="scroll-button left" aria-label="Scroll left"><i class="fas fa-chevron-left"></i></button>
    <div class="categories">
        <a href="#general" class="ctg-box"><i class="fas fa-question-circle fs-3"></i> General</a>
        <a href="#order" class="ctg-box"><i class="fas fa-shopping-cart fs-3"></i> Order</a>
        <a href="#product" class="ctg-box"><i class="fas fa-box fs-3"></i> Product</a>
        <a href="#payment" class="ctg-box"><i class="fas fa-credit-card fs-3"></i> Payment</a>
        <a href="#shipping" class="ctg-box"><i class="fas fa-truck fs-3"></i> Shipping & Delivery</a>
        <a href="#return" class="ctg-box"><i class="fas fa-undo fs-3"></i> Returns & Refunds</a>
    </div>
    <button class="scroll-button right" aria-label="Scroll right"><i class="fas fa-chevron-right"></i></button>
</div>

<div class="faq justify-content-center">
    <div class="col-lg-8" id="faq-content">
        <!-- general question -->
        <div id="general">
            <h1 class="text-5xl font-bold mb-2 text-center text-pink-600">General</h1>
            <p class="text-black text-sm text-center">Get answers to common questions about our store and services</p>
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How can I contact your customer service?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body">
                            You can contact our customer service team via email at cuddlebug24@gmail.com, by phone at
                            60-011 11001100, or through our website's contact form. We are available Mon-Fri and
                            9:00am-5:00pm to assist you.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Do you have a physical store where I can purchase products?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            Currently, we operate exclusively online and do not have a physical store. You can shop our
                            full range of products conveniently on our website.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How can I stay updated on new product arrivals and promotions?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            To stay updated on new product arrivals, promotions, and special offers, you can subscribe
                            to our newsletter or follow us on social media platforms like Facebook, Instagram, and
                            Twitter.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- order question -->
        <div id="order">
            <h1 class="text-5xl font-bold mb-2 text-center text-pink-600">Order Related</h1>
            <p class="text-black text-sm text-center">Find information on placing, tracking, and managing your orders
            </p>
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How can I place an order?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body">
                            To place an order, simply browse our products, select the items you wish to purchase, and
                            proceed to checkout. Follow the prompts to enter your shipping information and payment
                            details to complete your order.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Can I modify my order after it has been placed?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            Unfortunately, once an order is placed, we are unable to modify it. This includes changing
                            items, quantities, or shipping addresses. Please review your order carefully before
                            proceeding to checkout.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Do you offer gift wrapping services?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            Yes, we offer gift wrapping services for an additional fee. During checkout, you can select
                            the gift wrapping option and add a personalized message to your order.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- product question -->
        <div id="product">
            <h1 class="text-5xl font-bold mb-2 text-center text-pink-600">Product Related</h1>
            <p class="text-black text-sm text-center">Discover details about our products, including features and care
                instructions.</p>
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Can I return or exchange a product if I'm not satisfied?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body">
                            Yes, we offer a hassle-free return and exchange policy. If you are not satisfied with your
                            purchase, you can return it within [number] days for a refund or exchange, excluding
                            shipping costs.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What should I do if I receive a damaged or defective product?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            If you receive a damaged or defective product, please contact our customer service team
                            immediately. We will arrange for a replacement or refund as per our return policy.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Are your products BPA-free and non-toxic?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            Yes, our products are BPA-free and made from non-toxic materials. We prioritize safety and
                            ensure our products meet or exceed regulatory standards.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- payment question -->
        <div id="payment">
            <h1 class="text-5xl font-bold mb-2 text-center text-pink-600">Payment Related</h1>
            <p class="text-black text-sm text-center">Find out about payment options, security, and billing.</p>
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Can I get a refund if I cancel my order?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body">
                            Yes, you can receive a refund if you cancel your order before it is shipped. Once shipped,
                            you may need to return the item for a refund, excluding shipping costs.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Do you offer installment payment options?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            Currently, we do not offer installment payment options. We accept full payment at the time
                            of purchase.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            What should I do if my payment fails?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            If your payment fails, please check that your card details are correct and try again.
                            Alternatively, you can use a different payment method or contact your bank for assistance.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- shipping question -->
        <div id="shipping">
            <h1 class="text-5xl font-bold mb-2 text-center text-pink-600">Shipping and Delivery</h1>
            <p class="text-black text-sm text-center">Learn about our shipping methods, delivery times, and costs.</p>
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How long will it take for my order to arrive?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body">
                            Delivery times vary depending on your location and shipping method selected during checkout.
                            Typically, orders are processed within 1-2 business days, with delivery taking 3-7 business
                            days thereafter.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Do you offer free shipping?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            Yes, we offer free standard shipping on orders over a certain amount. Check our website or
                            promotional emails for current offers and conditions.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Can I change my delivery address after placing an order?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            Unfortunately, we cannot change the delivery address once an order is placed. Please ensure
                            the accuracy of your shipping information before completing your purchase.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Returns and Refunds question -->
        <div id="return">
            <h1 class="text-5xl font-bold mb-2 text-center text-pink-600">Returns and Refunds</h1>
            <p class="text-black text-sm text-center">Understand our policies on returns, exchanges, and refunds.</p>
            <div class="accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How do I initiate a return or exchange?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body">
                            To initiate a return or exchange, please contact our customer service team with your order
                            number and reason for return. We will provide you with further instructions and a return
                            shipping label if applicable.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How long does it take to process a refund?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            Refunds are processed within 14 business days after we receive your returned item. The
                            timing of the refund may vary depending on your payment method and financial institution.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            What should I do if I receive the wrong item?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            If you receive the wrong item, please contact our customer service team immediately. We will
                            arrange for the correct item to be sent to you and assist with the return of the incorrect
                            item.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-3 d-flex justify-content-center">
    <button id="showAllButton" class="btn btn-primary" onclick="showAllFAQs()" style="display:none;">Show All FAQs</button>
</div>



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

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script>
    function searchFAQs() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const faqContent = document.getElementById('faq-content');
        const faqItems = document.querySelectorAll('.accordion-item');
        const originalContent = faqContent.innerHTML;

        // Clear the existing content
        faqContent.innerHTML = '';

        let found = false;
        faqItems.forEach(item => {
            const question = item.querySelector('.accordion-button').innerText.toLowerCase();
            const answer = item.querySelector('.accordion-body').innerText.toLowerCase();
            if (question.includes(input) || answer.includes(input)) {
                const clonedItem = item.cloneNode(true);
                faqContent.appendChild(clonedItem);
                found = true;
            }
        });

        if (!found) {
            faqContent.innerHTML = '<p class="text-center fs-2">No FAQs found matching your query.</p>';
        }

        // Show the "Show All FAQs" button
        document.getElementById('showAllButton').style.display = 'block';

        // Save the original content for restoration
        document.getElementById('showAllButton').originalContent = originalContent;
    }

    function showAllFAQs() {
        const faqContent = document.getElementById('faq-content');
        const showAllButton = document.getElementById('showAllButton');

        // Restore the original content
        faqContent.innerHTML = showAllButton.originalContent;

        // Hide the "Show All FAQs" button
        showAllButton.style.display = 'none';
    }
</script>


<script>
    const scrollAmount = 300; // Adjust this value as needed

    document.querySelector('.scroll-button.left').addEventListener('click', function() {
        const container = document.querySelector('.categories');
        container.scrollBy({
            left: -scrollAmount, // Scroll left by specified amount
            behavior: 'smooth'
        });
    });

    document.querySelector('.scroll-button.right').addEventListener('click', function() {
        const container = document.querySelector('.categories');
        container.scrollBy({
            left: scrollAmount, // Scroll right by specified amount
            behavior: 'smooth'
        });
    });
</script>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
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
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
