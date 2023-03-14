<?php


namespace Core\Services;


use Core\Models\User;

trait AuthorizePackageTrait
{
    /**
     * @param User $user
     * @param string $feature
     * @return AttributePackageFeature|null
     */
    public function getUserFeaturePackage(User $user, string $feature): ?AttributePackageFeature
    {
        $data = $user->lastPackage->attributes->firstWhere('name', $feature)->toArray();
        if ($data) {
            return new AttributePackageFeature($data);
        }

        return null;
    }
}