<?php

namespace App\Utils;

class Calculator {
    public static function stdev($arr) 
    { 
        if (empty($arr))
            return 0;
        $num_of_elements = count($arr); 
        $variance = 0.0; 
        $average = Calculator::arg($arr);
        foreach($arr as $i) 
        { 
            $variance += pow(($i - $average), 2); 
        } 
          
        return (float)sqrt($variance/$num_of_elements); 
    } 

    public static function arg($arr)
    {
        if (empty($arr))
            return 0;
        $num_of_elements = count($arr); 
        return array_sum($arr)/$num_of_elements; 
    }
}
