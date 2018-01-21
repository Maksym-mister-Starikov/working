<?php
?>

<!DOCTYPE html>
<html>
    <head>
        <title>my work</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            table, th, td {
            border: 1px solid black;
            border-collapse: collapse;}
        </style>
    </head>
    <body>
        <div>
            <form action="" method="post" enctype="multipart/form-data">
                <input name="images" type="file">
                <input name="submit" type="submit">
            </form>
        </div>
       <div>
           <table style="border:1px solid black; width: 50%;height: 50%;" >
                <?php for ($i = 0; $i < count($finish); $i++): ?>
                    <tr>
                        <?php  for ($j = 0; $j < count($finish[$i]); $j++): ?>
                        
                            <th><?php echo $finish[$i][$j]; ?></th>
                            
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>
    </body>
</html>

