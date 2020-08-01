<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Result</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <style>
        .results {
            height: 21px;
            width: 100%;
            margin: 5px 0 0;
        }

        .results .on {
            height: 21px;
            opacity: 0.75;
            width: 0;
        }

        .results .on .count {
            line-height: 21px;
            vertical-align: middle;
            text-align: left;
            margin: 0 10px;
            position: relative;
            right: 0%;
            display: inline-block;
        }

        .results {
            background-color: #B3D4FA;
        }
        .results .on {
            background-color: #0C78F4;
        }
    </style>
</head>
<body>
    <div class="container mt-5 border border-dark rounded p-5">
        <h1 class="title"><?= ($poll_data->title)?></h1>
        <div class="container">
            <?
                foreach($choice_data as $choice){
            ?>
            <div class="option my-3">
                <h5 class="option-label"><?= $choice->value ?></h3>
                <div class="results">
                    <div class="on" style="width: <?=round((($choice->vote)/$total_vote),2)*100?>%;">
                        <span class="count text-white"><?=round((($choice->vote)/$total_vote),2)*100?> % (<?=$choice->vote?>)</span>
                    </div>
                </div>
            </div>
            <?}?>
            <br>
            Total Votes: <?=$total_vote?>
        </div>
    </div>
</body>

</html>