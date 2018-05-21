<?php
$family = [
    'Nasty' => 34,
    'Mira' => 4,
    'Danya' => 10,
    "Viktor" => 35,
    'Sviatoslav' => 4
];
foreach ($family as $name => $age) {
    echo "<br>Hello, ";
    echo "<br><b>" . $name . ": My name is " . $name . "</b>";
    echo "<br> I am " . $age . " years old";

}


$digits_a = rand(10, 100);
$digits_b = rand(10, 100);
 echo "<br>".$digits_a." умножить на ".$digits_b." = ".$digits_a*$digits_b;

