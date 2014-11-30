<?php 
/*
Plugin Name: Kadol Semangat Posting | Go 1M2015
Plugin URI: 
Description: Kadol Semangat Posting. Sebuah Persembahan dari KADOL untuk KADOL | Sukses 1M2015
Author: Anggota KADOL
Version: 1.0
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

 ?>