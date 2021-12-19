<?php 
    $con = new mysqli('localhost','root','','mybank');
    define('bankName', 'Online Bank',true);
    $ar = $con->query("select * from userAccounts,branch where id = '$_SESSION[userId]' AND userAccounts.branch = branch.branchId");
    $userData = $ar->fetch_assoc();
   
    // echo "<pre>";
    // print_r($userData);
    // exit;
?>
<script type="text/javascript">
	$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>