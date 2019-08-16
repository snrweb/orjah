<?php
    namespace Core;

    class Categories {

        public static function list() {
            return [
                    'Electronics' => ['Home Electronics', 'Kitchen'], 
                    'Home And Office' => [], 
                    'Phones' => ['Ear Piece', 'Charger'], 
                    'Computers' => [], 
                    'Automobile' => [], 
                    'Books' => [], 
                    'Real Estate' => [], 
                    'Wears' => [
                        'Men' => ['Wallet', 'Shoes', 'Wrist watch', 'Underwear'], 
                        'Women' => ['Trouser', 'Shirt', 'Wrist watch'], 
                        'Boys' => ['Trouser', 'Shirt', 'Wrist watch'], 
                        'Girls' => ['Trouser', 'Shirt', 'Wrist watch'],
                        'Babies' => ['Trouser', 'Shirt', 'Wrist watch']
                    ], 
                    'Health And Beauty' => []
                ];
        }

        public static function category() {
            return [
                    'Electronics', 
                    'Home And Office', 
                    'Phones', 
                    'Computers', 
                    'Automobile', 
                    'Books', 
                    'Real Estate', 
                    'Wears', 
                    'Health And Beauty'
                ];
        }
    }
?>