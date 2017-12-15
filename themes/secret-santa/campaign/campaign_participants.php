<?php
echo "<a href='/Campaign/assign?id=" . $_GET['id'] ."' > Zuweisen </a> '";
 foreach ($dataFromDB as $entry):
    echo "Id: " . $entry['username'];
endforeach; ?>