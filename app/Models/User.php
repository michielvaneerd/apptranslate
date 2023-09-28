<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLES = ['root', 'admin', 'editor'];

    const SESSION_KEY_TFA_AUTHENTICATED = 'tfa_authenticated';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'email_hash',
        'tfa_secret',
        'role',
        'tfa_secret_verified_at',
        'email_verified_at',
        'email_token',
        'email_token_created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'email_hash',
        'tfa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tfa_secret_verified_at' => 'datetime',
        'email_token_created_at' => 'datetime',
        'password' => 'hashed',
        'tfa_secret' => 'encrypted',
    ];

    public static function hashProperty(string $value): string
    {
        return hash('sha256', $value);
    }

    public static function createToken(): string
    {
        return Str::random(48);
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Crypt::decryptString($value),
            set: fn (string $value) => [
                'email' => Crypt::encryptString($value),
                'email_hash' => self::hashProperty($value),
            ]
        );
    }

    public static function generateSecretTfaKey(): string
    {
        $tfa = new Google2FA();

        return $tfa->generateSecretKey();
    }

    public function getTfaSecretAsSvg(): string
    {
        $google2fa = new Google2FA();
        $url = $google2fa->getQRCodeUrl(
            config('app.name'),
            $this->email,
            $this->tfa_secret
        );
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        return $writer->writeString($url);
    }

    public function tfaAuthenticate()
    {
        session()->put(self::SESSION_KEY_TFA_AUTHENTICATED, true);
    }
}
