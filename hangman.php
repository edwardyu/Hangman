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
$guessed_chars = array();
$current_guess = array();
//print_r($words);

//computer chooses a word, user has to guess the word within 7 tries. 

function choose_word($words)
{
    return trim($words[array_rand($words)]);
}

function check_guess($char, $word)
{
    global $guessed_chars;
    $wordArray = str_split($word);
    $guessed_chars[] = $char;
    global $current_guess;
    if(strpos($word, $char) === false)
    {
       return false;
    }
    else
    {
        for($i = 0; $i < sizeof($wordArray); $i++)
        {
            if($wordArray[$i] == $char)
            {
                $current_guess[$i] = $char;
            }
            else if(!isset($current_guess[$i]))
            {
                $current_guess[$i] = '';
            }
        }
        
        return $current_guess;
    }
    
}

//main interface
function play()
{
    global $words;
    global $wrong;
    global $wrong_limit;
    $wrong = 0;
    print "Welcome to Hangman!\n";
    print "The computer is choosing a word...\n\n";
    $word = choose_word($words);
    $guess = '';
    while(true)
    {
        print "Guess a character: ";
        $guess = trim(fgets(STDIN));
        if(check_guess($guess, $word) == false)
        {
            $wrong++;
            print "You've made $wrong wrong guesses.\n\n";
            if($wrong == $wrong_limit)
            {
                print "Sorry, you lose! The correct word is $word.\n";
                print "Play again? y/n \n";
                if(trim(fgets(STDIN)) != 'y')
                {
                    exit();
                }
                else
                    play();

            }
        }
        else if(check_guess($guess, $word) == str_split($word))
        {
            print "You've guessed it!\n\n";
            print "Play again? y/n \n";
            if(trim(fgets(STDIN)) != 'y')
            {
                exit();
            }
            else
                play();
        }
        else 
        {
         print_r(check_guess($guess, $word));
        }

    }
}

play();

?>
