<?php
function block_qmulsearch_get_string(string $identifier, string $plugin = 'block_qmulsearch', $parameters = null):string{
    try {
        $string = get_string($identifier, $plugin, $parameters);
    } catch (coding_exception $e) {
        $string = $identifier;
    }
    return $string;
}