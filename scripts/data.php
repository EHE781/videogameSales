<?php
require_once 'db.php';
?>

<?php
if (isset($_GET['choice'])) {
    switch ($_GET['choice']) {
        case 'pie':
            #SELECT of first 20 best sales in database
            $query = DB::run("SELECT
    g.game_name AS 'Name', SUM(rs.num_sales) AS 'Global_Sales'
    FROM region_sales rs
    INNER JOIN region r ON rs.region_id = r.id
    INNER JOIN game_platform gp ON rs.game_platform_id = gp.id
    INNER JOIN game_publisher gpub ON gp.game_publisher_id = gpub.id
    INNER JOIN game g ON gpub.game_id = g.id
    INNER JOIN platform pl ON gp.platform_id = pl.id
    INNER JOIN publisher pub ON gpub.publisher_id = pub.id
    GROUP BY g.game_name, pl.platform_name, gp.release_year, pub.publisher_name
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
    ORDER BY SUM(rs.num_sales) DESC", $year);
            $js_code = 'console.log(' . json_encode($query, JSON_HEX_TAG) .
                ');';
            var_dump($query);
            echo '$.console.log(We have a query: ' . $query . ')';
            if ($with_script_tags) {
                $js_code = '<script>' . $js_code . '</script>';
            }
            echo $js_code;
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
    ORDER BY SUM(rs.num_sales) DESC", $year);
                array_push($consult, $query);
            }
            echo 'alert(' . $consult . ')';
            break;
        default:
            break;
    }
}
?>;