<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation.php"); ?>

<?php find_selected_page(); ?>

<div id="main">
  <div id="navigation">
  	<?php echo navigation($current_subject, $current_page); ?>
  </div>

  <div id="page">

    <?php echo message(); ?>
    <?php $errors = errors(); ?>
    <?php echo form_errors($errors); ?>

    <h2>Create Page</h2>
    <form action="create_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
      <p>Menu name:
        <input type="text" name="menu_name" value="">
      </p>
      <p>Position:
        <select name="position" id="">
          <?php
            $page_set = find_pages_for_subject($current_subject["id"]);
            $page_count = mysqli_num_rows($page_set);
            for ($count=1; $count <= ($page_count + 1); $count++) {
              echo "<option value=\"{$count}\">{$count}</option>";
            }
          ?>
        </select>
      </p>
      <p>Visible:
        <input type="radio" name="visible" value="0">No &nbsp;
        <input type="radio" name="visible" value="1">Yes &nbsp;
      </p>
      <p>Content:<br />
        <textarea name="content" rows="20" cols="80"></textarea>
      </p>
      <input type="submit" value="Create Page" name="submit" id="">
    </form>
    <br>
    <a href="manage_content.php?subject=<?php urlencode($current_subject['id']); ?>">Cancel</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>