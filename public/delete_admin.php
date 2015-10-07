<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");

$admin = find_admin_by_id($_GET["id"]);

if (!$admin) {
  # Admin ID was missing or invlaid
  # Admin couldn't be found in database
  redirect_to("manage_admins.php");
}

  $id = $admin["id"];

  # Perform a database query
  $query = "DELETE FROM admins WHERE id = {$id} LIMIT 1";

  $result = mysqli_query($connection, $query);

  if ($result && mysqli_affected_rows($connection) == 1 ) {
    # Success
    $_SESSION["message"] = "The admin user has been deleted successfully.";
    redirect_to("manage_admins.php");
  } else {
    # Failure
    $_SESSION["message"] = "The admin user deletion failed.";
    redirect_to("new_admin.php");
  }