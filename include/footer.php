<footer class="footer">
    <div class="footer-content">
        <div class="footer-left">
            <h3>Programs</h3>
            <ul class="footer-links">
                <li>Corporate</li>
                <li>One to One</li>
                <li>Consulting</li>
            </ul>
        </div>
        <div class="footer-center">
            <h3>Service</h3>
            <ul class="footer-links">
                <li>Training</li>
                <li>Coaching</li>
                <li>Consulting</li>
            </ul>
        </div>
        <div class="footer-center">
            <h3>Contact</h3>
            <ul class="footer-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <h3>Newsletter</h3>
            <form>
                <input type="email" placeholder="Email Address" required>
                <button type="submit">Subscribe</button>
            </form>
            <div class="social-icons">
                <a href="#"><i class="fab fa-whatsapp"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="contact-info">
                <p>Mobile: +917892474250</p>
                <p>Email: santhosh@crosscultureconnects.com</p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="logo">
        <a href="#"><img src="../uploads/C.png" alt="Logo" alt="CrossCulture Connects"></a>
        </div>
        <p>&copy; 2023 crosscultureconnects.com</p>
        <ul class="footer-links">
            <li><a href="#">Terms & Conditions</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Cancellation & Refund Policy</a></li>
            <li><a href="#">Shipping & Delivery Policy</a></li>
        </ul>
    </div>
</footer>

<style>
    /* Footer styles */
    .footer {
        background-color: #213555;
        color: #fff;
        padding: 40px;
        text-align: center;
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
        margin-bottom: 10px;
    }

    .footer-links {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .footer-links li {
        list-style: none;
        margin-bottom: 5px;
    }

    .footer-links li a {
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
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
        background-color: #FF6B00;
        color: #fff;
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

    .footer-bottom {
        background-color: #4F709C;
        padding: 20px;
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
</style>