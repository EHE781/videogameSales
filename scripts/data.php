<?php
require_once 'db.php';
?>

<?php
if (isset($_GET['choice'])) {
    switch ($_GET['choice']) {
        case 'pie':
            #SELECT of first 20 best sales in database
            $query = DB::run("SELECT
    pub.publisher_name AS 'Publisher', SUM(rs.num_sales) AS 'Global_Sales'
    FROM region_sales rs
    INNER JOIN region r ON rs.region_id = r.id
    INNER JOIN game_platform gp ON rs.game_platform_id = gp.id
    INNER JOIN game_publisher gpub ON gp.game_publisher_id = gpub.id
    INNER JOIN game g ON gpub.game_id = g.id
    INNER JOIN platform pl ON gp.platform_id = pl.id
    INNER JOIN publisher pub ON gpub.publisher_id = pub.id
    GROUP BY pub.publisher_name
    ORDER BY SUM(rs.num_sales) DESC");
            $statement = $query->fetchAll(PDO::FETCH_ASSOC);
            $top20 = array();
            for ($i = 0; $i < 20; $i++) {
                $top20[$i] = $statement[$i];
            }
            echo json_encode($top20);
            break;
        case 'race':
            $year = $_GET['year'];
            $consult = array();
            $i = 0;
            $query = DB::run("SELECT
    g.game_name AS 'Name', SUM(rs.num_sales) AS 'Global_Sales'
    FROM region_sales rs
    INNER JOIN region r ON rs.region_id = r.id
    INNER JOIN game_platform gp ON rs.game_platform_id = gp.id AND gp.release_year = ?
    INNER JOIN game_publisher gpub ON gp.game_publisher_id = gpub.id
    INNER JOIN game g ON gpub.game_id = g.id
    INNER JOIN platform pl ON gp.platform_id = pl.id
    INNER JOIN publisher pub ON gpub.publisher_id = pub.id
    GROUP BY g.game_name, pl.platform_name, gp.release_year, pub.publisher_name
    ORDER BY SUM(rs.num_sales) DESC", [$year]);
            $fullStatement = $query->fetchAll(PDO::FETCH_ASSOC);
            $statement = array();
            for ($it = 0; $it < 20 && $it < count($fullStatement); $it++) {
                $statement[$it] = $fullStatement[$it];
            }
            $consult[$i++] = $statement;
            #$js_code = 'console.log(' . json_encode($statement, JSON_HEX_TAG) .
            #    ');';
            #$js_code = '<script>' . $js_code . '</script>';
            #echo $js_code;
            while ($year < 2020) {
                $year += 1;
                $query = DB::run("SELECT
    g.game_name AS 'Name', SUM(rs.num_sales) AS 'Global_Sales'
    FROM region_sales rs
    INNER JOIN region r ON rs.region_id = r.id
    INNER JOIN game_platform gp ON rs.game_platform_id = gp.id AND gp.release_year = ?
    INNER JOIN game_publisher gpub ON gp.game_publisher_id = gpub.id
    INNER JOIN game g ON gpub.game_id = g.id
    INNER JOIN platform pl ON gp.platform_id = pl.id
    INNER JOIN publisher pub ON gpub.publisher_id = pub.id
    GROUP BY g.game_name, pl.platform_name, gp.release_year, pub.publisher_name
    ORDER BY SUM(rs.num_sales) DESC", [$year]);
                $fullStatement = $query->fetchAll(PDO::FETCH_ASSOC);
                for ($it = 0; $it < 20 && $it < count($fullStatement); $it++) {
                    $statement[$it] = $fullStatement[$it];
                }
                $consult[$i++] = $statement;
            }
            echo json_encode($consult);
            break;
        case 'donut':
            $query = DB::run("SELECT genre_name AS 'Genre' FROM genre");
            $genres = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = DB::run("SELECT
            g.game_name AS 'Name', genre.genre_name AS 'Genre'
            FROM region_sales rs
            INNER JOIN region r ON rs.region_id = r.id
            INNER JOIN game_platform gp ON rs.game_platform_id = gp.id
            INNER JOIN game_publisher gpub ON gp.game_publisher_id = gpub.id
            INNER JOIN game g ON gpub.game_id = g.id
            INNER JOIN platform pl ON gp.platform_id = pl.id
            INNER JOIN publisher pub ON gpub.publisher_id = pub.id
            INNER JOIN genre ON g.genre_id = genre.id
            GROUP BY g.game_name
            ORDER BY genre.genre_name DESC;");
            $statement = $query->fetchAll(PDO::FETCH_ASSOC);
            $classifiedGames = array();
            for ($i = 0; $i < count($genres); $i++) {
                $classifiedGames[$genres[$i]['Genre']] = $genres[$i]['Genre'];
                $classifiedGames[$genres[$i]['Genre']] = array();
            }
            $hj = 0;
            foreach ($statement as $game) {
                //$classifiedGames[$game['Genre']][] = $game['Name'];
                array_splice($classifiedGames[$game['Genre']], 0, 0, $game['Name']);
                /*if ($hj++ < 1) {
                    echo '<script>console.log(' . var_dump($classifiedGames) . ')</script>';
                } */
            }

            echo json_encode($classifiedGames);
            break;
        default:
            echo 'alert(Query or request not defined or unknown!)';
            break;
    }
}
?>