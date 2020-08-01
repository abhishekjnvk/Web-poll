<?
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Add Poll</title>
</head>

<body class="container">
    <form class="col-lg-6 border border-dark mx-auto mt-5 p-4 rounded" method="post" action="<?=$_ENV['baseURL']?>/home/add">

        <?
    if(isset($_SESSION['page_message'])){?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>
                <?= $_SESSION['page_message'] ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?}?>
        <div class="form-group">
            <label for="poll_title">Poll Title</label>
            <input type="text" class="form-control" id="poll_title" name="poll_title" placeholder="Pole Title">
            <input type="hidden" class="form-control" name="poll_hash" value="<?= md5(time() + rand(0, 9999)) ?>">
        </div>

        <div id="options">
            <label>Options</label>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Options" name="options[]">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Options" name="options[]">
            </div>
        </div>
        <button id="add" class="btn btn-sm btn-primary" type="button">Add More Option</button>

        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="visibility" id="gridCheck">
                <label class="form-check-label" for="gridCheck">
                    Public Poll
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create Poll</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>

<script>
    $("#add").click(function() {
        $("#options").append(`
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Options" name="options[]">
        </div>
        `);
    });
</script>