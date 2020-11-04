<div class="locations--store-time">
    <table>
        <?php
        $prev_open = "";
        $prev_close = "";
        $newHours = array();

        if($location->hours){
            foreach (json_decode($location->hours, true) as $key => $day) {
                if(array_key_exists('close', $day)) {
                    if (($prev_open == $day["open"]) && ($prev_close == $day["close"])) {
                        $prevHours = array_pop($newHours);
                        if (isset($prevHours["day_start"]) && !empty($prevHours["day_start"])) {
                            $newHours[] = array("day_start" => $prevHours["day_start"], "day_end" => $key, "open" => $day["open"], "close" => $day["close"]);
                        } else {
                            $newHours[] = array("day_start" => $prev_day, "day_end" => $key, "open" => $day["open"], "close" => $day["close"]);
                        }
                    } else {
                        $newHours[] = array("day" => $key, "open" => $day["open"], "close" => $day["close"]);
                    }

                    $prev_open = $day["open"];
                    $prev_close = $day["close"];
                    $prev_day = $key;
                }else{
                    continue;
                }
            }


            foreach ($newHours as $key => $day) {

                echo "<tr>";
                if ( ($day["open"] || $day["close"]) == 0  ) {
                    if (@$day["day_start"]) {
                        echo "<td class=\"locations--store-time-day\" width=\"230\">" . ucfirst($day["day_start"]) . " - " . ucfirst($day["day_end"])  . "</td>";
                    } else {
                        echo "<td>" . ucfirst($day["day"]) . "</td>";
                    }
                    echo "<td>Closed</td>";
                } else {
                    if (@$day["day_start"]) {
                        echo "<td class=\"locations--store-time-day\" width=\"230\">" . ucfirst($day["day_start"]) . " - " . ucfirst($day["day_end"])  . "</td>";
                    } else {
                        echo "<td class=\"locations--store-time-day\">" . ucfirst($day["day"]) . "</td>";
                    }
                    echo  "<td>" . $day["open"] . "</td>";
                    echo "<td align=\'center\' width=\'15\'> &ndash; </td>";
                    echo "<td>" . $day["close"] . "</td>";
                }
                echo "</tr>";
            }
        }

        ?>
    </table>
</div>