<?php


namespace App\Modules\Auth\Register;


use Core\Domains\BaseJob;
use Core\Models\Package;
use Core\Models\PackageUser;
use Core\Models\User;
use Illuminate\Support\Carbon;

class GenerateUserPackageJob extends BaseJob
{

    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    public function handle()
    {
        $package = Package::where('name', 'normal')->first();

        $insert = [
            'package_id'   => $package->id,
            'user_id'      => $this->user->id,
            'price'        => '0.0',
            'activated_at' => Carbon::now(),
            'expires_at'   => Carbon::now()->addYears(100),
            'finished_at'  => null,
        ];
        PackageUser::create($insert);
    }
}