<?php 
/*
Plugin Name: Kadol Semangat Posting | Go 1M2015
Plugin URI: 
Description: Kadol Semangat Posting. Sebuah Persembahan dari KADOL untuk KADOL | Sukses 1M2015
Author: Anggota KADOL
Version: 1.0.1
Author URI: https://www.facebook.com/groups/kadol/

*/

/*
	Helper Function
*/

function loadView ($view, $data = array()) {
	$viewFile = __DIR__."/views/$view.php";
	if(file_exists($viewFile)) {
		extract($data);
		include ($viewFile);
	}
}

/*
	Fungsi menampilkan quote
*/

function getQuote() {
	$data = file_get_contents(__DIR__."/semangat.txt");
	$data = explode("\n", $data);
	return $data[rand(0, count($data)-1)];
}

/*
	Membuat Menu
*/

add_action('admin_menu', 'kadol_addmenu');
function kadol_addmenu() {
    add_menu_page('Kadolers Ayo Posting', 'Ayo Posting', 'manage_options', 'kadol_index', 'kadol_index', plugins_url( 'KadolSemangatPosting/image/icon.ico' ));    
}


/*
	Membuat Tampilan Menu
*/

function kadol_index() {	
	loadView('index');
}


/*
	Fungsi upoad image by link
	Panjang banget, kalo ada yang punya ide lebih simple silahkan disampaikan
*/
function somatic_attach_external_image( $url = null, $post_id = null, $thumb = null, $filename = null, $post_data = array() ) {
    if ( !$url || !$post_id ) return new WP_Error('missing', "Need a valid URL and post ID...");
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    // Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
    $tmp = download_url( $url );

    // If error storing temporarily, unlink
    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);   // clean up
        $file_array['tmp_name'] = '';
        return $tmp; // output wp_error
    }

    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);    // fix file filename for query strings
    $url_filename = basename($matches[0]);                                                  // extract filename from url for title
    $url_type = wp_check_filetype($url_filename);                                           // determine file type (ext and mime/type)

    // override filename if given, reconstruct server path
    if ( !empty( $filename ) ) {
        $filename = sanitize_file_name($filename);
        $tmppath = pathinfo( $tmp );                                                        // extract path parts
        $new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];          // build new path
        rename($tmp, $new);                                                                 // renames temp file on server
        $tmp = $new;                                                                        // push new filename (in path) to be used in file array later
    }

    // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
    $file_array['tmp_name'] = $tmp;                                                         // full server path to temp file

    if ( !empty( $filename ) ) {
        $file_array['name'] = $filename . "." . $url_type['ext'];                           // user given filename for title, add original URL extension
    } else {
        $file_array['name'] = $url_filename;                                                // just use original URL filename
    }

    // set additional wp_posts columns
    if ( empty( $post_data['post_title'] ) ) {
        $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
    }

    // make sure gets tied to parent
    if ( empty( $post_data['post_parent'] ) ) {
        $post_data['post_parent'] = $post_id;
    }

    // required libraries for media_handle_sideload
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // do the validation and storage stuff
    $att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );             // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status

    // If error storing permanently, unlink
    if ( is_wp_error($att_id) ) {
        @unlink($file_array['tmp_name']);   // clean up
        return $att_id; // output wp_error
    }

    // set as post thumbnail if desired
    if ($thumb) {
        set_post_thumbnail($post_id, $att_id);
    }

    return $att_id;
}

/*
	Menangkap Fungsi Ajax
	Menambahkan Aksi Penangkapan Fungsi Ajax 
	add_action( wp_ajax_<nama_action>, nama_fungsi );
*/
add_action('wp_ajax_posting', 'Posting');
function Posting(){    
	// Persiapan awal membuat isi dari postingan baru
	$myPost = array(
		'post_status' => 'publish',
		'post_type' => 'post',		
		'post_title' => $_POST['title'],
		'comment_status' => 'closed',
		'ping_status' => 'closed',
		'post_category' => array($_POST['cat']),
        	'tags_input' => $_POST['tags'],
        	'post_date'      => $_POST['tanggal'], 
        	'post_date_gmt'  => $_POST['tanggal']
	);

	// Membuat Post baru berdasarkan variabel $myPost yang telah di buat
	$newPostID = wp_insert_post($myPost);

	// Membuat attachment
	$att = array('post_title' => $_POST['title']);
	$setatt = somatic_attach_external_image($_POST['imageurl'], $newPostID, null, $_POST['title'], $att);


	//$attlink = wp_get_attachment_image_src($setatt);
	$attlink = wp_get_attachment_image( $setatt, 'medium' );

	// Update post
	$updatePost = array(
	  'ID'           => $newPostID,
	  'post_content' => $attlink
	);

	// Update the post into the database
	$updatePostID = wp_update_post( $updatePost );

	if($setatt and $updatePostID){
		echo '<div class="updated"><p><strong>Berhasil</strong> Postingan berjudul '.$_POST['title'].' berhasil dibuat</p></div>';
	}else{
		echo '<div class="error"><p><strong>ERROR</strong> Terdapat kesalahan pada post yang dibuat</p></div>';
	}

	die();
}

 ?>
