<?php


require_once './lib/Datastore.php';

$datastore = new Datastore();


?>

<html>
<head>

</head>
<body>
<?php
    if(isset($_POST['submit']))
    {
        $data = $datastore->mark($_POST);
        $questions = $data['resource'];
        $score = $data['correct'] . "/" . $data['total'];
        echo "<h3>" . $score . "</h3>";
    }
    else
    {
        $questions = $datastore->getResource(TRUE);
    }

?>
<form action="" method="post">
    <?php foreach($questions as $question): ?>
        <div class="question">
            <div class="q">
                <h4><?php echo $question['name']; ?></h4>
            </div>
            <?php if($question['type'] === 'mcq'): ?>
                <ol>
                    <?php foreach($question['choices'] as $choice): ?>
                        <li>
                            <input type="radio" name="<?php echo $question['id']; ?>" value="<?php echo $choice; ?>"> <?php echo $choice; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>

            <?php else: ?>
                <input type="text" name="<?php echo $question['id']; ?>" autocomplete="off" >
            <?php endif; ?>
            <?php if(array_key_exists('result', $question)): ?>
                <?php echo $question['result']; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <input type="submit" name="submit" value="Send for marking">
</form>


</body>
</html>