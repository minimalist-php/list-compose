<?php

# <php-modules>

# https://cdn.jsdelivr.net/gh/minimalist-php/list-addition@2024.05.1/index.php
define("minimalist-php/list-addition@2024.05.1", function () {
return function ($parameters) {
    $list = $parameters["list"];
    $carry = $parameters["carry"];
    $iteration = $parameters["iteration"];
    $entry = -1;

    $list_addition = function (...$parameters) use ($list, $iteration, &$entry) {
        $carry = $parameters[0];
        $element = $parameters[1];
        $entry = $entry + 1;

        return $iteration([
            "carry" => $carry,
            "element" => $element,
            "entry" => $entry,
            "list" => $list
        ]);
    };
    return array_reduce($list, $list_addition, $carry);
};
});

# </php-modules>

$list_addition = constant("minimalist-php/list-addition@2024.05.1")();

return function ($expressions) use ($list_addition) {

    if (count($expressions) === 0) {
        throw new Exception("list_compose: The list must not be empty");
    };
    $iteration = function ($parameters) use ($expressions) {
        $entry = $parameters["entry"];
        $carry = $parameters["carry"];
        $last_expression = $entry === count($expressions) - 1;

        if (! $last_expression) {
            return $expressions[$entry + 1]($carry);
        };
        return $carry;
    };
    return $list_addition([
        "list" => $expressions,
        "carry" => $expressions[0],
        "iteration" => $iteration
    ]);
};
