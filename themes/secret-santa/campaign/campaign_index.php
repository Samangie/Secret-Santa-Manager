<?php foreach ($dataFromDB as $entry):
    echo "Id;" . $entry['id'];
    echo "Title;" . $entry['title'];
    echo "Startdate;" . $entry['startdate'];
    echo "<a href='/Campaign/delete?id=". $entry['id'] . "'>X</a>";
    echo "<a href='/Campaign/addUser?id=". $entry['id'] . "'>O</a>";
 endforeach; ?>

<form action="/Campaign/create" method="post">

    <input type="text" name="title" placeholder="Titel" />
    <input type="date" name="startdate" placeholder="Startdatum" />
    <button type="submit" name="create-campaign">Button</button>
</form>