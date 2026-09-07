<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use App\Services\TicketGenerationService;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\BoxOfficeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

echo "=== STARTING QUOTA SPLIT TEST ===\n";

DB::beginTransaction();

try {
    // 1. Get or create category, company, user
    $cat = Category::first() ?: Category::create(['name' => 'Concierto', 'slug' => 'concierto', 'icon' => 'music', 'is_active' => true]);
    $company = Company::first() ?: Company::create(['name' => 'Empresa Test', 'ruc' => '20123456789', 'email' => 'test@test.com', 'is_active' => true]);
    $user = User::first() ?: User::create(['name' => 'Tester', 'email' => 'test_quota@test.com', 'password' => bcrypt('secret123')]);

    // 2. TEST CASE 1: Split Enabled Event (80 physical, 20 digital, 10 courtesy)
    echo "\n--- TEST CASE 1: Event with Quota Split Enabled ---\n";
    $eventSplit = Event::create([
        'title' => 'Concierto Test Split',
        'category_id' => $cat->id,
        'company_id' => $company->id,
        'user_id' => $user->id,
        'event_date' => date('Y-m-d', strtotime('+7 days')),
        'event_time' => '20:00:00',
        'venue_name' => 'Estadio Nacional',
        'address' => 'Av. Test 123',
        'total_capacity' => 100,
        'sales_type' => 'ambas',
        'status' => 'Publicado',
        'zones' => [
            [
                'name' => 'Zona General',
                'capacity' => 100,
                'price' => 50.00,
                'physical_capacity' => 80,
                'virtual_capacity' => 20
            ]
        ],
        'courtesy_settings' => [
            'enabled' => true,
            'zones' => [
                [
                    'name' => 'Zona General',
                    'enabled' => true,
                    'stock' => 10
                ]
            ]
        ],
        'quota_split_settings' => [
            'enabled' => true,
            'zones' => [
                [
                    'name' => 'Zona General',
                    'zone_name' => 'Zona General',
                    'capacity' => 100,
                    'physical' => 80,
                    'virtual' => 20
                ]
            ]
        ]
    ]);

    $ticketService = new TicketGenerationService();
    $ticketService->syncEventTickets($eventSplit);

    // Verify ticket counts and correlatives
    $physTickets = EventTicket::where('event_id', $eventSplit->id)->where('ticket_type', 'fisica')->orderBy('ticket_number')->get();
    $virtTickets = EventTicket::where('event_id', $eventSplit->id)->where('ticket_type', 'digital')->orderBy('ticket_number')->get();
    $cortTickets = EventTicket::where('event_id', $eventSplit->id)->where('ticket_type', 'cortesia')->orderBy('ticket_number')->get();

    echo "Physical tickets count: " . $physTickets->count() . " (Expected: 80)\n";
    echo "Digital tickets count: " . $virtTickets->count() . " (Expected: 20)\n";
    echo "Courtesy tickets count: " . $cortTickets->count() . " (Expected: 10)\n";

    if ($physTickets->count() !== 80 || $virtTickets->count() !== 20 || $cortTickets->count() !== 10) {
        throw new Exception("FAIL: Ticket count mismatch for split event");
    }

    echo "Physical ticket numbers: min=" . $physTickets->first()->ticket_number . ", max=" . $physTickets->last()->ticket_number . " (Expected: 1..80)\n";
    echo "Digital ticket numbers: min=" . $virtTickets->first()->ticket_number . ", max=" . $virtTickets->last()->ticket_number . " (Expected: 1..20)\n";
    echo "Courtesy ticket numbers: min=" . $cortTickets->first()->ticket_number . ", max=" . $cortTickets->last()->ticket_number . " (Expected: 1..10)\n";

    if ($physTickets->first()->ticket_number != 1 || $physTickets->last()->ticket_number != 80) {
        throw new Exception("FAIL: Physical ticket numbers are not 1..80");
    }
    if ($virtTickets->first()->ticket_number != 1 || $virtTickets->last()->ticket_number != 20) {
        throw new Exception("FAIL: Digital ticket numbers are not 1..20");
    }
    if ($cortTickets->first()->ticket_number != 1 || $cortTickets->last()->ticket_number != 10) {
        throw new Exception("FAIL: Courtesy ticket numbers are not 1..10");
    }

    // Verify all QR hashes / payloads are unique across all 110 tickets
    $allTickets = EventTicket::where('event_id', $eventSplit->id)->get();
    $uniqueHashes = $allTickets->pluck('security_hash')->unique()->count();
    $uniqueCodes = $allTickets->pluck('ticket_code')->unique()->count();
    echo "Total tickets: " . $allTickets->count() . ", Unique Hashes: $uniqueHashes, Unique Codes: $uniqueCodes\n";
    if ($uniqueHashes !== $allTickets->count() || $uniqueCodes !== $allTickets->count()) {
        throw new Exception("FAIL: Non-unique ticket security hashes or codes detected");
    }

    // Test getRegisteredTickets (for plancha print) excludes digital tickets
    $eventController = new EventController();
    $registeredResp = $eventController->getRegisteredTickets($eventSplit->id);
    $registeredData = json_decode($registeredResp->getContent(), true);
    echo "Registered tickets for plancha: " . $registeredData['total_tickets'] . " (Expected: 90 = 80 physical + 10 courtesy)\n";
    if ($registeredData['total_tickets'] !== 90) {
        throw new Exception("FAIL: Registered tickets for plancha should be 90, got " . $registeredData['total_tickets']);
    }

    // 3. TEST CASE 2: Backward Compatibility (Split Disabled / Legacy Event)
    echo "\n--- TEST CASE 2: Event without Quota Split (Legacy Mode) ---\n";
    $eventUnsplit = Event::create([
        'title' => 'Concierto Test Unsplit',
        'category_id' => $cat->id,
        'company_id' => $company->id,
        'user_id' => $user->id,
        'event_date' => date('Y-m-d', strtotime('+7 days')),
        'event_time' => '20:00:00',
        'venue_name' => 'Teatro Municipal',
        'address' => 'Jr. Test 456',
        'total_capacity' => 50,
        'sales_type' => 'fisica',
        'status' => 'Publicado',
        'zones' => [
            [
                'name' => 'Zona Platea',
                'capacity' => 50,
                'price' => 100.00
            ]
        ],
        'courtesy_settings' => [
            'enabled' => true,
            'zones' => [
                [
                    'name' => 'Zona Platea',
                    'enabled' => true,
                    'stock' => 5
                ]
            ]
        ],
        'quota_split_settings' => null // Disabled / legacy
    ]);

    $ticketService->syncEventTickets($eventUnsplit);

    $unsplitTickets = EventTicket::where('event_id', $eventUnsplit->id)->orderBy('ticket_number')->get();
    echo "Total unsplit tickets: " . $unsplitTickets->count() . " (Expected: 55 = 50 regular + 5 courtesy)\n";
    echo "Unsplit ticket numbers: min=" . $unsplitTickets->first()->ticket_number . ", max=" . $unsplitTickets->last()->ticket_number . " (Expected: 1..55)\n";

    if ($unsplitTickets->count() !== 55) {
        throw new Exception("FAIL: Unsplit tickets count mismatch");
    }
    if ($unsplitTickets->first()->ticket_number != 1 || $unsplitTickets->last()->ticket_number != 55) {
        throw new Exception("FAIL: Unsplit tickets should have continuous numbers 1..55");
    }

    echo "\n>>> ALL TESTS PASSED SUCCESSFULLY! <<<\n";

} finally {
    // Rollback test data so database remains clean
    DB::rollBack();
    echo "Database rolled back cleanly.\n";
}
