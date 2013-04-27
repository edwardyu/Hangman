<?php
//load words from text file
$words = array();
$handle = fopen('wordsEn.txt', 'r');
$wrong = 0;
$wrong_limit = 7;
while(!feof($handle))
{
    $words[] = fgets($handle);
}

//print_r($words);

//computer chooses a word, user has to guess the word within 7 tries. 

function choose_word($words)
{
    return array_rand($words);
}

function check_guess($char, $word)
{
    $word = str_split($word);
    $afterGuess = '';
    foreach($word as $value)
    {
        if($value == $char)
        {
            $afterGuess .= "$value";
        }
        else
            $afterGuess .= "_";
    }
    
    if(!in_array($char, $word))
    {
        print "You guessed wrong!\n";
        global $wrong;
        global $wrong_limit;
        $wrong++;
        if($wrong == $wrong_limit)
        {
            print "Game over!";
            exit();
        }
        else
            print "You have guessed $wrong wrong characters.\n";
    }
    
    return $afterGuess;
}

//main interface
print "Welcome to Hangman!\n";
print "The computer is choosing a word...";
$word = choose_word($words);
$guess = '';
print "Guess a character: ";

?>
