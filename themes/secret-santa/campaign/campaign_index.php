

<form action="/Campaign/create" method="post">

    <input type="text" name="title" placeholder="Titel" />
    <input type="date" name="startdate" placeholder="Startdatum" />
    <button type="submit" name="create-campaign">Erstellen</button>
</form>

<br/>
<?php foreach ($dataFromDB as $entry):
    echo "Id: " . $entry['id'];
    echo "Title: " . $entry['title'];
    echo "Startdate: " . $entry['startdate'];
    echo " <a href='/Campaign/delete?id=". $entry['id'] . "'>X</a>";
    echo " <a href='/Campaign/addParticipant?id=". $entry['id'] . "'>O</a>";
    echo "<br/> <a href='/Campaign/showParticipant?id=". $entry['id'] . "'>Alle Teilnehmer</a>";
endforeach; ?>