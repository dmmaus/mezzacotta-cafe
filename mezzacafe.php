<?php

require_once('../md5rand.php');

$mc_file_flags = FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES;
# foods
$mc_amounts       = file('amount.txt',       $mc_file_flags);
$mc_ingredients   = file('ingredient.txt',   $mc_file_flags);
$mc_joins         = file('join.txt',         $mc_file_flags);
$mc_methods       = file('method.txt',       $mc_file_flags);
$mc_presentations = file('presentation.txt', $mc_file_flags);
# wines
$mc_wine_adjectives        = file('wine_adjective.txt',        $mc_file_flags);
$mc_wine_bouquets          = file('wine_bouquet.txt',          $mc_file_flags);
$mc_wine_bouquet_joins     = file('wine_bouquet_join.txt',     $mc_file_flags);
$mc_wine_colours           = file('wine_colour.txt',           $mc_file_flags);
$mc_wine_colour_adjectives = file('wine_colour_adjective.txt', $mc_file_flags);
$mc_wine_descriptors       = file('wine_descriptor.txt',       $mc_file_flags);
$mc_wine_grapes            = file('wine_grape.txt',            $mc_file_flags);
$mc_wine_grape_adjectives  = file('wine_grape_adjective.txt',  $mc_file_flags);
$mc_wine_intensifiers      = file('wine_intensifier.txt',      $mc_file_flags);
$mc_wine_regions           = file('wine_region.txt',           $mc_file_flags);
$mc_wine_types             = file('wine_type.txt',             $mc_file_flags);
$mc_winery_adjectives      = file('winery_adjective.txt',      $mc_file_flags);
$mc_winery_names           = file('winery_name.txt',           $mc_file_flags);
$mc_winery_nouns           = file('winery_noun.txt',           $mc_file_flags);

function replace_hashes($s, $r)
{
    if (strpos($s, '#') !== false)
    {
        $n = $r->range(99);
        $s = str_replace('##', sprintf("%d",$n), $s);
        $n = $r->range(9);
        if ($n == 0)
            $s = str_replace('#', 'no ', $s);
        elseif ($n == 1)
            $s = str_replace('#', 'two ', $s);
        elseif ($n == 2)
            $s = str_replace('#', 'three ', $s);
        elseif ($n == 3)
            $s = str_replace('#', 'four ', $s);
        elseif ($n == 4)
            $s = str_replace('#', 'five ', $s);
        elseif ($n == 5)
            $s = str_replace('#', 'six ', $s);
        elseif ($n == 6)
            $s = str_replace('#', 'seven ', $s);
        elseif ($n == 7)
            $s = str_replace('#', 'eight ', $s);
        elseif ($n == 8)
            $s = str_replace('#', 'nine ', $s);
        else
            $s = str_replace('#', 'ten ', $s);
        return $s;
    }
}

# food list

function MC_RandomFood($r=null)
{
    if ($r === null)
        $r = new MD5Rand();

    $s = '';
    
    if ($r->next_float() < 0.05)
        $s = MC_Amount($r) . ' ';

    if ($r->next_float() < 0.5)
        $s .= MC_Dish1($r) . ' ' . MC_Join($r) . ' ' . MC_RandomFood2($r);
    else
        $s .= MC_Dish2($r);

    $s = str_replace(' -', '-', $s);
    $s = str_replace('- ', '-', $s);
    $s = str_replace(' +', '', $s);
    $s = str_replace('+ ', '', $s);

    if ($r->next_float() < 0.1)
        $s .= '<br />With this dish may we recommend:<br />' . MC_RandomWine($r);

    if ($s[0] == '"')
        return '"' . ucfirst(substr($s, 1));
    else
        return ucfirst(trim($s));
}

function MC_RandomFood2($r)
{
    $s = '';
    
    if ($r->next_float() < 0.05)
        $s = MC_Amount($r) . ' ';

    if ($r->next_float() < 0.5)
        $s .= MC_Dish1($r) . ' ' . MC_Join($r) . ' ' . MC_RandomFood2($r);
    else
        $s .= MC_Dish1($r);

    return $s;
}

function MC_IngredientList($r)
{
    if ($r->range(3) < 2)
        return MC_Ingredient($r, true) . ' and ' . MC_Ingredient($r, true);
    else
        return MC_Ingredient($r, true) . ', ' . MC_Ingredient($r, true) . ', and ' . MC_Dish3($r);
}

function MC_Dish1($r)
{
    $n = $r->range(9);

    if ($n == 0)
        return MC_IngredientList($r);

    elseif ($n < 3)
        return MC_Ingredient($r, true);

    elseif ($n < 5)
        return MC_Ingredient($r, false) . ' ' . MC_Presentation($r);

    elseif ($n < 7)
        return MC_Method($r) . ' ' . MC_Ingredient($r, true);

    else
        return MC_Method($r) . ' ' . MC_Ingredient($r, false) . ' ' . MC_Presentation($r);
}


function MC_Dish2($r)
{
    $n = $r->range(10);

    if ($n == 0)
        return MC_IngredientList($r);

    elseif ($n < 4)
        return MC_Ingredient($r, false) . ' ' . MC_Presentation($r);

    elseif ($n < 7)
        return MC_Method($r) . ' ' . MC_Ingredient($r, true);

    else
        return MC_Method($r) . ' ' . MC_Ingredient($r, false) . ' ' . MC_Presentation($r);
}

function MC_Dish3($r)
{
    $n = $r->range(3);

    if ($n == 0)
        return MC_Ingredient($r, false) . ' ' . MC_Presentation($r);

    elseif ($n == 1)
        return MC_Method($r) . ' ' . MC_Ingredient($r, true);

    else
        return MC_Ingredient($r, true);
}

function MC_Method($r)
{
    global $mc_methods;
    $s = $r->choice($mc_methods);
    if (strpos($s, "#") !== False)
        $s = replace_hashes($s, $r);

    if ($r->next_float() < 0.005)
        $s = '"' . $s . '"';

    return $s;
}

function MC_Ingredient($r, $plural)
{
    global $mc_ingredients;

    $s = $r->choice($mc_ingredients);
        
    if ($s[0] == '%' or $s[0] == '$' or $s[0] == '^')
    {
        if ($plural)
        {
            if ($s[0] == '%')
                $s .= 's';
            elseif ($s[0] == '$')
                $s .= 'es';
            else
                $s = substr($s, 0, -1) . 'ies';
        }
        $s = substr($s, 1);
    }

    if (strpos($s, "#") !== False)
        $s = replace_hashes($s, $r);

    if ($r->next_float() < 0.02)
        $s = MC_Ingredient($r, false) . ' ' . $s;

    if ($r->next_float() < 0.005)
        $s = '"' . $s . '"';

    return $s;
}

function MC_Presentation($r)
{
    global $mc_presentations;
    
    $s = $r->choice($mc_presentations);
    
    if (strpos($s, "#") !== False)
        $s = replace_hashes($s, $r);

    if ($s[0] == '%' or $s[0] == '$' or $s[0] == '^')
    {
        if ($r->next_float() < 0.5)
        {
            if ($s[0] == '%')
                $s .= 's';
            elseif ($s[0] == '$')
                $s .= 'es';
            else
                $s = substr($s, 0, -1) . 'ies';
        }
        $s = substr($s, 1);
    }

    if ($r->next_float() < 0.005)
        $s = '"' . $s . '"';

    return $s;
}

function MC_Join($r)
{
    global $mc_joins;
    return $r->choice($mc_joins);
}

function MC_Amount($r)
{
    global $mc_amounts;
    $s = $r->choice($mc_amounts);
    $s = replace_hashes($s, $r);
    return $s;
}

# wine list

function MC_RandomWine($r=null)
{
    if ($r === null)
        $r = new MD5Rand();

    $s = '';

    if ($r->next_float() < 0.5)
        $s .= MC_Wine_Name($r) . ' ' . MC_Wine_Type2($r) . ', ' . MC_Wine_Bouquet2($r) . MC_Wine_Descriptor2($r);
    else
        $s .= MC_Wine_Name($r) . ' ' . MC_Wine_Type2($r) . ', ' . MC_Wine_Colour2($r) . ', ' . MC_Wine_Bouquet2($r) . MC_Wine_Descriptor2($r);
    
    $s = str_replace('- ', '-', $s);

    return $s;
}

function MC_Wine_Name($r)
{
    return MC_Winery($r) . ' ' . MC_Wine_Grape($r) . ', ' . MC_Wine_Vintage($r) . ' (' . MC_Wine_Region($r) . ').';
}

function MC_Winery($r)
{
    if ($r->next_float() < 0.25)
        return MC_Winery_Adjective($r) . ' ' . MC_Winery_Name2($r) . ' ' . MC_Winery_Noun($r);
    elseif ($r->next_float() < 0.5)
        return MC_Winery_Adjective($r) . ' ' . MC_Winery_Name2($r);
    elseif ($r->next_float() < 0.75)
        return MC_Winery_Adjective($r) . ' ' . MC_Winery_Noun($r);
    else
        return MC_Winery_Name2($r) . ' ' . MC_Winery_Noun($r);
}

function MC_Winery_Name2($r)
{
    $s = '';

    if ($r->next_float() < 0.9)
        $s .= MC_Winery_Name($r);
    else
        $s .= MC_Winery_Name($r) . "'s";

    if ($s[0] == '%')
    {
        $s = substr($s, 1);
        if ($r->next_float() < 0.1)
            $s = 'Mc' . $s;
        elseif ($r->next_float() < 0.15)
            $s = "O'" . $s;
        elseif ($r->next_float() < 0.2)
            $s = 'Fitz' . $s;
    }
    return $s;
}

function MC_Wine_Vintage($r)
{
    return round(date('Y') + 20 * log($r->next_float()));
}

function MC_Wine_Type2($r)
{
    $s = '';

    if ($r->next_float() < 0.4)
        $s .= 'A ' . MC_Wine_Adjective($r) . ' ' . MC_Wine_Type($r);
    elseif ($r->next_float() < 0.9)
        $s .= 'A ' . MC_Wine_Intensifier($r) . ' ' . MC_Wine_Adjective($r) . ' ' . MC_Wine_Type($r);
    else
        $s .= 'A ' . MC_Wine_Intensifier($r) . ' ' . MC_Wine_Adjective($r) . ' and ' . MC_Wine_Adjective($r) . ' ' . MC_Wine_Type($r);

    $s = str_replace('A a', 'An a', $s);
    $s = str_replace('A e', 'An e', $s);
    $s = str_replace('A i', 'An i', $s);
    $s = str_replace('A o', 'An o', $s);
    $s = str_replace('A u', 'An u', $s);
    return $s;
}

function MC_Wine_Colour2($r)
{
    if ($r->next_float() < 0.3)
        return MC_Wine_Colour($r) . ' in colour';
    elseif ($r->next_float() < 0.95)
        return MC_Wine_Colour_Adjective($r) . ' ' . MC_Wine_Colour($r) . ' in colour';
    else
        return MC_Wine_Intensifier($r) . ' ' . MC_Wine_Colour_Adjective($r) . ' ' . MC_Wine_Colour($r) . ' in colour';
}

function MC_Wine_Bouquet_Join2($r)
{
    $s = MC_Wine_Bouquet_Join($r);

    if ($r->next_float() < 0.3)
        $s = str_replace('_ADJECTIVE_', MC_Wine_Adjective($r), $s);
    else
        $s = str_replace('_ADJECTIVE_ ', '', $s);

    $s = str_replace(' a a', ' an a', $s);
    $s = str_replace(' a e', ' an e', $s);
    $s = str_replace(' a i', ' an i', $s);
    $s = str_replace(' a o', ' an o', $s);
    $s = str_replace(' a u', ' an u', $s);
    return $s;
}

function MC_Wine_Bouquet2($r)
{
    if ($r->next_float() < 0.8)
        return MC_Wine_Bouquet_Join2($r) . ' ' . MC_Wine_Bouquet3($r);
    else
        return MC_Wine_Bouquet_Join2($r) . ' ' . MC_Wine_Bouquet3($r) . ' and ' . MC_Wine_Bouquet_Join2($r) . ' ' . MC_Wine_Bouquet3($r);
}

function MC_Wine_Bouquet3($r)
{
    if ($r->next_float() < 0.4)
        return MC_Wine_Bouquet4($r) . ' and ' . MC_Wine_Bouquet4($r);
    elseif ($r->next_float() < 0.9)
        return MC_Wine_Bouquet4($r) . ', ' . MC_Wine_Bouquet4($r) . ', and ' . MC_Wine_Bouquet4($r);
    else
        return MC_Wine_Bouquet4($r) . ', ' . MC_Wine_Bouquet4($r) . ', ' . MC_Wine_Bouquet4($r) . ', and ' . MC_Wine_Bouquet4($r);
}

function MC_Wine_Bouquet4($r)
{
    if ($r->next_float() < 0.6)
        return MC_Wine_Bouquet($r);
    elseif ($r->next_float() < 0.95)
        return MC_Wine_Adjective($r) . ' ' . MC_Wine_Bouquet($r);
    else
        return MC_Wine_Intensifier($r) . ' ' . MC_Wine_Adjective($r) . ' ' . MC_Wine_Bouquet($r);
}

function MC_Wine_Descriptor2($r)
{
    
    if ($r->next_float() < 0.4)
        return ' and ' . MC_Wine_Descriptor3($r);
    elseif ($r->next_float() < 0.9)
        return ', ' . MC_Wine_Descriptor3($r) . ' and ' . MC_Wine_Descriptor3($r);
    else
        return ', ' . MC_Wine_Descriptor3($r) . ', ' . MC_Wine_Descriptor3($r) . ' and ' . MC_Wine_Descriptor3($r);
}

function MC_Wine_Descriptor3($r)
{
    
    if ($r->next_float() < 0.6)
        return MC_Wine_Adjective($r) . ' ' . MC_Wine_Descriptor($r);
    elseif ($r->next_float() < 0.7)
        return MC_Wine_Adjective($r) . ', ' . MC_Wine_Adjective($r) . ' ' . MC_Wine_Descriptor($r);
    else
        return MC_Wine_Intensifier($r) . ' ' . MC_Wine_Adjective($r) . ' ' . MC_Wine_Descriptor($r);
}

function MC_Wine_Adjective($r)
{
    global $mc_wine_adjectives;
    return $r->choice($mc_wine_adjectives);
}

function MC_Wine_Bouquet($r)
{
    global $mc_wine_bouquets;
    if ($r->next_float() < 0.01)
        return MC_Ingredient($r, false);
    else
        return $r->choice($mc_wine_bouquets);
}

function MC_Wine_Bouquet_Join($r)
{
    global $mc_wine_bouquet_joins;
    return $r->choice($mc_wine_bouquet_joins);
}

function MC_Wine_Colour($r)
{
    global $mc_wine_colours;
    return $r->choice($mc_wine_colours);
}

function MC_Wine_Colour_Adjective($r)
{
    global $mc_wine_colour_adjectives;
    return $r->choice($mc_wine_colour_adjectives);
}

function MC_Wine_Descriptor($r)
{
    global $mc_wine_descriptors;
    return $r->choice($mc_wine_descriptors);
}

function MC_Wine_Grape($r)
{
    global $mc_wine_grapes;
    if ($r->next_float() < 0.9)
        return $r->choice($mc_wine_grapes);
    elseif ($r->next_float() < 0.5)
        return MC_Wine_Grape_Adjective($r) . ' ' . $r->choice($mc_wine_grapes);
    else
        return $r->choice($mc_wine_grapes) . '/' . $r->choice($mc_wine_grapes) . ' blend';
}

function MC_Wine_Grape_Adjective($r)
{
    global $mc_wine_grape_adjectives;
    return $r->choice($mc_wine_grape_adjectives);
}

function MC_Wine_Intensifier($r)
{
    global $mc_wine_intensifiers;
    return $r->choice($mc_wine_intensifiers);
}

function MC_Wine_Region($r)
{
    global $mc_wine_regions;
    return $r->choice($mc_wine_regions);
}

function MC_Wine_Type($r)
{
    global $mc_wine_types;
    return $r->choice($mc_wine_types);
}

function MC_Winery_Adjective($r)
{
    global $mc_winery_adjectives;
    return $r->choice($mc_winery_adjectives);
}

function MC_Winery_Name($r)
{
    global $mc_winery_names;
    return $r->choice($mc_winery_names);
}

function MC_Winery_Noun($r)
{
    global $mc_winery_nouns;
    return $r->choice($mc_winery_nouns);
}

?>
