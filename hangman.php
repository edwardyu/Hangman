<?php
//load words from text file
$words = array();
$handle = fopen('wordsEn.txt', 'r');
$wrong = 0;
$wrong_limit = 7;
while(!feof($handle))
{
    $words[] = trim(fgets($handle));
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

//let the computer guess the most likely letter for you
function cheat()
{
    $matches = array();
    global $words;
    global $current_guess;
    foreach($words as $word)
    {
        $match = true;
        $word = str_split($word);
        for($i = 0; $i < sizeof($word); $i++)
        {
            if(!empty($current_guess[$i]) && $current_guess[$i] != $word[$i])
            {
                $match = false;
                break;
            }
                
            
        }
        
        if($match)
        {
            $matches[] = $word;
        }
    }
    
    return get_most_frequent_char($matches);
}

function get_most_frequent_char($array)
{
    global $guessed_chars;
    
    $frequency = array();
    foreach($array as $word)
    {
        foreach($word as $char)
        {
            if(isset($frequency["$char"]))
                    $frequency["$char"]++; 
            else
                $frequency["$char"] = 1;
        }
    }
    
    natsort($frequency);
    end($frequency);
    while(in_array(key($frequency), $guessed_chars))
    {
        prev($frequency);
    }
    return key($frequency);
}
//main interface
function play()
{
    global $words;
    global $wrong;
    global $wrong_limit;
    global $guessed_chars;
    global $current_guess;
    $wrong = 0;
    $guessed_chars = array();
    $current_guess = array();
    print "Welcome to Hangman!\n";
    print "You have $wrong_limit attempts to guess the correct word.\n";
    print "The computer is choosing a word...\n\n";
    $word = choose_word($words);
    $guess = '';
    while(true)
    {
        print "Guess a character: ";
        $guess = trim(fgets(STDIN));
        if($guess == 'cheat')
        {
            $suggested_char = cheat();
            print "The computer recommends you choose '$suggested_char'.\n";
        }
        else if(in_array($guess, $guessed_chars))
        {
            print "You've already guessed this character!\n\n";
            
        }
        else if(check_guess($guess, $word) == false)
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
            print "You've guessed it! The word is $word\n\n";
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
