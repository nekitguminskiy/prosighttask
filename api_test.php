<?php

/**
 * API Test Suite for Salesmen API
 * Run this file to test all endpoints
 */

// Configuration
$baseUrl = 'http://prosighttask';
$testResults = [];
$createdSalesmanId = null;

// Helper function to make HTTP requests
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Content-Type: application/json'], $headers));

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'body' => $response,
        'error' => $error
    ];
}

// Helper function to log test results
function logTest($name, $expectedCode, $actualCode, $response, $passed = null) {
    global $testResults;

    if ($passed === null) {
        $passed = $actualCode === $expectedCode;
    }

    $testResults[] = [
        'name' => $name,
        'expected' => $expectedCode,
        'actual' => $actualCode,
        'passed' => $passed,
        'response' => $response
    ];

    $status = $passed ? '✅ PASS' : '❌ FAIL';
    echo sprintf("%-40s %s (Expected: %d, Got: %d)\n", $name, $status, $expectedCode, $actualCode);

    if (!$passed && $response) {
        echo "  Response: " . substr($response, 0, 200) . (strlen($response) > 200 ? '...' : '') . "\n";
    }
    echo "\n";
}

// Helper function to extract ID from response
function extractId($response) {
    $data = json_decode($response, true);
    return $data['data']['id'] ?? $data['id'] ?? null;
}

echo "🚀 Starting API Tests...\n";
echo str_repeat("=", 80) . "\n\n";

// Test 1: Health Check
echo "1. Health Check\n";
$response = makeRequest($baseUrl . '/api/health');
logTest('Health Check', 200, $response['code'], $response['body']);

// Test 2: Get Codelists
echo "2. Get Codelists\n";
$response = makeRequest($baseUrl . '/api/v1/codelists');
logTest('Get Codelists', 200, $response['code'], $response['body']);

// Test 3: Get Empty Salesmen List
echo "3. Get Empty Salesmen List\n";
$response = makeRequest($baseUrl . '/api/v1/salesmen');
logTest('Get Empty Salesmen List', 200, $response['code'], $response['body']);

// Test 4: Get Salesmen with Pagination
echo "4. Get Salesmen with Pagination\n";
$response = makeRequest($baseUrl . '/api/v1/salesmen?page=1&per_page=5');
logTest('Get Salesmen with Pagination', 200, $response['code'], $response['body']);

// Test 5: Create Salesman
echo "5. Create Salesman\n";
$salesmanData = [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'titles_before' => ['Ing.'],
    'titles_after' => ['PhD.'],
    'prosight_id' => '12345',
    'email' => 'john.doe@example.com',
    'phone' => '+1234567890',
    'gender' => 'm',
    'marital_status' => 'single'
];

$response = makeRequest($baseUrl . '/api/v1/salesmen', 'POST', $salesmanData);
logTest('Create Salesman', 201, $response['code'], $response['body']);

// Extract ID for further tests
$createdSalesmanId = extractId($response['body']);

if ($createdSalesmanId) {
    echo "Created Salesman ID: $createdSalesmanId\n\n";

    // Test 6: Get Created Salesman
    echo "6. Get Created Salesman\n";
    $response = makeRequest($baseUrl . '/api/v1/salesmen/' . $createdSalesmanId);
    logTest('Get Created Salesman', 200, $response['code'], $response['body']);

    // Test 7: Update Salesman
    echo "7. Update Salesman\n";
    $updateData = [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'titles_before' => ['Dr.'],
        'titles_after' => ['MBA'],
        'prosight_id' => '12345',
        'email' => 'jane.smith@example.com',
        'phone' => '+9876543210',
        'gender' => 'f',
        'marital_status' => 'married'
    ];

    $response = makeRequest($baseUrl . '/api/v1/salesmen/' . $createdSalesmanId, 'PUT', $updateData);
    logTest('Update Salesman', 200, $response['code'], $response['body']);

    // Test 8: Get Updated Salesman
    echo "8. Get Updated Salesman\n";
    $response = makeRequest($baseUrl . '/api/v1/salesmen/' . $createdSalesmanId);
    logTest('Get Updated Salesman', 200, $response['code'], $response['body']);

    // Test 9: Test Filtering
    echo "9. Test Filtering\n";
    $response = makeRequest($baseUrl . '/api/v1/salesmen?gender=f');
    logTest('Filter by Gender', 200, $response['code'], $response['body']);

    // Test 10: Test Search
    echo "10. Test Search\n";
    $response = makeRequest($baseUrl . '/api/v1/salesmen?search=Jane');
    logTest('Search by Name', 200, $response['code'], $response['body']);

    // Test 11: Delete Salesman
    echo "11. Delete Salesman\n";
    $response = makeRequest($baseUrl . '/api/v1/salesmen/' . $createdSalesmanId, 'DELETE');
    logTest('Delete Salesman', 200, $response['code'], $response['body']);

    // Test 12: Try to Get Deleted Salesman
    echo "12. Try to Get Deleted Salesman\n";
    $response = makeRequest($baseUrl . '/api/v1/salesmen/' . $createdSalesmanId);
    logTest('Get Deleted Salesman (should fail)', 404, $response['code'], $response['body']);

} else {
    echo "❌ Failed to create salesman, skipping dependent tests\n\n";
}

// Test 13: Validation Errors
echo "13. Test Validation Errors\n";

// Test invalid gender
$invalidData = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'prosight_id' => '99999',
    'email' => 'test@example.com',
    'gender' => 'invalid'
];

$response = makeRequest($baseUrl . '/api/v1/salesmen', 'POST', $invalidData);
logTest('Invalid Gender Validation', 422, $response['code'], $response['body']);

// Test missing required fields
$incompleteData = [
    'first_name' => 'Test'
];

$response = makeRequest($baseUrl . '/api/v1/salesmen', 'POST', $incompleteData);
logTest('Missing Required Fields', 422, $response['code'], $response['body']);

// Test duplicate email
$duplicateData = [
    'first_name' => 'Duplicate',
    'last_name' => 'User',
    'prosight_id' => '11111',
    'email' => 'john.doe@example.com', // This email was used before
    'gender' => 'm'
];

$response = makeRequest($baseUrl . '/api/v1/salesmen', 'POST', $duplicateData);
logTest('Duplicate Email Validation', 422, $response['code'], $response['body']);

// Test 14: Invalid UUID
echo "14. Test Invalid UUID\n";
$response = makeRequest($baseUrl . '/api/v1/salesmen/invalid-uuid');
logTest('Invalid UUID', 404, $response['code'], $response['body']);

// Test 15: Create Multiple Salesmen
echo "15. Create Multiple Salesmen for Testing\n";
$salesmen = [
    [
        'first_name' => 'Alice',
        'last_name' => 'Johnson',
        'titles_before' => ['Mgr.'],
        'titles_after' => ['MBA'],
        'prosight_id' => '11111',
        'email' => 'alice.johnson@example.com',
        'phone' => '+1111111111',
        'gender' => 'f',
        'marital_status' => 'single'
    ],
    [
        'first_name' => 'Bob',
        'last_name' => 'Wilson',
        'titles_before' => ['Dr.'],
        'titles_after' => ['PhD.'],
        'prosight_id' => '22222',
        'email' => 'bob.wilson@example.com',
        'phone' => '+2222222222',
        'gender' => 'm',
        'marital_status' => 'married'
    ],
    [
        'first_name' => 'Charlie',
        'last_name' => 'Brown',
        'prosight_id' => '33333',
        'email' => 'charlie.brown@example.com',
        'gender' => 'm',
        'marital_status' => 'divorced'
    ]
];

$createdIds = [];
foreach ($salesmen as $index => $salesman) {
    $response = makeRequest($baseUrl . '/api/v1/salesmen', 'POST', $salesman);
    $id = extractId($response['body']);
    if ($id) {
        $createdIds[] = $id;
    }
    logTest("Create Salesman " . ($index + 1), 201, $response['code'], $response['body']);
}

// Test 16: Get All Salesmen
echo "16. Get All Salesmen\n";
$response = makeRequest($baseUrl . '/api/v1/salesmen');
logTest('Get All Salesmen', 200, $response['code'], $response['body']);

// Test 17: Advanced Filtering
echo "17. Advanced Filtering\n";
$response = makeRequest($baseUrl . '/api/v1/salesmen?gender=m&marital_status=married');
logTest('Filter by Gender and Marital Status', 200, $response['code'], $response['body']);

// Clean up created salesmen
echo "18. Cleanup - Delete Created Salesmen\n";
foreach ($createdIds as $index => $id) {
    $response = makeRequest($baseUrl . '/api/v1/salesmen/' . $id, 'DELETE');
    logTest("Delete Salesman " . ($index + 1), 200, $response['code'], $response['body']);
}

// Summary
echo str_repeat("=", 80) . "\n";
echo "📊 TEST SUMMARY\n";
echo str_repeat("=", 80) . "\n";

$totalTests = count($testResults);
$passedTests = array_filter($testResults, fn($test) => $test['passed']);
$passedCount = count($passedTests);
$failedCount = $totalTests - $passedCount;

echo "Total Tests: $totalTests\n";
echo "Passed: $passedCount ✅\n";
echo "Failed: $failedCount ❌\n";
echo "Success Rate: " . round(($passedCount / $totalTests) * 100, 2) . "%\n\n";

if ($failedCount > 0) {
    echo "❌ FAILED TESTS:\n";
    foreach ($testResults as $test) {
        if (!$test['passed']) {
            echo "  - {$test['name']} (Expected: {$test['expected']}, Got: {$test['actual']})\n";
        }
    }
    echo "\n";
}

if ($passedCount === $totalTests) {
    echo "🎉 ALL TESTS PASSED! API is working correctly.\n";
} else {
    echo "⚠️  Some tests failed. Please check the API implementation.\n";
}

echo str_repeat("=", 80) . "\n";
