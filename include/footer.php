<?php
// Include necessary files and establish a database connection
include("../include/config.php"); // Include your database connection
?>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-left"> 
            <h3>About Us</h3>
            <ul class="footer-links"> 
                <li>CheeseClick is a platform that connects photographers with clients seeking visual content. Our user-friendly interface and dedicated administrators facilitate seamless collaborations between creative professionals and businesses.</li> 
            </ul>
        </div>
        <div class="footer-center">
            <h3>Service</h3>
            <ul class="footer-links">
                <li><a href="/photodb/customer/commercial.php">Commercial</a></li>
                <li><a href="/photodb/customer/landscape.php">Landscape</a></li>
                <li><a href="/photodb/customer/event.php">Event</a></li>
                <li><a href="/photodb/customer/newborn.php">Newborn</a></li>
                <li><a href="/photodb/customer/family.php">Family</a></li>
                <li><a href="/photodb/customer/portrait.php">Portrait</a></li>
                <li><a href="/photodb/customer/fashion.php">Fashion</a></li>
                <li><a href="/photodb/customer/sports.php">Sports</a></li>
                <li><a href="/photodb/customer/food.php">Food</a></li>
                <li><a href="/photodb/customer/wedding.php">Wedding</a></li>
            </ul>
        </div>
        <div class="footer-center">
            <h3>Contact</h3>
            <ul class="footer-links">
                <li><a href="/photodb/customer/customerdashboard.php">Home</a></li>
                <li><a href="/photodb/customer/phreviews.php">Reviews</a></li>
                <li><a href="#">About us</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <h3>Newsletter</h3>
            <form>
                <input type="email" placeholder="Email Address" required>
                <button type="submit">Subscribe</button>
            </form>
            <div class="social-icons">
                <a href="facebook.com"><i class="fab fa-whatsapp"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="contact-info">
                <p>Mobile: +9458374043</p>
                <p>Email: admin@cheeseclickphotography.com</p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="logo">
        <a href="/photodb/customer/customerdashboard.php"><img src="../uploads/C.png" alt="Logo" alt="CrossCulture Connects"></a>
        </div>
        <p>&copy; 2024 CheeseClickPhotography.com</p>
        <ul class="footer-links">
            <li><a href="#">Terms &amp; Conditions</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Cancellation &amp; Refund Policy</a></li>
            <li><a href="#">Service Delivery Policy</a></li>
        </ul>
    </div>
</footer>

<style>
    /* Footer styles */
    .footer {
        background-color: rgba(75, 192, 192, 20);
        color: #fff;
        padding: 20px;
        text-align: center;
        margin-top: 40px;
    }

    .footer-content {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    .footer-left,
    .footer-center,
    .footer-right {
        flex: 1;
        margin: 20px;
    }

    .footer-left h3,
    .footer-center h3,
    .footer-right h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .footer-links {
        display: flex;
        text-align: justify;
        flex-wrap: wrap;
        justify-content: center; /* Center the items */
    }

    .footer-links li {
        list-style: none;
        margin-bottom: 10px;
    }

    .footer-links a {
        text-decoration: none;
        color: #333;
        font-size: 1rem;
        padding: 5px 10px;
        background-color: #fff;
        border-radius: 5px;
        margin-right: 10px; /* Add margin between the links */
    }

    .footer-links a:hover {
        background-color: rgba(75, 192, 192, 20);
    }

    .footer-right form {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .footer-right input[type="email"] {
        padding: 10px;
        border: none;
        border-radius: 5px 0 0 5px;
        width: 200px;
    }

    .footer-right button[type="submit"] {
        padding: 10px 20px;
        background-color: #fffff0;
        color: #333;
        border: none;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
    }

    .social-icons {
        margin-bottom: 20px;
    }

    .social-icons a {
        color: #fff;
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .contact-info p {
        font-size: 1rem;
        margin-bottom: 5px;
    }

    .footer-left {
        margin-right: 100px;
        margin-left: 70px;
    }
    .footer-left h3 {
        font-family: 'satisfy';
    }

    .footer-bottom {
        background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, 10);
    backdrop-filter: blur(10px);        padding: 20px;
        text-align: center;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-bottom .logo img {
        height: 50px;
        width: auto;
    }

    .footer-bottom p {
        font-size: 0.9rem;
        margin: 10px 0;
    }

    .footer-bottom .footer-links {
        flex-direction: row;
    }

    .footer-bottom .footer-links li {
        margin: 0 10px;
        
    }
    .footer-center {
    flex: 2; /* Set the flex value to 2 for the "Service" section */
    margin: 1px;
}

.footer-center h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    font-family: 'satisfy';
}

.footer-center .footer-links {
    display: grid;
    grid-template-columns: repeat(3, auto); /* Display links in two columns */
    gap: 10px; /* Add some gap between the links */
}

.footer-center .footer-links li {
    list-style: none;
    margin-bottom: 10px;
}

.footer-center .footer-links a {
    text-decoration: none;
    color: #333;
    font-size: 13px;
    padding: 5px 8px;
    background-color: #fff;
    border-radius: 3px;
    width: auto;
}

.footer-center .footer-links a:hover {
    background-color: #fffff0;
}

</style>
