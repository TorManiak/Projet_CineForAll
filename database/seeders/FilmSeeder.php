<?php

namespace Database\Seeders;
use App\Models\Genre;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('film')->insert([
            [
                'nomFil' => 'Inception',
                'datFil' => '02:28:00',
                'afiFil' => 'inception.jpg',
                'desFil' => 'Un voleur qui pénètre dans les rêves des gens est chargé d\'implanter une idée.',
                'idGen' => 1,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=YoHD9XEInc0',
                'annSor' => '2010'
            ],
            [
                'nomFil' => 'Interstellar',
                'datFil' => '02:49:00',
                'afiFil' => 'interstellar.jpg',
                'desFil' => 'Un groupe d\'explorateurs voyage à travers un trou de ver pour sauver l\'humanité.',
                'idGen' => 6,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=zSWdZVtXT7E',
                'annSor' => '2014'
            ],
            [
                'nomFil' => 'La Haine',
                'datFil' => '01:38:00',
                'afiFil' => 'lahaine.jpg',
                'desFil' => 'Vingt-quatre heures dans la vie de trois jeunes des banlieues parisiennes.',
                'idGen' => 5,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=PIGCKbrCVc4',
                'annSor' => '1995'
            ],
            [
                'nomFil' => 'Amélie',
                'datFil' => '02:02:00',
                'afiFil' => 'amelie.jpg',
                'desFil' => 'Une jeune femme décide de changer la vie des gens autour d’elle.',
                'idGen' => 4,
                'malVoyEnt' => false,
                'banAnn' => 'https://youtube.com/watch?v=HUECWi5HlEo',
                'annSor' => '2001'
            ],
            [
                'nomFil' => 'Shutter Island',
                'datFil' => '02:11:00',
                'afiFil' => 'shutterisland.jpg',
                'desFil' => 'Un marshal enquête sur la disparition d\'une patiente dans un hôpital psychiatrique.',
                'idGen' => 17,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=5iaYLCiq5RM',
                'annSor' => '2010'
            ],
            [
                'nomFil' => 'The Dark Knight',
                'datFil' => '02:32:00',
                'afiFil' => 'thedarkknight.jpg',
                'desFil' => 'Batman affronte le Joker, un criminel anarchiste qui sème le chaos à Gotham.',
                'idGen' => 1,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=EXeTwQWrcwY',
                'annSor' => '2008'
            ],
            [
                'nomFil' => 'Django Unchained',
                'datFil' => '02:45:00',
                'afiFil' => 'django.jpg',
                'desFil' => 'Un esclave libéré devient chasseur de primes pour sauver sa femme.',
                'idGen' => 1,
                'malVoyEnt' => false,
                'banAnn' => 'https://youtube.com/watch?v=0fUCuvKZ6xc',
                'annSor' => '2012'
            ],
            [
                'nomFil' => 'The Revenant',
                'datFil' => '02:36:00',
                'afiFil' => 'therevenant.jpg',
                'desFil' => 'Un trappeur survivant d’une attaque lutte pour venger la mort de son fils.',
                'idGen' => 5,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=LoebZZ8K5N0',
                'annSor' => '2015'
            ],
            [
                'nomFil' => 'Parasite',
                'datFil' => '02:12:00',
                'afiFil' => 'parasite.jpg',
                'desFil' => 'Une famille pauvre s’infiltre dans une riche famille sud-coréenne.',
                'idGen' => 15,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=5xH0HfJHsaY',
                'annSor' => '2019'
            ],
            [
                'nomFil' => 'The Grand Budapest Hotel',
                'datFil' => '01:39:00',
                'afiFil' => 'grandbudapest.jpg',
                'desFil' => 'Les aventures d’un concierge et de son jeune protégé dans un hôtel prestigieux.',
                'idGen' => 4,
                'malVoyEnt' => false,
                'banAnn' => 'https://youtube.com/watch?v=1Fg5iWmQjwk',
                'annSor' => '2014'
            ],
            [
                'nomFil' => 'Mad Max: Fury Road',
                'datFil' => '02:00:00',
                'afiFil' => 'madmax.jpg',
                'desFil' => 'Un guerrier s’associe à une rebelle pour fuir un tyran post-apocalyptique.',
                'idGen' => 1,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=hEJnMQG9ev8',
                'annSor' => '2015'
            ],
            [
                'nomFil' => 'Spirited Away',
                'datFil' => '02:05:00',
                'afiFil' => 'spiritedaway.jpg',
                'desFil' => 'Une jeune fille est piégée dans un monde spirituel et doit sauver ses parents.',
                'idGen' => 3,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=ByXuk9QqQkk',
                'annSor' => '2001'
            ],
            [
                'nomFil' => 'The Social Network',
                'datFil' => '02:04:00',
                'afiFil' => 'socialnetwork.jpg',
                'desFil' => 'L’histoire de la création de Facebook et de ses conflits internes.',
                'idGen' => 5,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=lB95KLmpLR4',
                'annSor' => '2010'
            ],
            [
                'nomFil' => 'Whiplash',
                'datFil' => '01:47:00',
                'afiFil' => 'whiplash.jpg',
                'desFil' => 'Un jeune batteur est poussé à ses limites par un professeur impitoyable.',
                'idGen' => 5,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=tYkuvB2f5XU',
                'annSor' => '2014'
            ],
            [
                'nomFil' => 'The Imitation Game',
                'datFil' => '01:54:00',
                'afiFil' => 'imitationgame.jpg',
                'desFil' => 'Alan Turing tente de décrypter le code Enigma pendant la Seconde Guerre mondiale.',
                'idGen' => 9,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=S5CjKEFb-sM',
                'annSor' => '2014'
            ],
            [
                'nomFil' => 'Get Out',
                'datFil' => '01:44:00',
                'afiFil' => 'getout.jpg',
                'desFil' => 'Un homme découvre un complot terrifiant lors d\'une visite chez sa petite amie.',
                'idGen' => 11,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=DzfpyUB60YY',
                'annSor' => '2017'
            ],
            [
                'nomFil' => 'La La Land',
                'datFil' => '02:08:00',
                'afiFil' => 'lalaland.jpg',
                'desFil' => 'Une actrice et un musicien tombent amoureux à Los Angeles.',
                'idGen' => 12,
                'malVoyEnt' => false,
                'banAnn' => 'https://youtube.com/watch?v=0pdqf4P9MB8',
                'annSor' => '2016'
            ],
            [
                'nomFil' => 'The Lobster',
                'datFil' => '01:59:00',
                'afiFil' => 'thelobster.jpg',
                'desFil' => 'Dans un monde futuriste, les célibataires sont transformés en animaux s’ils ne trouvent pas l’amour.',
                'idGen' => 8,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=Uy23OZq1zzE',
                'annSor' => '2015'
            ],
            [
                'nomFil' => 'Moonlight',
                'datFil' => '01:51:00',
                'afiFil' => 'moonlight.jpg',
                'desFil' => 'L’histoire d’un jeune Afro-Américain explorant son identité et son homosexualité.',
                'idGen' => 5,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=9NJj12tJzqc',
                'annSor' => '2016'
            ],
            [
                'nomFil' => 'Arrival',
                'datFil' => '01:56:00',
                'afiFil' => 'arrival.jpg',
                'desFil' => 'Une linguiste tente de communiquer avec des extraterrestres.',
                'idGen' => 6,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=tFMo3UJ4B4g',
                'annSor' => '2016'
            ],
            [
                'nomFil' => 'Blade Runner 2049',
                'datFil' => '02:44:00',
                'afiFil' => 'bladerunner2049.jpg',
                'desFil' => 'Un agent découvre un secret qui menace de plonger la société dans le chaos.',
                'idGen' => 6,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=gCcx85zbxz4',
                'annSor' => '2017'
            ],
            [
                'nomFil' => 'The Shape of Water',
                'datFil' => '02:03:00',
                'afiFil' => 'shapeofwater.jpg',
                'desFil' => 'Une femme muette tombe amoureuse d’une créature aquatique.',
                'idGen' => 8,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=X0Rn6aFo1_M',
                'annSor' => '2017'
            ],
            [
                'nomFil' => 'Joker',
                'datFil' => '02:02:00',
                'afiFil' => 'joker.jpg',
                'desFil' => 'L’ascension d’un homme rejeté par la société vers la folie et la criminalité.',
                'idGen' => 17,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=zAGVQLHvwOY',
                'annSor' => '2019'
            ],
            [
                'nomFil' => 'Tenet',
                'datFil' => '02:30:00',
                'afiFil' => 'tenet.jpg',
                'desFil' => 'Un agent manipule le temps pour empêcher une guerre mondiale.',
                'idGen' => 1,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=LdOM0x0XDMo',
                'annSor' => '2020'
            ],
            [
                'nomFil' => 'Nomadland',
                'datFil' => '01:47:00',
                'afiFil' => 'nomadland.jpg',
                'desFil' => 'Une femme vit dans une fourgonnette et parcourt l’Ouest américain.',
                'idGen' => 5,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=PS1WPLaYjEc',
                'annSor' => '2020'
            ],
            [
                'nomFil' => 'Dune',
                'datFil' => '02:35:00',
                'afiFil' => 'dune.jpg',
                'desFil' => 'Un jeune homme destiné à devenir le sauveur d’un monde désertique.',
                'idGen' => 6,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=n9xhJrEQJEQ',
                'annSor' => '2021'
            ],
            [
                'nomFil' => 'Everything Everywhere All at Once',
                'datFil' => '02:19:00',
                'afiFil' => 'everything.jpg',
                'desFil' => 'Une femme découvre qu’elle peut accéder à d’autres univers parallèles.',
                'idGen' => 8,
                'malVoyEnt' => true,
                'banAnn' => 'https://youtube.com/watch?v=wxN1T1uxQ2g',
                'annSor' => '2022'
            ]
        ]);
    }
}
