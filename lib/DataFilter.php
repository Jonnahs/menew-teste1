<?php

class DataFilter
{
    /**
    * Retira pontuacao da string 
    * @param string $st_data
    * @return string
    */
    static function alphaNum( $st_data )
    {
        $st_data = preg_replace("([[:punct:]]| )",'',$st_data);
        return $st_data;
    }
      
    /**
    * Retira caracteres nao numericos da string
    * @param string $st_data
    * @return string
    */
    static function numeric( $st_data )
    {
        $st_data = preg_replace("([[:punct:]]|[[:alpha:]]| )",'',$st_data);
        return $st_data;    
    }
      
      
    /**
     * 
     * Retira tags HTML / XML e adiciona "\" antes
     * de aspas simples e aspas duplas
     * @param string $st_string
     */
    static function cleanString( $st_string )
    {
        return addslashes(strip_tags($st_string));
    }
}
?>