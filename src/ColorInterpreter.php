<?php

/*
 * (c) Our Code World <dev@ourcodeworld.com>
 *
 * This library is a PHP port (written by Our Code World) of the JavaScript library NTC JS.
 *
    +-----------------------------------------------------------------+
    |     Created by Chirag Mehta - http://chir.ag/projects/ntc       |
    |-----------------------------------------------------------------|
    |               ntc js (Name that Color JavaScript)               |
    +-----------------------------------------------------------------+

    All the functions, code, lists etc. have been written specifically
    for the Name that Color JavaScript by Chirag Mehta unless otherwise
    specified.

    http://chir.ag/projects/ntc/
 * 
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ourcodeworld\NameThatColor;

class ColorInterpreter {

    public function __construct(){
        $color = null;
        $rgb = null;
        $hsl = null;

        for($i = 0; $i < count($this->names); $i++)
        {
            $color = "#" . $this->names[$i][0];
            $rgb = $this->rgb($color);
            $hsl = $this->hsl($color);

            array_push(
                $this->names[$i], 
                $rgb[0], 
                $rgb[1], 
                $rgb[2], 
                $hsl[0], 
                $hsl[1], 
                $hsl[2]
            );
        }   
    }

    public function name($color){
        $color = strtoupper($color);

        if(strlen($color) < 3 || strlen($color) > 7){
            return array(
                "hex" => "#000000",
                "name" => "Invalid Color: " . $color,
                "exact" => false
            );
        }
        
        // If given the format without #
        if(strlen($color) % 3 == 0){
            $color = "#" . $color;
        }
        
        // If given #fea, duplicate every char e.g #ffeeaa
        if(strlen($color) == 4){
            $color = "#" . $color.substr(1, 1) . $color.substr(1, 1) . $color.substr(2, 1) . $color.substr(2, 1) . $color.substr(3, 1) . $color.substr(3, 1);
        }
          
        $rgb = $this->rgb($color);

        $r = $rgb[0];
        $g = $rgb[1];
        $b = $rgb[2];

        $hsl = $this->hsl($color);

        $h = $hsl[0];
        $s = $hsl[1];
        $l = $hsl[2];

        $ndf1 = 0; 
        $ndf2 = 0; 
        $ndf = 0;

        $cl = -1;
        $df = -1;
    
        for($i = 0; $i < count($this->names); $i++)
        {
            if($color == "#" . $this->names[$i][0]){
                return array(
                    "hex" => "#" . $this->names[$i][0], 
                    "name" => $this->names[$i][1], 
                    "exact" => true
                );
            }

            $ndf1 = pow($r - $this->names[$i][2], 2) + pow($g - $this->names[$i][3], 2) + pow($b - $this->names[$i][4], 2);
            $ndf2 = pow($h - $this->names[$i][5], 2) + pow($s - $this->names[$i][6], 2) + pow($l - $this->names[$i][7], 2);
            $ndf = $ndf1 + $ndf2 * 2;

            if($df < 0 || $df > $ndf)
            {
                $df = $ndf;
                $cl = $i;
            }
        }
        
        if($cl < 0){
            return array(
                "hex" => "#000000",
                "name" => "Invalid Color: " . $color,
                "exact" => false
            );
        }else{
            return array(
                "hex" => "#" . $this->names[$cl][0],
                "name" => $this->names[$cl][1],
                "exact" => false
            );
        }
    }

    // adopted from: Farbtastic 1.2
    // http://acko.net/dev/farbtastic
    public function hsl($color) {
        $rgb = array(
            intval(hexdec(substr($color, 1, 2))) / 255, 
            intval(hexdec(substr($color, 3, 2))) / 255, 
            intval(hexdec(substr($color, 5, 7))) / 255
        );

        $min = null;
        $max = null;
        $delta = null;
        $h = null;
        $s = null;
        $l = null;

        $r = $rgb[0];
        $g = $rgb[1];
        $b = $rgb[2];

        $min = min($r, min($g, $b));
        $max = max($r, max($g, $b));
        $delta = $max - $min;
        $l = ($min + $max) / 2;

        $s = 0;
        if($l > 0 && $l < 1)
        $s = $delta / ($l < 0.5 ? (2 * $l) : (2 - 2 * $l));

        $h = 0;
        if($delta > 0)
        {
            if ($max == $r && $max != $g) $h += ($g - $b) / $delta;
            if ($max == $g && $max != $b) $h += (2 + ($b - $r) / $delta);
            if ($max == $b && $max != $r) $h += (4 + ($r - $g) / $delta);
            $h /= 6;
        }

        return array(
            intval($h * 255), 
            intval($s * 255),
            intval($l * 255)
        );
    }

    // adopted from: Farbtastic 1.2
    // http://acko.net/dev/farbtastic
    public function rgb($color) {
        return array(
            hexdec(substr($color, 1, 2)), 
            hexdec(substr($color, 3, 2)),  
            hexdec(substr($color, 5, 7))
        );
    }

    public $names = array(
        array("000000", "Black"),
        array("000080", "Navy Blue"),
        array("0000C8", "Dark Blue"),
        array("0000FF", "Blue"),
        array("002387", "Resolution Blue"),
        array("003153", "Prussian Blue"),
        array("003366", "Midnight Blue"),
        array("004620", "Kaitoke Green"),
        array("0047AB", "Cobalt"),
        array("004950", "Sherpa Blue"),
        array("0066CC", "Science Blue"),
        array("007BA7", "Deep Cerulean"),
        array("008080", "Teal"),
        array("0095B6", "Bondi Blue"),
        array("009DC4", "Pacific Blue"),
        array("00A693", "Persian Green"),
        array("00A86B", "Jade"),
        array("00CC99", "Caribbean Green"),
        array("00CCCC", "Robin's Egg Blue"),
        array("00FF00", "Green"),
        array("00FF7F", "Spring Green"),
        array("00FFFF", "Cyan / Aqua"),
        array("010D1A", "Blue Charcoal"),
        array("01361C", "Cardin Green"),
        array("01371A", "County Green"),
        array("013E62", "Astronaut Blue"),
        array("013F6A", "Regal Blue"),
        array("014B43", "Aqua Deep"),
        array("016162", "Blue Stone"),
        array("016D39", "Fun Green"),
        array("01796F", "Pine Green"),
        array("017987", "Blue Lagoon"),
        array("01A368", "Green Haze"),
        array("02402C", "Sherwood Green"),
        array("02478E", "Congress Blue"),
        array("026395", "Bahama Blue"),
        array("02A4D3", "Cerulean"),
        array("032B52", "Green Vogue"),
        array("041322", "Black Pearl"),
        array("042E4C", "Blue Whale"),
        array("044259", "Teal Blue"),
        array("051657", "Gulf Blue"),
        array("055989", "Venice Blue"),
        array("062A78", "Catalina Blue"),
        array("081910", "Black Bean"),
        array("082567", "Deep Sapphire"),
        array("088370", "Elf Green"),
        array("08E8DE", "Bright Turquoise"),
        array("09230F", "Palm Green"),
        array("093624", "Bottle Green"),
        array("095859", "Deep Sea Green"),
        array("0A001C", "Black Russian"),
        array("0B0B0B", "Cod Gray"),
        array("0B1107", "Gordons Green"),
        array("0B1304", "Black Forest"),
        array("0C1911", "Racing Green"),
        array("0C7A79", "Surfie Green"),
        array("0C8990", "Blue Chill"),
        array("0D0332", "Black Rock"),
        array("101405", "Green Waterloo"),
        array("13264D", "Blue Zodiac"),
        array("1450AA", "Tory Blue"),
        array("161D10", "Hunter Green"),
        array("16322C", "Timber Green"),
        array("163531", "Gable Green"),
        array("175579", "Chathams Blue"),
        array("182D09", "Deep Forest Green"),
        array("193751", "Nile Blue"),
        array("1959A8", "Fun Blue"),
        array("1C1E13", "Rangoon Green"),
        array("1C39BB", "Persian Blue"),
        array("1E90FF", "Dodger Blue"),
        array("1E9AB0", "Eastern Blue"),
        array("20208D", "Jacksons Purple"),
        array("204852", "Blue Dianne"),
        array("220878", "Deep Blue"),
        array("228B22", "Forest Green"),
        array("240A40", "Violet"),
        array("242E16", "Black Olive"),
        array("24500F", "Green House"),
        array("251607", "Graphite"),
        array("251706", "Cannon Black"),
        array("25311C", "Green Kelp"),
        array("2596D1", "Curious Blue"),
        array("262335", "Steel Gray"),
        array("290C5E", "Violent Violet"),
        array("29AB87", "Jungle Green"),
        array("2A380B", "Turtle Green"),
        array("2A52BE", "Cerulean Blue"),
        array("2B0202", "Sepia Black"),
        array("2C0E8C", "Blue Gem"),
        array("2E8B57", "Sea Green"),
        array("2F519E", "Sapphire"),
        array("301F1E", "Cocoa Brown"),
        array("30D5C8", "Turquoise"),
        array("314459", "Pickled Bluewood"),
        array("315BA1", "Azure"),
        array("37290E", "Brown Tumbleweed"),
        array("380474", "Blue Diamond"),
        array("384555", "Oxford Blue"),
        array("3B91B4", "Boston Blue"),
        array("3C4151", "Bright Gray"),
        array("3C493A", "Lunar Green"),
        array("3E3A44", "Ship Gray"),
        array("3F2109", "Bronze"),
        array("3F5D53", "Mineral Green"),
        array("401801", "Brown Pod"),
        array("403D19", "Thatch Green"),
        array("40A860", "Chateau Green"),
        array("4169E1", "Royal Blue"),
        array("41AA78", "Ocean Green"),
        array("423921", "Lisbon Brown"),
        array("431560", "Scarlet Gum"),
        array("436A0D", "Green Leaf"),
        array("441D00", "Morocco Brown"),
        array("45B1E8", "Picton Blue"),
        array("462425", "Crater Brown"),
        array("465945", "Gray Asparagus"),
        array("4682B4", "Steel Blue"),
        array("480404", "Rustic Red"),
        array("480607", "Bulgarian Rose"),
        array("483131", "Woody Brown"),
        array("492615", "Brown Derby"),
        array("49371B", "Metallic Bronze"),
        array("495400", "Verdun Green"),
        array("496679", "Blue Bayoux"),
        array("4A3004", "Deep Bronze"),
        array("4D282E", "Livid Brown"),
        array("4D400F", "Bronzetone"),
        array("4E420C", "Bronze Olive"),
        array("4F7942", "Fern Green"),
        array("507096", "Kashmir Blue"),
        array("50C878", "Emerald"),
        array("516E3D", "Chalet Green"),
        array("51808F", "Smalt Blue"),
        array("53824B", "Hippie Green"),
        array("544333", "Judge Gray"),
        array("54534D", "Fuscous Gray"),
        array("5590D9", "Havelock Blue"),
        array("56B4BE", "Fountain Blue"),
        array("583401", "Saddle Brown"),
        array("589AAF", "Hippie Blue"),
        array("592804", "Brown Bramble"),
        array("593737", "Congo Brown"),
        array("5F5F6E", "Mid Gray"),
        array("5F6672", "Shuttle Gray"),
        array("5FA777", "Aqua Forest"),
        array("61845F", "Glade Green"),
        array("6456B7", "Blue Violet"),
        array("6495ED", "Cornflower Blue"),
        array("652DC1", "Purple Heart"),
        array("660099", "Purple"),
        array("66023C", "Tyrian Purple"),
        array("661010", "Dark Tan"),
        array("66B58F", "Silver Tree"),
        array("66FF00", "Bright Green"),
        array("66FF66", "Screamin' Green"),
        array("67032D", "Black Rose"),
        array("676662", "Ironside Gray"),
        array("678975", "Viridian Green"),
        array("6B3FA0", "Royal Purple"),
        array("6B5755", "Dorado"),
        array("6B8BA2", "Bermuda Gray"),
        array("6CDAE7", "Turquoise Blue"),
        array("6D6C6C", "Dove Gray"),
        array("6E0902", "Red Oxide"),
        array("704A07", "Antique Bronze"),
        array("708090", "Slate Gray"),
        array("715D47", "Tobacco Brown"),
        array("716338", "Yellow Metal"),
        array("717486", "Storm Gray"),
        array("71D9E2", "Aquamarine Blue"),
        array("72010F", "Venetian Red"),
        array("748881", "Blue Smoke"),
        array("7666C6", "Blue Marguerite"),
        array("76D7EA", "Sky Blue"),
        array("77DD77", "Pastel Green"),
        array("78866B", "Camouflage Green"),
        array("7A58C1", "Fuchsia Blue"),
        array("7A89B8", "Wild Blue Yonder"),
        array("7B3801", "Red Beech"),
        array("7B6608", "Yukon Gold"),
        array("7C881A", "Trendy Green"),
        array("7F76D3", "Moody Blue"),
        array("7FFFD4", "Aquamarine"),
        array("800000", "Maroon"),
        array("800B47", "Rose Bud Cherry"),
        array("801818", "Falu Red"),
        array("80341F", "Red Robin"),
        array("803790", "Vivid Violet"),
        array("80461B", "Russet"),
        array("807E79", "Friar Gray"),
        array("808000", "Olive"),
        array("808080", "Gray"),
        array("816E71", "Spicy Pink"),
        array("819885", "Spanish Green"),
        array("828F72", "Battleship Gray"),
        array("8581D9", "Chetwode Blue"),
        array("860111", "Red Devil"),
        array("86949F", "Regent Gray"),
        array("878D91", "Oslo Gray"),
        array("888387", "Suva Gray"),
        array("893843", "Solid Pink"),
        array("894367", "Cannon Pink"),
        array("8AB9F1", "Jordy Blue"),
        array("8B00FF", "Electric Violet"),
        array("8B8680", "Natural Gray"),
        array("8C055E", "Cardinal Pink"),
        array("8C6495", "Trendy Pink"),
        array("8D3D38", "Sanguine Brown"),
        array("8D8974", "Granite Green"),
        array("8DA8CC", "Polo Blue"),
        array("8E0000", "Red Berry"),
        array("8FD6B4", "Vista Blue"),
        array("9370DB", "Medium Purple"),
        array("93DFB8", "Algae Green"),
        array("964B00", "Brown"),
        array("9678B6", "Purple Mountain's Majesty"),
        array("967BB6", "Lavender Purple"),
        array("96BBAB", "Summer Green"),
        array("98FF98", "Mint Green"),
        array("991199", "Violet Eggplant"),
        array("997A8D", "Mountbatten Pink"),
        array("9999CC", "Blue Bell"),
        array("9AC2B8", "Shadow Green"),
        array("9B9E8F", "Lemon Grass"),
        array("9DACB7", "Gull Gray"),
        array("9EB1CD", "Rock Blue"),
        array("9FA0B1", "Santas Gray"),
        array("A1ADB5", "Hit Gray"),
        array("A1DAD7", "Aqua Island"),
        array("A2AAB3", "Gray Chateau"),
        array("A3E3ED", "Blizzard Blue"),
        array("A4AF6E", "Green Smoke"),
        array("A69279", "Donkey Brown"),
        array("A72525", "Mexican Red"),
        array("A8989B", "Dusty Gray"),
        array("A9A491", "Gray Olive"),
        array("A9B2C3", "Cadet Blue"),
        array("A9BDBF", "Tower Gray"),
        array("AAD6E6", "Regent St Blue"),
        array("ABA0D9", "Cold Purple"),
        array("ACB78E", "Swamp Green"),
        array("ADDFAD", "Moss Green"),
        array("ADFF2F", "Green Yellow"),
        array("AE4560", "Hippie Pink"),
        array("AF593E", "Brown Rust"),
        array("B0E0E6", "Powder Blue"),
        array("B10000", "Bright Red"),
        array("B35213", "Fiery Orange"),
        array("B3AF95", "Taupe Gray"),
        array("B43332", "Well Read"),
        array("B57281", "Turkish Rose"),
        array("B5B35C", "Olive Green"),
        array("B6B095", "Heathered Gray"),
        array("B81104", "Milano Red"),
        array("B8C1B1", "Green Spring"),
        array("BA0101", "Guardsman Red"),
        array("BB3385", "Medium Red Violet"),
        array("BB8983", "Brandy Rose"),
        array("BDBBD7", "Lavender Gray"),
        array("BDBDC6", "French Gray"),
        array("BEB5B7", "Pink Swan"),
        array("BFBED8", "Blue Haze"),
        array("BFC1C2", "Silver Sand"),
        array("C08081", "Old Rose"),
        array("C0C0C0", "Silver"),
        array("C0D8B6", "Pixie Green"),
        array("C154C1", "Fuchsia Pink"),
        array("C1A004", "Buddha Gold"),
        array("C1BECD", "Gray Suit"),
        array("C3C3BD", "Gray Nickel"),
        array("C3CDE6", "Periwinkle Gray"),
        array("C3DDF9", "Tropical Blue"),
        array("C45655", "Fuzzy Wuzzy Brown"),
        array("C45719", "Orange Roughy"),
        array("C4C4BC", "Mist Gray"),
        array("C5E17A", "Yellow Green"),
        array("C62D42", "Brick Red"),
        array("C69191", "Oriental Pink"),
        array("C71585", "Red Violet"),
        array("C7BCA2", "Coral Reef"),
        array("C9B93B", "Earls Green"),
        array("C9FFE5", "Aero Blue"),
        array("CADCD4", "Paris White"),
        array("CBCAB6", "Foggy Gray"),
        array("CBD3B0", "Green Mist"),
        array("CC3333", "Persian Red"),
        array("CC5500", "Burnt Orange"),
        array("CCCAA8", "Thistle Green"),
        array("CD5C5C", "Chestnut Rose"),
        array("CEB98F", "Sorrell Brown"),
        array("CFB53B", "Old Gold"),
        array("D05F04", "Red Stage"),
        array("D0F0C0", "Tea Green"),
        array("D1C6B4", "Soft Amber"),
        array("D29EAA", "Careys Pink"),
        array("D2F6DE", "Blue Romance"),
        array("D4E2FC", "Hawkes Blue"),
        array("D6D6D1", "Quill Gray"),
        array("D7837F", "New York Pink"),
        array("DA6A41", "Red Damask"),
        array("DE3163", "Cerise Red"),
        array("DEE5C0", "Beryl Green"),
        array("DEF5FF", "Pattens Blue"),
        array("DFFF00", "Chartreuse Yellow"),
        array("E0B974", "Harvest Gold"),
        array("E0FFFF", "Baby Blue"),
        array("E1C0C8", "Pink Flare"),
        array("E1E6D6", "Periglacial Blue"),
        array("E2F3EC", "Apple Green"),
        array("E3BEBE", "Cavern Pink"),
        array("E4D5B7", "Grain Brown"),
        array("E5D7BD", "Stark White"),
        array("E6D7B9", "Double Spanish White"),
        array("E6F8F3", "Off Green"),
        array("E6FFE9", "Hint of Green"),
        array("E79F8C", "Tonys Pink"),
        array("E7BCB4", "Rose Fog"),
        array("E7ECE6", "Gray Nurse"),
        array("E7F8FF", "Lily White"),
        array("E8EBE0", "Green White"),
        array("E8F1D4", "Chrome White"),
        array("E97C07", "Tahiti Gold"),
        array("E9CECD", "Oyster Pink"),
        array("EAE8D4", "White Rock"),
        array("ECA927", "Fuel Yellow"),
        array("ECEBBD", "Fall Green"),
        array("ED0A3F", "Red Ribbon"),
        array("ED9121", "Carrot Orange"),
        array("ED989E", "Sea Pink"),
        array("EEE3AD", "Double Colonial White"),
        array("EEF0F3", "Athens Gray"),
        array("EEF6F7", "Catskill White"),
        array("EEFDFF", "Twilight Blue"),
        array("F0EEFF", "Titan White"),
        array("F0F8FF", "Alice Blue"),
        array("F1E9FF", "Blue Chalk"),
        array("F2C3B2", "Mandys Pink"),
        array("F2FAFA", "Black Squeeze"),
        array("F3E9E5", "Dawn Pink"),
        array("F4A460", "Sandy brown"),
        array("F5E9D3", "Albescent White"),
        array("F5F3E5", "Ecru White"),
        array("F5F5DC", "Beige"),
        array("F64A8A", "French Rose"),
        array("F653A6", "Brilliant Rose"),
        array("F6F7F7", "Black Haze"),
        array("F7468A", "Violet Red"),
        array("F77FBE", "Persian Pink"),
        array("F7F2E1", "Quarter Spanish White"),
        array("F8DD5C", "Energy Yellow"),
        array("F8F0E8", "White Linen"),
        array("F8F7FC", "White Lilac"),
        array("F9E0ED", "Carousel Pink"),
        array("FADFAD", "Peach Yellow"),
        array("FAF7D6", "Citrine White"),
        array("FAFDE4", "Hint of Yellow"),
        array("FB607F", "Brink Pink"),
        array("FBA0E3", "Lavender Rose"),
        array("FBAED2", "Lavender Pink"),
        array("FBB2A3", "Rose Bud"),
        array("FBCCE7", "Classic Rose"),
        array("FBE870", "Marigold Yellow"),
        array("FC0FC0", "Shocking Pink"),
        array("FC80A5", "Tickle Me Pink"),
        array("FCC01E", "Lightning Yellow"),
        array("FCF8F7", "Vista White"),
        array("FD0E35", "Torch Red"),
        array("FD9FA2", "Sweet Pink"),
        array("FDD7E4", "Pig Pink"),
        array("FDF6D3", "Half Colonial White"),
        array("FE28A2", "Persian Rose"),
        array("FE4C40", "Sunset Orange"),
        array("FEA904", "Yellow Sea"),
        array("FEF3D8", "Bleach White"),
        array("FEF4DB", "Half Spanish White"),
        array("FEF4F8", "Wisp Pink"),
        array("FEF5F1", "Provincial Pink"),
        array("FEF7DE", "Half Dutch White"),
        array("FEF8FF", "White Pointer"),
        array("FEF9E3", "Off Yellow"),
        array("FEFCED", "Orange White"),
        array("FF0000", "Red"),
        array("FF007F", "Rose"),
        array("FF00CC", "Purple Pizzazz"),
        array("FF00FF", "Magenta / Fuchsia"),
        array("FF2400", "Scarlet"),
        array("FF33CC", "Razzle Dazzle Rose"),
        array("FF355E", "Radical Red"),
        array("FF3F34", "Red Orange"),
        array("FF4040", "Coral Red"),
        array("FF4F00", "International Orange"),
        array("FF6037", "Outrageous Orange"),
        array("FF6600", "Blaze Orange"),
        array("FF66FF", "Pink Flamingo"),
        array("FF681F", "Orange"),
        array("FF69B4", "Hot Pink"),
        array("FF6FFF", "Blush Pink"),
        array("FF7034", "Burning Orange"),
        array("FF7F00", "Flush Orange"),
        array("FF7F50", "Coral"),
        array("FF8C69", "Salmon"),
        array("FF91A4", "Pink Salmon"),
        array("FF9966", "Atomic Tangerine"),
        array("FF9980", "Vivid Tangerine"),
        array("FFA000", "Orange Peel"),
        array("FFA500", "Web Orange"),
        array("FFA6C9", "Carnation Pink"),
        array("FFAB81", "Hit Pink"),
        array("FFAE42", "Yellow Orange"),
        array("FFB555", "Texas Rose"),
        array("FFBA00", "Selective Yellow"),
        array("FFBF00", "Amber"),
        array("FFC0CB", "Pink"),
        array("FFC3C0", "Your Pink"),
        array("FFCC99", "Peach Orange"),
        array("FFD1DC", "Pastel Pink"),
        array("FFD700", "Gold"),
        array("FFD800", "School bus Yellow"),
        array("FFDDF4", "Pink Lace"),
        array("FFDEAD", "Navajo White"),
        array("FFE1F2", "Pale Rose"),
        array("FFEDBC", "Colonial White"),
        array("FFEFC1", "Egg White"),
        array("FFEFEC", "Fair Pink"),
        array("FFF1D8", "Pink Lady"),
        array("FFF4CE", "Barley White"),
        array("FFF6F5", "Rose White"),
        array("FFF8D1", "Baja White"),
        array("FFFCEA", "Buttery White"),
        array("FFFDF3", "Orchid White"),
        array("FFFEEC", "Apricot White"),
        array("FFFEF6", "Black White"),
        array("FFFF00", "Yellow"),
        array("FFFFF0", "Ivory"),
        array("FFFFFF", "White")
    );
}
