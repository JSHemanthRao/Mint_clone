<?php
use App\Models\User;

$user = User::where('email', 'hemanthrao@gmail.com')->first();
if ($user) {
    // Setting plaintext password. The 'hashed' cast in the User model will automatically hash this.
    $user->password = '1234512345';
    $user->save();
    echo "Password reset successfully for " . $user->email . "\n";
} else {
    echo "User not found.\n";
}
