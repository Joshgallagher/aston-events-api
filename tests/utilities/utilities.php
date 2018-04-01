<?php

/**
 * Wrapper function around the 'factory()->create()' API.
 *
 * @param string $class
 * @param array  $attributes
 * @param int    $amount
 *
 * @return \PHPUnit\Runner\Filter\Factory
 */
function create(string $class, array $attributes = [], int $amount = null)
{
    return factory("App\Models\\".$class, $amount)->create($attributes);
}

/**
 * Wrapper function around the 'factory()->make()' API.
 *
 * @param string $class
 * @param array  $attributes
 * @param int    $amount
 *
 * @return \PHPUnit\Runner\Filter\Factory
 */
function make(string $class, array $attributes = [], int $amount = null)
{
    return factory("App\Models\\".$class, $amount)->make($attributes);
}
