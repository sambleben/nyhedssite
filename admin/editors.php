<?php
    // denne fil inkluderes fra index.php, så der er allerede hul igennem
    // til database, sessions og al design osv...
    // skulle det ske at nogen prøvede at åbne filen direkte,
    // så indlæses siden korrekt med en header-location
    if ( !isset($database_link))
    {
        die(header('location: index.php?page=editors'));
    }
?>

<h2>Redaktør Administration</h2>
<div class="panel panel-info">
	<p class="panel-heading">
		Dette skal du lave :)
	</p>
	<div class="panel-body">
		<ul>
			<li>
				Hver enkelt redaktør skal have adgang til en eller flere kategorier.
			</li>
			<li>
				Slettes en kategori, så skal redaktørens adgang selvfølgelig også fjernes
			</li>

		</ul>
	</div>
</div>


