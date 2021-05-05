<?php 
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

 /**
  * Under "global", specify the Filters you want to apply in all URL addresses in your project. 
  * 
  */
$config["globals"] = [
    "filters"    => [

    ],

    "before"    => [
        
    ],

    "after"     => [
        
    ]
];

$config["filters"] = [
    "myFilter" => [
        "before" => [
            "/:any"
        ],
        "after" => [
            "/:any"
        ]
    ]
];
