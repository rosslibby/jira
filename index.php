<?php

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

# This application serves as a slack Jira client
# Functionality includes:
#
# - Work item linking: /jira [item id] [message]
# - Commit linking: /jira -git [environment] [full sha]
# - Build linking: /jira build [item id]
#
# The application key
$app_key = $_ENV['APP_TOKEN'];
$jiraDomain = $_ENV['JIRA_DOMAIN'];

# Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];
$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$channel_name = $_POST['channel_name'];
$team_id = $_POST['team_id'];
$team_domain = $_POST['team_domain'];

# Check the token and make sure the request is from our team
if($token != $app_key){ #replace this with the token from your slash command configuration page
    $msg = "The token for the slash command doesn't match. Check your script.";
    die($msg);
    echo $msg;
}

if ($text == '-help') {
    $response = "Supported commands:\n\n";
    $response .= "`/jira [your #{item id} message]`\n";
    $response .= "`/jira [item id] [message]`\n";
} else {

    # explode the commands to decipher text and shit
    $shrapnel = explode(' ', $text);

    if (count($shrapnel) > 1) {
        if (strpos($text, ' #') !== false) {
            # find the ID in the text
            $isolate = explode('#', $text)[1];
            $getid = explode(" ", $isolate)[0];
            $id = $getid;

            # build the link
            $link = "<".$jiraDomain.$id."|#".$id.">";

            $message = '';

            # replace the id with its link
            foreach ($shrapnel as $word) {
                if (substr($word, 0, 1) == '#') {
                    $message .= ' '.$link;
                } else {
                    $message .= ' '.$word;
                }
            }

            $response = $message;
        } else {

            # Get the work item id
            $id = $shrapnel[0];

            # Get the message
            $message = implode(' ', array_slice($shrapnel, 1));

            # Build the response
            $response = "*Jira #<".$jiraDomain.$id."|".$id.">* _".$message."_";
        }
    } else {
        $response = "*Jira #<".$jiraDomain.$text."|".$text.">*";
    }
}

header('Content-type: application/json');

# Build our response
$reply = [
    'response_type' => 'in_channel',
    'text' => $response
];

# Send the reply back to the user.
echo json_encode($reply);