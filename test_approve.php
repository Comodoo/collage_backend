<?php
try { 
    $reg = \App\Models\Registration::find(1); 
    if (!$reg) { echo 'Registration 1 not found'; exit; }
    $reg->update(['status' => 'approved', 'registration_number' => 'ZMS-26-01-0004']); 
    echo 'OK'; 
} catch (\Exception $e) { 
    echo 'ERROR: ' . $e->getMessage(); 
}
