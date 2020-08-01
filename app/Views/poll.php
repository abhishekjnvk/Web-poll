<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Result</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
</head>

<body>
    <div class="col-lg-6 mx-auto mt-5 border border-dark rounded p-5">
        <h1 class="text-center"><?= ($poll_data->title) ?></h1>
        <div class="container text-center">
            <?
                foreach($choice_data as $choice){
            ?>
            <div class="option my-3">
                <button class="btn btn-sm btn-secondary col-lg-4" onclick="abhishek(`<?= $choice->option ?>`, `<?= $choice->value ?>`)"><?= $choice->value ?></button>
            </div>
            <?}?>
        </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

</html>
<script>
    function abhishek(option, value) {
        $.confirm({
            title: 'Please Confirm!',
            content: `Are You Sure You want to vote in favour of <b>${value}</b>`,
            type: 'dark',
            buttons: {
                Yes: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function() {
                        voteNow(option, `<?= $poll_data->poll_id ?>`)
                    }
                },
                No: {
                    text: 'No',
                    btnClass: 'btn-red',
                    action: function() {

                    }
                },
            }
        });
    }




    function voteNow(option) {


        $.confirm({
            type: 'dark',
            content: function() {
                var self = this;
                var settings = {
                    "url": "<?= getenv('baseURL') ?>/Api/voteNow",
                    "method": "POST",
                    "headers": {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    "data": {
                        "pole_id": "<?= $poll_data->poll_id ?>",
                        "option": `${option}`
                    }
                };
                return $.ajax(settings).done(function(response) {
                    // self.setContent('Description: ' + response);
                    self.setTitle(response);
                }).fail(function() {
                    self.setContent('Something went wrong.');
                });
            },
            buttons: {
                'View Result': {
                    text: 'View Result',
                    btnClass: 'btn-green',
                    action: function() {
                        window.location = '<?= getenv('baseURL') ?>/view/result/<?= $poll_data->poll_id ?>'
                    }
                },
            }
        });
    }


    <?php if ($isUserEligible == false) { ?>
        $.alert({
            title: 'You Already Voted For this poll',
            content: '',
            type: 'dark',
            buttons: {
                view: {
                    text: 'View Result',
                    btnClass: 'btn-green',
                    action: function() {
                        window.location = '<?= getenv('baseURL') ?>/view/result/<?= $poll_data->poll_id ?>'
                    }
                }
            }
        });
    <?php } ?>
</script>