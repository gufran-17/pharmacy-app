<?php

include("../config/constants.php"); 
include("login-check.php"); 

?>

<html>
<head>
  <title>Admin - Online Pharmacy</title>
</head>
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="../favicon.ico" type="image/ico">
<link rel="stylesheet" href="../css/admin.css">

<body>
  <div id='page-container'>
  <div id="content-wrap">
  <!-- navbar starts -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link active" href="./index.php">Home</a>
          <a class="nav-link active" href="./manage-admin.php">Admin</a>
          <a class="nav-link active" href="./manage-category.php">Category</a>
          <a class="nav-link active" href="./manage-medicine.php">Medicine</a>
          <a class="nav-link active" href="./manage-order.php">Order</a>
          <div class="col-12"></div>
          <div class="col-9"></div>
          <!-- <a class="nav-link active" href="./logout.php"><i class="fa fa-sign-out" style="font-size:16px;color:white;vertical-align:bottom;"></i></a>&nbsp; -->
          <a class="nav-link active" href="./logout.php" style="vertical-align:middle;">Logout</a>
        </div>
      </div>
    </div>
  </nav>
  <!-- navbar ends -->
  