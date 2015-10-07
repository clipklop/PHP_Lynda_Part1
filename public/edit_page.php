<?php
require_once("../includes/session.php");
require_once("../includes/db_connection.php");
require_once("../includes/functions.php");
require_once("../includes/validation.php");

find_selected_page();

# Unlike create_page.php, we don't a subject_id to be sent
# We already have it store in pages.subject_id
if (!$current_page) {
  # Page ID was missing or invlaid
  # Page couldn't be found in database
  redirect_to("manage_content.php");
}

if (isset($_POST['submit'])) {
  # Process the form...
  $id = $current_page["id"];
  $menu_name = mysql_prep($_POST["menu_name"]);
  $position = (int) $_POST["position"];
  $visible = (int) $_POST["visible"];

  # Escape all strings with user function
  $content = mysql_prep($_POST["content"]);

  # Validations
  $required_fields = array("menu_name", "position", "visible", "content");
  validate_presences($required_fields);

  $fields_with_max_lengths = array("menu_name" => 30);
  validate_max_lengths($fields_with_max_lengths);

  if (empty($errors)) {
    # Perform update
    $query = "UPDATE pages SET menu_name = '{$menu_name}', ";
    $query .= "position = {$position}, visible = {$visible}, ";
    $query .= "content = '{$content}' WHERE id = {$id} LIMIT 1";

    $result = mysqli_query($connection, $query);

    if ($result && mysqli_affected_rows($connection) == 1) {
      # Success
      $_SESSION["message"] = "Page updated successfully.";
      redirect_to("manage_content.php?page={$id}");
    } else {
      # Failure
      $_SESSION["message"] = "Page update failed.";
    }
}
} else {
  # this is probably a GET request
}
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
  <div id="navigation">
    <?php echo navigation($current_subject, $current_page); ?>
  </div>

  <div id="page">

    <?php echo message(); ?>
    <?php $errors = errors(); ?>
    <?php echo form_errors($errors); ?>

    <h2>Edit Page: <?php htmlentities($current_page["menu_name"]); ?></h2>
    <form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
      <p>Menu name:
        <input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>">
      </p>
      <p>Position:
        <select name="position" id="">

        <?php
          $page_set = find_pages_for_subject($current_page["subject_id"]);
          $page_count = mysqli_num_rows($page_set);
          for ($count=1; $count <= $page_count; $count++) {
            echo "<option value='{$count}'";
            if ($current_page["position"] == $count) {
              echo " selected";
            }
            echo ">{$count}</option>";
          }
        ?>
        </select>
      </p>
      <p>Visible:
        <input type="radio" name="visible" value="0"<?php if ($current_subject["visible"] == 0) { echo "checked"; } ?>>No &nbsp;
        <input type="radio" name="visible" value="1"<?php if ($current_subject["visible"] == 1) { echo "checked"; } ?>>Yes &nbsp;
      </p>
      <p>Content: <br>
        <textarea name="content" rows="20" cols="80"><?php echo htmlentities($current_page["content"]); ?></textarea>
      </p>
        <input type="submit" value="Edit Page" name="submit" id="">
    </form>
    <br>
    <a href="manage_content.php?page=<?php echo urlencode($current_page["id"]); ?>">Cancel</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>