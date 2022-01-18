
<?php
session_start();
if(!isset($_SESSION['userId'])){ header('location:login.php');}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Online Banking</title>
  <?php require 'assets/autoloader.php'; ?>
  <?php require 'assets/db.php'; ?>
  <?php require 'assets/function.php'; ?>
  <?php
    $error = "";
    if (isset($_POST['userLogin']))
    {
      $error = "";
        $user = $_POST['email'];
        $pass = $_POST['password'];
       
        $result = $con->query("select * from userAccounts where email='$user' AND password='$pass'");
        if($result->num_rows>0)
        { 
          session_start();
          $data = $result->fetch_assoc();
          $_SESSION['userId']=$data['id'];
          $_SESSION['user'] = $data;
          header('location:index.php');
         }
        else
        {
          $error = "<div class='alert alert-warning text-center rounded-0'>Username or password wrong try again!</div>";
        }
    }

   ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="print.css" media="print">
</head>
<body style="background:#95a5a6;background-size: 100%">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
 <a class="navbar-brand" href="#">
    <img src="images/logo1.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
   <?php echo bankname; ?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link " href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item ">  <a class="nav-link" href="accounts.php">Accounts</a></li>
      <li class="nav-item active">  <a class="nav-link" href="statements.php">Account Statements</a></li>
      <li class="nav-item ">  <a class="nav-link" href="transfer.php">Funds Transfer</a></li>
      <!-- <li class="nav-item ">  <a class="nav-link" href="profile.php">Profile</a></li> -->


    </ul>
    <?php include 'sideButton.php'; ?>
  </div>
</nav><br><br><br>
<div class="container">
  <div class="card  w-75 mx-auto">
  <div class="text-center">
        <button onclick="window.print();" class="btn btn-primary" id="print-btn">Print</button>
      </div>
  <div> 
  <h6> Select Your Date</h6>

  <form action="" method="post">
    <label for="birthday">from:</label>
    <input type="date" id="from" name="from_date">
    <label for="birthday">to:</label>
      <input type="date" id="to" name="to_date">
    <button type="submit" name="submit">filter</button>
  </form>

  
  </div>
</div>
<br>

  <div class="card shadowBlue">
  <div class="card-header text-center">
    <div class="row">
    <div class="col-md-6">
        <img src="images/logo1.jpg" class="rounded " alt="">
      </div>
      <div class="col-md-6">
      <table class="table table-striped table-white w-75 mx-auto">
  <thead>
  <tr>
      <th scope="col">Account Holder Name</th>
      <td scope="col"><?php echo $userData['name']; ?></td>
    </tr>
    
  </thead>
  <tbody>
  <tr>
      <td scope="row">Account No</td>
      <th ><?php echo $userData['accountNo']; ?></th>
    </tr>
    <tr>
      <th scope="row">Branch</th>
      <td><?php echo $userData['branchName']; ?></td>
    </tr>
    <tr>
      <th scope="row">Branch Code</th>
      <td><?php echo $userData['branchNo']; ?></td>
    </tr>
    <tr>
      <th scope="row">Account Type</th>
      <td><?php echo $userData['accountType']; ?></td>
    </tr>
    <tr>
      <th scope="row">Account Created</th>
      <td><?php echo $userData['date']; ?></td>
    </tr>
  </tbody>
</table>

      </div>
      
    </div>
  </div>
  <div class="card-header text-center">
    Transaction Statements
  </div>
<?php 
  if(isset($_POST['submit'])){

   $from=$_POST['from_date'];
   $date1 = date("Y-m-d H:i:s", strtotime($from));
  //  $date1 ='2021-12-10 00:00:00';
  //  $date2 ='2021-12-12 23:59:00';
  
   $to=$_POST['to_date'];
   $date2= date("Y-m-d 23:59:00", strtotime($to));

      $array = $con->query("SELECT * FROM `transaction`  WHERE  `date` BETWEEN  '".$date1."' AND '".$date2."'  ");
    

      if (!empty($array) && $array ->num_rows > 0) 
      {

        ?>


          <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th scope="col">TransactionID</th>
                    <th scope="col">Action</th>
                    <th scope="col">Debit</th>
                    <th scope="col">Credit</th>
                    <th scope="col">Reference</th>
                    <!-- <th scope="col">Balance</th> -->
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>

                <?php
                while ($row = $array->fetch_assoc()) 
                {
                ?>
                  <tr>
                    <td><?php echo $row['transactionId'] ?></td>
                    <td><?php echo $row['action'] ?></td>
                    <td><?php echo $row['debit'] ?></td>
                    <td><?php echo $row['credit'] ?></td>
                    <td><?php echo $row['other'] ?></td>
                    <!-- <td><?php echo $row['balance'] ?></td> -->
                    <td><?php echo $row['date'] ?></td>
                  </tr>
                  <?php

                }
                  ?>
                </tbody>
              </table>
              <div class="card-footer text-muted text-center">
                <?php echo bankname; ?>
              </div>
      
        <?php




        //  while ($row = $array->fetch_assoc()) 
        //  {
        //     if ($row['action'] == 'withdraw') 
        //     {
        //       echo "<div class='alert alert-secondary '>You withdraw tk.$row[debit] from your account at $row[date]</div>";
        //     }
        //     if ($row['action'] == 'deposit') 
        //     {
        //       echo "<div class='alert alert-success '>You deposit tk.$row[credit] in your account at $row[date]</div>";
        //     }
        //     if ($row['action'] == 'deduction') 
        //     {
        //       echo "<div class='alert alert-danger '>Deduction have been made for  tk.$row[debit] from your account at $row[date] in case of $row[other]</div>";
        //     }
        //     if ($row['action'] == 'transfer') 
        //     {
        //       echo "<div class='alert alert-warning '>Transfer have been made for  tk.$row[debit] from your account at $row[date] in  account no.$row[other]</div>";
        //     }

        //  }
      }
      else {
        die("No matching data found");
      } 
    }
    ?>
  </div>



</div>

</div>
</body>

</html>