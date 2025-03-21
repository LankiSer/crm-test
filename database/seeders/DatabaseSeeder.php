<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call other seeders
        $this->call([
            RoleSeeder::class,
            // Отключаем AdminUserSeeder, так как создаем пользователей здесь
            // AdminUserSeeder::class,
        ]);

        // Create test user accounts with different roles
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Sales Manager',
            'email' => 'manager@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'manager',
        ]);

        $sales = User::create([
            'name' => 'Sales Representative',
            'email' => 'sales@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'sales',
        ]);

        $support = User::create([
            'name' => 'Support Agent',
            'email' => 'support@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'support',
        ]);
        
        // Create test companies
        $companies = [
            [
                'name' => 'Acme Corporation',
                'email' => 'info@acme.com',
                'phone' => '(555) 123-4567',
                'website' => 'https://www.acme.com',
                'address' => '123 Main St, Suite 100, New York, NY 10001',
                'industry' => 'Technology',
                'employees' => 250,
                'notes' => 'One of our oldest clients. Been working together since 2015.',
            ],
            [
                'name' => 'Globex Industries',
                'email' => 'contact@globex.com',
                'phone' => '(555) 987-6543',
                'website' => 'https://www.globex.com',
                'address' => '456 Park Avenue, Chicago, IL 60601',
                'industry' => 'Manufacturing',
                'employees' => 1200,
                'notes' => 'Large manufacturing client with international presence.',
            ],
            [
                'name' => 'Stark Enterprises',
                'email' => 'hello@stark.com',
                'phone' => '(555) 789-4561',
                'website' => 'https://www.stark.com',
                'address' => '789 Tower Road, Malibu, CA 90265',
                'industry' => 'Technology',
                'employees' => 500,
                'notes' => 'High-tech innovative company. Great potential for upselling.',
            ],
            [
                'name' => 'Wayne Enterprises',
                'email' => 'info@wayne.com',
                'phone' => '(555) 456-7890',
                'website' => 'https://www.wayne.com',
                'address' => '1007 Mountain Drive, Gotham, NJ 07001',
                'industry' => 'Diversified',
                'employees' => 10000,
                'notes' => 'Multinational conglomerate with diverse business interests.',
            ],
            [
                'name' => 'Umbrella Corporation',
                'email' => 'contact@umbrella.com',
                'phone' => '(555) 321-7654',
                'website' => 'https://www.umbrella.com',
                'address' => '544 Raccoon City Ave, Seattle, WA 98101',
                'industry' => 'Pharmaceuticals',
                'employees' => 2500,
                'notes' => 'Leading pharmaceutical research company.',
            ],
        ];
        
        foreach ($companies as $companyData) {
            Company::create($companyData);
        }
        
        // Create test contacts associated with companies
        $contacts = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@acme.com',
                'phone' => '(555) 111-2222',
                'position' => 'CEO',
                'company_id' => 1,
                'notes' => 'Key decision maker. Prefers communication via email.',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@acme.com',
                'phone' => '(555) 222-3333',
                'position' => 'CTO',
                'company_id' => 1,
                'notes' => 'Technical contact. Very responsive to new technology proposals.',
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Johnson',
                'email' => 'robert.johnson@globex.com',
                'phone' => '(555) 333-4444',
                'position' => 'Procurement Manager',
                'company_id' => 2,
                'notes' => 'Handles all purchasing decisions. Budget conscious.',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.williams@globex.com',
                'phone' => '(555) 444-5555',
                'position' => 'HR Director',
                'company_id' => 2,
                'notes' => 'Interested in HR solutions and employee management tools.',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@stark.com',
                'phone' => '(555) 555-6666',
                'position' => 'CIO',
                'company_id' => 3,
                'notes' => 'Very tech-savvy. Looking for cutting-edge solutions.',
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@wayne.com',
                'phone' => '(555) 666-7777',
                'position' => 'Marketing Director',
                'company_id' => 4,
                'notes' => 'Interested in marketing automation tools.',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Miller',
                'email' => 'david.miller@umbrella.com',
                'phone' => '(555) 777-8888',
                'position' => 'Research Director',
                'company_id' => 5,
                'notes' => 'Looking for data analysis and research tools.',
            ],
            [
                'first_name' => 'Jessica',
                'last_name' => 'Wilson',
                'email' => 'jessica.wilson@umbrella.com',
                'phone' => '(555) 888-9999',
                'position' => 'CFO',
                'company_id' => 5,
                'notes' => 'Focuses on ROI and cost efficiency.',
            ],
        ];
        
        foreach ($contacts as $contactData) {
            Contact::create($contactData);
        }
        
        // Create test deals
        $deals = [
            [
                'name' => 'Enterprise Software Package',
                'amount' => 120000.00,
                'status' => 'negotiation',
                'expected_close_date' => now()->addDays(30),
                'company_id' => 1,
                'contact_id' => 1,
                'user_id' => $sales->id,
                'description' => 'Complete enterprise software solution including CRM, ERP, and analytics modules.',
            ],
            [
                'name' => 'Hardware Infrastructure Upgrade',
                'amount' => 85000.00,
                'status' => 'proposal',
                'expected_close_date' => now()->addDays(45),
                'company_id' => 2,
                'contact_id' => 3,
                'user_id' => $sales->id,
                'description' => 'Upgrading their server infrastructure and networking equipment.',
            ],
            [
                'name' => 'Cloud Migration Services',
                'amount' => 65000.00,
                'status' => 'qualified',
                'expected_close_date' => now()->addDays(60),
                'company_id' => 3,
                'contact_id' => 5,
                'user_id' => $manager->id,
                'description' => 'Migrating their on-premise systems to our cloud platform.',
            ],
            [
                'name' => 'Marketing Automation Implementation',
                'amount' => 45000.00,
                'status' => 'closed_won',
                'expected_close_date' => now()->subDays(15),
                'company_id' => 4,
                'contact_id' => 6,
                'user_id' => $manager->id,
                'description' => 'Implementing our marketing automation suite with custom integrations.',
            ],
            [
                'name' => 'Data Analytics Platform',
                'amount' => 95000.00,
                'status' => 'closed_lost',
                'expected_close_date' => now()->subDays(30),
                'company_id' => 5,
                'contact_id' => 7,
                'user_id' => $sales->id,
                'description' => 'Comprehensive data analytics platform with custom dashboards.',
            ],
            [
                'name' => 'Annual Support Contract',
                'amount' => 35000.00,
                'status' => 'won',
                'expected_close_date' => now()->addDays(10),
                'company_id' => 1,
                'contact_id' => 2,
                'user_id' => $support->id,
                'description' => 'Annual premium support contract renewal with additional services.',
            ],
        ];
        
        foreach ($deals as $dealData) {
            Deal::create($dealData);
        }
        
        // Create test tasks
        $tasks = [
            [
                'title' => 'Prepare proposal for Acme Corp',
                'description' => 'Create detailed proposal for the enterprise software package including pricing options and implementation timeline.',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => now()->addDays(3),
                'user_id' => $sales->id,
                'assigned_to' => $sales->id,
                'taskable_type' => 'App\Models\Deal',
                'taskable_id' => 1,
            ],
            [
                'title' => 'Follow up with Robert Johnson',
                'description' => 'Call Robert to discuss the hardware upgrade proposal and address any questions.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addDays(2),
                'user_id' => $sales->id,
                'assigned_to' => $sales->id,
                'taskable_type' => 'App\Models\Contact',
                'taskable_id' => 3,
            ],
            [
                'title' => 'Site visit to Globex Industries',
                'description' => 'Visit Globex to assess their current infrastructure and finalize requirements.',
                'status' => 'completed',
                'priority' => 'high',
                'due_date' => now()->subDays(2),
                'user_id' => $manager->id,
                'assigned_to' => $sales->id,
                'taskable_type' => 'App\Models\Company',
                'taskable_id' => 2,
            ],
            [
                'title' => 'Prepare implementation plan for Wayne Enterprises',
                'description' => 'Create detailed implementation plan for the marketing automation system.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(5),
                'user_id' => $manager->id,
                'assigned_to' => $manager->id,
                'taskable_type' => 'App\Models\Deal',
                'taskable_id' => 4,
            ],
            [
                'title' => 'Send contract to John Smith',
                'description' => 'Prepare and send the final contract for signature for the enterprise software package.',
                'status' => 'pending',
                'priority' => 'critical',
                'due_date' => now()->addDays(1),
                'user_id' => $admin->id,
                'assigned_to' => $sales->id,
                'taskable_type' => 'App\Models\Contact',
                'taskable_id' => 1,
            ],
            [
                'title' => 'Quarterly business review with Umbrella Corp',
                'description' => 'Conduct quarterly business review meeting to discuss current projects and future opportunities.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addDays(10),
                'user_id' => $manager->id,
                'assigned_to' => $manager->id,
                'taskable_type' => 'App\Models\Company',
                'taskable_id' => 5,
            ],
            [
                'title' => 'Technical support for Stark Enterprises',
                'description' => 'Resolve reported issues with the cloud migration process.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(2),
                'user_id' => $support->id,
                'assigned_to' => $support->id,
                'taskable_type' => 'App\Models\Company',
                'taskable_id' => 3,
            ],
        ];
        
        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }
    }
}
