<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo "------------------------------------------------------------------------------------------\n";
echo "- This demo shows how to obtain a token and evaluate a flag within the Flags application -\n";
echo "------------------------------------------------------------------------------------------\n\n";

echo "- Request an auth token to connect to the service \n";
$user = new \Flags\User('demouser@flags.com', 'q1w2e3r4');
$authorizer = new \Flags\Authorizer();

try {
    $authorizer->authorize($user);

    echo "- Initialize a flag with identifier \n";

    $flag = new \Flags\Flag('zonK7735w4P1qecQCB1flw');
    $applicationUser = 'NLkbalhbjhdbvksjdhbfva';

    echo "- Create an evaluator instance \n";

    $evaluator = new \Flags\Evaluator();

    echo "- Evaluate the flag \n";
    $response = $evaluator->evaluate($flag, $user, $applicationUser);
    $result = $response->getResult() ? 'On' : 'Off';
    echo sprintf("Flag %s is %s for user %s \n\n", $flag->getIdentifier(), $result, $applicationUser);

} catch(\Flags\Exception\AuthException $e) {
    echo "Couldn't authorize user: \n";
    echo $e->getMessage() . "\n\n";
} catch (\Flags\Exception\EvaluationException|\Flags\Exception\ConnectionException $e) {
    echo "Oops! Something went wrong: \n";
    echo $e->getMessage() . "\n\n";
}


echo "---------------------\n";
echo "- End of Flags demo -\n";
echo "---------------------\n\n";