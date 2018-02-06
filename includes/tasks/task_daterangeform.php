<div class="form-group">
    <label for="from">From:</label>
    <input type="date" class="form-control" value="<?= $_GET['from']; ?>" name="from" id="from">
	</div>
<div class="form-group">
    <label for="to">To:</label>
    <input type="date" class="form-control" value="<?= $_GET['to']; ?>" name="to" id="to">
	</div>
	<button class="btn btn-success btn-sm" type="submit" name="rangeFilter">FILTER DUE DATE</button>