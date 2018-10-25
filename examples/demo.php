<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo "------------------------------------------------------------------------------------------\n";
echo "- This demo shows how to obtain a token and evaluate a flag within the Flags application -\n";
echo "------------------------------------------------------------------------------------------\n\n";

echo "- Initialize a flag with identifier -\n";

$flag = new \Flags\Flag('zonK7735w4P1qecQCB1flw');
$user = 'NLkbalhbjhdbvksjdhbfva';

echo "- Create an evaluator instance -\n";

$evaluator = new \Flags\Evaluator();

echo "- Evaluate the flag -\n";

try {
    $response = $evaluator->evaluate($flag, $user);
    $result = $response->getResult() ? 'On' : 'Off';
    echo sprintf("Flag %s is %s for user %s", $flag->getIdentifier(), $result, $user);
} catch (\Flags\Exception\EvaluationException|\Flags\Exception\ConnectionException $e) {
    echo "Oops! Something went wrong: \n";
    echo $e->getMessage() . "\n";
}


echo "---------------------\n";
echo "- End of Flags demo -\n";
echo "---------------------\n\n";