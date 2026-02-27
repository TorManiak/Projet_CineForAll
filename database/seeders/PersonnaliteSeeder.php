<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonnaliteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personnalite')->insert([
            // --- Réalisateurs ---
            ['idPer' => 1,  'nomPer' => 'Nolan',        'prePer' => 'Christopher',  'datNaiPer' => '1970-07-30', 'desPer' => 'Realisateur.',  'idNat' => 6],
            ['idPer' => 2,  'nomPer' => 'Kassovitz',    'prePer' => 'Mathieu',      'datNaiPer' => '1967-08-03', 'desPer' => 'Realisateur.',  'idNat' => 1],
            ['idPer' => 3,  'nomPer' => 'Jeunet',       'prePer' => 'Jean-Pierre',  'datNaiPer' => '1953-09-03', 'desPer' => 'Realisateur.',  'idNat' => 1],
            ['idPer' => 4,  'nomPer' => 'Scorsese',     'prePer' => 'Martin',       'datNaiPer' => '1942-11-17', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 5,  'nomPer' => 'Tarantino',    'prePer' => 'Quentin',      'datNaiPer' => '1963-03-27', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 6,  'nomPer' => 'Inarritu',     'prePer' => 'Alejandro',    'datNaiPer' => '1963-08-15', 'desPer' => 'Realisateur.',  'idNat' => 10],
            ['idPer' => 7,  'nomPer' => 'Bong',         'prePer' => 'Joon-ho',      'datNaiPer' => '1969-09-14', 'desPer' => 'Realisateur.',  'idNat' => 11],
            ['idPer' => 8,  'nomPer' => 'Anderson',     'prePer' => 'Wes',          'datNaiPer' => '1969-05-01', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 9,  'nomPer' => 'Miller',       'prePer' => 'George',       'datNaiPer' => '1945-03-03', 'desPer' => 'Realisateur.',  'idNat' => 9],
            ['idPer' => 10, 'nomPer' => 'Miyazaki',     'prePer' => 'Hayao',        'datNaiPer' => '1941-01-05', 'desPer' => 'Realisateur.',  'idNat' => 4],
            ['idPer' => 11, 'nomPer' => 'Fincher',      'prePer' => 'David',        'datNaiPer' => '1962-08-28', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 12, 'nomPer' => 'Chazelle',     'prePer' => 'Damien',       'datNaiPer' => '1985-01-19', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 13, 'nomPer' => 'Tyldum',       'prePer' => 'Morten',       'datNaiPer' => '1967-05-19', 'desPer' => 'Realisateur.',  'idNat' => 18],
            ['idPer' => 14, 'nomPer' => 'Peele',        'prePer' => 'Jordan',       'datNaiPer' => '1979-02-21', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 15, 'nomPer' => 'Lanthimos',    'prePer' => 'Yorgos',       'datNaiPer' => '1973-09-27', 'desPer' => 'Realisateur.',  'idNat' => 19],
            ['idPer' => 16, 'nomPer' => 'Jenkins',      'prePer' => 'Barry',        'datNaiPer' => '1979-11-19', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 17, 'nomPer' => 'Villeneuve',   'prePer' => 'Denis',        'datNaiPer' => '1967-10-03', 'desPer' => 'Realisateur.',  'idNat' => 7],
            ['idPer' => 18, 'nomPer' => 'Del Toro',     'prePer' => 'Guillermo',    'datNaiPer' => '1964-10-09', 'desPer' => 'Realisateur.',  'idNat' => 10],
            ['idPer' => 19, 'nomPer' => 'Phillips',     'prePer' => 'Todd',         'datNaiPer' => '1970-12-20', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 20, 'nomPer' => 'Zhao',         'prePer' => 'Chloe',        'datNaiPer' => '1982-03-31', 'desPer' => 'Realisatrice.', 'idNat' => 5],
            ['idPer' => 21, 'nomPer' => 'Kwan',         'prePer' => 'Daniel',       'datNaiPer' => '1988-02-10', 'desPer' => 'Realisateur.',  'idNat' => 2],
            ['idPer' => 22, 'nomPer' => 'Scheinert',    'prePer' => 'Daniel',       'datNaiPer' => '1987-06-07', 'desPer' => 'Realisateur.',  'idNat' => 2],

            // --- Acteurs (principaux) ---
            ['idPer' => 23, 'nomPer' => 'DiCaprio',     'prePer' => 'Leonardo',     'datNaiPer' => '1974-11-11', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 24, 'nomPer' => 'Gordon-Levitt','prePer' => 'Joseph',       'datNaiPer' => '1981-02-17', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 25, 'nomPer' => 'Page',         'prePer' => 'Elliot',       'datNaiPer' => '1987-02-21', 'desPer' => 'Acteur.',       'idNat' => 7],

            ['idPer' => 26, 'nomPer' => 'McConaughey',  'prePer' => 'Matthew',      'datNaiPer' => '1969-11-04', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 27, 'nomPer' => 'Hathaway',     'prePer' => 'Anne',         'datNaiPer' => '1982-11-12', 'desPer' => 'Actrice.',      'idNat' => 2],
            ['idPer' => 28, 'nomPer' => 'Chastain',     'prePer' => 'Jessica',      'datNaiPer' => '1977-03-24', 'desPer' => 'Actrice.',      'idNat' => 2],

            ['idPer' => 29, 'nomPer' => 'Cassel',       'prePer' => 'Vincent',      'datNaiPer' => '1966-11-23', 'desPer' => 'Acteur.',       'idNat' => 1],
            ['idPer' => 30, 'nomPer' => 'Kounde',       'prePer' => 'Hubert',       'datNaiPer' => '1970-12-30', 'desPer' => 'Acteur.',       'idNat' => 1],
            ['idPer' => 31, 'nomPer' => 'Taghmaoui',    'prePer' => 'Said',         'datNaiPer' => '1973-07-19', 'desPer' => 'Acteur.',       'idNat' => 1],

            ['idPer' => 32, 'nomPer' => 'Tautou',       'prePer' => 'Audrey',       'datNaiPer' => '1976-08-09', 'desPer' => 'Actrice.',      'idNat' => 1],
            ['idPer' => 33, 'nomPer' => 'Rufus',        'prePer' => 'Rufus',        'datNaiPer' => '1942-12-19', 'desPer' => 'Acteur.',       'idNat' => 1],

            ['idPer' => 34, 'nomPer' => 'Ruffalo',      'prePer' => 'Mark',         'datNaiPer' => '1967-11-22', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 35, 'nomPer' => 'Kingsley',     'prePer' => 'Ben',          'datNaiPer' => '1943-12-31', 'desPer' => 'Acteur.',       'idNat' => 6],

            ['idPer' => 36, 'nomPer' => 'Bale',         'prePer' => 'Christian',    'datNaiPer' => '1974-01-30', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 37, 'nomPer' => 'Ledger',       'prePer' => 'Heath',        'datNaiPer' => '1979-04-04', 'desPer' => 'Acteur.',       'idNat' => 9],
            ['idPer' => 38, 'nomPer' => 'Eckhart',      'prePer' => 'Aaron',        'datNaiPer' => '1968-03-12', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 39, 'nomPer' => 'Foxx',         'prePer' => 'Jamie',        'datNaiPer' => '1967-12-13', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 40, 'nomPer' => 'Waltz',        'prePer' => 'Christoph',    'datNaiPer' => '1956-10-04', 'desPer' => 'Acteur.',       'idNat' => 8],

            ['idPer' => 41, 'nomPer' => 'Hardy',        'prePer' => 'Tom',          'datNaiPer' => '1977-09-15', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 42, 'nomPer' => 'Gleeson',      'prePer' => 'Domhnall',     'datNaiPer' => '1983-05-12', 'desPer' => 'Acteur.',       'idNat' => 13],

            ['idPer' => 43, 'nomPer' => 'Song',         'prePer' => 'Kang-ho',      'datNaiPer' => '1967-01-17', 'desPer' => 'Acteur.',       'idNat' => 11],
            ['idPer' => 44, 'nomPer' => 'Lee',          'prePer' => 'Sun-kyun',     'datNaiPer' => '1975-03-02', 'desPer' => 'Acteur.',       'idNat' => 11],
            ['idPer' => 45, 'nomPer' => 'Cho',          'prePer' => 'Yeo-jeong',    'datNaiPer' => '1981-02-10', 'desPer' => 'Actrice.',      'idNat' => 11],

            ['idPer' => 46, 'nomPer' => 'Fiennes',      'prePer' => 'Ralph',        'datNaiPer' => '1962-12-22', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 47, 'nomPer' => 'Revolori',     'prePer' => 'Tony',         'datNaiPer' => '1996-04-28', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 48, 'nomPer' => 'Brody',        'prePer' => 'Adrien',       'datNaiPer' => '1973-04-14', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 49, 'nomPer' => 'Theron',       'prePer' => 'Charlize',     'datNaiPer' => '1975-08-07', 'desPer' => 'Actrice.',      'idNat' => 17],
            ['idPer' => 50, 'nomPer' => 'Hoult',        'prePer' => 'Nicholas',     'datNaiPer' => '1989-12-07', 'desPer' => 'Acteur.',       'idNat' => 6],

            ['idPer' => 51, 'nomPer' => 'Eisenberg',    'prePer' => 'Jesse',        'datNaiPer' => '1983-10-05', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 52, 'nomPer' => 'Garfield',     'prePer' => 'Andrew',       'datNaiPer' => '1983-08-20', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 53, 'nomPer' => 'Timberlake',   'prePer' => 'Justin',       'datNaiPer' => '1981-01-31', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 54, 'nomPer' => 'Teller',       'prePer' => 'Miles',        'datNaiPer' => '1987-02-20', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 55, 'nomPer' => 'Simmons',      'prePer' => 'J.K.',         'datNaiPer' => '1955-01-09', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 56, 'nomPer' => 'Reiser',       'prePer' => 'Paul',         'datNaiPer' => '1956-03-30', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 57, 'nomPer' => 'Cumberbatch',  'prePer' => 'Benedict',     'datNaiPer' => '1976-07-19', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 58, 'nomPer' => 'Knightley',    'prePer' => 'Keira',        'datNaiPer' => '1985-03-26', 'desPer' => 'Actrice.',      'idNat' => 6],
            ['idPer' => 59, 'nomPer' => 'Goode',        'prePer' => 'Matthew',      'datNaiPer' => '1978-04-03', 'desPer' => 'Acteur.',       'idNat' => 6],

            ['idPer' => 60, 'nomPer' => 'Kaluuya',      'prePer' => 'Daniel',       'datNaiPer' => '1989-02-24', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 61, 'nomPer' => 'Williams',     'prePer' => 'Allison',      'datNaiPer' => '1988-04-13', 'desPer' => 'Actrice.',      'idNat' => 2],
            ['idPer' => 62, 'nomPer' => 'Whitford',     'prePer' => 'Bradley',      'datNaiPer' => '1959-06-10', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 63, 'nomPer' => 'Gosling',      'prePer' => 'Ryan',         'datNaiPer' => '1980-11-12', 'desPer' => 'Acteur.',       'idNat' => 7],
            ['idPer' => 64, 'nomPer' => 'Stone',        'prePer' => 'Emma',         'datNaiPer' => '1988-11-06', 'desPer' => 'Actrice.',      'idNat' => 2],
            ['idPer' => 65, 'nomPer' => 'Legend',       'prePer' => 'John',         'datNaiPer' => '1978-12-28', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 66, 'nomPer' => 'Farrell',      'prePer' => 'Colin',        'datNaiPer' => '1976-05-31', 'desPer' => 'Acteur.',       'idNat' => 13],
            ['idPer' => 67, 'nomPer' => 'Weisz',        'prePer' => 'Rachel',       'datNaiPer' => '1970-03-07', 'desPer' => 'Actrice.',      'idNat' => 6],
            ['idPer' => 68, 'nomPer' => 'Seydoux',      'prePer' => 'Lea',          'datNaiPer' => '1985-07-01', 'desPer' => 'Actrice.',      'idNat' => 1],

            ['idPer' => 69, 'nomPer' => 'Rhodes',       'prePer' => 'Trevante',     'datNaiPer' => '1990-02-10', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 70, 'nomPer' => 'Holland',      'prePer' => 'Andre',        'datNaiPer' => '1979-12-28', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 71, 'nomPer' => 'Harris',       'prePer' => 'Naomie',       'datNaiPer' => '1976-09-06', 'desPer' => 'Actrice.',      'idNat' => 6],

            ['idPer' => 72, 'nomPer' => 'Adams',        'prePer' => 'Amy',          'datNaiPer' => '1974-08-20', 'desPer' => 'Actrice.',      'idNat' => 2],
            ['idPer' => 73, 'nomPer' => 'Renner',       'prePer' => 'Jeremy',       'datNaiPer' => '1971-01-07', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 74, 'nomPer' => 'Whitaker',     'prePer' => 'Forest',       'datNaiPer' => '1961-07-15', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 75, 'nomPer' => 'Ford',         'prePer' => 'Harrison',     'datNaiPer' => '1942-07-13', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 76, 'nomPer' => 'De Armas',     'prePer' => 'Ana',          'datNaiPer' => '1988-04-30', 'desPer' => 'Actrice.',      'idNat' => 15],

            ['idPer' => 77, 'nomPer' => 'Hawkins',      'prePer' => 'Sally',        'datNaiPer' => '1976-04-27', 'desPer' => 'Actrice.',      'idNat' => 6],
            ['idPer' => 78, 'nomPer' => 'Shannon',      'prePer' => 'Michael',      'datNaiPer' => '1974-08-07', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 79, 'nomPer' => 'Jenkins',      'prePer' => 'Richard',      'datNaiPer' => '1947-05-04', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 80, 'nomPer' => 'Phoenix',      'prePer' => 'Joaquin',      'datNaiPer' => '1974-10-28', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 81, 'nomPer' => 'De Niro',      'prePer' => 'Robert',       'datNaiPer' => '1943-08-17', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 82, 'nomPer' => 'Beetz',        'prePer' => 'Zazie',        'datNaiPer' => '1991-06-01', 'desPer' => 'Actrice.',      'idNat' => 2],

            ['idPer' => 83, 'nomPer' => 'Washington',   'prePer' => 'John David',   'datNaiPer' => '1984-07-28', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 84, 'nomPer' => 'Pattinson',    'prePer' => 'Robert',       'datNaiPer' => '1986-05-13', 'desPer' => 'Acteur.',       'idNat' => 6],
            ['idPer' => 85, 'nomPer' => 'Debicki',      'prePer' => 'Elizabeth',    'datNaiPer' => '1990-08-24', 'desPer' => 'Actrice.',      'idNat' => 9],

            ['idPer' => 86, 'nomPer' => 'McDormand',    'prePer' => 'Frances',      'datNaiPer' => '1957-06-23', 'desPer' => 'Actrice.',      'idNat' => 2],
            ['idPer' => 87, 'nomPer' => 'Strathairn',   'prePer' => 'David',        'datNaiPer' => '1949-01-26', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 88, 'nomPer' => 'May',          'prePer' => 'Linda',        'datNaiPer' => '1941-01-01', 'desPer' => 'Actrice.',      'idNat' => 2],

            ['idPer' => 89, 'nomPer' => 'Chalamet',     'prePer' => 'Timothee',     'datNaiPer' => '1995-12-27', 'desPer' => 'Acteur.',       'idNat' => 2],
            ['idPer' => 90, 'nomPer' => 'Ferguson',     'prePer' => 'Rebecca',      'datNaiPer' => '1983-10-19', 'desPer' => 'Actrice.',      'idNat' => 12],
            ['idPer' => 91, 'nomPer' => 'Isaac',        'prePer' => 'Oscar',        'datNaiPer' => '1979-03-09', 'desPer' => 'Acteur.',       'idNat' => 2],

            ['idPer' => 92, 'nomPer' => 'Yeoh',         'prePer' => 'Michelle',     'datNaiPer' => '1962-08-06', 'desPer' => 'Actrice.',      'idNat' => 16],
            ['idPer' => 93, 'nomPer' => 'Quan',         'prePer' => 'Ke Huy',       'datNaiPer' => '1971-08-20', 'desPer' => 'Acteur.',       'idNat' => 20],
            ['idPer' => 94, 'nomPer' => 'Hsu',          'prePer' => 'Stephanie',    'datNaiPer' => '1990-11-25', 'desPer' => 'Actrice.',      'idNat' => 2],
        ]);
    }
}
