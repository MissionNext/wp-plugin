<?php

/**
 * Функция получения элемента массива.
 *
 * @param mixed
 * @param array
 * @param mixed
 *
 * @return mixed
 */
if(!function_exists('element'))
{
    function element($key, $array, $default = FALSE)
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }

        return $default;
    }
}

/* End of file Array.php */
/* Location: ./lib/helpers/Array.php */