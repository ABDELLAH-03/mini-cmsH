<?

namespace App\Policies;

use App\Models\User;
use App\Models\Site;

class SitePolicy
{
    public function view(User $user, Site $site)
    {
        return $user->id === $site->user_id || $user->isAdmin();
    }

    public function update(User $user, Site $site)
    {
        return $user->id === $site->user_id || $user->isAdmin();
    }

    public function delete(User $user, Site $site)
    {
        return $user->id === $site->user_id || $user->isAdmin();
    }
}