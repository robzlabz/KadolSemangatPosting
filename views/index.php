<div class="wrap">
	<h2>KADOLers Semangat Posting</h2>
	<form action="" method="POST" id="form">
		<input type="hidden" name="action" value="posting">
		<table class="form-table" id="table">
			<tr>
				<th><label>Nama Posting : </label></th>
				<td>
					<input type="text" name="title" style="width:70%" placeholder="Judul Posting">
				</td>
			</tr>
			<tr>
				<th><label>Category : </label></th>
				<td>
					<?php wp_dropdown_categories(array('hide_empty' => 0)) ?>
				</td>
			</tr>
			<tr>
				<th><label>Tags : </label></th>
				<td>
					<input type="text" name="tags" style="width:70%" placeholder="Daftar Tags">
					<p class="description">Pisahkan dengan koma (,) ; contoh : (Mobil, Rubicon, Pajero Sport)</p>
				</td>
			</tr>
			<tr>
				<th><label>URL Gambar : </label></th>
				<td>
					<input type="text" name="imageurl" style="width:100%" placeholder="URL Gambar (Contoh : http://contoh.com/gambar.jpg)">
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<button type="submit" class="button-primary" id="push">Posting</button>
				</td>
			</tr>
		</table>
	</form>
	<hr>
	<p><strong>Random Quote </strong> <?php echo getQuote() ?></p>
	<div id="result"></div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#push").click(function(e){
			e.preventDefault();

			var data = $('#form').serialize();

			$.post(ajaxurl, data, function(result){
				$("#result").html(result).fadeIn(1000).fadeOut(5000);
			})
		});
	});
</script>