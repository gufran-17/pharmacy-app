<?php include("./config/constants.php") ?>

<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Pharmacy</title>
  <link rel="icon" href="./favicon.ico" type="image/ico">
  <link href="./css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" type="text/css" href="./css/main.css">
  <link rel="stylesheet" type="text/css" href="./css/order.css">
</head>

<body>
  <!-- navbar starts -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><img src="./img/first-aid-kit.png" alt="logo" style="height: 30px; object-fit: scale-down;"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href=<?php echo SITEURL; ?>>Home</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href=<?php echo SITEURL."categories.php"; ?>>Categories</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href=<?php echo SITEURL."medicines.php"; ?>>Medicines</a>
        </li>
        <div class="col-12"></div>
        <div class="col-12"></div>
        <div class="col-1"></div>
        <li class="nav-item active">
          <a class="nav-link" href=<?php echo SITEURL."admin/login.php"; ?>>Admin</a>
        </li>
      </ul>
    </div>
    <!-- <form class="d-flex">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-info" type="submit">Search</button>
    </form> -->
  </nav>
  <!-- navbar ends -->

 
