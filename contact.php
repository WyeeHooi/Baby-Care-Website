<?php
require_once('db/config.php');

    // Check if user is logged in
    session_start();
    $user_logged_in = isset($_SESSION['user_id']);
    $user_id = $user_logged_in ? $_SESSION['user_id'] : null;

$errorMessages = '';
$fieldErrors = [];

$username = '';
$email = '';
$subject = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate input
    if (empty($username)) {
        $fieldErrors['username'] = "Username is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = "Invalid email format.";
    }

    if (empty($subject)) {
        $fieldErrors['subject'] = "Subject is required.";
    }

    if (empty($message)) {
        $fieldErrors['message'] = "Message is required.";
    }

    if (!empty($fieldErrors)) {
        $errorMessages = "<p class='error-bar'>" . implode("<br>", $fieldErrors) . "</p>";
    }

    // Insert into database if no errors
    if (empty($fieldErrors)) {
        // Determine member check and user_id
        $member_check = $user_logged_in ? 'member' : 'not member';

        // Prepare and execute SQL statement
        $stmt = $con->prepare("INSERT INTO contact_form (member_check, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $member_check, $email, $subject, $message);

        if ($stmt->execute()) {
            $_SESSION['form_submitted'] = true;
        } else {
            $errorMessages .= "<p class='error-bar'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="contact.css">
</head>
<body class="bg-white"> 
<div id="pop-message" style="display:none;"><p>Message successfully sent. We will reply as soon as possible.</p></div>
<section id="page-header" class="text-center" style="padding: 80px 0";>
    <h2 style="font-size: 3em; margin-bottom: 10px;">Contact Us</h2>
    <p style="font-size: 17px; max-width: 500px; margin-bottom: 20px; font-weight:300;">We're Here to Help You Find the Best for Your Little One!</p>
</section>

<div class="contact-container">
    <div class="contact-detail">
        <div class="contact-boxes">
            <div class="box">
                <i class="fas fa-phone fs-3 mb-1"></i>
                <h3 class="text-pink-600 text-xl font-semibold mb-2">Phone</h3>
                <p class="text-black text-sm font-semibold">60-011 00110011</p>
            </div>
            <div class="box">
                <i class="fas fa-envelope fs-3 mb-1"></i>
                <h3 class="text-pink-600 text-xl font-semibold mb-2">Email</h3>
                <p class="text-black text-sm font-semibold">cuddlebug20@gmail.com</p>
            </div>
        </div>
        <div class="contact-boxes">
            <div class="box w-100">
                <i class="fa-solid fa-shop fs-3 mb-1"></i>
                <h3 class="text-pink-600 text-xl font-semibold mb-2">Our Store</h3>
                <p class="text-black text-sm font-semibold">Jalan Satu Taman Satu, Cheras 56000 Kuala Lumpur</p>
            </div>
        </div>

        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3984.050426110995!2d101.73410216772646!3d3.081215315864124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2smy!4v1721471052953!5m2!1sen!2smy" width="100%" height="450" style="border:0;border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <div class="contact-formbox">
        <div class="form-box">
            <h1 class="text-5xl font-bold mb-2">Get in Touch</h1>
            <p class="text-black text-sm">We're just a message away! <br />Contact us with your questions, feedback, or concerns.</p>
            <div class="my-4">
                <?php echo $errorMessages; ?>
                <form class="contact-form" id="contactForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div id="username-error" class="error-message"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div id="email-error" class="error-message"></div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                        <div id="subject-error" class="error-message"></div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        <div id="message-error" class="error-message"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary inline-block bg-gray-800 hover:bg-pink-600 text-white py-3 px-10 rounded-full w-100" name="submit">Send Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to validate username
        function validateUsername() {
            var username = document.getElementById('username').value.trim();
            var usernameError = document.getElementById('username-error');
            var successIcon = '<i class="fas fa-check-double"></i>';
            if (username === '') {
                usernameError.textContent = 'Please enter your name.';
                return false;
            } else {
                usernameError.innerHTML = successIcon;
                return true;
            }
        }

        // Function to validate email
        function validateEmail() {
            var email = document.getElementById('email').value.trim();
            var emailError = document.getElementById('email-error');
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var successIcon = '<i class="fas fa-check-double"></i>';
            if (!emailRegex.test(email)) {
                emailError.textContent = 'Please enter a valid email address.';
                return false;
            } else {
                emailError.innerHTML = successIcon;
                return true;
            }
        }

        // Function to validate subject
        function validateSubject() {
            var subject = document.getElementById('subject').value.trim();
            var subjectError = document.getElementById('subject-error');
            var successIcon = '<i class="fas fa-check-double"></i>';
            if (subject === '') {
                subjectError.textContent = 'Please enter a subject.';
                return false;
            } else {
                subjectError.innerHTML = successIcon;
                return true;
            }
        }

        // Function to validate message
        function validateMessage() {
            var message = document.getElementById('message').value.trim();
            var messageError = document.getElementById('message-error');
            var successIcon = '<i class="fas fa-check-double"></i>';
            if (message === '') {
                messageError.textContent = 'Please enter your message.';
                return false;
            } else {
                messageError.innerHTML = successIcon;
                return true;
            }
        }

        // Event listeners for input events
        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('email').addEventListener('input', validateEmail);
        document.getElementById('subject').addEventListener('input', validateSubject);
        document.getElementById('message').addEventListener('input', validateMessage);

        // Form submission validation
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            var isUsernameValid = validateUsername();
            var isEmailValid = validateEmail();
            var isSubjectValid = validateSubject();
            var isMessageValid = validateMessage();

            // If any field is invalid, prevent form submission
            if (!isUsernameValid || !isEmailValid || !isSubjectValid || !isMessageValid) {
                event.preventDefault();
                // Optionally, you can display a general error message here
                // document.getElementById('error-messages').innerHTML = '<p class="error-message">Please fill out all required fields.</p>';
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']): ?>
            var successMessage = document.getElementById('pop-message');
            successMessage.style.display = 'block'; // Show the success message

            // Hide the success message after 5 seconds
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds

            // Clear the session variable after displaying the message
            <?php unset($_SESSION['form_submitted']); ?>
        <?php endif; ?>
    });
</script>

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
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
