<div id="sidebar" class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <!-- Navigation links -->
            <li><a href="/photodb/customer/customerdashboard.php">Home</a></li>       
            <li><a href="/photodb/customer/photographer.php">Photographers</a></li>


            <li class="dropdown">
            <a href="#">Services</a>
            <div class="dropdown-content">
            <?php
        $serviceTypesSql = "SELECT * FROM servicetypes";
        $serviceTypesResult = $conn->query($serviceTypesSql);

        while ($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
            $typeName = $serviceTypeRow['TypeName'];
            $typeParam = urlencode(strtolower(str_replace(
                array('Wedding Photography', 'Portrait Photography', 'Event Coverage', 'Commercial Photography', 'Family Photography', 'Fashion Photography', 'Newborn Photography', 'Landscape Photography', 'Food Photography', 'Sports Photography'),
                array('wedding', 'portrait', 'event', 'commercial', 'family', 'fashion', 'newborn', 'landscape', 'food', 'sports'),
                $typeName
            )));

            echo "<a href='$typeParam.php'>$typeName</a>";
        }
        ?>
            </div>
            </li>
            <li><a href="/photodb/customer/phreviews.php">Reviews</a></li>
            <li><a href="/photodb/customer/gallery.php">Photo Gallery</a></li>
            <li><a href="/photodb/customer/price.php">Pricing</a></li>
            <li><a href="/photodb/customer/appointment.php">Appointment</a></li>
            <li><a href="/photodb/admin/aboutus.php">About Us</a></li>
        </ul>
    </nav>
</div>


<style>
    /* Sidebar styles */
.sidebar {
    position: fixed;
    top: 0;
    left: -250px; /* Hide sidebar by default */
    width: 250px;
    height: 100%;
    background-color: #213555;
    z-index: 1000; /* Ensure sidebar is above other content */
    transition: left 0.3s ease-in-out; /* Add smooth transition effect */
}

.sidebar-nav {
    margin-top: 80px; /* Adjust based on your navbar height */
}

.sidebar-nav ul {
    list-style-type: none;
    padding: 0;
}

.sidebar-nav ul li {
    padding: 10px 0;
}

.sidebar-nav ul li a {
    display: block;
    padding: 10px 20px;
    color: #fff;
    text-decoration: none;
}

.sidebar-nav ul li a:hover {
    background-color: #4F709C;
}

</style>