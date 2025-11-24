<?php

function eSMAutoload($class)
{
    include "libs/Utils/".$class.'.php';
}

spl_autoload_register('eSMAutoload');