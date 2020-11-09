<?php

/// ...


// init inputs - they are on the verge for using as global variable
$inputs = new \kalanis\kw_input\Inputs();
$inputs->loadInputs($argv); // argv is for params from cli

/// ...

/// init core
$system = new Core1();

/// ...

$system->setInputs($inputs); // and kwcms3 core got every input value that came in the same defined way

/// ...
