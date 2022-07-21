<?php
/**
 * @package X_Bulk
 * @version 1.0.0
 */
/*
Plugin Name: X-Bulk 
Plugin URI: NA
Description: For bulk repair processing.
Author: X-Bulk 
Version: 1.0.0
Author URI: NA
*/
require_once("DBP_tb_file.php");
register_activation_hook( __FILE__, 'DBP_tb_create' );
register_uninstall_hook( __FILE__, 'DBP_tb_delete' );

add_shortcode( 'xbulk_customer_fillup', 'xbulk_customer_fillup_callback' );

function xbulk_customer_fillup_callback() {
	$returnurl = get_site_url();
  return
  "<form action='".$returnurl."' method='post' enctype='multipart/form-data' id='regForm'>
    <input type='hidden' name='submit1' value='submit'>
    <div style='text-align:center;margin-top:40px;'>
      <span class='step'></span>
      <span class='step'></span>
      <span class='step'></span>
    </div>
    <div class='tab'>
    	<h1>Get a quote today!</h1>
    	<p>Car registration #: <input placeholder='e.g. AB12345' name='regno' autocomplete='off'></p>
    </div>
    <div class='tab'>
    	<h1>Upload photos of the damages</h1>
      <div class='input-images-1' style='padding-top: .5rem;'></div>
    </div>
    <div class='tab'>
      <h1>Add Contact Info</h1>
      <p><input placeholder='Mobile number' name='mobilenumber'></p>
      <p><input placeholder='Name' name='name'></p>
      <p><input placeholder='Zip Code' name='zipcode'></p>
      <p><input placeholder='Car brand' name='carbrand'></p>
      <p><input placeholder='Model' name='model'></p>
    </div>
    <div style='overflow:auto;'>
      <div style='float:right;'>
        <button type='button' id='prevBtn' onclick='nextPrev(-1)'>Previous</button>
        <button type='button' id='nextBtn' onclick='nextPrev(1)'>Next</button>
      </div>
    </div>
  </form>
  ";
}

//add_action('wp_enqueue_scripts', 'wp_style_render');

wp_uploader_style_render();
wp_uploader_script_render();
wp_jquery_script_render();
wp_style_render();
wp_script_render();

function wp_style_render() {
	//$file_url = plugin_dir_url('xbulk-wpplugin/xbulk_style.css', __FILE__);
	$file_url = get_site_url()."/wp-content/plugins/xbulk-wpplugin/xbulk_style.css";
  wp_enqueue_style('xbulk_style', $file_url);
}

function wp_uploader_style_render() {
  //$file_url = plugin_dir_url('xbulk-wpplugin/xbulk_style.css', __FILE__);
  $file_url = get_site_url()."/wp-content/plugins/xbulk-wpplugin/image-uploader.min.css";
  wp_enqueue_style('image_uploader_style', $file_url);
}

function wp_script_render() {
  //$file_url = plugin_dir_url('xbulk-wpplugin/xbulk_style.css', __FILE__);
  $file_url = get_site_url()."/wp-content/plugins/xbulk-wpplugin/xbulk_script.js";
  wp_enqueue_script('xbulk_script', $file_url, array(), NULL, true);
}

function wp_uploader_script_render() {
  //$file_url = plugin_dir_url('xbulk-wpplugin/xbulk_style.css', __FILE__);
  $file_url = get_site_url()."/wp-content/plugins/xbulk-wpplugin/image-uploader.min.js";
  wp_enqueue_script('image_uploader_script', $file_url, array(), NULL, true);
}

function wp_jquery_script_render() {
  //$file_url = plugin_dir_url('xbulk-wpplugin/xbulk_style.css', __FILE__);
  $file_url = get_site_url()."/wp-content/plugins/xbulk-wpplugin/jquery.min.js";
  wp_enqueue_script('jquery1', $file_url, array(), NULL, false);
}

/*
$cardetails = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM wp_cardetail ORDER BY id DESC LIMIT 1" ) );
echo "<script>alert('".$cardetails->id."')</script>";
*/

add_action('admin_menu', 'xbulk_transactions');

function xbulk_transactions() {
    add_menu_page('X-Bulk Transactions', 'X-Bulk Transactions', 'manage_options', 'xbulk-transactions', 'indexmenu');
}

function indexmenu() {
    echo "<h1>X-Bulk Transactions</h1>";
    echo "
    <br>
    <table style='width: 100%;'>
    <thead>
    <tr>
    <th>Request Date</th>
    <th>Car Registation Number</th>
    <th>Mobile Number</th>
    <th>Name</th>
    <th>Zip</th>
    <th>Car brand</th>
    <th>Model</th>
    <th></th>
    </tr>
    </thead>
    <tbody>";
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM `wp_cardetails`");
    if(!empty($results))
    {          
      foreach($results as $row){   
        $userip = $row->user_ip;
        echo "<tr>";
        echo "<td style='text-align:center;'>".$row->regno."</td>";
        echo "<td style='text-align:center;'>".$row->mobilenumber."</td>";
        echo "<td style='text-align:center;'>".$row->name."</td>";
        echo "<td style='text-align:center;'>".$row->zip."</td>";
        echo "<td style='text-align:center;'>".$row->carbrand."</td>";
        echo "<td style='text-align:center;'>".$row->model."</td>";
        echo "<td style='text-align:center;'><a href='#'>View/Edit</a></td>";
        echo "</tr>";
      }
    } else {
        echo "<tr>";
        echo "<td>No data available.</td>";
        echo "</tr>";
    }

    echo "</tbody>
    </table>
    ";
}

if(isset($_POST['submit1'])) {
  global $wpdb;
  $cardetailsid = $wpdb->get_row($wpdb->prepare( "SELECT id FROM wp_cardetails ORDER BY id DESC LIMIT 1" ));

  if($cardetailsid->id == "") {
    $id = 1;
  } else {
    $id = (int)$cardetailsid->id + 1;
  }

  $wordpress_upload_dir = wp_upload_dir();
  $countfiles = count($_FILES['images']['name']);
  for($i=0; $i<$countfiles; $i++){
      //$filename = $_FILES['images']['name'][$i];
      $file = $wordpress_upload_dir['path'] . basename($_FILES['images']['name'][$i]);
      $urlname = $wordpress_upload_dir['url'] . basename($_FILES['images']['name'][$i]);
      move_uploaded_file($_FILES['images']['tmp_name'][$i], $file);

      $wpdb->insert('wp_carphotos', array('cardetailsid' => $id, 'filename' => $_FILES['images']['name'][$i], 'fileurl' => $urlname));
      //move_uploaded_file($_FILES['images']['tmp_name'][$i],'upload/'.$filename);
  }

  $wpdb->insert('wp_cardetails', array('id' => $id, 'regno' => $_POST['regno'], 'mobilenumber' => $_POST['mobilenumber'], 'name' => $_POST['name'], 'zip' => $_POST['zipcode'], 'carbrand' => $_POST['carbrand'], 'model' => $_POST['model'])); 
  echo "<script>alert('Successfully saved new repair request!');</script>";
}