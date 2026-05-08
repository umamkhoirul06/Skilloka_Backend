<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $tenant_id
 * @property int $user_id
 * @property string $schedule_id
 * @property string $code
 * @property string $status
 * @property numeric $amount
 * @property string|null $qr_code
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\CourseSchedule $schedule
 * @property-read \App\Models\Tenant $tenant
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUserId($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property string|null $color
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $tenant_id
 * @property string $lpk_id
 * @property string $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $syllabus
 * @property numeric $price
 * @property string $level
 * @property int $duration_hours
 * @property string|null $cert_type
 * @property array<array-key, mixed>|null $images
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $max_participants
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Lpk $lpk
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseSchedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \App\Models\Tenant $tenant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCertType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDurationHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereLpkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereMaxParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereSyllabus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUpdatedAt($value)
 */
	class Course extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $course_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon $daily_start
 * @property \Illuminate\Support\Carbon $daily_end
 * @property int $max_capacity
 * @property string $days_of_week
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereDailyEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereDailyStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereDaysOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereMaxCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSchedule whereUpdatedAt($value)
 */
	class CourseSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $level
 * @property string $code
 * @property numeric|null $lat
 * @property numeric|null $long
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @property-read int|null $children_count
 * @property-read Location|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $nib
 * @property int|null $location_id
 * @property string|null $address
 * @property numeric|null $lat
 * @property numeric|null $long
 * @property string|null $description
 * @property array<array-key, mixed>|null $facilities
 * @property bool $is_verified
 * @property string $status
 * @property array<array-key, mixed>|null $contact_info
 * @property string|null $logo
 * @property array<array-key, mixed>|null $images
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status_verifikasi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\Tenant $tenant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LpkVerification> $verifications
 * @property-read int|null $verifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereContactInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereFacilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereLegalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lpk whereUpdatedAt($value)
 */
	class Lpk extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Lpk|null $lpk
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpkVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpkVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpkVerification query()
 */
	class LpkVerification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $booking_id
 * @property string $method
 * @property string $provider
 * @property string|null $external_id
 * @property numeric $amount
 * @property string $status
 * @property string|null $paid_at
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $platform_name
 * @property string|null $support_email
 * @property string $timezone
 * @property string $language
 * @property int $platform_fee
 * @property string $payment_method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePlatformFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSupportEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $domain
 * @property string $name
 * @property array<array-key, mixed>|null $settings
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $lpk_name
 * @property string|null $legal_name
 * @property string|null $nib
 * @property string|null $description
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $tiktok
 * @property string|null $province
 * @property string|null $city
 * @property string|null $district
 * @property string|null $address
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string|null $logo
 * @property string|null $banner
 * @property string|null $facilities
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereFacilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLegalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLpkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNib($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereTiktok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereWebsite($value)
 */
	class Tenant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $guard_name
 * @method bool hasRole(string|array $roles)
 * @method \Illuminate\Support\Collection getRoleNames()
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tenant_id
 * @property string|null $phone
 * @property string|null $avatar
 * @property int|null $location_id
 * @property string|null $fcm_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $lpk_id
 * @property string $role
 * @property string|null $status
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Tenant|null $tenant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLpkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

