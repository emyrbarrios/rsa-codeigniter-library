<h2>Enter URL</h2>
Enter URL:
<?php echo validation_errors(); ?>

<?php echo form_open('cycret/url');?>
	<label for="uniqueUrl">URL</label> 
	<input type="input" name="uniqueUrl" /><br />
	
	<input type="submit" name="submit" value="Go" /> 
</form>
