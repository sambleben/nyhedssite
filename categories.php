<?php
    // denne fil inkluderes fra index.php, så der er allerede hul igennem
    // til database, sessions og al design osv...
    // skulle det ske at nogen prøvede at åbne filen direkte,
    // så indlæses siden korrekt med en header-location
    if ( !isset($database_link))
    {
        die(header('location: index.php?page=categories'));
    }

    // denne side er afhængig af at der findes en kategori id i adressebaren
    if ( !isset($_GET['category_id']))
    {
        // hvis der IKKE findes en category id i URL'en så udskrives en besked
        echo '<p class="alert alert-info"><strong>INFO</strong> Der er ikke valgt nogen kategori...</p>';
    }
    else
    {
        // når der ER en category id i URL'en, så hent nyhederne fra kategorien.
        // Husk lige at sikre at id'en er et TAL (ved at gange med 1))
        $category_id = ($_GET['category_id'] * 1);
        $query = "  SELECT news_id, news_title, news_content, news_postdate, user_name
                    FROM news
                    INNER JOIN categories ON category_id = news.fk_categories_id
                    INNER JOIN users ON user_id = news.fk_users_id 
                    WHERE news.fk_categories_id = $category_id
                    ORDER BY news_postdate DESC";
        $result = mysqli_query($database_link, $query) or if_sql_error_then_die(mysqli_error($database_link), $query, __LINE__, __FILE__);

        if (mysqli_num_rows($result) <= 0)
        {
            // hvis der ikke er noget resultat fra databasen
            // (rækker <= 0) så udskrives en besked
            echo '<p class="alert alert-info"><strong>INFO</strong> Der er ikke oprettet nogen nyheder under denne kategori...</p>';
        }
        else
        {
            // når der ER rækker fra databasen, så udskriv dem
            while ($row = mysqli_fetch_assoc($result))
            {
                $news_id = $row['news_id'];
                $news_title = $row['news_title'];
                // nyhedens indhold kommer til at bestå af HTML fra CKeditoren
                // derfor stippes al HTML med php-funktionen strip_tags
                // derefter klippes indholdet ned til 250 tegn.
                $news_content = substr(strip_tags($row['news_content']), 0, 247).'...';
                // datoen hentes ud af databasen, og udskrives på dansk dette
                // virker fordi setlocale() er sat til 'danish, øverst på
                // index.php
                $news_postdate = strftime('%d. %B %Y - %H:%M', strtotime($row['news_postdate']));
                $user_name = $row['user_name'];

                echo '  <section class="news_category">
                            <h3>'.$news_title.'</h3>
                            <p>
                                <a href="index.php?page=news&amp;category_id='.$category_id.'&amp;news_id='.$news_id.'">'.$news_content.'</a>
                            </p>
                            <em>'.$user_name.' - '.$news_postdate.'</em><hr />
                        </section>';
            }

            // breadcrumbs / krummesti
            // her er det nødvendigt at hente kategoriens titel fra databasen
            // (der kunne sikkert laves noget smart ved at gemme kategorinavnet
            // mens menuen udskrives, for at spare dette SQL kald)
            $query = "  SELECT category_title
                        FROM categories 
                        WHERE category_id = $category_id";
            $result = mysqli_query($database_link, $query) or if_sql_error_then_die(mysqli_error($database_link), $query, __LINE__, __FILE__);
            $row = mysqli_fetch_assoc($result);
            echo '  <div class="bottom">
                        <ul class="breadcrumb">
                            <li><a href="index.php?page=frontpage">Forside</a></li>
                            <li class="active">'.$row['category_title'].'</li>
                        </ul>
                    </div>';
        }
    }
?>