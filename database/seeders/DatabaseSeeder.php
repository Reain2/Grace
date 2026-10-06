<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\ReadingPlan;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $traditions = collect([
            ['name' => 'Katolik', 'slug' => 'katolik'],
            ['name' => 'Kristen Protestan', 'slug' => 'kristen-protestan'],
            ['name' => 'Buddha', 'slug' => 'buddha'],
            ['name' => 'Hindu', 'slug' => 'hindu'],
            ['name' => 'Konghucu', 'slug' => 'konghucu'],
            ['name' => 'Islam', 'slug' => 'islam'],
        ])->mapWithKeys(fn (array $tradition) => [
            $tradition['slug'] => Tradition::updateOrCreate(['slug' => $tradition['slug']], $tradition),
        ]);

        User::updateOrCreate(
            ['email' => 'superadmin@grace.test'],
            [
                'name' => 'Grace Superadmin DEMO',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'status' => 'active',
                'verification_status' => 'approved',
                'email_verified_at' => now(),
            ],
        );

        foreach ($traditions as $tradition) {
            $admin = User::updateOrCreate(
                ['email' => "admin.{$tradition->slug}@grace.test"],
                [
                    'name' => "Admin {$tradition->name} DEMO",
                    'password' => Hash::make('password'),
                    'role' => 'religion_admin',
                    'tradition_id' => $tradition->id,
                    'status' => 'active',
                    'verification_status' => 'approved',
                    'email_verified_at' => now(),
                ],
            );

            Quote::firstOrCreate([
                'tradition_id' => $tradition->id,
                'body' => "Renungan demo untuk tradisi {$tradition->name}.",
            ], [
                'source' => 'Konten demo Grace',
                'is_active' => true,
                'created_by' => $admin->id,
            ]);

            $plan = ReadingPlan::firstOrCreate([
                'tradition_id' => $tradition->id,
                'title' => "Rencana awal {$tradition->name}",
            ], [
                'description' => 'Rencana bacaan contoh untuk pengujian fitur Grace.',
                'total_days' => 3,
                'source' => 'Konten demo Grace',
                'is_active' => true,
                'created_by' => $admin->id,
            ]);

            User::updateOrCreate(
                ['email' => "user.{$tradition->slug}@grace.test"],
                [
                    'name' => "User {$tradition->name} DEMO",
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'tradition_id' => $tradition->id,
                    'status' => 'active',
                    'verification_status' => 'approved',
                    'timezone' => 'Asia/Jakarta',
                    'email_verified_at' => now(),
                ],
            );

            $pendingUser = User::updateOrCreate(
                ['email' => "pending.{$tradition->slug}@grace.test"],
                [
                    'name' => "Pending {$tradition->name} DEMO",
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'tradition_id' => $tradition->id,
                    'status' => 'active',
                    'verification_status' => 'pending',
                    'timezone' => 'Asia/Jakarta',
                    'email_verified_at' => now(),
                ],
            );
            $pendingPath = "verification-proofs/seed-pending-{$tradition->slug}.png";
            Storage::disk('local')->put($pendingPath, 'DEMO PENDING PROOF');
            $pendingUser->verificationRequests()->updateOrCreate(
                ['user_id' => $pendingUser->id, 'tradition_id' => $tradition->id],
                ['proof_type' => 'dummy_image', 'proof_path' => $pendingPath, 'status' => 'pending'],
            );

            $rejectedUser = User::updateOrCreate(
                ['email' => "rejected.{$tradition->slug}@grace.test"],
                [
                    'name' => "Rejected {$tradition->name} DEMO",
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'tradition_id' => $tradition->id,
                    'status' => 'active',
                    'verification_status' => 'rejected',
                    'timezone' => 'Asia/Jakarta',
                    'email_verified_at' => now(),
                ],
            );
            $rejectedPath = "verification-proofs/seed-rejected-{$tradition->slug}.png";
            Storage::disk('local')->put($rejectedPath, 'DEMO REJECTED PROOF');
            $rejectedUser->verificationRequests()->updateOrCreate(
                ['user_id' => $rejectedUser->id, 'tradition_id' => $tradition->id],
                ['proof_type' => 'dummy_image', 'proof_path' => $rejectedPath, 'status' => 'rejected', 'attempts' => 1, 'reject_reason' => 'Demo rejection untuk menguji resubmit.'],
            );

            foreach ([
                ['day_number' => 1, 'title' => 'Hari pertama', 'reference' => 'Rujukan demo 1'],
                ['day_number' => 2, 'title' => 'Hari kedua', 'reference' => 'Rujukan demo 2'],
                ['day_number' => 3, 'title' => 'Hari ketiga', 'reference' => 'Rujukan demo 3'],
            ] as $item) {
                $plan->items()->firstOrCreate(['day_number' => $item['day_number']], $item);
            }

            $tradition->holyDays()->firstOrCreate([
                'name' => "Hari penting {$tradition->name} DEMO",
                'date' => now()->addMonths(2)->toDateString(),
            ], [
                'description' => 'Tanggal contoh untuk pengujian kalender.',
                'source' => 'Konten demo Grace',
                'is_annual' => true,
            ]);
        }
    }
}
