<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .hero {
            background: linear-gradient(135deg, #6c5ce7, #00b894);
            color: #fff;
            padding: 80px 20px;
            text-align: center;
        }
        .about {
            padding: 60px 20px;
        }
        .footer {
            background: #2d3436;
            color: #dfe6e9;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/hospitaldashboard">Hospital Appointment Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link btn btn-outline-light ms-2" href="/auth/login">Admin Login</a></li>
                <li class="nav-item"><a class="nav-link btn btn-success ms-2 text-white" href="/patient/login">Patient Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to Hospital Appointment Portal</h1>
        <p class="lead">Caring for you since 2025 – founded by Dr. Sandeep Shetty</p>
    </div>
</section>

<!-- About Section -->
<section class="about" id="about">
    <div class="container">
        <h2 class="mb-4 text-center">About Us</h2>
        <p class="lead text-center">
            Hospital Appointment Portal was founded in 2025 by <strong>Dr. Sandeep Shetty</strong> with a vision to provide affordable and 
            quality healthcare to everyone. Over the years, we’ve grown into a 500-bed multi-specialty hospital 
            with state-of-the-art facilities and a team of dedicated doctors and staff.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="about" id="contact">
    <div class="container">
        <h2 class="mb-4 text-center">Contact Us</h2>
        <p class="text-center">📍 A I Innovation Center  Agrahara main road Kogilu Yelahanka 560064 India</p>
        <p class="text-center">📞 +91 8050750015 | ✉ nksandeep44@gmail.com</p>
    </div>
</section>

<!-- Footer -->
<div class="footer">
    <p>&copy; <?= date("Y") ?> MyHospital. All rights reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
