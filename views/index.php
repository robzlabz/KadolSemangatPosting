<div class="wrap">
	<h2>KADOLers Semangat Posting</h2>
	<form action="" method="POST">
		<table class="form-table" id="table">
			<tr>
				<th><label>Nama Posting : </label></th>
				<td>
					<input type="text" name="title" style="width:70%" placeholder="Judul Posting">
				</td>
			</tr>
			<tr>
				<th><label>URL Gambar : </label></th>
				<td>
					<input type="text" name="imageurl" style="width:100%" placeholder="URL Gambar (ex : http://contoh.com/gambar.jpg)">
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
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#push").click(function(e){
			e.preventDefault();

			alert("Clicked");
		});
	});
</script>