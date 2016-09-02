<?php

if(!function_exists('is_get'))
{
    /**
     * Функция проверки GET запроса.
     *
     * @return bool
     */
    function is_get()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}

if(!function_exists('is_post'))
{
    /**
     * Функция проверки POST запроса.
     *
     * @return bool
     */
    function is_post()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

/* End of file Request.php */
/* Location: ./lib/helpers/Request.php */
