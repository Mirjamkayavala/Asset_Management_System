<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User; // Ensure to use your User model

class InventoryTest extends DuskTestCase
{
    public function testInventoryPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1)) // Ensure the user is logged in
                    ->visit('/dashboard')
                    ->waitForLocation('/dashboard') // Wait for 30 seconds for the location to load
                    ->waitForText('Dashboard') // Wait for 30 seconds for the text to appear
                    ->screenshot('inventory_test') // Capture a screenshot for debugging
                    ->assertSee('Dashboard') // Ensure "Dashboard" is visible
                    ->assertSee('Inventory')
                    ->assertSee('Overview')
                    ->assertSee('Reports')
                    ->assertSee('Settings')
                    ->assertSee('User Managements')
                    ->assertSee('Roles and Permissions')
                    ->assertSee('Settings');
        });
    }
}
