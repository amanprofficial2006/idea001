<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Public Unique User ID
            $table->string('user_uid', 20)
                ->unique()
                ->after('id');

            // Profile Image
            $table->string('profile_image')
                ->nullable()
                ->after('password');

            // Account Status
            $table->boolean('is_active')
                ->default(true)
                ->after('profile_image');

            $table->boolean('is_blocked')
                ->default(false)
                ->after('is_active');

            $table->string('blocked_reason')
                ->nullable()
                ->after('is_blocked');

            // Online Status
            $table->boolean('is_online')
                ->default(false)
                ->after('blocked_reason');

            $table->timestamp('last_seen_at')
                ->nullable()
                ->after('is_online');

            // Location
            $table->decimal('last_latitude', 10, 8)
                ->nullable()
                ->after('last_seen_at');

            $table->decimal('last_longitude', 11, 8)
                ->nullable()
                ->after('last_latitude');

            $table->timestamp('location_updated_at')
                ->nullable()
                ->after('last_longitude');

            // Wallet
            $table->decimal('wallet_balance', 10, 2)
                ->default(0)
                ->after('location_updated_at');

            $table->decimal('total_earned', 10, 2)
                ->default(0)
                ->after('wallet_balance');

            // Rating
            $table->decimal('rating_avg', 3, 2)
                ->default(0.00)
                ->after('total_earned');

            $table->integer('rating_count')
                ->default(0)
                ->after('rating_avg');

            // Verification
            $table->boolean('is_verified')
                ->default(false)
                ->after('rating_count');

            $table->enum('verification_type', ['phone', 'email', 'document'])
                ->nullable()
                ->after('is_verified');

            // Device / Notification
            $table->enum('device_type', ['android', 'ios', 'web'])
                ->nullable()
                ->after('verification_type');

            $table->text('device_token')
                ->nullable()
                ->after('device_type');

            // Security / Tracking
            $table->timestamp('last_login_at')
                ->nullable()
                ->after('device_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'user_uid',
                'profile_image',
                'is_active',
                'is_blocked',
                'blocked_reason',
                'is_online',
                'last_seen_at',
                'last_latitude',
                'last_longitude',
                'location_updated_at',
                'wallet_balance',
                'total_earned',
                'rating_avg',
                'rating_count',
                'is_verified',
                'verification_type',
                'device_type',
                'device_token',
                'last_login_at',
            ]);
        });
    }
};
