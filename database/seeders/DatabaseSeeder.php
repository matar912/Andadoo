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
        $admin = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Administrateur GO CAR',
            'email' => 'matar9@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'phone' => '+221770000001',
            'locale' => 'fr',
            'email_verified_at' => now(),
        ]);

        // CLIENTS
        $client1 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Matar Gueye',
            'email' => 'matar@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'client',
            'phone' => '+221770000002',
            'locale' => 'fr',
            'email_verified_at' => now(),
        ]);

        $client2 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Awa Diop',
            'email' => 'awa@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'client',
            'phone' => '+221770000003',
            'locale' => 'fr',
            'email_verified_at' => now(),
        ]);

        $client3 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Ibrahima Fall',
            'email' => 'ibrahima@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'client',
            'phone' => '+221770000004',
            'locale' => 'fr',
            'email_verified_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | CHAUFFEURS (USERS)
        |--------------------------------------------------------------------------
        */

        $driverUser1 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Ousmane Ndiaye',
            'email' => 'ousmane@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'driver',
            'phone' => '+221770000005',
            'locale' => 'fr',
            'email_verified_at' => now(),
        ]);

        $driverUser2 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Cheikh Sow',
            'email' => 'cheikh@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'driver',
            'phone' => '+221770000006',
            'locale' => 'fr',
            'email_verified_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | DRIVERS
        |--------------------------------------------------------------------------
        */

        $driver1 = Driver::create([
            'uuid' => Str::uuid(),
            'user_id' => $driverUser1->id,
            'license_number' => 'DK-2026-0001',
            'license_expiry' => '2028-12-31',
            'bilingual' => true,
            'status' => 'disponible',
        ]);

        $driver2 = Driver::create([
            'uuid' => Str::uuid(),
            'user_id' => $driverUser2->id,
            'license_number' => 'DK-2026-0002',
            'license_expiry' => '2029-06-30',
            'bilingual' => false,
            'status' => 'disponible',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VEHICLES
        |--------------------------------------------------------------------------
        */

        $vehicle1 = Vehicle::create([
            'uuid' => Str::uuid(),
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2024,
            'plate_number' => 'DK-1001-AA',
            'category' => 'berline',
            'seats' => 5,
            'transmission' => 'automatique',
            'daily_price' => 35000,
            'status' => 'disponible',
            'photo_path' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=800&q=80',
            'description' => 'Berline confortable et économique.',
        ]);

        $vehicle2 = Vehicle::create([
            'uuid' => Str::uuid(),
            'brand' => 'Toyota',
            'model' => 'RAV4',
            'year' => 2023,
            'plate_number' => 'DK-1002-AA',
            'category' => 'suv',
            'seats' => 5,
            'transmission' => 'automatique',
            'daily_price' => 55000,
            'status' => 'disponible',
            'photo_path' => 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=800&q=80',
            'description' => 'SUV moderne idéal pour les déplacements professionnels.',
        ]);

        $vehicle3 = Vehicle::create([
            'uuid' => Str::uuid(),
            'brand' => 'Hyundai',
            'model' => 'Tucson',
            'year' => 2024,
            'plate_number' => 'DK-1003-AA',
            'category' => 'suv',
            'seats' => 5,
            'transmission' => 'automatique',
            'daily_price' => 50000,
            'status' => 'disponible',
            'photo_path' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
            'description' => 'SUV spacieux et confortable.',
        ]);

        $vehicle4 = Vehicle::create([
            'uuid' => Str::uuid(),
            'brand' => 'Mercedes-Benz',
            'model' => 'Classe E',
            'year' => 2022,
            'plate_number' => 'DK-1004-AA',
            'category' => 'berline',
            'seats' => 5,
            'transmission' => 'automatique',
            'daily_price' => 85000,
            'status' => 'en_location',
            'photo_path' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80',
            'description' => 'Berline haut de gamme avec intérieur premium.',
        ]);

        $vehicle5 = Vehicle::create([
            'uuid' => Str::uuid(),
            'brand' => 'Toyota',
            'model' => 'Hilux',
            'year' => 2023,
            'plate_number' => 'DK-1005-AA',
            'category' => '4x4',
            'seats' => 5,
            'transmission' => 'manuelle',
            'daily_price' => 60000,
            'status' => 'disponible',
            'photo_path' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
            'description' => '4x4 robuste adapté aux longues distances.',
        ]);

        $vehicle6 = Vehicle::create([
            'uuid' => Str::uuid(),
            'brand' => 'Hyundai',
            'model' => 'H1',
            'year' => 2022,
            'plate_number' => 'DK-1006-AA',
            'category' => 'minibus',
            'seats' => 9,
            'transmission' => 'manuelle',
            'daily_price' => 75000,
            'status' => 'maintenance',
            'photo_path' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
            'description' => 'Minibus idéal pour les déplacements en groupe.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PARTNERS
        |--------------------------------------------------------------------------
        */

        $partner1 = Partner::create([
            'uuid' => Str::uuid(),
            'name' => 'Sénégal Travel',
            'type' => 'agence_voyage',
            'contact_email' => 'contact@senegaltravel.sn',
            'contact_phone' => '+221338000001',
        ]);

        $partner2 = Partner::create([
            'uuid' => Str::uuid(),
            'name' => 'Hôtel Teranga',
            'type' => 'hotel',
            'contact_email' => 'contact@hotelteranga.sn',
            'contact_phone' => '+221338000002',
        ]);

        $partner3 = Partner::create([
            'uuid' => Str::uuid(),
            'name' => 'Air Sénégal',
            'type' => 'compagnie_aerienne',
            'contact_email' => 'contact@airsenegal.sn',
            'contact_phone' => '+221338000003',
        ]);

        $partner4 = Partner::create([
            'uuid' => Str::uuid(),
            'name' => 'Business Travel Dakar',
            'type' => 'autre',
            'contact_email' => 'contact@btdakar.sn',
            'contact_phone' => '+221338000004',
        ]);

        /*
        |--------------------------------------------------------------------------
        | OPTIONS
        |--------------------------------------------------------------------------
        */

        $option1 = Option::create([
            'name' => 'Siège bébé',
            'extra_price' => 5000,
        ]);

        $option2 = Option::create([
            'name' => 'GPS',
            'extra_price' => 3000,
        ]);

        $option3 = Option::create([
            'name' => 'Wi-Fi à bord',
            'extra_price' => 5000,
        ]);

        $option4 = Option::create([
            'name' => 'Chauffeur professionnel',
            'extra_price' => 15000,
        ]);

        $option5 = Option::create([
            'name' => 'Assurance premium',
            'extra_price' => 10000,
        ]);

        $option6 = Option::create([
            'name' => 'Livraison du véhicule',
            'extra_price' => 7500,
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESERVATIONS
        |--------------------------------------------------------------------------
        */

        $reservation1 = Reservation::create([
            'uuid' => Str::uuid(),
            'client_id' => $client1->id,
            'vehicle_id' => $vehicle1->id,
            'driver_id' => null,
            'partner_id' => $partner1->id,
            'formula' => 'location_locale',
            'with_driver' => false,
            'flight_number' => null,
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

        $reservation3 = Reservation::create([
            'uuid' => Str::uuid(),
            'client_id' => $client3->id,
            'vehicle_id' => $vehicle3->id,
            'driver_id' => null,
            'partner_id' => null,
            'formula' => 'longue_duree',
            'with_driver' => false,
            'flight_number' => null,
            'pickup_location' => 'Dakar',
            'dropoff_location' => 'Dakar',
            'start_at' => now()->addDays(10),
            'end_at' => now()->addDays(20),
            'status' => 'en_attente',
            'total_price' => 500000,
        ]);

        $reservation4 = Reservation::create([
            'uuid' => Str::uuid(),
            'client_id' => $client1->id,
            'vehicle_id' => $vehicle5->id,
            'driver_id' => $driver2->id,
            'partner_id' => $partner3->id,
            'formula' => 'transfert_simple',
            'with_driver' => true,
            'flight_number' => 'HC512',
            'pickup_location' => 'Aéroport Blaise Diagne',
            'dropoff_location' => 'Saly',
            'start_at' => now()->subDays(20),
            'end_at' => now()->subDays(19),
            'status' => 'terminee',
            'total_price' => 65000,
        ]);

        $reservation5 = Reservation::create([
            'uuid' => Str::uuid(),
            'client_id' => $client2->id,
            'vehicle_id' => $vehicle4->id,
            'driver_id' => null,
            'partner_id' => $partner4->id,
            'formula' => 'location_locale',
            'with_driver' => false,
            'flight_number' => null,
            'pickup_location' => 'Almadies',
            'dropoff_location' => 'Almadies',
            'start_at' => now()->addDays(25),
            'end_at' => now()->addDays(28),
            'status' => 'en_attente',
            'total_price' => 255000,
        ]);

        $reservation6 = Reservation::create([
            'uuid' => Str::uuid(),
            'client_id' => $client3->id,
            'vehicle_id' => $vehicle1->id,
            'driver_id' => null,
            'partner_id' => null,
            'formula' => 'location_locale',
            'with_driver' => false,
            'flight_number' => null,
            'pickup_location' => 'Plateau',
            'dropoff_location' => 'Plateau',
            'start_at' => now()->subDays(5),
            'end_at' => now()->subDays(3),
            'status' => 'terminee',
            'total_price' => 70000,
        ]);

        /*
        |--------------------------------------------------------------------------
        | OPTIONS DES RESERVATIONS
        |--------------------------------------------------------------------------
        */

        $reservation1->options()->attach([$option2->id, $option5->id]);
        $reservation2->options()->attach([$option3->id, $option4->id]);
        $reservation3->options()->attach([$option5->id]);
        $reservation4->options()->attach([$option4->id, $option6->id]);
        $reservation5->options()->attach([$option2->id, $option5->id]);

        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

        Payment::create([
            'uuid' => Str::uuid(),
            'reservation_id' => $reservation1->id,
            'amount' => 105000,
            'method' => 'wave',
            'status' => 'reussi',
            'transaction_ref' => 'WAVE-2026-000001',
            'paid_at' => now()->subDays(10),
        ]);

        Payment::create([
            'uuid' => Str::uuid(),
            'reservation_id' => $reservation2->id,
            'amount' => 180000,
            'method' => 'orange_money',
            'status' => 'reussi',
            'transaction_ref' => 'OM-2026-000001',
            'paid_at' => now()->subDays(1),
        ]);

        Payment::create([
            'uuid' => Str::uuid(),
            'reservation_id' => $reservation3->id,
            'amount' => 250000,
            'method' => 'carte',
            'status' => 'en_attente',
            'transaction_ref' => null,
            'paid_at' => null,
        ]);

        Payment::create([
            'uuid' => Str::uuid(),
            'reservation_id' => $reservation4->id,
            'amount' => 65000,
            'method' => 'free_money',
            'status' => 'reussi',
            'transaction_ref' => 'FM-2026-000001',
            'paid_at' => now()->subDays(20),
        ]);

        Payment::create([
            'uuid' => Str::uuid(),
            'reservation_id' => $reservation6->id,
            'amount' => 70000,
            'method' => 'wave',
            'status' => 'reussi',
            'transaction_ref' => 'WAVE-2026-000002',
            'paid_at' => now()->subDays(5),
        ]);

        /*
        |--------------------------------------------------------------------------
        | REVIEWS
        |--------------------------------------------------------------------------
        */

        Review::create([
            'reservation_id' => $reservation1->id,
            'client_id' => $client1->id,
            'rating' => 5,
            'comment' => 'Excellent véhicule, propre et très confortable.',
        ]);

        Review::create([
            'reservation_id' => $reservation4->id,
            'client_id' => $client1->id,
            'rating' => 4,
            'comment' => 'Très bonne expérience avec le chauffeur.',
        ]);

        Review::create([
            'reservation_id' => $reservation6->id,
            'client_id' => $client3->id,
            'rating' => 5,
            'comment' => 'Service rapide et véhicule impeccable.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | MAINTENANCE
        |--------------------------------------------------------------------------
        */

        MaintenanceLog::create([
            'vehicle_id' => $vehicle1->id,
            'performed_at' => now()->subMonths(2),
            'description' => 'Vidange moteur et remplacement des filtres.',
            'cost' => 75000,
        ]);

        MaintenanceLog::create([
            'vehicle_id' => $vehicle2->id,
            'performed_at' => now()->subMonths(1),
            'description' => 'Contrôle général et changement des plaquettes.',
            'cost' => 120000,
        ]);

        MaintenanceLog::create([
            'vehicle_id' => $vehicle3->id,
            'performed_at' => now()->subWeeks(3),
            'description' => 'Révision complète du véhicule.',
            'cost' => 95000,
        ]);

        MaintenanceLog::create([
            'vehicle_id' => $vehicle6->id,
            'performed_at' => now()->subDays(3),
            'description' => 'Réparation du système de freinage.',
            'cost' => 180000,
        ]);

        /*
        |--------------------------------------------------------------------------
        | MESSAGE DE FIN
        |--------------------------------------------------------------------------
        */

        $this->command->info('========================================');
        $this->command->info('   SEEDER GO CAR TERMINE AVEC SUCCES');
        $this->command->info('========================================');
        $this->command->info('Admin : matar9@gmail.com / 12345678');
        $this->command->info('Client 1 : matar@gmail.com / 12345678');
        $this->command->info('Client 2 : awa@gmail.com / 12345678');
        $this->command->info('Client 3 : ibrahima@gmail.com / 12345678');
        $this->command->info('Driver 1 : ousmane@gmail.com / 12345678');
        $this->command->info('Driver 2 : cheikh@gmail.com / 12345678');
    }
}
