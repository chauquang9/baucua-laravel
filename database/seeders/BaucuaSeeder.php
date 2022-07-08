<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Hash;

class BaucuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('baucua')->insert(
            [
                [
                    'name'     => 'Heo',
                    'image'    => '/images/heo.jpg',
                    'position' => 1,
                ],
                [
                    'name'     => 'Cua',
                    'image'    => '/images/cua.jpg',
                    'position' => 2,
                ],
                [
                    'name'     => 'Tôm',
                    'image'    => '/images/tom.jpg',
                    'position' => 3,
                ],
                [
                    'name'     => 'Gà',
                    'image'    => '/images/ga.jpg',
                    'position' => 4,
                ],
                [
                    'name'     => 'Bầu',
                    'image'    => '/images/bau.jpg',
                    'position' => 5,
                ],
                [
                    'name'     => 'Nai',
                    'image'    => '/images/nai.jpg',
                    'position' => 6,
                ],
            ]
        );

        $client = Passport::client()->forceFill([
            'user_id'                => NULL,
            'name'                   => 'admin',
            'secret'                 => 'KJ3ogcAJdVwB1C0FaU3agYPBR17Ovvcu8wdF8CWK',
            'provider'               => 'users',
            'redirect'               => 'localhost',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'revoked'                => FALSE,
        ]);

        $client->save();

        $dataUsers = [
            [
                'email'    => 'quang.chau@monimedia.com',
                'name'     => 'Quang Chau',
                'password' => Hash::make('Quang0908043771'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'quan.nguyen@monimedia.com',
                'name'     => 'Quan Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'khoa.nguyen@monimedia.com',
                'name'     => 'Khoa Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'loc.nguyen@monimedia.com',
                'name'     => 'Loc Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'tam.nc@monimedia.com',
                'name'     => 'Tam Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'long.nguyen@monimedia.com',
                'name'     => 'Long Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'anhkhoa.nguyen@monimedia.com',
                'name'     => 'Khoa Nho',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'phu.huynh@monimedia.com',
                'name'     => 'Phu Huynh',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'phung.duong@monimedia.com',
                'name'     => 'Phung Duong',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'hai.pham@monimedia.com',
                'name'     => 'Hai Pham',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'yen.truong@monimedia.com',
                'name'     => 'Yen Truong',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'tan.nguyen@monimedia.com',
                'name'     => 'Tan Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'viet.bui@monimedia.com',
                'name'     => 'Viet Bui',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'huy.duc@monimedia.com',
                'name'     => 'Huy Duc',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'tamchi.nguyen@monimedia.com',
                'name'     => 'Tam Chi Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'trang.nguyen@monimedia.com',
                'name'     => 'Trang Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'quynh.nguyen@monimedia.com',
                'name'     => 'Quynh Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'huy.nguyen@monimedia.com',
                'name'     => 'Huy Nguyen',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
            [
                'email'    => 'trinh.le@monimedia.com',
                'name'     => 'Trinh Le',
                'password' => Hash::make('Testing1'),
                'colorHex' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ],
        ];

        User::upsert($dataUsers, ['id'], [
            'email',
            'name',
            'colorHex',
            'password',
        ]);
    }
}
