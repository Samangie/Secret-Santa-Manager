<?php foreach ($dataFromDB as $entry):
    echo "Id;" . $entry['id'];
    echo "Title;" . $entry['title'];
    echo "Startdate;" . $entry['startdate'];
 endforeach; ?>

<form action="/Campaign/create" method="post">

    <input type="text" name="title" placeholder="Titel" />
    <input type="date" name="startdate" placeholder="Startdatum" />
    <button type="submit" name="create-campaign">Button</button>
</form>