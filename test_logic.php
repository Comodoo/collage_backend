<?php
try {
    $registration = \App\Models\Registration::find(1);
    $registrationNumber = 'ZMS-' . date('y') . '-01-' . str_pad($registration->user_id, 4, '0', STR_PAD_LEFT);
    $registration->update([
        'status' => 'approved',
        'registration_number' => $registrationNumber,
        'approved_at' => now(),
    ]);
    echo "SUCCESS: " . $registrationNumber;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
