<?php

use App\Models\User;

function makeProfileTestUser(): User
{
    return User::create([
        'name' => 'Akun Pengujian',
        'username' => fake()->unique()->userName(),
        'role' => 'admin',
        'is_active' => true,
        'password' => 'password',
    ]);
}

test('profile page is displayed', function () {
    $user = makeProfileTestUser();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response
        ->assertOk()
        ->assertSeeText('Informasi Profil')
        ->assertSeeText('Akun Pengujian')
        ->assertSeeText('admin');
});

test('profile information is read only', function () {
    $user = makeProfileTestUser();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', ['name' => 'Nama Baru']);

    $response->assertMethodNotAllowed();

    $this->assertSame('Akun Pengujian', $user->fresh()->name);
});

