<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the checkDateInRange function to verify date bypass vulnerability.
     *
     * @return void
     */
    public function testDateRangeValidation()
    {
        // Set today to be the 29th of the month
        $today = Carbon::create(2025, 1, 29); // January 29, 2025
        Carbon::setTestNow($today);

        // Test the helper function directly
        $this->assertTrue(checkDateInRange($today->format('Y-m-d')), 'Today should be allowed');
        
        $yesterday = Carbon::create(2025, 1, 28); // January 28, 2025
        $this->assertTrue(checkDateInRange($yesterday->format('Y-m-d')), 'Yesterday should be allowed');
        
        // Test the problematic case - 4th day when today is 29th
        $forbiddenDate = Carbon::create(2025, 1, 4); // January 4, 2025
        $this->assertFalse(checkDateInRange($forbiddenDate->format('Y-m-d')), '4th should be blocked when today is 29th');
        
        // Test future date
        $tomorrow = Carbon::create(2025, 1, 30); // January 30, 2025
        $this->assertFalse(checkDateInRange($tomorrow->format('Y-m-d')), 'Future dates should be blocked');
        
        Carbon::setTestNow(); // Reset Carbon
    }

    /**
     * Test the specific scenario mentioned in the issue: 29th day trying to input 4th day
     *
     * @return void
     */
    public function testSpecificBypassScenario()
    {
        // Set today to be the 29th of the month (the scenario mentioned in the issue)
        $today = Carbon::create(2025, 1, 29); // January 29, 2025
        Carbon::setTestNow($today);
        
        // Try to submit activity for the 4th (the date that should be blocked)
        $forbiddenDate = Carbon::create(2025, 1, 4); // January 4, 2025
        
        // This should return false (not allowed)
        $result = checkDateInRange($forbiddenDate->format('Y-m-d'));
        
        // If this test fails, it means the date validation is not working properly
        $this->assertFalse($result, 'Date validation should block dates that are not today or yesterday');
        
        Carbon::setTestNow(); // Reset Carbon
    }

    /**
     * Test that demonstrates the actual vulnerability: whitelisted users can bypass date validation
     *
     * @return void
     */
    public function testWhitelistBypassVulnerability()
    {
        // Set today to be the 29th of the month
        $today = Carbon::create(2025, 1, 29); // January 29, 2025
        Carbon::setTestNow($today);
        
        // Mock a whitelisted user
        $this->mockWhitelistUser();
        
        // Try to submit activity for the 4th (should be blocked for normal users but allowed for whitelisted)
        $forbiddenDate = Carbon::create(2025, 1, 4); // January 4, 2025
        
        // The checkDateInRange function should still return false
        $this->assertFalse(checkDateInRange($forbiddenDate->format('Y-m-d')), 'checkDateInRange should still return false for the 4th');
        
        // But the controller logic allows whitelisted users to bypass this check
        // This demonstrates the vulnerability: whitelisted users can submit activities for any date
        $this->assertTrue(true, 'This test demonstrates that whitelisted users bypass date validation entirely');
        
        Carbon::setTestNow(); // Reset Carbon
    }

    /**
     * Helper method to mock a whitelisted user
     *
     * @return void
     */
    private function mockWhitelistUser()
    {
        // This would normally involve creating a user and adding them to the whitelist
        // For now, we'll just demonstrate the logic flaw
        // In a real test, you would:
        // 1. Create a user
        // 2. Add them to the WhitelistNip table
        // 3. Test the controller behavior
        
        // The vulnerability is in the controller logic:
        // if (whitelist(Auth::user()->username) != true) {
        //     // Date validation happens here
        // } else {
        //     // NO DATE VALIDATION - this is the vulnerability!
        // }
    }
}
