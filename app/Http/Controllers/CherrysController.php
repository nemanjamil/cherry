<?php

namespace App\Http\Controllers;

use App\Cherry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CherrysController extends Controller
{

    function oneeurobet($euro)
    {
        return $euro * 100;
    }

    function randomNumbers()
    {
        $input = array("9", "10", "J", "Q", "K", "A", "cat", "dog", "monkey", "bird");
        //$input = array("9", "10","J");
        $rand_keys = array_rand($input, 1);
        return $input[$rand_keys];
    }

    function listRandom($n = 15)
    {
        $ary = [];
        foreach (range(0, $n - 1) as $number) {
            $ary[$number] = $this->randomNumbers();
        }
        return $ary;
    }

    public function  index()
    {
        $listRandom = $this->listRandom();
        echo $this->showTable($listRandom);
        $ww = $this->listofPatterns($listRandom);

        // SHOW ARRAY OF WINS
        // var_dump($ww);

        $bet_amount = $this->oneeurobet(1);
        $total_win = 0;
        $lr = [];
        foreach($ww as $m => $v) {

            $listawe = implode(" ",$v[1]);
            if ($v[2]==3) {
                $price = $bet_amount * 0.2;
            } else if ($v[2]==4){
                $price = $bet_amount * 2;
            } else {
                $price = $bet_amount * 10;
            }
            $total_win += $price;
            $lr[] = array($listawe => $v[2]);
        }


        echo "Bet Amount: ".$bet_amount."<br>";
        echo "Total Win : ".$total_win."<br>";
        echo "Count Win/s: ".count($ww)."<br>";
        echo "<br>JSON RESPONSE <br>";

        $arr['board'] = $listRandom;
        $arr['paylines'] = $lr;
        $arr['bet_amount'] = $bet_amount;
        $arr['total_win'] = $total_win;
        echo json_encode($arr);




    }

    public function listofPatterns($listRandom)
    {

        $horizontalPattern = $this->hp([0, 1, 2], 3);
        $verticalPattern = $this->vp([0, 3, 6, 9, 12], 1);
        $dinamicPattern = $this->dp(5, 3);
        $result = array_merge($horizontalPattern, $verticalPattern, $dinamicPattern);
        return $ww = $this->validateBet($result, $listRandom);



    }

    public function dp($column, $row)
    {

        // !!!!!! !!!!!!! !!!!!  On this example a presume that win position is this one's
        $dp = [];
        $dp[] = [0,4,8,10,12];
        $dp[] = [3,7,11,13];
        $dp[] = [1,3,7,11]; // ?? i presume this one is win position
        $dp[] = [2,4,6,10,14];
        $dp[] = [5,7,9,13];
        $dp[] = [1,5,7,9]; // ?? i presume this one is win position


        return $dp;

    }


    public function validateBet($result, $listRandom)
    {
        $win = [];

        foreach ($result as $hp) {


            $hit = [];
            $previousValue = null;
            foreach ($hp as $irr) {
                $current = $listRandom[$irr];

                if ($previousValue == $current) {
                    array_push($arr, $current);
                } else {
                    $arr = [];
                    $arr[] = $current;
                }

                if (count($arr) >= 3) {
                    $hit = [];
                    $hit[] = $arr;
                    $hit[] = $hp;
                    $hit[] = count($arr);
                }
                $previousValue = $current;
            }

            if ($hit) {
                $win[] = $hit;
            }



        }

        return $win;


    }

    public function vp($ary, $int)
    {
        $pat = [];
        foreach ($ary as $i => $item) {
            $intary = [];
            for ($i = $item; $i < $item + 3; $i++) {
                $intary[] = $i;
            }
            $pat[$item] = $intary;
        }
        return $pat;
    }

    public function hp($ary, $int)
    {
        $pat = [];
        foreach ($ary as $i => $item) {
            $intary = [];
            for ($i = $item; $i < $int * 5; $i += 3) {
                $intary[] = $i;
            }
            $pat[$item] = $intary;
        }
        return $pat;
    }

    public function showTable($listRand)
    {
        return $t = '<table style="width: 100%;" border="1" cellpadding="9">
            <tbody>
            <tr>
            <td>' . $listRand[0] . '</td>
            <td>' . $listRand[3] . '</td>
            <td>' . $listRand[6] . '</td>
            <td>' . $listRand[9] . '</td>
            <td>' . $listRand[12] . '</td>
            </tr>
            <tr>
            <td>' . $listRand[1] . '</td>
            <td>' . $listRand[4] . '</td>
            <td>' . $listRand[7] . '</td>
            <td>' . $listRand[10] . '</td>
            <td>' . $listRand[13] . '</td>
            </tr>
            <tr>
            <td>' . $listRand[2] . '</td>
            <td>' . $listRand[5] . '</td>
            <td>' . $listRand[8] . '</td>
            <td>' . $listRand[11] . '</td>
            <td>' . $listRand[14] . '</td>
            </tr>
            </tbody>
            </table>';
    }
}