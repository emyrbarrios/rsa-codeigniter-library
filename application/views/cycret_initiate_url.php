<h2>Enter URL</h2>
The URL you entered is incorrect or does not exist. Please enter a valid URL:
<?php echo validation_errors(); ?>

<?php echo form_open('cycret/initiate');?>
	<label for="url">URL</label> 
	<input type="input" name="url" /><br />
	
	<input type="submit" name="submit" value="Go" /> 
</form>
