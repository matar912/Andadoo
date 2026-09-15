<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\MaintenanceLog;
use App\Models\Option;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'matar9@gmail.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Administrateur GO CAR',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'phone' => '+221770000001',
                'locale' => 'fr',
                'email_verified_at' => now(),
            ]
        );

        // CLIENTS
        $client1 = User::firstOrCreate(
            ['email' => 'matar@gmail.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Matar Gueye',
                'password' => Hash::make('12345678'),
                'role' => 'client',
                'phone' => '+221770000002',
                'locale' => 'fr',
                'email_verified_at' => now(),
            ]
        );

        $client2 = User::firstOrCreate(
            ['email' => 'awa@gmail.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Awa Diop',
                'password' => Hash::make('12345678'),
                'role' => 'client',
                'phone' => '+221770000003',
                'locale' => 'fr',
                'email_verified_at' => now(),
            ]
        );

        $client3 = User::firstOrCreate(
            ['email' => 'ibrahima@gmail.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Ibrahima Fall',
                'password' => Hash::make('12345678'),
                'role' => 'client',
                'phone' => '+221770000004',
                'locale' => 'fr',
                'email_verified_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CHAUFFEURS (USERS)
        |--------------------------------------------------------------------------
        */

        $driverUser1 = User::firstOrCreate(
            ['email' => 'ousmane@gmail.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Ousmane Ndiaye',
                'password' => Hash::make('12345678'),
                'role' => 'driver',
                'phone' => '+221770000005',
                'locale' => 'fr',
                'email_verified_at' => now(),
            ]
        );

        $driverUser2 = User::firstOrCreate(
            ['email' => 'cheikh@gmail.com'],
            [
                'uuid' => Str::uuid(),
                'name' => 'Cheikh Sow',
                'password' => Hash::make('12345678'),
                'role' => 'driver',
                'phone' => '+221770000006',
                'locale' => 'fr',
                'email_verified_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | DRIVERS
        |--------------------------------------------------------------------------
        */

        $driver1 = Driver::firstOrCreate(
            ['user_id' => $driverUser1->id],
            [
                'uuid' => Str::uuid(),
                'license_number' => 'DK-2026-0001',
                'license_expiry' => '2028-12-31',
                'bilingual' => true,
                'status' => 'disponible',
            ]
        );

        $driver2 = Driver::firstOrCreate(
            ['user_id' => $driverUser2->id],
            [
                'uuid' => Str::uuid(),
                'license_number' => 'DK-2026-0002',
                'license_expiry' => '2029-06-30',
                'bilingual' => false,
                'status' => 'disponible',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VEHICLES
        |--------------------------------------------------------------------------
        */

        $vehicle1 = Vehicle::firstOrCreate(
            ['plate_number' => 'DK-1001-AA'],
            [
                'uuid' => Str::uuid(),
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2024,
                'category' => 'berline',
                'seats' => 5,
                'transmission' => 'automatique',
                'daily_price' => 35000,
                'status' => 'disponible',
                'photo_path' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=800&q=80',
                'description' => 'Berline confortable et économique.',
            ]
        );

        $vehicle2 = Vehicle::firstOrCreate(
            ['plate_number' => 'DK-1002-AA'],
            [
                'uuid' => Str::uuid(),
                'brand' => 'Toyota',
                'model' => 'RAV4',
                'year' => 2023,
                'category' => 'suv',
                'seats' => 5,
                'transmission' => 'automatique',
                'daily_price' => 55000,
                'status' => 'disponible',
                'photo_path' => 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=800&q=80',
                'description' => 'SUV moderne idéal pour les déplacements professionnels.',
            ]
        );

        $vehicle3 = Vehicle::firstOrCreate(
            ['plate_number' => 'DK-1003-AA'],
            [
                'uuid' => Str::uuid(),
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'year' => 2024,
                'category' => 'suv',
                'seats' => 5,
                'transmission' => 'automatique',
                'daily_price' => 50000,
                'status' => 'disponible',
                'photo_path' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                'description' => 'SUV spacieux et confortable.',
            ]
        );

        $vehicle4 = Vehicle::firstOrCreate(
            ['plate_number' => 'DK-1004-AA'],
            [
                'uuid' => Str::uuid(),
                'brand' => 'Mercedes-Benz',
                'model' => 'Classe E',
                'year' => 2022,
                'category' => 'berline',
                'seats' => 5,
                'transmission' => 'automatique',
                'daily_price' => 85000,
                'status' => 'en_location',
                'photo_path' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80',
                'description' => 'Berline haut de gamme avec intérieur premium.',
            ]
        );

        $vehicle5 = Vehicle::firstOrCreate(
            ['plate_number' => 'DK-1005-AA'],
            [
                'uuid' => Str::uuid(),
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2023,
                'category' => '4x4',
                'seats' => 5,
                'transmission' => 'manuelle',
                'daily_price' => 60000,
                'status' => 'disponible',
                'photo_path' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
                'description' => '4x4 robuste adapté aux longues distances.',
            ]
        );

        $vehicle6 = Vehicle::firstOrCreate(
            ['plate_number' => 'DK-1006-AA'],
            [
                'uuid' => Str::uuid(),
                'brand' => 'Hyundai',
                'model' => 'H1',
                'year' => 2022,
                'category' => 'minibus',
                'seats' => 9,
                'transmission' => 'manuelle',
                'daily_price' => 75000,
                'status' => 'maintenance',
                'photo_path' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
                'description' => 'Minibus idéal pour les déplacements en groupe.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | PARTNERS
        |--------------------------------------------------------------------------
        */

        $partner1 = Partner::firstOrCreate(['name' => 'Sénégal Travel'], [
            'uuid' => Str::uuid(),
            'type' => 'agence_voyage',
            'contact_email' => 'contact@senegaltravel.sn',
            'contact_phone' => '+221338000001',
        ]);

        $partner2 = Partner::firstOrCreate(['name' => 'Hôtel Teranga'], [
            'uuid' => Str::uuid(),
            'type' => 'hotel',
            'contact_email' => 'contact@hotelteranga.sn',
            'contact_phone' => '+221338000002',
        ]);

        $partner3 = Partner::firstOrCreate(['name' => 'Air Sénégal'], [
            'uuid' => Str::uuid(),
            'type' => 'compagnie_aerienne',
            'contact_email' => 'contact@airsenegal.sn',
            'contact_phone' => '+221338000003',
        ]);

        $partner4 = Partner::firstOrCreate(['name' => 'Business Travel Dakar'], [
            'uuid' => Str::uuid(),
            'type' => 'autre',
            'contact_email' => 'contact@btdakar.sn',
            'contact_phone' => '+221338000004',
        ]);

        /*
        |--------------------------------------------------------------------------
        | OPTIONS
        |--------------------------------------------------------------------------
        */

        $option1 = Option::firstOrCreate(['name' => 'Siège bébé'], ['extra_price' => 5000]);
        $option2 = Option::firstOrCreate(['name' => 'GPS'], ['extra_price' => 3000]);
        $option3 = Option::firstOrCreate(['name' => 'Wi-Fi à bord'], ['extra_price' => 5000]);
        $option4 = Option::firstOrCreate(['name' => 'Chauffeur professionnel'], ['extra_price' => 15000]);
        $option5 = Option::firstOrCreate(['name' => 'Assurance premium'], ['extra_price' => 10000]);
        $option6 = Option::firstOrCreate(['name' => 'Livraison du véhicule'], ['extra_price' => 7500]);

        /*
        |--------------------------------------------------------------------------
        | RESERVATIONS, PAYMENTS, REVIEWS, MAINTENANCE
        |--------------------------------------------------------------------------
        */

        if (Reservation::count() === 0) {
            $reservation1 = Reservation::create([
                'uuid' => Str::uuid(),
                'client_id' => $client1->id,
                'vehicle_id' => $vehicle1->id,
                'driver_id' => null,
                'partner_id' => $partner1->id,
                'formula' => 'location_locale',
                'with_driver' => false,
                'pickup_location' => 'Dakar Centre',
                'dropoff_location' => 'Dakar Centre',
                'start_at' => now()->subDays(10),
                'end_at' => now()->subDays(7),
                'status' => 'terminee',
                'total_price' => 105000,
            ]);

            $reservation2 = Reservation::create([
                'uuid' => Str::uuid(),
                'client_id' => $client2->id,
                'vehicle_id' => $vehicle2->id,
                'driver_id' => $driver1->id,
                'partner_id' => $partner2->id,
                'formula' => 'transfert_plus_location',
                'with_driver' => true,
                'flight_number' => 'HC401',
                'pickup_location' => 'Aéroport Blaise Diagne',
                'dropoff_location' => 'Hôtel Teranga',
                'start_at' => now()->addDays(2),
                'end_at' => now()->addDays(5),
                'status' => 'confirmee',
                'total_price' => 180000,
            ]);

            $reservation1->options()->syncWithoutDetaching([$option2->id, $option5->id]);
            $reservation2->options()->syncWithoutDetaching([$option3->id, $option4->id]);

            Payment::create([
                'uuid' => Str::uuid(),
                'reservation_id' => $reservation1->id,
                'amount' => 105000,
                'method' => 'wave',
                'status' => 'reussi',
                'transaction_ref' => 'WAVE-2026-000001',
                'paid_at' => now()->subDays(10),
            ]);

            Review::create([
                'reservation_id' => $reservation1->id,
                'client_id' => $client1->id,
                'rating' => 5,
                'comment' => 'Excellent véhicule, propre et très confortable.',
            ]);
        }

        if (MaintenanceLog::count() === 0) {
            MaintenanceLog::create([
                'vehicle_id' => $vehicle1->id,
                'performed_at' => now()->subMonths(2),
                'description' => 'Vidange moteur et remplacement des filtres.',
                'cost' => 75000,
            ]);
        }

        $this->command->info('========================================');
        $this->command->info('   SEEDER GO CAR TERMINE AVEC SUCCES    ');
        $this->command->info('========================================');
    }
}
