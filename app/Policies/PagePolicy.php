<?

namespace App\Policies;

use App\Models\User;
use App\Models\Page;

class PagePolicy
{
    public function update(User $user, Page $page)
    {
        return $user->id === $page->site->user_id || $user->isAdmin();
    }

    public function delete(User $user, Page $page)
    {
        return $user->id === $page->site->user_id || $user->isAdmin();
    }
}