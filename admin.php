<?php

require_once './lib/Datastore.php';
require_once './lib/indent.php';

$datastore = new Datastore();
if(isset($_POST['data']))
{
    $datastore->update($_POST['data']);
}
if(isset($_POST['qname']))
{
    $new_question = array();
    $new_question['name'] = $_POST['qname'];
    $new_question['type'] = $_POST['type'];
    $new_question['opts'] = $_POST['opts'];
    $new_question['correct'] = $_POST['correct'];
    $datastore->add($new_question);
}
$json = indent($datastore->getJSON());

?>

<html>
<head>
<style>
    .left {
        float:left;
        width: 50%;
    }

    .right {
        width: 50%;
        float: left;
    }

    .right input {
        width: 100%;
    }
</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
<div class="block left">
    <form action="admin.php" method="post">
        <textarea rows="50" cols="80" name="data"><?php echo $json; ?></textarea>
        <input type="submit" value="Save">
    </form>
</div>
<div class="block right">
    <form action="admin.php" method="post">
        <p>
            If entering the answers for the MCQ, enter them into the textarea separated by |
        </p>
        <input type="text" placeholder="Question name" id="qname" name="qname"  autocomplete="off" ><br/>
        <select name="type" id="type">
            <option value="mcq">MCQ</option>
            <option value="fill">Fill in the blanks</option>
        </select><br/>
        <input type="text" name="correct" id="correct" placeholder="Correct Answer" autocomplete="off"><br/>
        <textarea name="opts" id="opts">

        </textarea>
        <input type="submit" value="Add">
    </form>
</div>
<script>
    $('#qname').focus();
    $('#correct').on('keyup', function(){
       if($('#correct').val() == "true" || $('#correct').val() == "false")
       {
           $('#type').val('mcq');
           $('#opts').html('true|false');
       }
    });
</script>
</body>
</html>
